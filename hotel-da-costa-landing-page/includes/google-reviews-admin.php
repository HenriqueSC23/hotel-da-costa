<?php
/** Native WordPress settings; no credentials or admin actions are exposed to the LP. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class HDC_LP_Google_Reviews_Admin {
	public static function init() {
		add_action( 'admin_menu', static function () {
			add_options_page( 'Hotel da Costa — Avaliações', 'Hotel da Costa — Avaliações', 'manage_options', 'hdc-lp-reviews', array( __CLASS__, 'page' ) );
		} );
		add_action( 'admin_post_hdc_lp_reviews_action', array( __CLASS__, 'action' ) );
		add_action( 'admin_post_hdc_lp_google_oauth_callback', array( __CLASS__, 'callback' ) );
	}

	private static function authorize() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Acesso restrito a administradores.', '', array( 'response' => 403 ) ); }
	}

	private static function finish( $result ) {
		HDC_LP_Google_Reviews::diagnose( $result );
		HDC_LP_Google_Reviews::schedule();
		wp_safe_redirect( admin_url( 'options-general.php?page=hdc-lp-reviews' ) );
		exit;
	}

	private static function form_start( $operation ) {
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
		echo '<input type="hidden" name="action" value="hdc_lp_reviews_action"><input type="hidden" name="hdc_lp_operation" value="' . esc_attr( $operation ) . '">';
		wp_nonce_field( 'hdc_lp_reviews_' . $operation );
	}

	public static function action() {
		self::authorize();
		$operation = sanitize_key( wp_unslash( $_POST['hdc_lp_operation'] ?? '' ) );
		check_admin_referer( 'hdc_lp_reviews_' . $operation );
		if ( 'source' === $operation ) {
			$provider = sanitize_key( wp_unslash( $_POST['hdc_lp_source'] ?? '' ) );
			if ( ! in_array( $provider, array( 'google', 'trustindex' ), true ) ) { self::finish( new WP_Error( 'hdc_lp_reviews_source', 'Selecione uma fonte válida para as avaliações.' ) ); }
			$source = HDC_LP_Trustindex_Reviews::settings();
			if ( 'trustindex' === $provider ) {
				$profile = HDC_LP_Trustindex_Reviews::profile();
				if ( is_wp_error( $profile ) ) { self::finish( $profile ); }
				$place_id = sanitize_text_field( wp_unslash( $_POST['hdc_lp_place_id'] ?? '' ) );
				if ( $place_id !== $profile['id'] ) { self::finish( new WP_Error( 'hdc_lp_trustindex_changed', 'O perfil do Trustindex mudou. Recarregue o painel e confira o nome e o endereço antes de confirmar.' ) ); }
				if ( ! $profile['count'] ) { self::finish( new WP_Error( 'hdc_lp_trustindex_empty', 'Conclua a importação das avaliações no Trustindex antes de usar esta fonte.' ) ); }
				$source['place_id'] = $profile['id'];
			}
			$source['provider'] = $provider;
			update_option( HDC_LP_Trustindex_Reviews::SOURCE, $source, false );
			self::finish( 'trustindex' === $provider ? 'Fonte alterada para Trustindex. As avaliações importadas aparecem no carrossel da landing page, conforme os filtros.' : 'Fonte alterada para a API oficial do Google. Confira a conexão, selecione o perfil e sincronize as avaliações.' );
		}
		if ( 'credentials' === $operation ) {
			$previous = HDC_LP_Google_Reviews::private_data( 'hdc_lp_reviews_credentials' );
			$id = trim( sanitize_text_field( wp_unslash( $_POST['hdc_lp_client_id'] ?? '' ) ) );
			$secret = trim( sanitize_text_field( wp_unslash( $_POST['hdc_lp_client_secret'] ?? '' ) ) );
			if ( ! preg_match( '/^[a-zA-Z0-9._-]+\.apps\.googleusercontent\.com$/', $id ) || strlen( $secret ) > 512 ) { self::finish( new WP_Error( 'hdc_lp_credentials', 'Informe um client ID OAuth válido do tipo Aplicativo Web.' ) ); }
			if ( '' === $secret && $id === ( $previous['client_id'] ?? '' ) ) { $secret = $previous['client_secret'] ?? ''; }
			if ( '' === $secret ) { self::finish( new WP_Error( 'hdc_lp_credentials', 'Informe o segredo OAuth. Ele não será exibido após salvar.' ) ); }
			$result = HDC_LP_Google_Reviews::store_private( 'hdc_lp_reviews_credentials', array( 'client_id' => $id, 'client_secret' => $secret ) );
			if ( is_wp_error( $result ) ) { self::finish( $result ); }
			if ( $id !== ( $previous['client_id'] ?? '' ) || $secret !== ( $previous['client_secret'] ?? '' ) ) {
				HDC_LP_Google_Reviews::disconnect();
				delete_user_meta( get_current_user_id(), 'hdc_lp_reviews_oauth_state' );
			}
			self::finish( 'Credenciais protegidas no servidor. Agora conecte a conta Google.' );
		}
		if ( 'connect' === $operation ) {
			$credentials = HDC_LP_Google_Reviews::private_data( 'hdc_lp_reviews_credentials' );
			if ( empty( $credentials['client_id'] ) || empty( $credentials['client_secret'] ) ) { self::finish( new WP_Error( 'hdc_lp_credentials', 'Salve as credenciais OAuth antes de conectar.' ) ); }
			$state = bin2hex( random_bytes( 32 ) );
			update_user_meta( get_current_user_id(), 'hdc_lp_reviews_oauth_state', array( 'hash' => hash( 'sha256', $state ), 'expires' => time() + 10 * MINUTE_IN_SECONDS ) );
			$url = add_query_arg( array(
				'client_id' => $credentials['client_id'], 'redirect_uri' => HDC_LP_Google_Reviews::redirect_uri(),
				'response_type' => 'code', 'scope' => 'https://www.googleapis.com/auth/business.manage',
				'access_type' => 'offline', 'prompt' => 'consent', 'state' => $state,
			), 'https://accounts.google.com/o/oauth2/v2/auth' );
			// Deliberate redirect to one hardcoded Google authorization host, never a user-supplied URL.
			wp_redirect( $url );
			exit;
		}
		if ( 'disconnect' === $operation ) {
			HDC_LP_Google_Reviews::disconnect();
			delete_user_meta( get_current_user_id(), 'hdc_lp_reviews_oauth_state' );
			self::finish( 'Conta desconectada. Tokens, perfis e avaliações em cache foram removidos.' );
		}
		if ( 'profiles' === $operation ) {
			$result = HDC_LP_Google_Reviews::profiles();
			self::finish( is_wp_error( $result ) ? $result : count( $result ) . ' perfil(is) encontrado(s). Escolha o perfil do hotel.' );
		}
		if ( 'settings' === $operation ) {
			$stars = array_values( array_intersect( array( 1, 2, 3, 4, 5 ), array_map( 'absint', (array) ( $_POST['hdc_lp_stars'] ?? array() ) ) ) );
			if ( ! $stars ) { self::finish( new WP_Error( 'hdc_lp_filter', 'Selecione pelo menos uma quantidade de estrelas.' ) ); }
			$settings = HDC_LP_Google_Reviews::settings();
			$parent = sanitize_text_field( wp_unslash( $_POST['hdc_lp_profile'] ?? '' ) );
			$profile = $settings['profile'];
			if ( $parent !== ( $profile['parent'] ?? '' ) ) {
				$profile = array();
				if ( '' !== $parent ) {
					$available = HDC_LP_Google_Reviews::private_data( 'hdc_lp_reviews_profiles' );
					if ( time() - ( $available['time'] ?? 0 ) > DAY_IN_SECONDS ) { self::finish( new WP_Error( 'hdc_lp_profile', 'Atualize a lista de perfis antes de selecionar.' ) ); }
					foreach ( $available['items'] ?? array() as $candidate ) { if ( $candidate['parent'] === $parent ) { $profile = $candidate; break; } }
					if ( ! $profile ) { self::finish( new WP_Error( 'hdc_lp_profile', 'Perfil inválido. Selecione um perfil retornado pelo Google.' ) ); }
				}
				delete_option( HDC_LP_Google_Reviews::CACHE );
				delete_option( 'hdc_lp_reviews_last_attempt' );
			}
			$settings = array( 'stars' => $stars, 'require_comment' => isset( $_POST['hdc_lp_require_comment'] ), 'limit' => max( 1, min( 50, absint( $_POST['hdc_lp_limit'] ?? 20 ) ) ), 'profile' => $profile );
			update_option( HDC_LP_Google_Reviews::SETTINGS, $settings, false );
			self::finish( HDC_LP_Trustindex_Reviews::selected() ? 'Filtros salvos. A landing page usa as avaliações importadas pelo Trustindex.' : 'Perfil e filtros salvos. Use “Baixar novas avaliações” para sincronizar o perfil selecionado.' );
		}
		if ( 'sync' === $operation ) { self::finish( HDC_LP_Google_Reviews::sync() ); }
		self::finish( new WP_Error( 'hdc_lp_action', 'Operação inválida.' ) );
	}

	public static function callback() {
		self::authorize();
		$stored = get_user_meta( get_current_user_id(), 'hdc_lp_reviews_oauth_state', true );
		delete_user_meta( get_current_user_id(), 'hdc_lp_reviews_oauth_state' );
		$state = sanitize_text_field( wp_unslash( $_GET['state'] ?? '' ) );
		if ( ! is_array( $stored ) || empty( $stored['hash'] ) || ( $stored['expires'] ?? 0 ) < time() || ! $state || ! hash_equals( $stored['hash'], hash( 'sha256', $state ) ) ) {
			self::finish( new WP_Error( 'hdc_lp_state', 'A autorização expirou ou seu estado é inválido. Inicie a conexão novamente.' ) );
		}
		if ( isset( $_GET['error'] ) || empty( $_GET['code'] ) ) { self::finish( new WP_Error( 'hdc_lp_consent', 'A autorização Google não foi concluída. Nenhum dado foi importado.' ) ); }
		$code = sanitize_text_field( wp_unslash( $_GET['code'] ) );
		$result = HDC_LP_Google_Reviews::token_request( array( 'grant_type' => 'authorization_code', 'code' => $code, 'redirect_uri' => HDC_LP_Google_Reviews::redirect_uri() ) );
		if ( is_wp_error( $result ) ) { self::finish( $result ); }
		// A new consent may refer to a different account. Never keep a previously selected profile.
		$settings = HDC_LP_Google_Reviews::settings();
		$settings['profile'] = array();
		update_option( HDC_LP_Google_Reviews::SETTINGS, $settings, false );
		delete_option( HDC_LP_Google_Reviews::CACHE );
		delete_option( 'hdc_lp_reviews_profiles' );
		delete_option( 'hdc_lp_reviews_last_attempt' );
		wp_clear_scheduled_hook( HDC_LP_Google_Reviews::CRON );
		$profiles = HDC_LP_Google_Reviews::profiles();
		self::finish( is_wp_error( $profiles ) ? $profiles : 'Conta conectada. Selecione o perfil correto do hotel e salve antes de baixar avaliações.' );
	}

	public static function page() {
		self::authorize();
		HDC_LP_Google_Reviews::purge_expired();
		$settings = HDC_LP_Google_Reviews::settings();
		$credentials = HDC_LP_Google_Reviews::private_data( 'hdc_lp_reviews_credentials' );
		$profiles = HDC_LP_Google_Reviews::private_data( 'hdc_lp_reviews_profiles' );
		$cache = HDC_LP_Google_Reviews::private_data( HDC_LP_Google_Reviews::CACHE );
		$diagnostic = get_option( 'hdc_lp_reviews_diagnostic', array() );
		$connected = HDC_LP_Google_Reviews::connected();
		$trustindex_selected = HDC_LP_Trustindex_Reviews::selected();
		$trustindex_profile = HDC_LP_Trustindex_Reviews::profile();
		?>
		<div class="wrap">
			<h1>Hotel da Costa — Avaliações</h1>
			<p>Avaliações reais do Google, renderizadas no carrossel próprio da landing page.</p>
			<?php if ( ! empty( $diagnostic['message'] ) ) : ?>
				<div class="notice <?php echo ! empty( $diagnostic['ok'] ) ? 'notice-success' : 'notice-error'; ?>"><p><?php echo esc_html( $diagnostic['message'] ); ?></p></div>
			<?php endif; ?>
			<h2>Fonte das avaliações</h2>
			<p>Fonte atual: <strong><?php echo $trustindex_selected ? 'Trustindex gratuito' : 'API oficial do Google Business Profile'; ?></strong>.</p>
			<h3>Trustindex gratuito — uso temporário</h3>
			<p>Instale e ative <a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=Widgets+for+Google+Reviews&tab=search&type=term' ) ); ?>">Widgets for Google Reviews, da Trustindex</a>. No painel do Trustindex, conecte o perfil correto do Hotel da Costa e conclua a importação. Depois volte aqui para confirmar o perfil abaixo.</p>
			<?php if ( is_wp_error( $trustindex_profile ) ) : ?>
				<p><?php echo esc_html( $trustindex_profile->get_error_message() ); ?></p>
			<?php else : ?>
				<p>Perfil conectado no Trustindex: <strong><?php echo esc_html( $trustindex_profile['title'] ); ?></strong><br><?php echo esc_html( $trustindex_profile['address'] ); ?><br>Avaliações importadas: <?php echo absint( $trustindex_profile['count'] ); ?>.</p>
				<?php if ( $trustindex_selected && $trustindex_profile['id'] !== HDC_LP_Trustindex_Reviews::settings()['place_id'] ) : ?>
					<p>O perfil foi trocado no Trustindex. A exibição está suspensa até você conferir e confirmar o novo perfil.</p>
				<?php endif; ?>
			<?php endif; ?>
			<?php self::form_start( 'source' ); ?>
				<input type="hidden" name="hdc_lp_source" value="trustindex">
				<input type="hidden" name="hdc_lp_place_id" value="<?php echo esc_attr( is_wp_error( $trustindex_profile ) ? '' : $trustindex_profile['id'] ); ?>">
				<p>Confira o nome e o endereço acima antes de usar as avaliações deste perfil.</p>
				<?php submit_button( 'Usar avaliações do Trustindex', 'primary', 'submit', true, is_wp_error( $trustindex_profile ) || ! $trustindex_profile['count'] ? array( 'disabled' => 'disabled' ) : array() ); ?>
			</form>
			<p>A versão gratuita importa inicialmente até dez avaliações. Novos downloads são manuais no Trustindex, conforme o intervalo liberado pelo serviço. Os filtros abaixo também são aplicados, e avaliações ocultadas no Trustindex permanecem ocultas na landing page. Não é necessário inserir um shortcode ou escolher um visual de widget.</p>
			<?php self::form_start( 'source' ); ?>
				<input type="hidden" name="hdc_lp_source" value="google">
				<?php submit_button( 'Usar API oficial do Google', 'secondary' ); ?>
			</form>
			<details <?php echo $trustindex_selected ? '' : 'open'; ?>>
			<summary>Configurar a conexão direta com o Google após a aprovação da API</summary>
			<h2>Credenciais OAuth</h2>
			<p>É necessário um projeto Google Cloud aprovado para a API Business Profile, acesso ao perfil verificado do hotel e um cliente OAuth do tipo <strong>Aplicativo Web</strong>. Habilite as APIs Business Profile, Account Management e Business Information.</p>
			<p>URI de redirecionamento autorizada (copie exatamente): <code><?php echo esc_html( HDC_LP_Google_Reviews::redirect_uri() ); ?></code></p>
			<p>Use HTTPS em produção. O ambiente localhost é apenas para testes. <a href="https://developers.google.com/my-business/content/prereqs" target="_blank" rel="noopener noreferrer">Pré-requisitos oficiais do Google</a>.</p>
			<?php self::form_start( 'credentials' ); ?>
				<table class="form-table" role="presentation"><tbody>
					<tr><th scope="row"><label for="hdc-lp-client-id">Client ID</label></th><td><input type="text" class="regular-text" id="hdc-lp-client-id" name="hdc_lp_client_id" value="<?php echo esc_attr( $credentials['client_id'] ?? '' ); ?>" required autocomplete="off"></td></tr>
					<tr><th scope="row"><label for="hdc-lp-client-secret">Client secret</label></th><td><input type="password" class="regular-text" id="hdc-lp-client-secret" name="hdc_lp_client_secret" value="" autocomplete="new-password"><p class="description">O segredo salvo nunca é exibido. Deixe em branco para mantê-lo se o client ID não mudou. Alterar as credenciais exige nova conexão.</p></td></tr>
				</tbody></table>
				<?php submit_button( 'Salvar credenciais' ); ?>
			</form>
			<h2>Conexão da conta Google</h2>
			<p>Status: <strong><?php echo $connected ? 'Conta conectada' : 'Não conectado'; ?></strong>. Os tokens e o cache são criptografados com as chaves do WordPress. Alterar os salts exige nova autorização.</p>
			<?php self::form_start( 'connect' ); submit_button( $connected ? 'Reconectar conta Google' : 'Conectar conta Google', 'secondary' ); ?></form>
			<?php if ( $connected ) : ?>
				<?php self::form_start( 'profiles' ); submit_button( 'Atualizar lista de perfis', 'secondary' ); ?></form>
				<?php self::form_start( 'disconnect' ); submit_button( 'Desconectar e remover cache', 'secondary' ); ?></form>
			<?php endif; ?>
			</details>
			<h2>Filtros do carrossel</h2>
			<?php self::form_start( 'settings' ); ?>
				<?php if ( $trustindex_selected ) : ?><input type="hidden" name="hdc_lp_profile" value="<?php echo esc_attr( $settings['profile']['parent'] ?? '' ); ?>"><?php endif; ?>
				<table class="form-table" role="presentation"><tbody>
					<?php if ( ! $trustindex_selected ) : ?>
					<tr><th scope="row"><label for="hdc-lp-profile">Perfil do hotel</label></th><td><select id="hdc-lp-profile" name="hdc_lp_profile"><option value="">Selecione explicitamente o perfil</option>
						<?php $listed = array(); foreach ( $profiles['items'] ?? array() as $profile ) : $listed[] = $profile['parent']; ?>
							<option value="<?php echo esc_attr( $profile['parent'] ); ?>" <?php selected( $settings['profile']['parent'] ?? '', $profile['parent'] ); ?>><?php echo esc_html( $profile['title'] . ' — ' . $profile['address'] . ' (' . $profile['parent'] . ')' ); ?></option>
						<?php endforeach; ?>
						<?php if ( ! empty( $settings['profile']['parent'] ) && ! in_array( $settings['profile']['parent'], $listed, true ) ) : ?>
							<option selected value="<?php echo esc_attr( $settings['profile']['parent'] ); ?>"><?php echo esc_html( $settings['profile']['title'] . ' (perfil salvo; atualize a lista para trocar)' ); ?></option>
						<?php endif; ?>
					</select><p class="description">Nenhum perfil é escolhido automaticamente. Confira nome e endereço antes de salvar.</p></td></tr>
					<?php endif; ?>
					<tr><th scope="row">Estrelas exibidas</th><td><fieldset><legend class="screen-reader-text">Selecione as estrelas</legend>
						<?php for ( $star = 1; $star <= 5; $star++ ) : ?><label><input type="checkbox" name="hdc_lp_stars[]" value="<?php echo absint( $star ); ?>" <?php checked( in_array( $star, $settings['stars'], true ) ); ?>> <?php echo absint( $star ); ?> estrela(s)</label><br><?php endfor; ?>
					</fieldset></td></tr>
					<tr><th scope="row">Comentários</th><td><label><input type="checkbox" name="hdc_lp_require_comment" value="1" <?php checked( $settings['require_comment'] ); ?>> Exigir comentário escrito</label></td></tr>
					<tr><th scope="row"><label for="hdc-lp-limit">Quantidade exibida</label></th><td><input type="number" id="hdc-lp-limit" name="hdc_lp_limit" min="1" max="50" value="<?php echo absint( $settings['limit'] ); ?>"><p class="description"><?php echo $trustindex_selected ? 'A fonte Trustindex gratuito exibe até dez avaliações importadas, respeitando este limite e os filtros.' : 'De 1 a 50, entre até 100 avaliações recentes consultadas.'; ?> Os filtros não alteram a nota geral do Google; nenhuma nota agregada é calculada aqui.</p></td></tr>
				</tbody></table>
				<?php submit_button( 'Salvar perfil e filtros' ); ?>
			</form>
			<h2>Atualização</h2>
			<?php if ( $trustindex_selected ) : ?>
				<p>Avaliações exibidas após os filtros: <?php echo absint( count( HDC_LP_Google_Reviews::public_data()['items'] ) ); ?>. A landing page lê as avaliações já importadas pelo Trustindex. Quando você realizar um novo download no Trustindex, o carrossel passa a usar os registros atualizados.</p>
				<p>Faça novos downloads em <a href="<?php echo esc_url( admin_url( 'admin.php?page=wp-reviews-plugin-for-google/settings.php&tab=my-reviews' ) ); ?>">Trustindex → Minhas avaliações</a>, quando a atualização manual estiver disponível. Para migrar após a aprovação, use “Usar API oficial do Google” e configure a conexão direta.</p>
			<?php else : ?>
			<p>Última sincronização completa: <strong><?php echo esc_html( ! empty( $cache['time'] ) ? wp_date( 'd/m/Y H:i', $cache['time'] ) : 'Ainda não realizada' ); ?></strong>. Avaliações no cache: <?php echo absint( count( $cache['items'] ?? array() ) ); ?>. Exibidas após os filtros: <?php echo absint( count( HDC_LP_Google_Reviews::public_data()['items'] ) ); ?>.</p>
			<p>Atualização diária pelo WP-Cron. Em falhas transitórias, o último cache válido dura no máximo sete dias. Após esse prazo a seção informa indisponibilidade; nunca usa exemplos como avaliações reais.</p>
			<?php self::form_start( 'sync' ); submit_button( 'Baixar novas avaliações', 'primary', 'submit', true, $connected && ! empty( $settings['profile']['parent'] ) ? array() : array( 'disabled' => 'disabled' ) ); ?></form>
			<p>Em sites com pouco tráfego, configure um agendamento do servidor para executar o WP-Cron diariamente. Ative a integração apenas após cumprir as políticas de uso do Google e informar o uso de avatares externos na política de privacidade.</p>
			<?php endif; ?>
		</div>
		<?php
	}
}

HDC_LP_Google_Reviews_Admin::init();
