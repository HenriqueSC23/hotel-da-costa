<?php
/** Native WordPress settings for administrator-managed tracking. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class HDC_LP_Tracking_Admin {
	const PAGE = 'hdc-lp-tracking';
	const GROUP = 'hdc_lp_tracking_settings';

	public static function menu() {
		add_submenu_page( 'hdc-lp-campaigns', 'Monitoramento — Hotel da Costa', 'Monitoramento', 'manage_options', self::PAGE, array( __CLASS__, 'page' ) );
	}

	public static function register() {
		register_setting( self::GROUP, HDC_LP_Tracking::OPTION, array(
			'type' => 'array',
			'default' => HDC_LP_Tracking::defaults(),
			'sanitize_callback' => array( __CLASS__, 'sanitize' ),
			'show_in_rest' => false,
		) );
	}

	public static function sanitize( $input ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			add_settings_error( HDC_LP_Tracking::OPTION, 'hdc_lp_tracking_permission', 'Você não tem permissão para alterar o monitoramento.' );
			return HDC_LP_Tracking::settings();
		}
		$result = HDC_LP_Tracking::validate( $input );
		if ( is_wp_error( $result ) ) {
			add_settings_error( HDC_LP_Tracking::OPTION, $result->get_error_code(), $result->get_error_message() );
			return HDC_LP_Tracking::settings();
		}
		return $result;
	}

	public static function page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Você não tem permissão para acessar o monitoramento.', '', array( 'response' => 403 ) );
		}
		$settings = HDC_LP_Tracking::settings();
		?>
		<div class="wrap hdc-lp-tracking-admin">
			<h1>Monitoramento — Hotel da Costa</h1>
			<?php settings_errors(); ?>
			<p>Configure o Google Tag Manager da landing page. Os códigos de Head e Body são gerados automaticamente a partir do ID do contêiner.</p>
			<form action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="post">
				<?php settings_fields( self::GROUP ); ?>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">Monitoramento</th>
							<td><label for="hdc-lp-tracking-enabled"><input type="checkbox" id="hdc-lp-tracking-enabled" name="hdc_lp_tracking[enabled]" value="1" <?php checked( $settings['enabled'] ); ?>> Ativar Google Tag Manager na landing page</label></td>
						</tr>
						<tr>
							<th scope="row"><label for="hdc-lp-tracking-container">ID do contêiner</label></th>
							<td>
								<input type="text" class="regular-text" id="hdc-lp-tracking-container" name="hdc_lp_tracking[container_id]" value="<?php echo esc_attr( $settings['container_id'] ); ?>" maxlength="64" autocomplete="off" spellcheck="false" aria-describedby="hdc-lp-tracking-container-help">
								<p class="description" id="hdc-lp-tracking-container-help">Exemplo: <code>GTM-KWXBNWVF</code>. Informe apenas o ID, sem colar scripts.</p>
							</td>
						</tr>
					</tbody>
				</table>
				<?php submit_button( 'Salvar monitoramento' ); ?>
			</form>
			<p>Esta configuração se aplica somente às páginas que usam o template Landing Page — Hotel da Costa.</p>
		</div>
		<?php
	}
}
add_action( 'admin_menu', array( 'HDC_LP_Tracking_Admin', 'menu' ), 30 );
add_action( 'admin_init', array( 'HDC_LP_Tracking_Admin', 'register' ) );
