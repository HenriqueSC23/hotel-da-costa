<?php
/** Google Business Profile: server-only authorization, synchronization and public cache. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class HDC_LP_Google_Reviews {

	const SETTINGS = 'hdc_lp_reviews_settings';
	const CACHE = 'hdc_lp_reviews_cache';
	const CRON = 'hdc_lp_reviews_daily_sync';
	const CLEANUP = 'hdc_lp_reviews_daily_cleanup';
	const TTL = 7 * DAY_IN_SECONDS;

	public static function init() {
		add_action( self::CRON, array( __CLASS__, 'sync' ) );
		add_action( self::CLEANUP, array( __CLASS__, 'purge_expired' ) );
		add_action( 'init', array( __CLASS__, 'schedule' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest' ) );
		add_action( 'template_redirect', static function () {
			if ( hdc_lp_is_landing_page() ) {
				// Reviews must not outlive the server cache through a browser/page-cache copy.
				if ( ! defined( 'DONOTCACHEPAGE' ) ) { define( 'DONOTCACHEPAGE', true ); }
				nocache_headers();
			}
		}, 0 );
	}

	public static function settings() {
		return wp_parse_args( get_option( self::SETTINGS, array() ), array(
			'stars' => array( 4, 5 ), 'require_comment' => true, 'limit' => 20, 'profile' => array(),
		) );
	}

	/** Salts stay in wp-config.php, not in the plugin or its exported ZIP. */
	public static function store_private( $name, $value ) {
		if ( ! function_exists( 'openssl_encrypt' ) ) {
			return new WP_Error( 'hdc_lp_crypto', 'O servidor precisa da extensão OpenSSL para proteger a conexão.' );
		}
		try { $iv = random_bytes( 12 ); } catch ( Exception $error ) {
			return new WP_Error( 'hdc_lp_crypto', 'Não foi possível proteger os dados no servidor.' );
		}
		$key = hash( 'sha256', wp_salt( 'auth' ) . wp_salt( 'secure_auth' ), true );
		$tag = '';
		$cipher = openssl_encrypt( wp_json_encode( $value ), 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag, $name );
		if ( false === $cipher ) { return new WP_Error( 'hdc_lp_crypto', 'Não foi possível proteger os dados no servidor.' ); }
		if ( ! update_option( $name, 'v1:' . base64_encode( $iv . $tag . $cipher ), false ) ) {
			return new WP_Error( 'hdc_lp_storage', 'Não foi possível salvar os dados protegidos no banco. O cache anterior foi preservado.' );
		}
		return true;
	}

	public static function private_data( $name ) {
		$stored = get_option( $name, '' );
		if ( ! is_string( $stored ) || 0 !== strpos( $stored, 'v1:' ) || ! function_exists( 'openssl_decrypt' ) ) { return array(); }
		$bytes = base64_decode( substr( $stored, 3 ), true );
		if ( false === $bytes || strlen( $bytes ) < 29 ) { return array(); }
		$key = hash( 'sha256', wp_salt( 'auth' ) . wp_salt( 'secure_auth' ), true );
		$json = openssl_decrypt( substr( $bytes, 28 ), 'aes-256-gcm', $key, OPENSSL_RAW_DATA, substr( $bytes, 0, 12 ), substr( $bytes, 12, 16 ), $name );
		$data = is_string( $json ) ? json_decode( $json, true ) : null;
		return is_array( $data ) ? $data : array();
	}

	public static function connected() {
		$tokens = self::private_data( 'hdc_lp_reviews_tokens' );
		return ! empty( $tokens['refresh_token'] );
	}

	public static function redirect_uri() {
		return admin_url( 'admin-post.php?action=hdc_lp_google_oauth_callback' );
	}

	public static function schedule() {
		if ( ! wp_next_scheduled( self::CLEANUP ) ) { wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', self::CLEANUP ); }
		$settings = self::settings();
		if ( ! HDC_LP_Trustindex_Reviews::selected() && self::connected() && ! empty( $settings['profile']['parent'] ) ) {
			if ( ! wp_next_scheduled( self::CRON ) ) { wp_schedule_event( time() + DAY_IN_SECONDS, 'daily', self::CRON ); }
		} else { wp_clear_scheduled_hook( self::CRON ); }
	}

	public static function deactivate() {
		wp_clear_scheduled_hook( self::CRON );
		wp_clear_scheduled_hook( self::CLEANUP );
		delete_option( self::CACHE );
		delete_option( 'hdc_lp_reviews_sync_lock' );
	}

	public static function disconnect( $revoke = true ) {
		$tokens = self::private_data( 'hdc_lp_reviews_tokens' );
		if ( $revoke && ! empty( $tokens['refresh_token'] ) ) {
			wp_remote_post( 'https://oauth2.googleapis.com/revoke', array(
				'timeout' => 10, 'redirection' => 0, 'body' => array( 'token' => $tokens['refresh_token'] ),
			) );
		}
		delete_option( 'hdc_lp_reviews_tokens' );
		delete_option( 'hdc_lp_reviews_profiles' );
		delete_option( self::CACHE );
		wp_clear_scheduled_hook( self::CRON );
		$settings = self::settings();
		$settings['profile'] = array();
		update_option( self::SETTINGS, $settings, false );
	}

	/** Never persist or echo the raw provider error (it may contain credentials). */
	public static function diagnose( $result ) {
		$data = array( 'time' => time(), 'ok' => ! is_wp_error( $result ), 'message' => is_wp_error( $result ) ? $result->get_error_message() : (string) $result );
		update_option( 'hdc_lp_reviews_diagnostic', $data, false );
		return $result;
	}

	public static function token_request( $body ) {
		$credentials = self::private_data( 'hdc_lp_reviews_credentials' );
		if ( empty( $credentials['client_id'] ) || empty( $credentials['client_secret'] ) ) {
			return new WP_Error( 'hdc_lp_credentials', 'Configure as credenciais OAuth antes de conectar.' );
		}
		$response = wp_remote_post( 'https://oauth2.googleapis.com/token', array(
			'timeout' => 20, 'redirection' => 0, 'limit_response_size' => 1048576,
			'body' => array_merge( $body, $credentials ),
		) );
		if ( is_wp_error( $response ) ) { return new WP_Error( 'hdc_lp_network', 'O Google não respondeu. Verifique a conexão do servidor e tente novamente.' ); }
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( is_array( $data ) && isset( $data['error'] ) && 'invalid_grant' === $data['error'] ) {
			self::disconnect( false );
			return new WP_Error( 'hdc_lp_revoked', 'A autorização expirou ou foi revogada. Conecte a conta novamente.' );
		}
		if ( 200 !== wp_remote_retrieve_response_code( $response ) || empty( $data['access_token'] ) || ! is_string( $data['access_token'] ) ) {
			return new WP_Error( 'hdc_lp_oauth', 'O Google recusou a autorização. Confira client ID, segredo e URI de redirecionamento no Google Cloud.' );
		}
		$previous = self::private_data( 'hdc_lp_reviews_tokens' );
		$refresh = isset( $data['refresh_token'] ) ? $data['refresh_token'] : ( 'refresh_token' === ( $body['grant_type'] ?? '' ) ? ( $previous['refresh_token'] ?? '' ) : '' );
		if ( ! is_string( $refresh ) || '' === $refresh ) { return new WP_Error( 'hdc_lp_offline', 'O Google não concedeu acesso offline. Conecte novamente e confirme o consentimento.' ); }
		$tokens = array( 'access_token' => $data['access_token'], 'refresh_token' => $refresh, 'expires' => time() + min( 7200, max( 60, absint( $data['expires_in'] ?? 3600 ) ) ) );
		$saved = self::store_private( 'hdc_lp_reviews_tokens', $tokens );
		return is_wp_error( $saved ) ? $saved : $tokens['access_token'];
	}

	public static function access_token( $force = false ) {
		$tokens = self::private_data( 'hdc_lp_reviews_tokens' );
		if ( ! $force && ! empty( $tokens['access_token'] ) && ( $tokens['expires'] ?? 0 ) > time() + 60 ) { return $tokens['access_token']; }
		if ( empty( $tokens['refresh_token'] ) ) { return new WP_Error( 'hdc_lp_not_connected', 'Conecte uma conta autorizada a administrar o perfil do hotel.' ); }
		return self::token_request( array( 'grant_type' => 'refresh_token', 'refresh_token' => $tokens['refresh_token'] ) );
	}

	/** GET only, fixed Google hosts and no redirects; callers never supply URLs from requests. */
	public static function api_get( $url, $retry = true ) {
		$allowed = array( 'mybusiness.googleapis.com', 'mybusinessaccountmanagement.googleapis.com', 'mybusinessbusinessinformation.googleapis.com' );
		if ( 'https' !== wp_parse_url( $url, PHP_URL_SCHEME ) || ! in_array( wp_parse_url( $url, PHP_URL_HOST ), $allowed, true ) ) {
			return new WP_Error( 'hdc_lp_endpoint', 'Endpoint da integração inválido.' );
		}
		$token = self::access_token();
		if ( is_wp_error( $token ) ) { return $token; }
		$response = wp_remote_get( $url, array( 'timeout' => 20, 'redirection' => 0, 'limit_response_size' => 2097152, 'headers' => array( 'Authorization' => 'Bearer ' . $token ) ) );
		if ( is_wp_error( $response ) ) { return new WP_Error( 'hdc_lp_network', 'Não foi possível consultar o Google. O último cache válido foi preservado.' ); }
		$code = wp_remote_retrieve_response_code( $response );
		if ( 401 === $code && $retry ) {
			$renewed = self::access_token( true );
			return is_wp_error( $renewed ) ? $renewed : self::api_get( $url, false );
		}
		if ( 401 === $code ) {
			self::disconnect( false );
			return new WP_Error( 'hdc_lp_revoked', 'O acesso foi recusado. Conecte novamente uma conta autorizada.' );
		}
		if ( 403 === $code ) { return new WP_Error( 'hdc_lp_forbidden', 'Acesso negado. Confira a aprovação/quota do projeto Google Cloud, as APIs habilitadas e a permissão da conta no perfil.' ); }
		if ( 429 === $code ) { return new WP_Error( 'hdc_lp_quota', 'O Google limitou as consultas. Aguarde antes de baixar novamente.' ); }
		if ( 200 !== $code ) { return new WP_Error( 'hdc_lp_api', 'A consulta ao Google falhou (HTTP ' . absint( $code ) . '). O último cache válido foi preservado.' ); }
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		return is_array( $data ) ? $data : new WP_Error( 'hdc_lp_json', 'Resposta inválida do Google. O cache não foi substituído.' );
	}

	private static function list_pages( $url, $field, $maximum_pages = 10 ) {
		$items = array(); $page = '';
		for ( $index = 0; $index < $maximum_pages; $index++ ) {
			$data = self::api_get( $page ? add_query_arg( 'pageToken', $page, $url ) : $url );
			if ( is_wp_error( $data ) ) { return $data; }
			if ( isset( $data[ $field ] ) && ! is_array( $data[ $field ] ) ) { return new WP_Error( 'hdc_lp_json', 'Lista de perfis inválida.' ); }
			$items = array_merge( $items, $data[ $field ] ?? array() );
			$page = $data['nextPageToken'] ?? '';
			if ( ! is_string( $page ) ) { return new WP_Error( 'hdc_lp_json', 'Paginação de perfis inválida.' ); }
			if ( ! $page ) { return $items; }
		}
		return new WP_Error( 'hdc_lp_profiles_limit', 'Esta conta contém perfis demais para a seleção automática. Use uma conta com acesso direto ao perfil do hotel.' );
	}

	public static function google_url( $url ) {
		$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
		if ( 'https' !== wp_parse_url( $url, PHP_URL_SCHEME ) || ! preg_match( '/(^|\.)google\.com(?:\.br)?$/', $host ) && ! in_array( $host, array( 'maps.app.goo.gl', 'g.page' ), true ) ) { return ''; }
		return esc_url_raw( $url );
	}

	public static function profiles() {
		$accounts = self::list_pages( 'https://mybusinessaccountmanagement.googleapis.com/v1/accounts?pageSize=20', 'accounts' );
		if ( is_wp_error( $accounts ) ) { return self::diagnose( $accounts ); }
		$profiles = array();
		foreach ( $accounts as $account ) {
			if ( empty( $account['name'] ) || ! preg_match( '#^accounts/[0-9]+$#', $account['name'] ) ) { continue; }
			$locations = self::list_pages( 'https://mybusinessbusinessinformation.googleapis.com/v1/' . $account['name'] . '/locations?readMask=name,title,storefrontAddress,metadata&pageSize=100', 'locations' );
			if ( is_wp_error( $locations ) ) { return self::diagnose( $locations ); }
			foreach ( $locations as $location ) {
				if ( empty( $location['name'] ) || ! preg_match( '#^locations/[0-9]+$#', $location['name'] ) ) { continue; }
				$profiles[] = array(
					'parent' => $account['name'] . '/' . $location['name'],
					'title' => sanitize_text_field( $location['title'] ?? '' ),
					'address' => sanitize_text_field( implode( ', ', $location['storefrontAddress']['addressLines'] ?? array() ) ),
					'googleUrl' => self::google_url( $location['metadata']['mapsUri'] ?? '' ),
				);
			}
		}
		$saved = self::store_private( 'hdc_lp_reviews_profiles', array( 'time' => time(), 'items' => $profiles ) );
		if ( is_wp_error( $saved ) ) { return self::diagnose( $saved ); }
		self::diagnose( count( $profiles ) . ' perfil(is) disponível(is). Selecione explicitamente o perfil do hotel.' );
		return $profiles;
	}

	public static function purge_expired() {
		$cache = self::private_data( self::CACHE );
		if ( get_option( self::CACHE, '' ) && ( empty( $cache['time'] ) || time() - $cache['time'] >= self::TTL ) ) { delete_option( self::CACHE ); }
		$profiles = self::private_data( 'hdc_lp_reviews_profiles' );
		if ( get_option( 'hdc_lp_reviews_profiles', '' ) && ( empty( $profiles['time'] ) || time() - $profiles['time'] >= DAY_IN_SECONDS ) ) { delete_option( 'hdc_lp_reviews_profiles' ); }
	}

	public static function sync() {
		if ( HDC_LP_Trustindex_Reviews::selected() ) {
			return new WP_Error( 'hdc_lp_reviews_source', 'A fonte atual é Trustindex. Faça novos downloads no painel do Trustindex ou selecione a API oficial do Google.' );
		}
		self::purge_expired();
		$settings = self::settings();
		$parent = $settings['profile']['parent'] ?? '';
		if ( ! self::connected() || ! preg_match( '#^accounts/[0-9]+/locations/[0-9]+$#', $parent ) ) {
			return self::diagnose( new WP_Error( 'hdc_lp_profile', 'Conecte a conta e selecione o perfil do hotel antes de baixar avaliações.' ) );
		}
		$lock = get_option( 'hdc_lp_reviews_sync_lock', 0 );
		if ( $lock && time() - $lock > 5 * MINUTE_IN_SECONDS ) { delete_option( 'hdc_lp_reviews_sync_lock' ); }
		if ( ! add_option( 'hdc_lp_reviews_sync_lock', time(), '', false ) ) { return new WP_Error( 'hdc_lp_busy', 'Uma sincronização já está em andamento.' ); }
		try {
			if ( time() - (int) get_option( 'hdc_lp_reviews_last_attempt', 0 ) < MINUTE_IN_SECONDS ) { return new WP_Error( 'hdc_lp_cooldown', 'Aguarde um minuto entre as tentativas de atualização.' ); }
			update_option( 'hdc_lp_reviews_last_attempt', time(), false );
			$items = array(); $page = ''; $seen_pages = array();
			$ratings = array( 'ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4, 'FIVE' => 5 );
			for ( $index = 0; $index < 2; $index++ ) {
				$url = 'https://mybusiness.googleapis.com/v4/' . $parent . '/reviews?pageSize=50&orderBy=updateTime%20desc';
				$data = self::api_get( $page ? add_query_arg( 'pageToken', $page, $url ) : $url );
				if ( is_wp_error( $data ) ) { return self::diagnose( $data ); }
				if ( ! isset( $data['reviews'] ) && ! isset( $data['totalReviewCount'] ) || isset( $data['reviews'] ) && ( ! is_array( $data['reviews'] ) || count( $data['reviews'] ) > 50 ) ) {
					return self::diagnose( new WP_Error( 'hdc_lp_json', 'Lista de avaliações inválida. O cache não foi substituído.' ) );
				}
				foreach ( $data['reviews'] ?? array() as $review ) {
					if ( ! is_array( $review ) || empty( $review['reviewId'] ) || ! is_string( $review['reviewId'] ) || ! is_string( $review['starRating'] ?? null ) || ! isset( $ratings[ $review['starRating'] ] ) || empty( $review['updateTime'] ) || ! is_string( $review['updateTime'] ) || false === strtotime( $review['updateTime'] ) ) {
						return self::diagnose( new WP_Error( 'hdc_lp_json', 'Uma avaliação retornou dados inválidos. O cache não foi substituído.' ) );
					}
					$reviewer = is_array( $review['reviewer'] ?? null ) ? $review['reviewer'] : array();
					$items[ $review['reviewId'] ] = array(
						'id' => $review['reviewId'], 'rating' => $ratings[ $review['starRating'] ],
						'author' => ! empty( $reviewer['isAnonymous'] ) ? 'Usuário do Google' : sanitize_text_field( $reviewer['displayName'] ?? 'Usuário do Google' ),
						'avatar' => ! empty( $reviewer['isAnonymous'] ) ? '' : self::avatar_url( $reviewer['profilePhotoUrl'] ?? '' ),
						'comment' => is_string( $review['comment'] ?? '' ) ? $review['comment'] ?? '' : '',
						'date' => self::review_date( $review['createTime'] ?? $review['updateTime'] ), 'updated' => $review['updateTime'],
					);
				}
				$page = $data['nextPageToken'] ?? '';
				if ( ! is_string( $page ) || $page && isset( $seen_pages[ $page ] ) ) { return self::diagnose( new WP_Error( 'hdc_lp_json', 'Paginação inválida. O cache não foi substituído.' ) ); }
				$seen_pages[ $page ] = true;
				if ( ! $page ) { break; }
			}
			$items = array_values( $items );
			usort( $items, static function ( $first, $second ) { return strcmp( $second['updated'], $first['updated'] ); } );
			// Do not commit a response if the administrator changed profiles during the request.
			if ( $parent !== ( self::settings()['profile']['parent'] ?? '' ) || ! self::connected() ) { return new WP_Error( 'hdc_lp_changed', 'A conexão mudou durante a sincronização. Baixe novamente.' ); }
			$saved = self::store_private( self::CACHE, array( 'time' => time(), 'parent' => $parent, 'items' => array_slice( $items, 0, 100 ) ) );
			if ( is_wp_error( $saved ) ) { return self::diagnose( $saved ); }
			return self::diagnose( count( $items ) . ' avaliações recentes baixadas. Os filtros são aplicados na exibição.' );
		} finally { delete_option( 'hdc_lp_reviews_sync_lock' ); }
	}

	public static function avatar_url( $url ) {
		$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
		return 'https' === wp_parse_url( $url, PHP_URL_SCHEME ) && preg_match( '/(^|\.)googleusercontent\.com$/', $host ) ? esc_url_raw( $url ) : '';
	}

	private static function review_date( $value ) {
		$timestamp = is_string( $value ) ? strtotime( $value ) : false;
		return false === $timestamp ? '' : gmdate( 'Y-m-d', $timestamp );
	}

	/** The only data available to visitors: selected, current cached reviews. No network calls. */
	public static function public_data() {
		if ( HDC_LP_Trustindex_Reviews::selected() ) { return HDC_LP_Trustindex_Reviews::public_data( self::settings() ); }
		self::purge_expired();
		$settings = self::settings();
		$cache = self::private_data( self::CACHE );
		$google_url = self::google_url( $settings['profile']['googleUrl'] ?? '' );
		$result = array( 'status' => 'unavailable', 'items' => array(), 'googleUrl' => $google_url ?: 'https://www.google.com/maps/search/?api=1&query=Hotel+da+Costa+by+Nobile+Aracaju', 'lastSynced' => null, 'source' => 'google', 'expiresAt' => null );
		if ( empty( $cache['time'] ) || ( $cache['parent'] ?? '' ) !== ( $settings['profile']['parent'] ?? '' ) ) { return $result; }
		$result['lastSynced'] = gmdate( 'c', $cache['time'] );
		$result['expiresAt'] = gmdate( 'c', $cache['time'] + 7 * DAY_IN_SECONDS );
		foreach ( $cache['items'] ?? array() as $item ) {
			if ( ! in_array( (int) $item['rating'], $settings['stars'], true ) || $settings['require_comment'] && '' === trim( $item['comment'] ) ) { continue; }
			unset( $item['updated'] );
			$item['author'] = $item['author'] ?: 'Usuário do Google';
			$item['dateLabel'] = $item['date'] ? wp_date( 'd/m/Y', strtotime( $item['date'] . 'T12:00:00Z' ), new DateTimeZone( 'UTC' ) ) : '';
			$result['items'][] = $item;
			if ( count( $result['items'] ) >= max( 1, min( 50, (int) $settings['limit'] ) ) ) { break; }
		}
		$result['status'] = $result['items'] ? 'ready' : 'empty';
		return $result;
	}

	public static function register_rest() {
		register_rest_route( 'hdc-lp/v1', '/reviews', array(
			'methods' => WP_REST_Server::READABLE, 'permission_callback' => '__return_true',
			'callback' => static function () {
				$response = rest_ensure_response( self::public_data() );
				$response->header( 'Cache-Control', 'no-store, max-age=0' );
				return $response;
			},
		) );
	}
}

HDC_LP_Google_Reviews::init();
