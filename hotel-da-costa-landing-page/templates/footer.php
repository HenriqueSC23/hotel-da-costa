<?php
/** Isolated LP footer. Never loads or changes the active theme footer. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$hdc_lp_privacy_url = esc_url_raw( apply_filters( 'hdc_lp_privacy_policy_url', get_privacy_policy_url() ), array( 'https', 'http' ) );
?>
<footer class="hdc-lp hdc-lp-footer" id="hdc-lp-footer" aria-label="Rodapé do Hotel da Costa">
	<div class="hdc-lp-container hdc-lp-footer__container">
		<img class="hdc-lp-footer__logo" src="<?php echo esc_url( $hdc_lp_asset_url . 'footer-logo.png' ); ?>" width="240" height="240" alt="Hotel da Costa by Nobile" loading="lazy" decoding="async">
		<div class="hdc-lp-footer__content">
			<p class="hdc-lp-footer__copyright">Site desenvolvido e criado pela agência Checkin © 2026. Todos os direitos reservados.</p>
			<?php if ( $hdc_lp_privacy_url ) : ?>
				<a class="hdc-lp-footer__privacy" href="<?php echo esc_url( $hdc_lp_privacy_url ); ?>">Política de privacidade</a>
			<?php else : ?>
				<span class="hdc-lp-footer__privacy hdc-lp-footer__privacy--unconfigured">Política de privacidade</span>
			<?php endif; ?>
		</div>
	</div>
</footer>
