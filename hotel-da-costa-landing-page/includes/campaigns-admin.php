<?php
/** Administrator-only campaign editing. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class HDC_LP_Campaigns_Admin {
	private static $screen = '';

	public static function menu() {
		$icon = 'data:image/svg+xml;base64,' . base64_encode( file_get_contents( HDC_LP_DIR . 'assets/icons/phosphor-bed.svg' ) );
		self::$screen = add_menu_page( 'Campanhas — Hotel da Costa', 'Hotel da Costa', 'manage_options', 'hdc-lp-campaigns', array( __CLASS__, 'page' ), $icon );
		add_submenu_page( 'hdc-lp-campaigns', 'Campanhas — Hotel da Costa', 'Campanhas', 'manage_options', 'hdc-lp-campaigns', array( __CLASS__, 'page' ) );
	}

	public static function assets( $hook ) {
		if ( self::$screen !== $hook || ! current_user_can( 'manage_options' ) ) { return; }
		wp_enqueue_media();
		wp_enqueue_style( 'hdc-lp-campaigns-admin', HDC_LP_URL . 'assets/css/campaigns-admin.css', array(), HDC_LP_VERSION );
		wp_enqueue_script( 'hdc-lp-campaigns-admin', HDC_LP_URL . 'assets/js/campaigns-admin.js', array( 'media-views' ), HDC_LP_VERSION, true );
	}

	public static function save() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Você não tem permissão para gerenciar campanhas.', '', array( 'response' => 403 ) ); }
		check_admin_referer( 'hdc_lp_save_campaigns' );
		$input = wp_unslash( $_POST['hdc_lp_campaigns'] ?? array() );
		$result = isset( $_POST['hdc_lp_campaigns_complete'] ) && 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' )
			? HDC_LP_Campaigns::validate( $input ) : new WP_Error( 'hdc_lp_campaigns_incomplete', 'O formulário não foi recebido por completo. Recarregue a tela e tente novamente.' );
		$notice = array( 'type' => 'success', 'message' => 'Campanhas salvas. A landing page já usa a nova configuração.' );
		if ( is_wp_error( $result ) ) {
			$notice = array( 'type' => 'error', 'message' => $result->get_error_message() );
		} else {
			$saved = get_option( HDC_LP_Campaigns::OPTION, null );
			if ( $saved !== $result && ! update_option( HDC_LP_Campaigns::OPTION, $result, false ) ) {
				$notice = array( 'type' => 'error', 'message' => 'Não foi possível salvar as campanhas. Tente novamente.' );
			}
		}
		set_transient( 'hdc_lp_campaigns_notice_' . get_current_user_id(), $notice, 60 );
		wp_safe_redirect( admin_url( 'admin.php?page=hdc-lp-campaigns' ) );
		exit;
	}

	public static function row( $item, $index ) {
		$image = HDC_LP_Campaigns::image( $item );
		$prefix = 'hdc_lp_campaigns[' . $index . ']';
		?>
		<fieldset class="hdc-lp-campaigns-admin__row" data-hdc-lp-campaign-row>
			<legend>Campanha <span data-hdc-lp-campaign-position><?php echo esc_html( $index + 1 ); ?></span></legend>
			<input type="hidden" data-hdc-lp-campaign-field="id" name="<?php echo esc_attr( $prefix ); ?>[id]" value="<?php echo esc_attr( $item['id'] ); ?>">
			<input type="hidden" data-hdc-lp-campaign-field="attachment_id" name="<?php echo esc_attr( $prefix ); ?>[attachment_id]" value="<?php echo esc_attr( $item['attachment_id'] ); ?>">
			<input type="hidden" data-hdc-lp-campaign-field="bundled_key" name="<?php echo esc_attr( $prefix ); ?>[bundled_key]" value="<?php echo esc_attr( $item['bundled_key'] ); ?>">
			<div class="hdc-lp-campaigns-admin__content">
				<div class="hdc-lp-campaigns-admin__media">
					<div class="hdc-lp-campaigns-admin__thumbnail">
						<img data-hdc-lp-campaign-image <?php if ( $image ) : ?>src="<?php echo esc_url( $image['url'] ); ?>"<?php endif; ?> alt="" <?php echo $image ? '' : 'hidden'; ?>>
					</div>
					<p data-hdc-lp-campaign-image-status><?php echo $image ? 'Imagem completa, sem recorte.' : 'Imagem indisponível. Selecione ou substitua a imagem; esta campanha não aparece na landing page.'; ?></p>
					<button type="button" class="button" data-hdc-lp-campaign-select>Selecionar ou substituir imagem</button>
				</div>
				<div class="hdc-lp-campaigns-admin__fields">
					<label>Nome interno <input type="text" data-hdc-lp-campaign-field="name" name="<?php echo esc_attr( $prefix ); ?>[name]" value="<?php echo esc_attr( $item['name'] ); ?>" required></label>
					<label>Texto alternativo <textarea data-hdc-lp-campaign-field="alt" name="<?php echo esc_attr( $prefix ); ?>[alt]" rows="3" required><?php echo esc_textarea( $item['alt'] ); ?></textarea></label>
					<p class="description">Descreva a arte e suas informações para quem usa leitor de tela.</p>
					<label><input type="checkbox" data-hdc-lp-campaign-field="active" name="<?php echo esc_attr( $prefix ); ?>[active]" value="1" <?php checked( ! empty( $item['active'] ) ); ?>> Ativa</label>
				</div>
			</div>
			<div class="hdc-lp-campaigns-admin__actions">
				<button type="button" class="button" draggable="true" data-hdc-lp-campaign-drag aria-label="Arrastar campanha para reordenar">Arrastar para reordenar</button>
				<button type="button" class="button" data-hdc-lp-campaign-up>Mover para cima</button>
				<button type="button" class="button" data-hdc-lp-campaign-down>Mover para baixo</button>
				<button type="button" class="button" data-hdc-lp-campaign-remove>Remover campanha</button>
			</div>
		</fieldset>
		<?php
	}

	public static function page() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Você não tem permissão para gerenciar campanhas.', '', array( 'response' => 403 ) ); }
		$notice_key = 'hdc_lp_campaigns_notice_' . get_current_user_id();
		$notice = get_transient( $notice_key );
		delete_transient( $notice_key );
		?>
		<div class="wrap hdc-lp-campaigns-admin">
			<h1>Campanhas — Hotel da Costa</h1>
			<?php if ( is_array( $notice ) ) : ?>
				<div class="notice notice-<?php echo esc_attr( $notice['type'] ); ?> is-dismissible"><p><?php echo esc_html( $notice['message'] ); ?></p></div>
			<?php endif; ?>
			<p>Gerencie as imagens da seção de ofertas. As alterações são aplicadas ao clicar em <strong>Salvar campanhas</strong>.</p>
			<p>Use JPEG, PNG ou WebP. Recomendamos proporção 4:5; as imagens aparecem completas, sem recorte. Remover uma campanha mantém sua imagem na Biblioteca de Mídia.</p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-hdc-lp-campaign-form>
				<input type="hidden" name="action" value="hdc_lp_save_campaigns">
				<?php wp_nonce_field( 'hdc_lp_save_campaigns' ); ?>
				<div data-hdc-lp-campaign-list>
					<?php foreach ( HDC_LP_Campaigns::all() as $index => $item ) { self::row( $item, $index ); } ?>
				</div>
				<p data-hdc-lp-campaign-empty <?php echo HDC_LP_Campaigns::all() ? 'hidden' : ''; ?>>Nenhuma campanha cadastrada. Sem campanhas ativas com imagem válida, toda a seção de ofertas fica oculta.</p>
				<p><button type="button" class="button" data-hdc-lp-campaign-add>Adicionar campanha</button></p>
				<input type="hidden" name="hdc_lp_campaigns_complete" value="1">
				<?php submit_button( 'Salvar campanhas' ); ?>
				<p class="screen-reader-text" aria-live="polite" data-hdc-lp-campaign-announcement></p>
				<noscript><p>A seleção de imagens e a ordenação precisam de JavaScript habilitado no navegador.</p></noscript>
			</form>
			<template data-hdc-lp-campaign-template><?php self::row( array( 'id' => '', 'name' => '', 'attachment_id' => 0, 'bundled_key' => '', 'alt' => '', 'active' => true ), 0 ); ?></template>
		</div>
		<?php
	}
}
add_action( 'admin_menu', array( 'HDC_LP_Campaigns_Admin', 'menu' ) );
add_action( 'admin_enqueue_scripts', array( 'HDC_LP_Campaigns_Admin', 'assets' ) );
add_action( 'admin_post_hdc_lp_save_campaigns', array( 'HDC_LP_Campaigns_Admin', 'save' ) );
