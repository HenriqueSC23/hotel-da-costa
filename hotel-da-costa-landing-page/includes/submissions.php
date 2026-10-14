<?php
/** Public reservation form and private submission storage. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class HDC_LP_Submissions {
	const SCHEMA_VERSION = '1';
	const FORM_ID = 'hdc-lp-landing-page';
	const UTM_FIELDS = array( 'utm_term', 'utm_campaign', 'utm_content', 'utm_source', 'utm_medium' );
	const DDDS = array( '11','12','13','14','15','16','17','18','19','21','22','24','27','28','31','32','33','34','35','37','38','41','42','43','44','45','46','47','48','49','51','53','54','55','61','62','63','64','65','66','67','68','69','71','73','74','75','77','79','81','82','83','84','85','86','87','88','89','91','92','93','94','95','96','97','98','99' );

	public static function table() { global $wpdb; return $wpdb->prefix . 'hdc_lp_submissions'; }

	public static function install() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$table = self::table();
		$collation = $wpdb->get_charset_collate();
		dbDelta( "CREATE TABLE $table (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			submission_key varchar(36) NOT NULL,
			payload_hash varchar(64) NOT NULL,
			form_id varchar(64) NOT NULL,
			form_name varchar(100) NOT NULL,
			page_id bigint(20) unsigned NOT NULL,
			page_title varchar(200) NOT NULL,
			page_url text NOT NULL,
			name varchar(150) NOT NULL,
			email varchar(254) NOT NULL,
			phone varchar(11) NOT NULL,
			utm_term varchar(200) NOT NULL DEFAULT '',
			utm_campaign varchar(200) NOT NULL DEFAULT '',
			utm_content varchar(200) NOT NULL DEFAULT '',
			utm_source varchar(200) NOT NULL DEFAULT '',
			utm_medium varchar(200) NOT NULL DEFAULT '',
			created_utc datetime NOT NULL,
			is_read tinyint(1) NOT NULL DEFAULT 0,
			trashed_at datetime DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY submission_key (submission_key),
			KEY created_utc (created_utc),
			KEY page_created (page_id,created_utc),
			KEY form_created (form_id,created_utc),
			KEY read_trash (is_read,trashed_at)
		) $collation;" );
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) ) ) === $table ) {
			update_option( 'hdc_lp_submissions_schema', self::SCHEMA_VERSION, false );
		}
	}

	public static function upgrade() {
		if ( self::SCHEMA_VERSION !== get_option( 'hdc_lp_submissions_schema' ) ) { self::install(); }
	}

	/** The campaign default is server-owned; retain the existing integration filter. */
	public static function whatsapp_url() {
		$default = 'https://api.whatsapp.com/send/?phone=5579998738067&text=Ol%C3%A1!%20Gostaria%20de%20consultar%20disponibilidade,%20valores%20e%20op%C3%A7%C3%B5es%20de%20acomoda%C3%A7%C3%A3o%20para%20minha%20estadia%20no%20hotel%20Da%20Costa%20em%20Aracaju%20-%20SE!';
		$url = esc_url_raw( apply_filters( 'hdc_lp_whatsapp_url', $default ), array( 'https' ) );
		$parts = wp_parse_url( $url );
		return $parts && 'https' === ( $parts['scheme'] ?? '' ) && empty( $parts['user'] ) && empty( $parts['pass'] ) && empty( $parts['port'] ) && in_array( strtolower( $parts['host'] ?? '' ), array( 'api.whatsapp.com', 'wa.me' ), true ) ? $url : $default;
	}

	public static function page( $id ) {
		$page = get_post( $id );
		return $page && 'page' === $page->post_type && 'publish' === $page->post_status && ! post_password_required( $page ) && HDC_LP_TEMPLATE_SLUG === get_page_template_slug( $id ) ? $page : null;
	}

	public static function length( $text ) { return preg_match_all( '/./us', $text ); }
	public static function phone( $value ) {
		$digits = preg_replace( '/\D/', '', $value );
		if ( 13 === strlen( $digits ) && '55' === substr( $digits, 0, 2 ) ) { $digits = substr( $digits, 2 ); }
		return $digits;
	}
	public static function valid_phone( $phone ) {
		return (bool) preg_match( '/^[1-9][0-9]9[0-9]{8}$/', $phone ) && in_array( substr( $phone, 0, 2 ), self::DDDS, true );
	}
	public static function format_phone( $phone ) { return '(' . substr( $phone, 0, 2 ) . ') ' . substr( $phone, 2, 5 ) . '-' . substr( $phone, 7 ); }

	public static function register_rest() {
		register_rest_route( 'hdc-lp/v1', '/lead-form', array(
			'methods' => WP_REST_Server::READABLE,
			'permission_callback' => '__return_true',
			'callback' => static function ( $request ) {
				$id = absint( $request->get_param( 'page_id' ) );
				if ( ! self::page( $id ) ) { return new WP_Error( 'hdc_lp_page', 'O formulário desta página está indisponível.', array( 'status' => 404 ) ); }
				$response = rest_ensure_response( array( 'formId' => self::FORM_ID, 'pageId' => $id, 'nonce' => wp_create_nonce( 'hdc_lp_lead_' . $id ), 'ddds' => self::DDDS ) );
				$response->header( 'Cache-Control', 'no-store, max-age=0' );
				return $response;
			},
		) );
		register_rest_route( 'hdc-lp/v1', '/submissions', array(
			'methods' => WP_REST_Server::CREATABLE,
			'permission_callback' => array( __CLASS__, 'same_origin' ),
			'callback' => array( __CLASS__, 'submit' ),
		) );
	}

	public static function same_origin( $request ) {
		$origin = $request->get_header( 'origin' );
		if ( $origin ) {
			$accepted = array();
			foreach ( array( home_url(), site_url() ) as $url ) {
				$p = wp_parse_url( $url );
				$accepted[] = $p['scheme'] . '://' . $p['host'] . ( isset( $p['port'] ) ? ':' . $p['port'] : '' );
			}
			if ( ! in_array( $origin, $accepted, true ) ) { return new WP_Error( 'hdc_lp_origin', 'Envio não autorizado.', array( 'status' => 403 ) ); }
		}
		return true;
	}

	/** Short option lock keeps the fixed-window rate counter atomic across PHP workers. */
	private static function rate_limit() {
		global $wpdb;
		$hash = hash_hmac( 'sha256', $_SERVER['REMOTE_ADDR'] ?? 'unknown', wp_salt( 'nonce' ) );
		$key = 'hdc_lp_lead_rate_' . $hash;
		$lock = 'hdc_lp_lead_lock_' . $hash;
		$owner = (string) ( time() + 15 );
		$acquired = add_option( $lock, $owner, '', false );
		if ( ! $acquired ) {
			$expired = get_option( $lock );
			if ( $expired && (int) $expired < time() ) {
				$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name = %s AND option_value = %s", $lock, $expired ) );
				wp_cache_delete( $lock, 'options' );
				$acquired = add_option( $lock, $owner, '', false );
			}
		}
		if ( ! $acquired ) { return new WP_Error( 'hdc_lp_busy', 'Aguarde um instante e tente novamente.', array( 'status' => 429 ) ); }
		try {
			$rate = get_transient( $key );
			if ( ! is_array( $rate ) || $rate['until'] <= time() ) { $rate = array( 'count' => 0, 'until' => time() + 600 ); }
			if ( $rate['count'] >= 10 ) { return new WP_Error( 'hdc_lp_rate', 'Muitas tentativas. Aguarde dez minutos e tente novamente.', array( 'status' => 429 ) ); }
			$rate['count']++;
			set_transient( $key, $rate, max( 1, $rate['until'] - time() ) );
			return true;
		} finally {
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name = %s AND option_value = %s", $lock, $owner ) );
			wp_cache_delete( $lock, 'options' );
		}
	}

	public static function submit( $request ) {
		global $wpdb;
		$input = $request->get_json_params();
		if ( ! is_array( $input ) || strlen( $request->get_body() ) > 16384 ) { return new WP_Error( 'hdc_lp_input', 'Os dados enviados são inválidos.', array( 'status' => 400 ) ); }
		foreach ( array_merge( array( 'name', 'email', 'phone', 'page_id', 'nonce', 'submission_key', 'website' ), self::UTM_FIELDS ) as $field ) {
			$key = 'hdc_lp_' . $field;
			if ( isset( $input[ $key ] ) && ! is_scalar( $input[ $key ] ) ) { return new WP_Error( 'hdc_lp_input', 'Os dados enviados são inválidos.', array( 'status' => 400 ) ); }
		}
		$page_id = absint( $input['hdc_lp_page_id'] ?? 0 );
		$page = self::page( $page_id );
		if ( ! $page ) { return new WP_Error( 'hdc_lp_page', 'O formulário desta página está indisponível.', array( 'status' => 404 ) ); }
		if ( ! wp_verify_nonce( $input['hdc_lp_nonce'] ?? '', 'hdc_lp_lead_' . $page_id ) ) { return new WP_Error( 'hdc_lp_nonce', 'Sua sessão expirou. Tente enviar novamente.', array( 'status' => 403 ) ); }
		if ( ! empty( $input['hdc_lp_website'] ) ) { return new WP_Error( 'hdc_lp_spam', 'Não foi possível enviar. Recarregue a página e tente novamente.', array( 'status' => 400 ) ); }
		$submission_key = strtolower( (string) ( $input['hdc_lp_submission_key'] ?? '' ) );
		if ( ! preg_match( '/^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/', $submission_key ) ) { return new WP_Error( 'hdc_lp_key', 'Reabra o formulário e tente novamente.', array( 'status' => 400 ) ); }
		$data = array( 'name' => sanitize_text_field( $input['hdc_lp_name'] ?? '' ), 'email' => trim( (string) ( $input['hdc_lp_email'] ?? '' ) ), 'phone' => self::phone( (string) ( $input['hdc_lp_phone'] ?? '' ) ) );
		$errors = array();
		if ( self::length( $data['name'] ) < 2 || self::length( $data['name'] ) > 150 || ! preg_match( '/\p{L}/u', $data['name'] ) ) { $errors['hdc_lp_name'] = 'Informe seu nome, com pelo menos dois caracteres.'; }
		if ( strlen( $data['email'] ) > 254 || ! is_email( $data['email'] ) ) { $errors['hdc_lp_email'] = 'Informe um e-mail válido.'; }
		if ( ! self::valid_phone( $data['phone'] ) ) { $errors['hdc_lp_phone'] = 'Informe um celular válido com DDD: (XX) XXXXX-XXXX.'; }
		foreach ( self::UTM_FIELDS as $field ) {
			$value = sanitize_text_field( $input[ 'hdc_lp_' . $field ] ?? '' );
			if ( self::length( $value ) > 200 ) { return new WP_Error( 'hdc_lp_utm', 'Os parâmetros da campanha são muito longos. Entre em contato com o responsável pela campanha.', array( 'status' => 400 ) ); }
			$data[ $field ] = $value;
		}
		if ( $errors ) {
			$rate = self::rate_limit();
			return is_wp_error( $rate ) ? $rate : new WP_Error( 'hdc_lp_validation', 'Confira os campos destacados.', array( 'status' => 422, 'fields' => $errors ) );
		}
		$hash = hash( 'sha256', wp_json_encode( array( $page_id, $data ) ) );
		$table = self::table();
		$previous = $wpdb->get_row( $wpdb->prepare( "SELECT id, payload_hash FROM $table WHERE submission_key = %s", $submission_key ), ARRAY_A );
		if ( $previous ) { return self::replay( $previous, $hash ); }
		$rate = self::rate_limit();
		if ( is_wp_error( $rate ) ) { return $rate; }
		$data = array_merge( $data, array( 'submission_key' => $submission_key, 'payload_hash' => $hash, 'form_id' => self::FORM_ID, 'form_name' => 'Landing page', 'page_id' => $page_id, 'page_title' => wp_html_excerpt( get_the_title( $page ), 200, '' ), 'page_url' => get_permalink( $page ), 'created_utc' => current_time( 'mysql', true ) ) );
		$old_suppression = $wpdb->suppress_errors( true );
		try { $saved = $wpdb->insert( $table, $data ); } finally { $wpdb->suppress_errors( $old_suppression ); }
		if ( false === $saved ) {
			// A simultaneous retry may have won the unique-key race.
			$previous = $wpdb->get_row( $wpdb->prepare( "SELECT id, payload_hash FROM $table WHERE submission_key = %s", $submission_key ), ARRAY_A );
			return $previous ? self::replay( $previous, $hash ) : new WP_Error( 'hdc_lp_storage', 'Não foi possível salvar seus dados. Tente novamente.', array( 'status' => 500 ) );
		}
		return self::success( $wpdb->insert_id, 201 );
	}

	private static function replay( $previous, $hash ) {
		return hash_equals( $previous['payload_hash'], $hash ) ? self::success( $previous['id'], 200 ) : new WP_Error( 'hdc_lp_conflict', 'Os dados deste envio foram alterados. Reabra o formulário e tente novamente.', array( 'status' => 409 ) );
	}
	private static function success( $id, $status ) {
		$response = new WP_REST_Response( array( 'id' => (int) $id, 'message' => 'Dados enviados! Abrindo o WhatsApp…', 'redirectUrl' => self::whatsapp_url() ), $status );
		$response->header( 'Cache-Control', 'no-store, max-age=0' );
		return $response;
	}
}
add_action( 'init', array( 'HDC_LP_Submissions', 'upgrade' ), 5 );
add_action( 'rest_api_init', array( 'HDC_LP_Submissions', 'register_rest' ) );
