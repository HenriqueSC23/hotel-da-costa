<?php
/** Reservation dialog, deliberately inside the LP root to inherit scoped tokens. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$hdc_lp_lead_config = array( 'pageId' => get_queried_object_id(), 'configUrl' => add_query_arg( 'page_id', get_queried_object_id(), rest_url( 'hdc-lp/v1/lead-form' ) ), 'submitUrl' => rest_url( 'hdc-lp/v1/submissions' ) );
?>
<dialog class="hdc-lp-lead-modal" id="hdc-lp-lead-modal" data-hdc-lp-lead-modal aria-labelledby="hdc-lp-lead-title" aria-describedby="hdc-lp-lead-description">
	<button class="hdc-lp-lead-modal__close" type="button" data-hdc-lp-lead-close aria-label="Fechar formulário"><span aria-hidden="true"></span></button>
	<h2 class="hdc-lp-lead-modal__title" id="hdc-lp-lead-title">Informe seus dados</h2>
	<p id="hdc-lp-lead-description">Para iniciar seu atendimento.</p>
	<form id="form_popup" class="hdc-lp-lead-form" data-hdc-lp-lead-form novalidate>
		<div class="hdc-lp-lead-form__field">
			<label for="hdc-lp-lead-name">Nome</label>
			<input id="hdc-lp-lead-name" name="hdc_lp_name" type="text" autocomplete="name" maxlength="150" placeholder="Seu nome aqui" required aria-describedby="hdc-lp-lead-name-error">
			<p class="hdc-lp-lead-form__error" id="hdc-lp-lead-name-error" data-hdc-lp-error="hdc_lp_name" hidden></p>
		</div>
		<div class="hdc-lp-lead-form__field">
			<label for="hdc-lp-lead-email">E-mail</label>
			<input id="hdc-lp-lead-email" name="hdc_lp_email" type="email" inputmode="email" autocomplete="email" maxlength="254" placeholder="exemplo@email.com" required aria-describedby="hdc-lp-lead-email-error">
			<p class="hdc-lp-lead-form__error" id="hdc-lp-lead-email-error" data-hdc-lp-error="hdc_lp_email" hidden></p>
		</div>
		<div class="hdc-lp-lead-form__field">
			<label for="hdc-lp-lead-phone">Telefone</label>
			<input id="hdc-lp-lead-phone" name="hdc_lp_phone" type="tel" inputmode="tel" autocomplete="tel" maxlength="15" placeholder="(XX) XXXXX-XXXX" required aria-describedby="hdc-lp-lead-phone-error">
			<p class="hdc-lp-lead-form__error" id="hdc-lp-lead-phone-error" data-hdc-lp-error="hdc_lp_phone" hidden></p>
		</div>
		<?php foreach ( HDC_LP_Submissions::UTM_FIELDS as $hdc_lp_utm ) : ?>
			<input type="hidden" name="<?php echo esc_attr( 'hdc_lp_' . $hdc_lp_utm ); ?>" value="">
		<?php endforeach; ?>
		<div class="hdc-lp-lead-form__honeypot" aria-hidden="true"><label for="hdc-lp-lead-website">Deixe este campo vazio</label><input id="hdc-lp-lead-website" name="hdc_lp_website" type="text" tabindex="-1" autocomplete="off"></div>
		<button class="hdc-lp-lead-form__submit" type="submit">RESERVAR VIA WHATSAPP</button>
		<p class="hdc-lp-lead-form__status" data-hdc-lp-lead-status role="status" aria-live="polite" aria-atomic="true"></p>
	</form>
</dialog>
<script type="application/json" id="hdc-lp-lead-config"><?php echo wp_json_encode( $hdc_lp_lead_config, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>
<p class="hdc-lp-lead-unavailable" data-hdc-lp-lead-unavailable role="status" hidden></p>
<noscript><p class="hdc-lp-lead-unavailable">Ative o JavaScript no navegador para preencher o formulário e reservar via WhatsApp.</p></noscript>
