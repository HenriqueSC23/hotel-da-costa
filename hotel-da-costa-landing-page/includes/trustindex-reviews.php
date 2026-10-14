<?php
/** Read imported Google reviews through the active Trustindex plugin; never download or render its widget. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class HDC_LP_Trustindex_Reviews {
	const SOURCE = 'hdc_lp_reviews_source';

	public static function settings() {
		$value = get_option( self::SOURCE, array() );
		return wp_parse_args( is_array( $value ) ? $value : array(), array( 'provider' => 'google', 'place_id' => '' ) );
	}

	public static function selected() {
		return 'trustindex' === self::settings()['provider'];
	}

	private static function plugin() {
		global $trustindex_pm_google;
		if ( ! class_exists( 'TrustindexPlugin_google', false ) || ! ( $trustindex_pm_google instanceof TrustindexPlugin_google ) ) {
			return new WP_Error( 'hdc_lp_trustindex_missing', 'Instale e ative “Widgets for Google Reviews”, da Trustindex, e conecte o perfil do hotel.' );
		}
		foreach ( array( 'getPageDetails', 'getReviews' ) as $method ) {
			if ( ! is_callable( array( $trustindex_pm_google, $method ) ) ) {
				return new WP_Error( 'hdc_lp_trustindex_version', 'Esta versão do Trustindex não é compatível. Atualize o plugin ou use a API oficial do Google.' );
			}
		}
		return $trustindex_pm_google;
	}

	/** Explicit confirmation pins the selected place; changing the Trustindex connection never mixes businesses. */
	public static function profile() {
		$plugin = self::plugin();
		if ( is_wp_error( $plugin ) ) { return $plugin; }
		try {
			$details = $plugin->getPageDetails();
			if ( ! is_array( $details ) || ! is_string( $details['id'] ?? null ) || ! preg_match( '/^[a-zA-Z0-9_-]{1,256}$/D', $details['id'] ) || ! is_string( $details['name'] ?? null ) || '' === trim( $details['name'] ) ) {
				return new WP_Error( 'hdc_lp_trustindex_profile', 'Conecte o perfil do Hotel da Costa no Trustindex e conclua a importação das avaliações.' );
			}
			$reviews = $plugin->getReviews();
			if ( ! is_array( $reviews ) ) { throw new UnexpectedValueException(); }
			return array(
				'id' => $details['id'], 'title' => sanitize_text_field( $details['name'] ),
				'address' => is_string( $details['address'] ?? null ) ? sanitize_text_field( $details['address'] ) : '',
				'count' => count( $reviews ),
			);
		} catch ( Throwable $error ) {
			return new WP_Error( 'hdc_lp_trustindex_data', 'Não foi possível ler as avaliações importadas. Confira a conexão e a versão do Trustindex.' );
		}
	}

	public static function public_data( $filters ) {
		$result = array(
			'status' => 'unavailable', 'items' => array(), 'source' => 'trustindex',
			'googleUrl' => 'https://www.google.com/maps/search/?api=1&query=Hotel+da+Costa+by+Nobile+Aracaju',
			'lastSynced' => null, 'expiresAt' => null,
		);
		$profile = self::profile();
		if ( is_wp_error( $profile ) || $profile['id'] !== self::settings()['place_id'] ) { return $result; }
		$result['googleUrl'] = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $profile['title'] . ' ' . $profile['address'] ) . '&query_place_id=' . rawurlencode( $profile['id'] );
		$plugin = self::plugin();
		try {
			$reviews = $plugin->getReviews();
			if ( ! is_array( $reviews ) ) { return $result; }
			foreach ( array_slice( $reviews, 0, 10 ) as $review ) {
				if ( ! is_object( $review ) || ! empty( $review->hidden ) || ! isset( $review->rating ) || ! is_numeric( $review->rating ) || ! in_array( (float) $review->rating, array( 1.0, 2.0, 3.0, 4.0, 5.0 ), true ) || ! is_string( $review->user ?? null ) || ! is_string( $review->text ?? null ) ) { continue; }
				$rating = (int) $review->rating;
				$comment = trim( wp_strip_all_tags( html_entity_decode( $review->text, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
				if ( ! in_array( $rating, $filters['stars'], true ) || $filters['require_comment'] && '' === $comment ) { continue; }
				$date = is_string( $review->date ?? null ) ? $review->date : '';
				if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/D', $date, $parts ) || ! checkdate( (int) $parts[2], (int) $parts[3], (int) $parts[1] ) ) { $date = ''; }
				$result['items'][] = array(
					'id' => 'hdc-lp-ti-' . hash( 'sha256', $profile['id'] . '|' . ( is_scalar( $review->reviewId ?? null ) ? (string) $review->reviewId : (string) ( $review->id ?? count( $result['items'] ) ) ) ),
					'author' => sanitize_text_field( $review->user ) ?: 'Usuário do Google',
					'avatar' => HDC_LP_Google_Reviews::avatar_url( is_string( $review->user_photo ?? null ) ? $review->user_photo : '' ),
					'rating' => $rating, 'comment' => $comment, 'date' => $date,
					'dateLabel' => $date ? wp_date( 'd/m/Y', strtotime( $date . 'T12:00:00Z' ), new DateTimeZone( 'UTC' ) ) : '',
				);
				if ( count( $result['items'] ) >= max( 1, min( 10, (int) $filters['limit'] ) ) ) { break; }
			}
		} catch ( Throwable $error ) {
			$result['items'] = array();
			return $result;
		}
		$result['status'] = $result['items'] ? 'ready' : 'empty';
		return $result;
	}
}
