<?php
/** Private submissions UI; native WordPress controls, no Elementor dependency. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class HDC_LP_Submissions_Admin {
	private static $screen = '';
	const PAGE = 'hdc-lp-submissions';
	const PER_PAGE = 20;

	public static function menu() {
		self::$screen = add_submenu_page( 'hdc-lp-campaigns', 'Envios — Hotel da Costa', 'Envios', 'manage_options', self::PAGE, array( __CLASS__, 'page' ) );
	}
	public static function assets( $hook ) {
		if ( self::$screen !== $hook || ! current_user_can( 'manage_options' ) ) { return; }
		wp_enqueue_style( 'hdc-lp-submissions-admin', HDC_LP_URL . 'assets/css/submissions-admin.css', array(), HDC_LP_VERSION );
		wp_enqueue_script( 'hdc-lp-submissions-admin', HDC_LP_URL . 'assets/js/submissions-admin.js', array(), HDC_LP_VERSION, true );
	}
	private static function authorize() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Você não tem permissão para acessar os envios.', '', array( 'response' => 403 ) ); }
	}
	private static function text( $value ) { return is_scalar( $value ) ? sanitize_text_field( (string) $value ) : ''; }
	public static function filters( $input ) {
		$input = is_array( $input ) ? wp_unslash( $input ) : array();
		$filters = array( 'status' => self::text( $input['status'] ?? 'all' ), 'search' => wp_html_excerpt( self::text( $input['search'] ?? '' ), 200, '' ), 'form_id' => self::text( $input['form_id'] ?? '' ), 'page_id' => absint( self::text( $input['page_id'] ?? 0 ) ), 'period' => self::text( $input['period'] ?? 'all' ), 'from' => self::text( $input['from'] ?? '' ), 'to' => self::text( $input['to'] ?? '' ), 'paged' => max( 1, absint( self::text( $input['paged'] ?? 1 ) ) ) );
		if ( ! in_array( $filters['status'], array( 'all', 'unread', 'read', 'trash' ), true ) ) { $filters['status'] = 'all'; }
		if ( ! in_array( $filters['period'], array( 'all', 'today', '7', '30', 'custom' ), true ) ) { $filters['period'] = 'all'; }
		return $filters;
	}
	private static function date( $value ) {
		$date = DateTimeImmutable::createFromFormat( '!Y-m-d', $value, wp_timezone() );
		return $date && $date->format( 'Y-m-d' ) === $value ? $date : null;
	}
	public static function where( $filters, $status = null ) {
		global $wpdb;
		$parts = array(); $values = array();
		$status = null === $status ? $filters['status'] : $status;
		$parts[] = 'trash' === $status ? 'trashed_at IS NOT NULL' : 'trashed_at IS NULL';
		if ( 'read' === $status || 'unread' === $status ) { $parts[] = 'is_read = %d'; $values[] = 'read' === $status ? 1 : 0; }
		if ( $filters['form_id'] ) { $parts[] = 'form_id = %s'; $values[] = $filters['form_id']; }
		if ( $filters['page_id'] ) { $parts[] = 'page_id = %d'; $values[] = $filters['page_id']; }
		if ( $filters['search'] ) {
			$search = '%' . $wpdb->esc_like( $filters['search'] ) . '%';
			$phone = preg_match( '/^[+()\d\s.-]+$/', $filters['search'] ) ? HDC_LP_Submissions::phone( $filters['search'] ) : $filters['search'];
			$parts[] = '(name LIKE %s OR email LIKE %s OR phone LIKE %s OR id = %d)';
			array_push( $values, $search, $search, '%' . $wpdb->esc_like( $phone ?: $filters['search'] ) . '%', ctype_digit( $filters['search'] ) ? (int) $filters['search'] : 0 );
		}
		$start = null; $end = null;
		if ( 'custom' === $filters['period'] ) {
			$start = $filters['from'] ? self::date( $filters['from'] ) : null;
			$end = $filters['to'] ? self::date( $filters['to'] ) : null;
			if ( ( ! $start && ! $end ) || ( $filters['from'] && ! $start ) || ( $filters['to'] && ! $end ) || ( $start && $end && $start > $end ) ) { return new WP_Error( 'hdc_lp_period', 'Informe um período válido. A data inicial deve ser anterior ou igual à data final.' ); }
		} elseif ( 'all' !== $filters['period'] ) {
			$end = new DateTimeImmutable( 'today', wp_timezone() );
			$days = 'today' === $filters['period'] ? 0 : (int) $filters['period'] - 1;
			$start = $end->modify( '-' . $days . ' days' );
		}
		$utc = new DateTimeZone( 'UTC' );
		if ( $start ) { $parts[] = 'created_utc >= %s'; $values[] = $start->setTimezone( $utc )->format( 'Y-m-d H:i:s' ); }
		if ( $end ) { $parts[] = 'created_utc < %s'; $values[] = $end->modify( '+1 day' )->setTimezone( $utc )->format( 'Y-m-d H:i:s' ); }
		$sql = implode( ' AND ', $parts );
		return $values ? $wpdb->prepare( $sql, $values ) : $sql;
	}
	private static function url( $filters = array(), $extra = array() ) {
		return add_query_arg( array_merge( array( 'page' => self::PAGE ), $filters, $extra ), admin_url( 'admin.php' ) );
	}
	private static function inputs( $filters ) {
		foreach ( $filters as $key => $value ) { printf( '<input type="hidden" name="hdc_lp_filters[%s]" value="%s">', esc_attr( $key ), esc_attr( $value ) ); }
	}
	public static function timestamp( $value ) { return wp_date( 'd/m/Y H:i', ( new DateTimeImmutable( $value, new DateTimeZone( 'UTC' ) ) )->getTimestamp(), wp_timezone() ); }
	private static function action_form( $row, $filters ) {
		?>
		<form class="hdc-lp-submissions-admin__row-actions" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="action" value="hdc_lp_submissions_action">
			<input type="hidden" name="hdc_lp_ids[]" value="<?php echo absint( $row['id'] ); ?>">
			<?php wp_nonce_field( 'hdc_lp_submissions_action', '_wpnonce', false ); self::inputs( $filters ); ?>
			<?php if ( $row['trashed_at'] ) : ?>
				<button class="button-link" name="hdc_lp_operation" value="restore">Restaurar</button>
				<button class="button-link button-link-delete" name="hdc_lp_operation" value="delete">Excluir definitivamente</button>
			<?php else : ?>
				<button class="button-link" name="hdc_lp_operation" value="<?php echo $row['is_read'] ? 'unread' : 'read'; ?>"><?php echo $row['is_read'] ? 'Marcar como não lido' : 'Marcar como lido'; ?></button>
				<button class="button-link button-link-delete" name="hdc_lp_operation" value="trash">Mover para a lixeira</button>
			<?php endif; ?>
		</form>
		<?php
	}
	private static function confirmation( $filters ) {
		$pending = get_transient( 'hdc_lp_submissions_delete_' . get_current_user_id() );
		if ( ! is_array( $pending ) || empty( $pending['ids'] ) ) { echo '<div class="notice notice-error"><p>A confirmação expirou. Selecione os envios novamente.</p></div>'; return; }
		?>
		<div class="hdc-lp-submissions-admin__confirmation">
			<h2>Excluir definitivamente?</h2>
			<p><?php echo esc_html( sprintf( 'Você está prestes a excluir %d envio(s) da lixeira. Esta ação não pode ser desfeita.', count( $pending['ids'] ) ) ); ?></p>
			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="hdc_lp_submissions_action"><input type="hidden" name="hdc_lp_operation" value="delete"><input type="hidden" name="hdc_lp_confirm_delete" value="1">
				<?php wp_nonce_field( 'hdc_lp_submissions_action', '_wpnonce', false ); self::inputs( $pending['filters'] ); foreach ( $pending['ids'] as $id ) { printf( '<input type="hidden" name="hdc_lp_ids[]" value="%d">', $id ); } ?>
				<button class="button" type="submit">Confirmar exclusão definitiva</button>
				<a class="button" href="<?php echo esc_url( self::url( $filters ) ); ?>">Cancelar</a>
			</form>
		</div>
		<?php
	}
	public static function page() {
		self::authorize(); global $wpdb;
		$filters = self::filters( $_GET );
		$table = HDC_LP_Submissions::table();
		?>
		<div class="wrap hdc-lp-submissions-admin">
		<h1>Envios — Hotel da Costa</h1>
		<?php
		$notice_key = 'hdc_lp_submissions_notice_' . get_current_user_id();
		$notice = get_transient( $notice_key );
		if ( $notice ) { delete_transient( $notice_key ); printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $notice ) ); }
		if ( ! empty( $_GET['hdc_lp_confirm'] ) ) { self::confirmation( $filters ); echo '</div>'; return; }
		$id = absint( self::text( $_GET['hdc_lp_id'] ?? 0 ) );
		if ( $id ) {
			$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ), ARRAY_A );
			if ( ! $row ) { echo '<div class="notice notice-error"><p>Envio não encontrado.</p></div>'; }
			else {
				echo '<h2>' . esc_html( 'Envio #' . $id ) . '</h2><dl class="hdc-lp-submissions-admin__details">';
				$values = array( 'Nome' => $row['name'], 'E-mail' => $row['email'], 'Telefone' => HDC_LP_Submissions::format_phone( $row['phone'] ), 'Formulário' => $row['form_name'] . ' (' . $row['form_id'] . ')', 'Página' => $row['page_title'] . ' (#' . $row['page_id'] . ')', 'URL da página' => $row['page_url'], 'Data do envio' => self::timestamp( $row['created_utc'] ), 'Estado' => $row['trashed_at'] ? 'Na lixeira' : ( $row['is_read'] ? 'Lido' : 'Não lido' ) );
				foreach ( HDC_LP_Submissions::UTM_FIELDS as $field ) { $values[ $field ] = $row[ $field ]; }
				foreach ( $values as $label => $value ) { printf( '<div><dt>%s</dt><dd>%s</dd></div>', esc_html( $label ), esc_html( '' !== $value ? $value : 'Não informado' ) ); }
				echo '</dl>'; self::action_form( $row, $filters );
			}
			printf( '<p><a class="button" href="%s">Voltar à lista</a></p></div>', esc_url( self::url( $filters ) ) ); return;
		}
		$where = self::where( $filters );
		$valid = ! is_wp_error( $where );
		if ( ! $valid ) { printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( $where->get_error_message() ) ); }
		echo '<ul class="subsubsub">';
		$views = array( 'all' => 'Todos', 'unread' => 'Não lidos', 'read' => 'Lidos', 'trash' => 'Lixeira' );
		foreach ( $views as $status => $label ) {
			$view_where = self::where( $filters, $status );
			$count = is_wp_error( $view_where ) ? 0 : (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE $view_where" );
			printf( '<li><a href="%s"%s>%s <span class="count">(%d)</span></a>%s</li>', esc_url( self::url( array_merge( $filters, array( 'status' => $status, 'paged' => 1 ) ) ) ), $status === $filters['status'] ? ' class="current" aria-current="page"' : '', esc_html( $label ), $count, 'trash' === $status ? '' : ' | ' );
		}
		echo '</ul>';
		$forms = $wpdb->get_results( "SELECT DISTINCT form_id, form_name FROM $table ORDER BY form_name", ARRAY_A );
		$pages = $wpdb->get_results( "SELECT page_id, MAX(page_title) AS page_title FROM $table GROUP BY page_id ORDER BY page_title", ARRAY_A );
		?>
		<form class="hdc-lp-submissions-admin__filters" method="get">
			<input type="hidden" name="page" value="hdc-lp-submissions"><input type="hidden" name="status" value="<?php echo esc_attr( $filters['status'] ); ?>">
			<label>Buscar <input type="search" name="search" value="<?php echo esc_attr( $filters['search'] ); ?>" placeholder="ID, nome, e-mail ou telefone"></label>
			<label>Formulário <select name="form_id"><option value="">Todos os formulários</option><?php foreach ( $forms as $form ) { printf( '<option value="%s" %s>%s</option>', esc_attr( $form['form_id'] ), selected( $form['form_id'], $filters['form_id'], false ), esc_html( $form['form_name'] ) ); } ?></select></label>
			<label>Página <select name="page_id"><option value="0">Todas as páginas</option><?php foreach ( $pages as $page ) { printf( '<option value="%d" %s>%s (#%d)</option>', $page['page_id'], selected( $page['page_id'], $filters['page_id'], false ), esc_html( $page['page_title'] ), $page['page_id'] ); } ?></select></label>
			<label>Período <select name="period" data-hdc-lp-period><?php foreach ( array( 'all' => 'Todo o período', 'today' => 'Hoje', '7' => 'Últimos 7 dias', '30' => 'Últimos 30 dias', 'custom' => 'Período personalizado' ) as $key => $label ) { printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $key, $filters['period'], false ), esc_html( $label ) ); } ?></select></label>
			<label data-hdc-lp-date>De <input type="date" name="from" value="<?php echo esc_attr( $filters['from'] ); ?>"></label><label data-hdc-lp-date>Até <input type="date" name="to" value="<?php echo esc_attr( $filters['to'] ); ?>"></label>
			<button class="button" type="submit">Filtrar</button><a href="<?php echo esc_url( self::url() ); ?>">Limpar filtros</a>
		</form>
		<?php
		$total = $valid ? (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE $where" ) : 0;
		$page_count = max( 1, (int) ceil( $total / self::PER_PAGE ) );
		$filters['paged'] = min( $filters['paged'], $page_count );
		$offset = ( $filters['paged'] - 1 ) * self::PER_PAGE;
		$rows = $valid ? $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE $where ORDER BY created_utc DESC, id DESC LIMIT %d OFFSET %d", self::PER_PAGE, $offset ), ARRAY_A ) : array();
		?>
		<form id="hdc-lp-submissions-bulk" class="hdc-lp-submissions-admin__toolbar" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="action" value="hdc_lp_submissions_action"><?php wp_nonce_field( 'hdc_lp_submissions_action', '_wpnonce', false ); self::inputs( $filters ); ?>
			<label class="screen-reader-text" for="hdc-lp-bulk-operation">Ação em lote</label><select id="hdc-lp-bulk-operation" name="hdc_lp_operation"><option value="">Ações em lote</option>
			<?php if ( 'trash' === $filters['status'] ) : ?><option value="restore">Restaurar</option><option value="delete">Excluir definitivamente</option><?php else : ?><option value="read">Marcar como lidos</option><option value="unread">Marcar como não lidos</option><option value="trash">Mover para a lixeira</option><?php endif; ?>
			<option value="export_selected">Exportar selecionados em CSV</option></select><button class="button" type="submit">Aplicar</button>
			<button class="button" type="submit" name="hdc_lp_export_filtered" value="1" <?php disabled( ! $valid || ! $total ); ?>>Exportar resultados filtrados em CSV</button>
		</form>
		<p><?php echo esc_html( sprintf( '%d envio(s) · Página %d de %d', $total, $filters['paged'], $page_count ) ); ?></p>
		<table class="widefat fixed hdc-lp-submissions-admin__table">
			<caption class="screen-reader-text">Envios do formulário de reservas da landing page</caption>
			<thead><tr><td class="check-column"><input type="checkbox" data-hdc-lp-select-all aria-label="Selecionar todos os envios desta página"></td><th scope="col">ID</th><th scope="col">Nome</th><th scope="col">E-mail</th><th scope="col">Telefone</th><th scope="col">Formulário</th><th scope="col">Página</th><th scope="col">Data</th><th scope="col">Leitura</th></tr></thead>
			<tbody><?php foreach ( $rows as $row ) : ?>
				<tr data-hdc-lp-submission="<?php echo absint( $row['id'] ); ?>">
					<th scope="row" class="check-column"><input form="hdc-lp-submissions-bulk" type="checkbox" name="hdc_lp_ids[]" value="<?php echo absint( $row['id'] ); ?>" aria-label="<?php echo esc_attr( 'Selecionar envio ' . $row['id'] ); ?>"></th>
					<td data-hdc-lp-label="ID"><?php echo absint( $row['id'] ); ?></td>
					<td data-hdc-lp-label="Nome"><a href="<?php echo esc_url( self::url( $filters, array( 'hdc_lp_id' => $row['id'] ) ) ); ?>"><strong><?php echo esc_html( $row['name'] ); ?></strong></a><?php self::action_form( $row, $filters ); ?></td>
					<td data-hdc-lp-label="E-mail"><?php echo esc_html( $row['email'] ); ?></td><td data-hdc-lp-label="Telefone"><?php echo esc_html( HDC_LP_Submissions::format_phone( $row['phone'] ) ); ?></td>
					<td data-hdc-lp-label="Formulário"><?php echo esc_html( $row['form_name'] ); ?><br><span class="hdc-lp-submissions-admin__identifier"><?php echo esc_html( $row['form_id'] ); ?></span></td>
					<td data-hdc-lp-label="Página"><?php echo esc_html( $row['page_title'] . ' (#' . $row['page_id'] . ')' ); ?></td><td data-hdc-lp-label="Data"><?php echo esc_html( self::timestamp( $row['created_utc'] ) ); ?></td><td data-hdc-lp-label="Leitura"><?php echo $row['is_read'] ? 'Lido' : 'Não lido'; ?></td>
				</tr>
			<?php endforeach; if ( ! $rows ) { echo '<tr><td colspan="9">Nenhum envio encontrado.</td></tr>'; } ?></tbody>
		</table>
		<nav class="hdc-lp-submissions-admin__pagination" aria-label="Paginação dos envios">
			<?php if ( $filters['paged'] > 1 ) { printf( '<a class="button" href="%s">Anterior</a>', esc_url( self::url( array_merge( $filters, array( 'paged' => $filters['paged'] - 1 ) ) ) ) ); } if ( $filters['paged'] < $page_count ) { printf( '<a class="button" href="%s">Próxima</a>', esc_url( self::url( array_merge( $filters, array( 'paged' => $filters['paged'] + 1 ) ) ) ) ); } ?>
		</nav></div>
		<?php
	}

	public static function action() {
		self::authorize();
		if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) { wp_die( 'Método não permitido.', '', array( 'response' => 405 ) ); }
		check_admin_referer( 'hdc_lp_submissions_action' );
		global $wpdb;
		$filters = self::filters( $_POST['hdc_lp_filters'] ?? array() );
		$operation = self::text( wp_unslash( $_POST['hdc_lp_operation'] ?? '' ) );
		if ( ! empty( $_POST['hdc_lp_export_filtered'] ) ) { self::export( $filters, null ); }
		$raw_ids = $_POST['hdc_lp_ids'] ?? array();
		if ( ! is_array( $raw_ids ) || count( $raw_ids ) > 1000 ) { wp_die( 'Seleção inválida.', '', array( 'response' => 400 ) ); }
		$ids = array_values( array_unique( array_filter( array_map( static function ( $id ) { return is_scalar( $id ) ? absint( $id ) : 0; }, $raw_ids ) ) ) ); sort( $ids );
		if ( ! $ids ) { wp_die( 'Selecione pelo menos um envio.', '', array( 'response' => 400, 'back_link' => true ) ); }
		if ( 'export_selected' === $operation ) { self::export( $filters, $ids ); }
		$table = HDC_LP_Submissions::table();
		$in = implode( ',', $ids ); // Only validated positive integers.
		if ( 'delete' === $operation ) {
			$key = 'hdc_lp_submissions_delete_' . get_current_user_id();
			if ( '1' !== self::text( $_POST['hdc_lp_confirm_delete'] ?? '' ) ) {
				$trash_ids = array_map( 'intval', $wpdb->get_col( "SELECT id FROM $table WHERE id IN ($in) AND trashed_at IS NOT NULL ORDER BY id" ) );
				if ( ! $trash_ids ) { wp_die( 'Apenas envios na lixeira podem ser excluídos definitivamente.', '', array( 'response' => 400 ) ); }
				set_transient( $key, array( 'ids' => $trash_ids, 'filters' => $filters ), 600 );
				wp_safe_redirect( self::url( $filters, array( 'hdc_lp_confirm' => 1 ) ) ); exit;
			}
			$pending = get_transient( $key );
			if ( ! is_array( $pending ) || $ids !== $pending['ids'] ) { wp_die( 'Confirmação inválida ou expirada.', '', array( 'response' => 403 ) ); }
			$result = $wpdb->query( "DELETE FROM $table WHERE id IN ($in) AND trashed_at IS NOT NULL" );
			delete_transient( $key );
		} elseif ( 'trash' === $operation ) {
			$result = $wpdb->query( $wpdb->prepare( "UPDATE $table SET trashed_at = %s WHERE id IN ($in) AND trashed_at IS NULL", current_time( 'mysql', true ) ) );
		} elseif ( 'restore' === $operation ) {
			$result = $wpdb->query( "UPDATE $table SET trashed_at = NULL WHERE id IN ($in) AND trashed_at IS NOT NULL" );
		} elseif ( in_array( $operation, array( 'read', 'unread' ), true ) ) {
			$result = $wpdb->query( $wpdb->prepare( "UPDATE $table SET is_read = %d WHERE id IN ($in) AND trashed_at IS NULL", 'read' === $operation ? 1 : 0 ) );
		} else { wp_die( 'Escolha uma ação válida.', '', array( 'response' => 400 ) ); }
		if ( false === $result ) { wp_die( 'Não foi possível atualizar os envios. Tente novamente.', '', array( 'response' => 500 ) ); }
		set_transient( 'hdc_lp_submissions_notice_' . get_current_user_id(), $result ? 'Envios atualizados.' : 'Nenhum envio foi alterado.', 60 );
		wp_safe_redirect( self::url( $filters ) ); exit;
	}

	public static function csv_cell( $value ) {
		$value = (string) $value;
		return preg_match( '/^(?:\s*[=+@-]|[\t\r\n])/u', $value ) ? "'" . $value : $value;
	}
	private static function export( $filters, $ids ) {
		global $wpdb;
		$where = self::where( $filters );
		if ( is_wp_error( $where ) ) { wp_die( esc_html( $where->get_error_message() ), '', array( 'response' => 400 ) ); }
		if ( null !== $ids ) { $where .= ' AND id IN (' . implode( ',', $ids ) . ')'; }
		$table = HDC_LP_Submissions::table();
		// Bound the export to the current snapshot; stream in batches instead of holding all leads in memory.
		$maximum = (int) $wpdb->get_var( "SELECT MAX(id) FROM $table WHERE $where" );
		nocache_headers(); header( 'Content-Type: text/csv; charset=utf-8' ); header( 'X-Content-Type-Options: nosniff' ); header( 'Content-Disposition: attachment; filename="hotel-da-costa-envios-' . wp_date( 'Y-m-d' ) . '.csv"' );
		$out = fopen( 'php://output', 'w' ); fwrite( $out, "\xEF\xBB\xBF" );
		fputcsv( $out, array( 'ID', 'Nome', 'E-mail', 'Telefone', 'Formulário', 'ID do formulário', 'ID da página', 'Página', 'URL da página', 'Data do envio (' . wp_timezone_string() . ')', 'Leitura', 'Lixeira', 'utm_term', 'utm_campaign', 'utm_content', 'utm_source', 'utm_medium' ), ';', '"', '' );
		$last = 0;
		do {
			$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE $where AND id > %d AND id <= %d ORDER BY id ASC LIMIT 200", $last, $maximum ), ARRAY_A );
			foreach ( $rows as $row ) {
				$values = array( $row['id'], $row['name'], $row['email'], HDC_LP_Submissions::format_phone( $row['phone'] ), $row['form_name'], $row['form_id'], $row['page_id'], $row['page_title'], $row['page_url'], self::timestamp( $row['created_utc'] ), $row['is_read'] ? 'Lido' : 'Não lido', $row['trashed_at'] ? 'Sim' : 'Não' );
				foreach ( HDC_LP_Submissions::UTM_FIELDS as $field ) { $values[] = $row[ $field ]; }
				fputcsv( $out, array_map( array( __CLASS__, 'csv_cell' ), $values ), ';', '"', '' ); $last = (int) $row['id'];
			}
		} while ( count( $rows ) === 200 );
		fclose( $out ); exit;
	}
}
add_action( 'admin_menu', array( 'HDC_LP_Submissions_Admin', 'menu' ), 20 );
add_action( 'admin_enqueue_scripts', array( 'HDC_LP_Submissions_Admin', 'assets' ) );
add_action( 'admin_post_hdc_lp_submissions_action', array( 'HDC_LP_Submissions_Admin', 'action' ) );
