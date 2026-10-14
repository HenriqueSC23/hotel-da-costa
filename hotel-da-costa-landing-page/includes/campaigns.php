<?php
/** Ordered campaign configuration and public, read-only images. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class HDC_LP_Campaigns {
	const OPTION = 'hdc_lp_campaigns';
	const SIZES = '(max-width: 359px) calc(100vw - 40px), (max-width: 767px) 320px, (max-width: 1280px) 360px, 384px';

	public static function defaults() {
		$items = array(
			'independencia-brasil' => array( 'Independência do Brasil', '7 de setembro — Dia da Independência do Brasil. O amanhã se constrói com a memória de ontem. Hotel da Costa by Nobile.' ),
			'dia-mundial-turismo' => array( 'Dia Mundial do Turismo', '27 de setembro — Dia Mundial do Turismo. Explorar novos lugares é o melhor investimento. Hotel da Costa by Nobile.' ),
			'dia-nacional-turismo' => array( 'Dia Nacional do Turismo', '8 de maio — Dia Nacional do Turismo. Viajar é o melhor remédio para sair da rotina e renovar as energias. Hotel da Costa by Nobile.' ),
			'independencia-sergipe' => array( 'Independência de Sergipe', '8 de julho — Independência de Sergipe. Orgulho sergipano: nossa história, nossa independência! Hotel da Costa by Nobile.' ),
		);
		$result = array();
		foreach ( $items as $key => $item ) {
			$result[] = array( 'id' => 'hdc-lp-campaign-' . $key, 'name' => $item[0], 'attachment_id' => 0, 'bundled_key' => $key, 'alt' => $item[1], 'active' => true );
		}
		return $result;
	}

	public static function all() {
		$saved = get_option( self::OPTION, null );
		return null === $saved ? self::defaults() : ( is_array( $saved ) ? array_values( $saved ) : array() );
	}

	public static function valid_attachment( $id ) {
		$source = wp_get_attachment_image_src( $id, 'large' );
		return $id > 0 && 'attachment' === get_post_type( $id ) && 'trash' !== get_post_status( $id ) &&
			in_array( get_post_mime_type( $id ), array( 'image/jpeg', 'image/png', 'image/webp' ), true ) &&
			$source && $source[1] > 0 && $source[2] > 0;
	}

	/** The same image payload is used by PHP, REST and the admin thumbnail. */
	public static function image( $item ) {
		$id = absint( $item['attachment_id'] ?? 0 );
		if ( $id ) {
			if ( ! self::valid_attachment( $id ) ) { return null; }
			$source = wp_get_attachment_image_src( $id, 'large' );
			return array( 'url' => $source[0], 'width' => $source[1], 'height' => $source[2], 'srcset' => wp_get_attachment_image_srcset( $id, 'large' ) ?: '', 'sizes' => self::SIZES, 'alt' => $item['alt'] ?? '' );
		}
		$key = $item['bundled_key'] ?? '';
		foreach ( self::defaults() as $default ) {
			if ( $key === $default['bundled_key'] ) {
				$base = HDC_LP_URL . 'assets/images/campanha-' . $key;
				return array( 'url' => $base . '-640.webp', 'width' => 640, 'height' => 800, 'srcset' => $base . '-640.webp 640w, ' . $base . '-960.webp 960w', 'sizes' => self::SIZES, 'alt' => $item['alt'] ?? '' );
			}
		}
		return null;
	}

	public static function public_items() {
		$result = array();
		foreach ( self::all() as $item ) {
			if ( ! is_array( $item ) || empty( $item['active'] ) ) { continue; }
			$image = self::image( $item );
			if ( $image ) { $result[] = $image; }
		}
		return $result;
	}

	/** Atomic validation: a rejected submission never overwrites the saved list. */
	public static function validate( $input ) {
		if ( ! is_array( $input ) ) { return new WP_Error( 'hdc_lp_campaigns_invalid', 'A lista de campanhas é inválida.' ); }
		$previous = array_column( self::all(), null, 'id' );
		$defaults = array_column( self::defaults(), null, 'id' );
		$result = array();
		$seen = array();
		foreach ( $input as $index => $row ) {
			if ( ! is_array( $row ) ) { return new WP_Error( 'hdc_lp_campaigns_invalid', 'Uma campanha contém dados inválidos.' ); }
			foreach ( array( 'id', 'name', 'alt', 'attachment_id', 'bundled_key', 'active' ) as $field ) {
				if ( isset( $row[ $field ] ) && ! is_scalar( $row[ $field ] ) ) { return new WP_Error( 'hdc_lp_campaigns_invalid', 'Uma campanha contém dados inválidos.' ); }
			}
			$id = sanitize_key( $row['id'] ?? '' );
			if ( ! preg_match( '/^hdc-lp-campaign-[a-z0-9-]+$/', $id ) || isset( $seen[ $id ] ) ) {
				return new WP_Error( 'hdc_lp_campaigns_id', 'Os identificadores das campanhas são inválidos ou repetidos. Recarregue a tela.' );
			}
			$seen[ $id ] = true;
			$name = sanitize_text_field( $row['name'] ?? '' );
			$alt = sanitize_text_field( $row['alt'] ?? '' );
			$attachment = (string) ( $row['attachment_id'] ?? '0' );
			if ( ! ctype_digit( $attachment ) ) { return new WP_Error( 'hdc_lp_campaigns_image', 'Selecione uma imagem válida na Biblioteca de Mídia.' ); }
			$item = array( 'id' => $id, 'name' => $name, 'alt' => $alt, 'attachment_id' => absint( $attachment ), 'bundled_key' => '', 'active' => ! empty( $row['active'] ) );
			if ( '' === $name || '' === $alt ) { return new WP_Error( 'hdc_lp_campaigns_text', 'Preencha o nome interno e o texto alternativo de cada campanha.' ); }
			if ( ! $item['attachment_id'] ) {
				// Bundled artwork can only be retained by its original stable campaign ID.
				$known = $previous[ $id ] ?? $defaults[ $id ] ?? null;
				$key = sanitize_key( $row['bundled_key'] ?? '' );
				if ( ! $known || empty( $known['bundled_key'] ) || $key !== $known['bundled_key'] ) { return new WP_Error( 'hdc_lp_campaigns_image', 'Selecione uma imagem JPEG, PNG ou WebP para cada campanha.' ); }
				$item['bundled_key'] = $key;
			} elseif ( ! self::valid_attachment( $item['attachment_id'] ) ) {
				// Keep a previously saved deleted image editable, so other campaigns can still be saved.
				$old = $previous[ $id ] ?? array();
				$deleted = ! get_post( $item['attachment_id'] ) || 'trash' === get_post_status( $item['attachment_id'] );
				if ( ! $deleted || $item['attachment_id'] !== (int) ( $old['attachment_id'] ?? 0 ) ) {
					return new WP_Error( 'hdc_lp_campaigns_image', 'Uma imagem é inválida. Use um anexo JPEG, PNG ou WebP da Biblioteca de Mídia.' );
				}
			}
			$result[] = $item;
		}
		return $result;
	}

	public static function register_rest() {
		register_rest_route( 'hdc-lp/v1', '/campaigns', array(
			'methods' => WP_REST_Server::READABLE,
			'permission_callback' => '__return_true',
			'callback' => static function () {
				$response = rest_ensure_response( array( 'items' => self::public_items() ) );
				$response->header( 'Cache-Control', 'no-store, max-age=0' );
				return $response;
			},
		) );
	}
}
add_action( 'rest_api_init', array( 'HDC_LP_Campaigns', 'register_rest' ) );
