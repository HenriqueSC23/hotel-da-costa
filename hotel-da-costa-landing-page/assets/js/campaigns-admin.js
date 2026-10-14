( function () {
	'use strict';
	const form = document.querySelector( '[data-hdc-lp-campaign-form]' );
	if ( ! form ) { return; }
	const list = form.querySelector( '[data-hdc-lp-campaign-list]' );
	const empty = form.querySelector( '[data-hdc-lp-campaign-empty]' );
	const announcement = form.querySelector( '[data-hdc-lp-campaign-announcement]' );
	const template = document.querySelector( '[data-hdc-lp-campaign-template]' );
	let dragged = null;
	const rows = () => Array.from( list.children );
	const announce = text => { announcement.textContent = text; };
	const sync = function () {
		const items = rows();
		empty.hidden = items.length > 0;
		items.forEach( function ( row, index ) {
			row.querySelector( '[data-hdc-lp-campaign-position]' ).textContent = index + 1;
			row.querySelectorAll( '[data-hdc-lp-campaign-field]' ).forEach( input => {
				input.name = 'hdc_lp_campaigns[' + index + '][' + input.dataset.hdcLpCampaignField + ']';
			} );
			row.querySelector( '[data-hdc-lp-campaign-up]' ).disabled = index === 0;
			row.querySelector( '[data-hdc-lp-campaign-down]' ).disabled = index === items.length - 1;
		} );
	};
	const move = function ( row, direction ) {
		const neighbour = direction < 0 ? row.previousElementSibling : row.nextElementSibling;
		if ( ! neighbour ) { return; }
		list.insertBefore( direction < 0 ? row : neighbour, direction < 0 ? neighbour : row );
		sync();
		announce( 'Campanha movida para a posição ' + ( rows().indexOf( row ) + 1 ) + '. Salve para aplicar.' );
	};
	form.querySelector( '[data-hdc-lp-campaign-add]' ).addEventListener( 'click', function () {
		const row = template.content.firstElementChild.cloneNode( true );
		const id = window.crypto.randomUUID ? window.crypto.randomUUID() : Date.now().toString( 36 ) + '-' + Math.random().toString( 36 ).slice( 2 );
		row.querySelector( '[data-hdc-lp-campaign-field="id"]' ).value = 'hdc-lp-campaign-' + id;
		list.append( row );
		sync();
		row.querySelector( '[data-hdc-lp-campaign-field="name"]' ).focus();
		announce( 'Campanha adicionada. Selecione uma imagem, preencha os textos e salve.' );
	} );
	list.addEventListener( 'click', function ( event ) {
		const button = event.target.closest( 'button' );
		const row = button && button.closest( '[data-hdc-lp-campaign-row]' );
		if ( ! row ) { return; }
		if ( button.hasAttribute( 'data-hdc-lp-campaign-up' ) ) { move( row, -1 ); }
		if ( button.hasAttribute( 'data-hdc-lp-campaign-down' ) ) { move( row, 1 ); }
		if ( button.hasAttribute( 'data-hdc-lp-campaign-remove' ) ) {
			const next = row.nextElementSibling || row.previousElementSibling;
			row.remove();
			sync();
			( next ? next.querySelector( '[data-hdc-lp-campaign-remove]' ) : form.querySelector( '[data-hdc-lp-campaign-add]' ) ).focus();
			announce( 'Campanha removida da lista. A imagem permanece na Biblioteca de Mídia. Salve para aplicar.' );
		}
		if ( button.hasAttribute( 'data-hdc-lp-campaign-select' ) ) {
			const media = wp.media( { title: 'Imagem da campanha', button: { text: 'Usar esta imagem' }, library: { type: [ 'image/jpeg', 'image/png', 'image/webp' ] }, multiple: false } );
			const current = Number( row.querySelector( '[data-hdc-lp-campaign-field="attachment_id"]' ).value );
			media.on( 'open', function () {
				if ( current ) { media.state().get( 'selection' ).add( wp.media.attachment( current ) ); }
			} );
			media.on( 'select', function () {
				const image = media.state().get( 'selection' ).first().toJSON();
				if ( ! [ 'image/jpeg', 'image/png', 'image/webp' ].includes( image.mime ) ) {
					announce( 'Selecione uma imagem JPEG, PNG ou WebP.' ); return;
				}
				row.querySelector( '[data-hdc-lp-campaign-field="attachment_id"]' ).value = image.id;
				row.querySelector( '[data-hdc-lp-campaign-field="bundled_key"]' ).value = '';
				const thumbnail = row.querySelector( '[data-hdc-lp-campaign-image]' );
				thumbnail.src = ( image.sizes && image.sizes.large ? image.sizes.large.url : image.url );
				thumbnail.hidden = false;
				row.querySelector( '[data-hdc-lp-campaign-image-status]' ).textContent = 'Imagem completa, sem recorte.';
				const name = row.querySelector( '[data-hdc-lp-campaign-field="name"]' );
				if ( ! name.value ) { name.value = image.title || ''; }
				const alt = row.querySelector( '[data-hdc-lp-campaign-field="alt"]' );
				alt.value = image.alt || '';
				alt.focus();
				announce( 'Imagem selecionada. Confira o texto alternativo e salve para aplicar.' );
			} );
			media.open();
		}
	} );
	list.addEventListener( 'dragstart', function ( event ) {
		const handle = event.target.closest( '[data-hdc-lp-campaign-drag]' );
		if ( ! handle ) { event.preventDefault(); return; }
		dragged = handle.closest( '[data-hdc-lp-campaign-row]' );
		dragged.classList.add( 'hdc-lp-campaigns-admin__row--dragging' );
		event.dataTransfer.effectAllowed = 'move';
		event.dataTransfer.setData( 'text/plain', dragged.querySelector( '[data-hdc-lp-campaign-field="id"]' ).value );
	} );
	list.addEventListener( 'dragover', function ( event ) {
		if ( ! dragged ) { return; }
		event.preventDefault();
		event.dataTransfer.dropEffect = 'move';
		const target = event.target.closest( '[data-hdc-lp-campaign-row]' );
		if ( target && target !== dragged ) {
			const rect = target.getBoundingClientRect();
			list.insertBefore( dragged, event.clientY < rect.top + rect.height / 2 ? target : target.nextElementSibling );
		}
	} );
	list.addEventListener( 'drop', event => { if ( dragged ) { event.preventDefault(); } } );
	list.addEventListener( 'dragend', function () {
		if ( ! dragged ) { return; }
		dragged.classList.remove( 'hdc-lp-campaigns-admin__row--dragging' );
		sync();
		announce( 'Campanha movida para a posição ' + ( rows().indexOf( dragged ) + 1 ) + '. Salve para aplicar.' );
		dragged.querySelector( '[data-hdc-lp-campaign-drag]' ).focus();
		dragged = null;
	} );
	form.addEventListener( 'submit', sync );
	sync();
}() );
