( function () {
	'use strict';

	function hdcLpInitScrollReveal() {
		const root = document.querySelector( '.hdc-lp' );

		if ( ! root ) {
			return;
		}

		const elements = Array.from( root.querySelectorAll( '[data-hdc-lp-scroll-reveal]' ) );

		if ( ! elements.length ) {
			return;
		}

		const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );
		let animationFrame = null;

		const showWithoutMotion = function () {
			root.classList.remove( 'hdc-lp--scroll-reveal-ready' );
			elements.forEach( function ( element ) {
				element.classList.add( 'hdc-lp-scroll-reveal--visible' );
			} );
		};

		const syncReveal = function () {
			if ( reducedMotion.matches ) {
				showWithoutMotion();
				return;
			}

			root.classList.add( 'hdc-lp--scroll-reveal-ready' );

			const triggerBottom = window.innerHeight * 0.9;
			const reachedPageEnd = Math.ceil( window.scrollY + window.innerHeight ) >= document.documentElement.scrollHeight - 1;

			elements.forEach( function ( element ) {
				if ( reachedPageEnd || element.getBoundingClientRect().top <= triggerBottom ) {
					element.classList.add( 'hdc-lp-scroll-reveal--visible' );
				} else {
					element.classList.remove( 'hdc-lp-scroll-reveal--visible' );
				}
			} );
		};

		const requestRevealSync = function () {
			if ( null !== animationFrame ) {
				return;
			}

			animationFrame = window.requestAnimationFrame( function () {
				animationFrame = null;
				syncReveal();
			} );
		};

		window.addEventListener( 'scroll', requestRevealSync, { passive: true } );
		window.addEventListener( 'resize', requestRevealSync, { passive: true } );

		if ( reducedMotion.addEventListener ) {
			reducedMotion.addEventListener( 'change', requestRevealSync );
		} else if ( reducedMotion.addListener ) {
			reducedMotion.addListener( requestRevealSync );
		}

		syncReveal();
	}

	function hdcLpInitPromotionCarousels() {
		const carousels = Array.from( document.querySelectorAll( '[data-hdc-lp-carousel]' ) );
		const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

		carousels.forEach( function ( carousel ) {
			const track = carousel.querySelector( '[data-hdc-lp-carousel-track]' );
			const previous = carousel.querySelector( '[data-hdc-lp-carousel-previous]' );
			const next = carousel.querySelector( '[data-hdc-lp-carousel-next]' );
			let drag = null;

			if ( ! track || ! previous || ! next ) {
				return;
			}

			const getScrollStep = function () {
				const card = track.querySelector( '.hdc-lp-promotions__card' );
				const styles = window.getComputedStyle( track );
				const gap = Number.parseFloat( styles.columnGap || styles.gap ) || 0;

				return card ? card.getBoundingClientRect().width + gap : track.clientWidth;
			};

			const getMaximum = function () {
				return Math.max( 0, track.scrollWidth - track.clientWidth );
			};

			const scrollToPosition = function ( target ) {
				track.scrollTo( {
					left: Math.max( 0, Math.min( getMaximum(), target ) ),
					behavior: reducedMotion.matches ? 'auto' : 'smooth',
				} );
			};

			const syncControls = function () {
				previous.disabled = getMaximum() <= 1;
				next.disabled = previous.disabled;
			};

			const move = function ( direction ) {
				const maximum = getMaximum();
				const atStart = track.scrollLeft <= 1;
				const atEnd = track.scrollLeft >= maximum - 1;
				let target = track.scrollLeft + ( getScrollStep() * direction );

				if ( direction < 0 && atStart ) {
					target = maximum;
				} else if ( direction > 0 && atEnd ) {
					target = 0;
				}

				scrollToPosition( target );
			};

			previous.addEventListener( 'click', function () {
				move( -1 );
			} );

			next.addEventListener( 'click', function () {
				move( 1 );
			} );

			// Native touch scrolling preserves vertical page scrolling, momentum and zoom.
			track.addEventListener( 'pointerdown', function ( event ) {
				if ( ! event.isPrimary || 'touch' === event.pointerType || 0 !== event.button || getMaximum() <= 1 ) {
					return;
				}
				drag = { id: event.pointerId, x: event.clientX, y: event.clientY, left: track.scrollLeft, moved: false };
			} );
			track.addEventListener( 'pointermove', function ( event ) {
				if ( ! drag || drag.id !== event.pointerId ) { return; }
				const deltaX = event.clientX - drag.x;
				const deltaY = event.clientY - drag.y;
				if ( ! drag.moved ) {
					if ( Math.max( Math.abs( deltaX ), Math.abs( deltaY ) ) < 6 ) { return; }
					if ( Math.abs( deltaY ) > Math.abs( deltaX ) ) { drag = null; return; }
					drag.moved = true;
					track.classList.add( 'hdc-lp-promotions__track--dragging' );
					track.setPointerCapture( event.pointerId );
				}
				event.preventDefault();
				track.scrollLeft = drag.left - deltaX;
			} );
			const finishDrag = function ( event ) {
				if ( ! drag || drag.id !== event.pointerId ) { return; }
				const moved = drag.moved;
				drag = null;
				track.classList.remove( 'hdc-lp-promotions__track--dragging' );
				if ( track.hasPointerCapture( event.pointerId ) ) { track.releasePointerCapture( event.pointerId ); }
				if ( moved ) {
					const step = getScrollStep();
					scrollToPosition( Math.round( track.scrollLeft / step ) * step );
				}
			};
			track.addEventListener( 'pointerup', finishDrag );
			track.addEventListener( 'pointercancel', finishDrag );
			track.addEventListener( 'lostpointercapture', finishDrag );
			track.addEventListener( 'dragstart', function ( event ) { event.preventDefault(); } );

			track.addEventListener( 'keydown', function ( event ) {
				if ( 'ArrowLeft' === event.key ) {
					event.preventDefault();
					move( -1 );
				} else if ( 'ArrowRight' === event.key ) {
					event.preventDefault();
					move( 1 );
				} else if ( 'Home' === event.key || 'End' === event.key ) {
					event.preventDefault();
					scrollToPosition( 'Home' === event.key ? 0 : getMaximum() );
				}
			} );
			if ( 'ResizeObserver' in window ) {
				new ResizeObserver( syncControls ).observe( track );
			} else {
				window.addEventListener( 'resize', syncControls, { passive: true } );
			}
			carousel.addEventListener( 'hdc-lp-campaigns-updated', function () {
				track.scrollLeft = 0;
				syncControls();
			} );
			syncControls();
		} );
	}

	function hdcLpInitSnapDotCarousels( config ) {
		const carousels = Array.from( document.querySelectorAll( config.carouselSelector ) );
		const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

		carousels.forEach( function ( carousel ) {
			const track = carousel.querySelector( config.trackSelector );
			const pagination = carousel.parentElement.querySelector( config.paginationSelector );
			const dots = pagination ? Array.from( pagination.querySelectorAll( config.dotSelector ) ) : [];
			let animationFrame = null;
			let dragState = null;
			let suppressClick = false;

			if ( ! track || ! dots.length ) {
				return;
			}

			const getMaximum = function () {
				return Math.max( 0, track.scrollWidth - track.clientWidth );
			};

			const getScrollStep = function () {
				const card = track.querySelector( config.cardSelector );
				const styles = window.getComputedStyle( track );
				const gap = Number.parseFloat( styles.columnGap || styles.gap ) || 0;

				return card ? card.getBoundingClientRect().width + gap : track.clientWidth;
			};

			const setActiveDot = function ( activeIndex ) {
				dots.forEach( function ( dot, index ) {
					const isActive = index === activeIndex;

					dot.classList.toggle( config.activeDotClass, isActive );
					if ( isActive ) {
						dot.setAttribute( 'aria-current', 'true' );
					} else {
						dot.removeAttribute( 'aria-current' );
					}
				} );
			};

			const syncPagination = function () {
				const maximum = getMaximum();
				const progress = maximum > 0 ? track.scrollLeft / maximum : 0;
				const activeIndex = Math.round( progress * ( dots.length - 1 ) );

				setActiveDot( Math.max( 0, Math.min( dots.length - 1, activeIndex ) ) );
			};

			const requestPaginationSync = function () {
				if ( null !== animationFrame ) {
					return;
				}

				animationFrame = window.requestAnimationFrame( function () {
					animationFrame = null;
					syncPagination();
				} );
			};

			const scrollToPosition = function ( target ) {
				track.scrollTo( {
					left: Math.max( 0, Math.min( getMaximum(), target ) ),
					behavior: reducedMotion.matches ? 'auto' : 'smooth',
				} );
			};

			const finishDrag = function ( pointerId, shouldSnap ) {
				if ( ! dragState || dragState.pointerId !== pointerId ) {
					return;
				}

				const wasDragging = dragState.isDragging;
				dragState = null;
				track.classList.remove( config.draggingClass );

				if ( track.hasPointerCapture && track.hasPointerCapture( pointerId ) ) {
					track.releasePointerCapture( pointerId );
				}

				if ( shouldSnap && wasDragging ) {
					suppressClick = true;
					const step = getScrollStep();
					scrollToPosition( Math.round( track.scrollLeft / step ) * step );
				} else {
					requestPaginationSync();
				}
			};

			dots.forEach( function ( dot, index ) {
				dot.addEventListener( 'click', function () {
					const progress = dots.length > 1 ? index / ( dots.length - 1 ) : 0;

					scrollToPosition( getMaximum() * progress );
				} );
			} );

			track.addEventListener( 'pointerdown', function ( event ) {
				if ( ! event.isPrimary || ( 'mouse' === event.pointerType && 0 !== event.button ) ) {
					return;
				}

				dragState = {
					pointerId: event.pointerId,
					startX: event.clientX,
					startY: event.clientY,
					startScrollLeft: track.scrollLeft,
					isDragging: false,
				};
			} );

			track.addEventListener( 'pointermove', function ( event ) {
				if ( ! dragState || dragState.pointerId !== event.pointerId ) {
					return;
				}

				const deltaX = event.clientX - dragState.startX;
				const deltaY = event.clientY - dragState.startY;

				if ( ! dragState.isDragging ) {
					if ( Math.abs( deltaX ) < 6 && Math.abs( deltaY ) < 6 ) {
						return;
					}

					if ( Math.abs( deltaY ) > Math.abs( deltaX ) ) {
						finishDrag( event.pointerId, false );
						return;
					}

					dragState.isDragging = true;
					track.classList.add( config.draggingClass );
					if ( track.setPointerCapture ) {
						track.setPointerCapture( event.pointerId );
					}
				}

				event.preventDefault();
				track.scrollLeft = dragState.startScrollLeft - deltaX;
			} );

			track.addEventListener( 'pointerup', function ( event ) {
				finishDrag( event.pointerId, true );
			} );

			track.addEventListener( 'pointercancel', function ( event ) {
				finishDrag( event.pointerId, true );
			} );

			track.addEventListener( 'lostpointercapture', function ( event ) {
				// Child touch capture loss bubbles while capture transfers to the track.
				if ( event.target === track ) {
					finishDrag( event.pointerId, true );
				}
			} );

			track.addEventListener( 'click', function ( event ) {
				if ( suppressClick ) {
					event.preventDefault();
					event.stopPropagation();
					suppressClick = false;
				}
			}, true );

			track.addEventListener( 'scroll', requestPaginationSync, { passive: true } );
			track.addEventListener( 'keydown', function ( event ) {
				if ( 'ArrowLeft' === event.key ) {
					event.preventDefault();
					scrollToPosition( track.scrollLeft - getScrollStep() );
				} else if ( 'ArrowRight' === event.key ) {
					event.preventDefault();
					scrollToPosition( track.scrollLeft + getScrollStep() );
				}
			} );
			window.addEventListener( 'resize', requestPaginationSync, { passive: true } );

			syncPagination();
		} );
	}

	function hdcLpInitServiceCarousels() {
		hdcLpInitSnapDotCarousels( {
			carouselSelector: '[data-hdc-lp-services-carousel]',
			trackSelector: '[data-hdc-lp-services-track]',
			paginationSelector: '.hdc-lp-services__pagination',
			dotSelector: '[data-hdc-lp-services-dot]',
			cardSelector: '.hdc-lp-services__card',
			activeDotClass: 'hdc-lp-services__dot--active',
			draggingClass: 'hdc-lp-services__track--dragging',
		} );
	}

	function hdcLpInitAccommodationCarousels() {
		hdcLpInitSnapDotCarousels( {
			carouselSelector: '[data-hdc-lp-accommodations-carousel]',
			trackSelector: '[data-hdc-lp-accommodations-track]',
			paginationSelector: '.hdc-lp-accommodations__pagination',
			dotSelector: '[data-hdc-lp-accommodations-dot]',
			cardSelector: '.hdc-lp-accommodations__card',
			activeDotClass: 'hdc-lp-accommodations__dot--active',
			draggingClass: 'hdc-lp-accommodations__track--dragging',
		} );
	}

	function hdcLpInitDifferentials() {
		const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

		document.querySelectorAll( '[data-hdc-lp-differentials]' ).forEach( function ( section ) {
			const items = Array.from( section.querySelectorAll( '[data-hdc-lp-differentials-item]' ) );
			const buttons = Array.from( section.querySelectorAll( '[data-hdc-lp-differentials-select]' ) );
			const backgrounds = Array.from( section.querySelectorAll( '[data-hdc-lp-differentials-background]' ) );
			const captions = Array.from( section.querySelectorAll( '[data-hdc-lp-differentials-caption]' ) );
			const captionRegion = section.querySelector( '[data-hdc-lp-differentials-captions]' );
			const duration = 5000;
			let activeIndex = 0;
			let remaining = duration;
			let deadline = 0;
			let timer = null;
			let inView = !( 'IntersectionObserver' in window );

			if ( items.length < 2 || items.length !== backgrounds.length ) {
				return;
			}

			const stopClock = function () {
				if ( null !== timer ) {
					window.clearTimeout( timer );
					timer = null;
					remaining = Math.max( 0, deadline - performance.now() );
				}
			};

			const syncPlayback = function () {
				const playing = inView && ! document.hidden && ! reducedMotion.matches;
				section.classList.toggle( 'hdc-lp-differentials--paused', ! playing );

				if ( ! playing ) {
					stopClock();
					return;
				}

				if ( null === timer ) {
					deadline = performance.now() + remaining;
					timer = window.setTimeout( function () {
						timer = null;
						selectItem( ( activeIndex + 1 ) % items.length, false );
					}, remaining );
				}
			};

			const selectItem = function ( index, manual ) {
				stopClock();
				activeIndex = index;
				remaining = duration;
				captionRegion.setAttribute( 'aria-live', manual ? 'polite' : 'off' );

				items.forEach( function ( item ) {
					item.classList.remove( 'hdc-lp-differentials__item--active' );
				} );
				// Restart the ring even when the current eye is selected again.
				void section.offsetWidth;
				items[ index ].classList.add( 'hdc-lp-differentials__item--active' );
				buttons.forEach( function ( button, itemIndex ) {
					button.setAttribute( 'aria-pressed', itemIndex === index ? 'true' : 'false' );
				} );
				backgrounds.forEach( function ( background, itemIndex ) {
					background.classList.toggle( 'hdc-lp-differentials__background--active', itemIndex === index );
				} );
				captions.forEach( function ( caption, itemIndex ) {
					caption.classList.toggle( 'hdc-lp-differentials__caption--active', itemIndex === index );
					caption.setAttribute( 'aria-hidden', itemIndex === index ? 'false' : 'true' );
				} );
				syncPlayback();
			};

			buttons.forEach( function ( button, index ) {
				button.addEventListener( 'click', function () {
					selectItem( index, true );
				} );
				button.addEventListener( 'keydown', function ( event ) {
					let target = index;
					if ( 'ArrowRight' === event.key ) {
						target = ( index + 1 ) % items.length;
					} else if ( 'ArrowLeft' === event.key ) {
						target = ( index + items.length - 1 ) % items.length;
					} else if ( 'Home' === event.key ) {
						target = 0;
					} else if ( 'End' === event.key ) {
						target = items.length - 1;
					} else {
						return;
					}
					event.preventDefault();
					selectItem( target, true );
					buttons[ target ].focus( { preventScroll: true } );
				} );
			} );

			document.addEventListener( 'visibilitychange', syncPlayback );
			if ( reducedMotion.addEventListener ) {
				reducedMotion.addEventListener( 'change', syncPlayback );
			} else if ( reducedMotion.addListener ) {
				reducedMotion.addListener( syncPlayback );
			}

			section.classList.add( 'hdc-lp-differentials--ready' );
			if ( 'IntersectionObserver' in window ) {
				const observer = new IntersectionObserver( function ( entries ) {
					inView = entries[ 0 ].isIntersecting;
					if ( inView ) {
						backgrounds.forEach( function ( background ) { background.loading = 'eager'; } );
					}
					syncPlayback();
				}, { threshold: 0.15 } );
				observer.observe( section );
			}
			syncPlayback();
		} );
	}

	function hdcLpInitGallery() {
		const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

		document.querySelectorAll( '[data-hdc-lp-gallery]' ).forEach( function ( gallery ) {
			const track = gallery.querySelector( '[data-hdc-lp-gallery-track]' );
			const links = Array.from( gallery.querySelectorAll( '[data-hdc-lp-gallery-open]' ) );
			const previous = gallery.querySelector( '[data-hdc-lp-gallery-previous]' );
			const next = gallery.querySelector( '[data-hdc-lp-gallery-next]' );
			const dialog = gallery.querySelector( '[data-hdc-lp-lightbox]' );
			if ( ! dialog ) { return; }
			const image = dialog.querySelector( '[data-hdc-lp-lightbox-image]' );
			const caption = dialog.querySelector( '[data-hdc-lp-lightbox-caption]' );
			const thumbnailStrip = dialog.querySelector( '[data-hdc-lp-lightbox-thumbnails]' );
			const close = dialog.querySelector( '[data-hdc-lp-lightbox-close]' );
			let activeIndex = 0;
			let opener = null;
			let drag = null;
			let suppressClick = false;
			let swipe = null;

			if ( ! track || ! links.length || ! previous || ! next ) {
				return;
			}

			const photos = links.map( function ( link ) {
				return { url: link.href, thumbnail: link.dataset.hdcLpGalleryThumbnail, alt: link.querySelector( 'img' ).alt };
			} );
			const getMaximum = function () { return Math.max( 0, track.scrollWidth - track.clientWidth ); };
			const getStep = function () {
				return track.firstElementChild.getBoundingClientRect().width + ( Number.parseFloat( getComputedStyle( track ).columnGap ) || 0 );
			};
			const scrollTo = function ( target ) {
				track.scrollTo( { left: Math.max( 0, Math.min( getMaximum(), target ) ), behavior: reducedMotion.matches ? 'auto' : 'smooth' } );
			};
			const moveTrack = function ( direction ) {
				const maximum = getMaximum();
				let target = track.scrollLeft + direction * getStep();
				if ( direction > 0 && track.scrollLeft >= maximum - 1 ) { target = 0; }
				if ( direction < 0 && track.scrollLeft <= 1 ) { target = maximum; }
				scrollTo( target );
			};
			const syncControls = function () {
				// Disable navigation only when every photo fits in the track.
				previous.disabled = getMaximum() <= 1;
				next.disabled = previous.disabled;
			};
			previous.addEventListener( 'click', function () { moveTrack( -1 ); } );
			next.addEventListener( 'click', function () { moveTrack( 1 ); } );
			track.addEventListener( 'keydown', function ( event ) {
				if ( 'ArrowLeft' === event.key || 'ArrowRight' === event.key ) {
					event.preventDefault();
					moveTrack( 'ArrowLeft' === event.key ? -1 : 1 );
				}
			} );

			// Touch uses native horizontal scrolling, preserving vertical page scroll and zoom.
			track.addEventListener( 'pointerdown', function ( event ) {
				if ( ! event.isPrimary || 'touch' === event.pointerType || 0 !== event.button ) { return; }
				drag = { id: event.pointerId, x: event.clientX, y: event.clientY, left: track.scrollLeft, moved: false };
			} );
			track.addEventListener( 'pointermove', function ( event ) {
				if ( ! drag || event.pointerId !== drag.id ) { return; }
				const deltaX = event.clientX - drag.x;
				const deltaY = event.clientY - drag.y;
				if ( ! drag.moved ) {
					if ( Math.max( Math.abs( deltaX ), Math.abs( deltaY ) ) < 6 ) { return; }
					if ( Math.abs( deltaY ) > Math.abs( deltaX ) ) { drag = null; return; }
					drag.moved = true;
					track.classList.add( 'hdc-lp-gallery__track--dragging' );
					track.setPointerCapture( event.pointerId );
				}
				event.preventDefault();
				track.scrollLeft = drag.left - deltaX;
			} );
			const endDrag = function ( event ) {
				if ( ! drag || event.pointerId !== drag.id ) { return; }
				const moved = drag.moved;
				drag = null;
				track.classList.remove( 'hdc-lp-gallery__track--dragging' );
				if ( track.hasPointerCapture( event.pointerId ) ) { track.releasePointerCapture( event.pointerId ); }
				if ( moved ) {
					suppressClick = true;
					window.setTimeout( function () { suppressClick = false; }, 0 );
					scrollTo( Math.round( track.scrollLeft / getStep() ) * getStep() );
				}
			};
			track.addEventListener( 'pointerup', endDrag );
			track.addEventListener( 'pointercancel', endDrag );
			track.addEventListener( 'lostpointercapture', endDrag );
			track.addEventListener( 'dragstart', function ( event ) { event.preventDefault(); } );
			track.addEventListener( 'click', function ( event ) {
				if ( suppressClick ) { event.preventDefault(); event.stopPropagation(); }
			}, true );
			if ( 'ResizeObserver' in window ) {
				new ResizeObserver( syncControls ).observe( track );
			} else {
				window.addEventListener( 'resize', syncControls, { passive: true } );
			}
			syncControls();
			gallery.classList.add( 'hdc-lp-gallery--ready' );

			// Without native dialog support, the links still open the full-resolution photos.
			if ( ! dialog || 'function' !== typeof dialog.showModal ) { return; }

			const thumbnails = photos.map( function ( photo, index ) {
				const button = document.createElement( 'button' );
				button.type = 'button';
				button.className = 'hdc-lp-lightbox__thumbnail';
				button.setAttribute( 'aria-label', 'Exibir imagem ' + ( index + 1 ) + ' de ' + photos.length + ': ' + photo.alt );
				button.setAttribute( 'aria-pressed', 'false' );
				const thumbnail = document.createElement( 'img' );
				thumbnail.src = photo.thumbnail;
				thumbnail.alt = '';
				thumbnail.width = 160;
				thumbnail.height = 107;
				thumbnail.loading = 'lazy';
				thumbnail.decoding = 'async';
				button.appendChild( thumbnail );
				button.addEventListener( 'click', function () { selectPhoto( index ); } );
				thumbnailStrip.appendChild( button );
				return button;
			} );
			const selectPhoto = function ( index ) {
				activeIndex = ( index + photos.length ) % photos.length;
				const photo = photos[ activeIndex ];
				image.classList.add( 'hdc-lp-lightbox__image--loading' );
				image.setAttribute( 'aria-busy', 'true' );
				image.alt = photo.alt;
				image.src = photo.url;
				caption.textContent = ( activeIndex + 1 ) + ' / ' + photos.length + ' — ' + photo.alt;
				thumbnails.forEach( function ( button, photoIndex ) {
					button.setAttribute( 'aria-pressed', photoIndex === activeIndex ? 'true' : 'false' );
				} );
				if ( dialog.open ) {
					thumbnails[ activeIndex ].scrollIntoView( { block: 'nearest', inline: 'nearest', behavior: reducedMotion.matches ? 'auto' : 'smooth' } );
				}
				if ( image.complete && image.naturalWidth ) { imageReady(); }
			};
			const imageReady = function () {
				image.classList.remove( 'hdc-lp-lightbox__image--loading' );
				image.removeAttribute( 'aria-busy' );
			};
			image.addEventListener( 'load', imageReady );
			image.addEventListener( 'error', function () {
				imageReady();
				caption.textContent = 'Não foi possível carregar esta imagem. Use as setas ou as miniaturas para continuar.';
			} );
			links.forEach( function ( link, index ) {
				link.setAttribute( 'aria-haspopup', 'dialog' );
				link.setAttribute( 'aria-controls', dialog.id );
				link.addEventListener( 'click', function ( event ) {
					if ( event.defaultPrevented || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey ) { return; }
					event.preventDefault();
					opener = link;
					selectPhoto( index );
					dialog.showModal();
					document.body.classList.add( 'hdc-lp-lightbox-open' );
					thumbnails[ activeIndex ].scrollIntoView( { block: 'nearest', inline: 'nearest' } );
				} );
			} );
			close.addEventListener( 'click', function () { dialog.close(); } );
			dialog.addEventListener( 'close', function () {
				document.body.classList.remove( 'hdc-lp-lightbox-open' );
				if ( opener ) { opener.focus( { preventScroll: true } ); }
				swipe = null;
			} );
			dialog.querySelector( '[data-hdc-lp-lightbox-previous]' ).addEventListener( 'click', function () { selectPhoto( activeIndex - 1 ); } );
			dialog.querySelector( '[data-hdc-lp-lightbox-next]' ).addEventListener( 'click', function () { selectPhoto( activeIndex + 1 ); } );
			dialog.addEventListener( 'keydown', function ( event ) {
				if ( 'Tab' === event.key ) {
					const controls = Array.from( dialog.querySelectorAll( 'button:not([disabled])' ) );
					const first = controls[ 0 ];
					const last = controls[ controls.length - 1 ];
					if ( event.shiftKey && document.activeElement === first ) {
						event.preventDefault();
						last.focus();
					} else if ( ! event.shiftKey && document.activeElement === last ) {
						event.preventDefault();
						first.focus();
					}
					return;
				}
				let target = activeIndex;
				if ( 'ArrowLeft' === event.key ) { target--; }
				else if ( 'ArrowRight' === event.key ) { target++; }
				else if ( 'Home' === event.key ) { target = 0; }
				else if ( 'End' === event.key ) { target = photos.length - 1; }
				else { return; }
				event.preventDefault();
				const thumbnailFocused = thumbnails.includes( document.activeElement );
				selectPhoto( target );
				if ( thumbnailFocused ) { thumbnails[ activeIndex ].focus( { preventScroll: true } ); }
			} );
			const stage = dialog.querySelector( '[data-hdc-lp-lightbox-stage]' );
			stage.addEventListener( 'pointerdown', function ( event ) {
				if ( event.isPrimary && 'touch' === event.pointerType && ! event.target.closest( 'button' ) ) {
					swipe = { id: event.pointerId, x: event.clientX, y: event.clientY };
				}
			} );
			stage.addEventListener( 'pointerup', function ( event ) {
				if ( ! swipe || event.pointerId !== swipe.id ) { return; }
				const deltaX = event.clientX - swipe.x;
				const deltaY = event.clientY - swipe.y;
				swipe = null;
				if ( Math.abs( deltaX ) > 50 && Math.abs( deltaX ) > Math.abs( deltaY ) * 1.5 ) {
					selectPhoto( activeIndex + ( deltaX < 0 ? 1 : -1 ) );
				}
			} );
			stage.addEventListener( 'pointercancel', function () { swipe = null; } );
		} );
	}

	function hdcLpInit() {
		hdcLpInitScrollReveal();
		hdcLpInitPromotionCarousels();
		hdcLpInitServiceCarousels();
		hdcLpInitAccommodationCarousels();
		hdcLpInitDifferentials();
		hdcLpInitGallery();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', hdcLpInit );
	} else {
		hdcLpInit();
	}
}() );
