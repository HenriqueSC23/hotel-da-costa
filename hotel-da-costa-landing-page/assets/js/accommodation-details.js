( function () {
	'use strict';

	function hdcLpInitRoomDetails() {
		const dialog = document.querySelector( '[data-hdc-lp-room-modal]' );
		const config = document.querySelector( '[data-hdc-lp-room-data]' );
		if ( ! dialog || ! config || 'function' !== typeof dialog.showModal ) { return; }
		let data;
		try { data = JSON.parse( config.textContent ); } catch ( error ) { return; }
		if ( ! data || ! Array.isArray( data.rooms ) ) { return; }
		const rooms = new Map( data.rooms.map( function ( room ) { return [ room.source, room ]; } ) );
		const assets = new URL( config.dataset.hdcLpRoomAssets, document.baseURI );
		const title = dialog.querySelector( '[data-hdc-lp-room-title]' );
		const description = dialog.querySelector( '[data-hdc-lp-room-description]' );
		const features = dialog.querySelector( '[data-hdc-lp-room-features]' );
		const track = dialog.querySelector( '[data-hdc-lp-room-track]' );
		const pagination = dialog.querySelector( '[data-hdc-lp-room-pagination]' );
		const status = dialog.querySelector( '[data-hdc-lp-room-status]' );
		const close = dialog.querySelector( '[data-hdc-lp-room-close]' );
		const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );
		let room = null;
		let activeIndex = 0;
		let opener = null;
		let drag = null;
		let commandedScroll = false;
		let scrollTimer = null;
		let backdropPress = false;

		const getStep = function () {
			return track.firstElementChild ? track.firstElementChild.getBoundingClientRect().width + Number.parseFloat( getComputedStyle( track ).columnGap ) : 0;
		};
		const updateDots = function () {
			Array.from( pagination.children ).forEach( function ( dot, index ) {
				if ( index === activeIndex ) { dot.setAttribute( 'aria-current', 'true' ); }
				else { dot.removeAttribute( 'aria-current' ); }
			} );
			if ( room ) { status.textContent = 'Foto ' + ( activeIndex + 1 ) + ' de ' + room.images.length + ' — ' + room.title; }
		};
		const selectPhoto = function ( index, instant ) {
			if ( ! room ) { return; }
			activeIndex = ( index + room.images.length ) % room.images.length;
			commandedScroll = true;
			updateDots();
			track.scrollTo( { left: Math.min( activeIndex * getStep(), track.scrollWidth - track.clientWidth ), behavior: instant || reducedMotion.matches ? 'auto' : 'smooth' } );
		};
		const openRoom = function ( selected, link ) {
			room = selected;
			opener = link;
			activeIndex = 0;
			title.textContent = room.title;
			description.textContent = room.description;
			features.replaceChildren();
			room.features.forEach( function ( label ) {
				const item = document.createElement( 'li' );
				const check = document.createElement( 'span' );
				const text = document.createElement( 'span' );
				item.className = 'hdc-lp-room-modal__feature';
				check.className = 'hdc-lp-room-modal__check';
				check.setAttribute( 'aria-hidden', 'true' );
				text.textContent = label;
				item.append( check, text );
				features.append( item );
			} );
			track.replaceChildren();
			pagination.replaceChildren();
			room.images.forEach( function ( photo, index ) {
				const item = document.createElement( 'li' );
				const image = document.createElement( 'img' );
				const dot = document.createElement( 'button' );
				item.className = 'hdc-lp-room-modal__photo';
				image.src = new URL( photo.file, assets ).href;
				image.alt = photo.alt;
				image.width = photo.width;
				image.height = photo.height;
				image.loading = 0 === index ? 'eager' : 'lazy';
				image.decoding = 'async';
				image.draggable = false;
				item.append( image );
				track.append( item );
				dot.className = 'hdc-lp-room-modal__dot';
				dot.type = 'button';
				dot.setAttribute( 'aria-label', 'Exibir foto ' + ( index + 1 ) + ' de ' + room.images.length + ': ' + room.title );
				dot.setAttribute( 'aria-controls', track.id );
				dot.addEventListener( 'click', function () { selectPhoto( index ); } );
				pagination.append( dot );
			} );
			dialog.showModal();
			dialog.scrollTop = 0;
			document.body.classList.add( 'hdc-lp-room-modal-open' );
			selectPhoto( 0, true );
			close.focus( { preventScroll: true } );
		};
		document.querySelectorAll( '.hdc-lp-accommodations__link' ).forEach( function ( link ) {
			const selected = rooms.get( link.href );
			if ( ! selected || ! selected.images.length ) { return; }
			link.setAttribute( 'aria-haspopup', 'dialog' );
			link.setAttribute( 'aria-controls', dialog.id );
			link.addEventListener( 'click', function ( event ) {
				if ( event.ctrlKey || event.metaKey || event.shiftKey || event.altKey ) { return; }
				event.preventDefault();
				openRoom( selected, link );
			} );
		} );
		close.addEventListener( 'click', function () { dialog.close(); } );
		dialog.addEventListener( 'close', function () {
			document.body.classList.remove( 'hdc-lp-room-modal-open' );
			window.clearTimeout( scrollTimer );
			if ( opener ) { opener.focus( { preventScroll: true } ); }
		} );
		dialog.addEventListener( 'pointerdown', function ( event ) {
			const rect = dialog.getBoundingClientRect();
			backdropPress = event.target === dialog && ( event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom );
		} );
		dialog.addEventListener( 'click', function ( event ) {
			if ( backdropPress && event.target === dialog ) { dialog.close(); }
			backdropPress = false;
		} );
		dialog.querySelector( '[data-hdc-lp-room-previous]' ).addEventListener( 'click', function () { selectPhoto( activeIndex - 1 ); } );
		dialog.querySelector( '[data-hdc-lp-room-next]' ).addEventListener( 'click', function () { selectPhoto( activeIndex + 1 ); } );
		dialog.addEventListener( 'keydown', function ( event ) {
			if ( 'ArrowLeft' === event.key || 'ArrowRight' === event.key || 'Home' === event.key || 'End' === event.key ) {
				event.preventDefault();
				selectPhoto( 'Home' === event.key ? 0 : 'End' === event.key ? room.images.length - 1 : activeIndex + ( 'ArrowRight' === event.key ? 1 : -1 ) );
			}
			if ( 'Tab' !== event.key ) { return; }
			const controls = Array.from( dialog.querySelectorAll( 'button, [tabindex="0"]' ) ).filter( function ( control ) { return control.getClientRects().length; } );
			const first = controls[0];
			const last = controls[controls.length - 1];
			if ( event.shiftKey && document.activeElement === first ) { event.preventDefault(); last.focus(); }
			else if ( ! event.shiftKey && document.activeElement === last ) { event.preventDefault(); first.focus(); }
		} );
		// Keep a directly selected final image active even when several images fit together.
		track.addEventListener( 'scroll', function () {
			window.clearTimeout( scrollTimer );
			if ( ! commandedScroll && room ) {
				const maximum = track.scrollWidth - track.clientWidth;
				activeIndex = track.scrollLeft >= maximum - 1 ? room.images.length - 1 : Math.max( 0, Math.min( room.images.length - 1, Math.round( track.scrollLeft / getStep() ) ) );
				updateDots();
			}
			scrollTimer = window.setTimeout( function () { commandedScroll = false; }, 150 );
		}, { passive: true } );
		track.addEventListener( 'wheel', function () { commandedScroll = false; }, { passive: true } );
		track.addEventListener( 'pointerdown', function ( event ) {
			commandedScroll = false;
			if ( ! event.isPrimary || 'touch' === event.pointerType || 0 !== event.button ) { return; }
			drag = { id: event.pointerId, x: event.clientX, y: event.clientY, left: track.scrollLeft, moved: false };
		} );
		track.addEventListener( 'pointermove', function ( event ) {
			if ( ! drag || drag.id !== event.pointerId ) { return; }
			const dx = event.clientX - drag.x;
			const dy = event.clientY - drag.y;
			if ( ! drag.moved ) {
				if ( Math.max( Math.abs( dx ), Math.abs( dy ) ) < 6 ) { return; }
				if ( Math.abs( dy ) > Math.abs( dx ) ) { drag = null; return; }
				drag.moved = true;
				track.classList.add( 'hdc-lp-room-modal__track--dragging' );
				track.setPointerCapture( event.pointerId );
			}
			event.preventDefault();
			track.scrollLeft = drag.left - dx;
		} );
		const endDrag = function ( event ) {
			if ( ! drag || drag.id !== event.pointerId ) { return; }
			const moved = drag.moved;
			drag = null;
			track.classList.remove( 'hdc-lp-room-modal__track--dragging' );
			if ( track.hasPointerCapture( event.pointerId ) ) { track.releasePointerCapture( event.pointerId ); }
			if ( moved ) { selectPhoto( Math.round( track.scrollLeft / getStep() ) ); }
		};
		track.addEventListener( 'pointerup', endDrag );
		track.addEventListener( 'pointercancel', endDrag );
		track.addEventListener( 'lostpointercapture', endDrag );
		track.addEventListener( 'dragstart', function ( event ) { event.preventDefault(); } );
		if ( 'ResizeObserver' in window ) {
			new ResizeObserver( function () { if ( dialog.open ) { selectPhoto( activeIndex, true ); } } ).observe( track );
		}
	}
	if ( 'loading' === document.readyState ) { document.addEventListener( 'DOMContentLoaded', hdcLpInitRoomDetails ); }
	else { hdcLpInitRoomDetails(); }
}() );
