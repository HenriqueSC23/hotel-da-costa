( function () {
	'use strict';
	const section = document.querySelector( '.hdc-lp-promotions' );
	if ( ! section ) { return; }
	section.dataset.hdcLpCampaignsStatus = 'loading';
	fetch( '/preview/api/campaigns', { cache: 'no-store' } )
		.then( response => { if ( ! response.ok ) { throw new Error( 'WordPress indisponível' ); } return response.json(); } )
		.then( data => {
			if ( ! data || ! Array.isArray( data.items ) ) { throw new Error( 'Dados inválidos' ); }
			const track = section.querySelector( '[data-hdc-lp-carousel-track]' );
			const cards = data.items.map( item => {
				const url = new URL( item.url );
				if ( ! [ 'http:', 'https:' ].includes( url.protocol ) || !( item.width > 0 && item.height > 0 ) ) { throw new Error( 'Imagem inválida' ); }
				const card = document.createElement( 'div' );
				card.className = 'hdc-lp-promotions__card';
				card.setAttribute( 'role', 'listitem' );
				const image = document.createElement( 'img' );
				image.className = 'hdc-lp-promotions__image';
				image.src = url.href;
				image.srcset = item.srcset || '';
				image.sizes = item.sizes || '';
				image.width = item.width;
				image.height = item.height;
				image.alt = item.alt || '';
				image.loading = 'lazy';
				image.decoding = 'async';
				image.draggable = false;
				card.append( image );
				return card;
			} );
			track.replaceChildren( ...cards );
			section.hidden = cards.length === 0;
			section.dataset.hdcLpCampaignsStatus = 'synced';
			section.querySelector( '[data-hdc-lp-carousel]' ).dispatchEvent( new Event( 'hdc-lp-campaigns-updated' ) );
			window.dispatchEvent( new Event( 'resize' ) );
		} )
		.catch( () => { section.dataset.hdcLpCampaignsStatus = 'reference'; } );
}() );
