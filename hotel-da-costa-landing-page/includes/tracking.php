<?php
/** Google Tag Manager output, isolated to the landing page template. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class HDC_LP_Tracking {
	const OPTION = 'hdc_lp_tracking';
	const DEFAULT_CONTAINER_ID = 'GTM-KWXBNWVF';

	public static function defaults() {
		return array( 'enabled' => true, 'container_id' => self::DEFAULT_CONTAINER_ID );
	}

	/** Only container IDs can enter the generated script and iframe URL. */
	private static function valid_id( $value ) {
		return is_string( $value ) && strlen( $value ) <= 64 && 1 === preg_match( '/^GTM-[A-Z0-9]+$/D', $value );
	}

	/** Invalid stored values fail closed instead of enabling the default container. */
	public static function settings() {
		$stored = get_option( self::OPTION, self::defaults() );
		$id = is_array( $stored ) && self::valid_id( $stored['container_id'] ?? null ) ? $stored['container_id'] : '';
		$enabled = is_array( $stored ) && in_array( $stored['enabled'] ?? false, array( true, 1, '1' ), true );
		return array( 'enabled' => $enabled && '' !== $id, 'container_id' => $id );
	}

	/** Validate the whole form before replacing either setting. */
	public static function validate( $input ) {
		if ( ! is_array( $input ) || ! isset( $input['container_id'] ) || ! is_string( $input['container_id'] ) ) {
			return new WP_Error( 'hdc_lp_tracking_incomplete', 'A configuração não foi recebida por completo. Recarregue a tela e tente novamente.' );
		}
		if ( isset( $input['enabled'] ) && ! in_array( $input['enabled'], array( true, false, 1, 0, '1', '0' ), true ) ) {
			return new WP_Error( 'hdc_lp_tracking_enabled', 'Informe um estado válido para o monitoramento.' );
		}
		$id = strtoupper( trim( $input['container_id'] ) );
		$enabled = in_array( $input['enabled'] ?? false, array( true, 1, '1' ), true );
		if ( ( '' !== $id && ! self::valid_id( $id ) ) || ( $enabled && '' === $id ) ) {
			return new WP_Error( 'hdc_lp_tracking_id', 'Informe somente um ID de contêiner válido, como GTM-KWXBNWVF. Para deixar o campo vazio, desative o monitoramento.' );
		}
		return array( 'enabled' => $enabled, 'container_id' => $id );
	}

	private static function container_id() {
		if ( ! hdc_lp_is_landing_page() ) {
			return '';
		}
		$settings = self::settings();
		return $settings['enabled'] ? $settings['container_id'] : '';
	}

	public static function head() {
		$id = self::container_id();
		if ( '' === $id ) {
			return;
		}
		?>
<!-- Google Tag Manager -->
<script id="hdc-lp-gtm">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer',<?php echo wp_json_encode( $id, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>);</script>
<!-- End Google Tag Manager -->
		<?php
	}

	public static function body() {
		$id = self::container_id();
		if ( '' === $id ) {
			return;
		}
		?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe class="hdc-lp-tracking__frame" src="<?php echo esc_url( 'https://www.googletagmanager.com/ns.html?id=' . $id ); ?>" height="0" width="0" hidden aria-hidden="true" tabindex="-1" title="Google Tag Manager"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<?php
	}
}
add_action( 'wp_head', array( 'HDC_LP_Tracking', 'head' ), -10 );
add_action( 'wp_body_open', array( 'HDC_LP_Tracking', 'body' ), -10 );
