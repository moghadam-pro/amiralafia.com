/**
 * Amir Al Afia — front-end behaviour.
 *
 * Everything here is progressive enhancement: the page is fully readable,
 * navigable and submittable with this file blocked.
 */
( function () {
	'use strict';

	var data = window.aaaData || {};
	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* ── Sticky navbar shadow ───────────────────────────────── */
	function initNavScroll() {
		var nav = document.getElementById( 'navbar' );
		if ( ! nav ) {
			return;
		}

		function update() {
			nav.classList.toggle( 'is-scrolled', window.scrollY > 12 );
		}

		window.addEventListener( 'scroll', update, { passive: true } );
		update();
	}

	/* ── Mobile menu ────────────────────────────────────────── */
	function initMobileMenu() {
		var btn = document.getElementById( 'nav-ham' );
		var menu = document.getElementById( 'nav-mobile' );
		if ( ! btn || ! menu ) {
			return;
		}

		function setOpen( open ) {
			btn.classList.toggle( 'is-open', open );
			btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			menu.hidden = ! open;
		}

		btn.addEventListener( 'click', function () {
			setOpen( menu.hidden );
		} );

		menu.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( 'a' ) ) {
				setOpen( false );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && ! menu.hidden ) {
				setOpen( false );
				btn.focus();
			}
		} );
	}

	/* ── Scroll reveal ──────────────────────────────────────────
	 * The `sr-armed` class on <html> is what actually hides elements, and it
	 * is only added here. If this script never runs, nothing is hidden.
	 */
	function initScrollReveal() {
		var els = document.querySelectorAll( '.sr' );
		if ( ! els.length || reduceMotion || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		document.documentElement.classList.add( 'sr-armed' );

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) {
					return;
				}
				var delay = parseInt( entry.target.dataset.delay, 10 ) || 0;
				window.setTimeout( function () {
					entry.target.classList.add( 'is-visible' );
				}, delay );
				observer.unobserve( entry.target );
			} );
		}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' } );

		els.forEach( function ( el ) {
			observer.observe( el );
		} );
	}

	/* ── Property filters ───────────────────────────────────── */
	function initFilters() {
		var bar = document.querySelector( '.filter-bar' );
		var grid = document.getElementById( 'props-grid' );
		if ( ! bar || ! grid || ! data.ajaxUrl ) {
			return;
		}

		var state = { type: 'all', deal: 'all' };
		var pending = null;

		function selected() {
			return Array.prototype.slice.call( bar.querySelectorAll( '.filter-btn' ) );
		}

		function paint( group, value ) {
			selected().forEach( function ( btn ) {
				if ( btn.dataset.group !== group ) {
					return;
				}
				var on = btn.dataset.val === value;
				btn.classList.toggle( 'is-active', on );
				btn.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
			} );
		}

		function load() {
			if ( pending ) {
				pending.abort();
			}

			var controller = new AbortController();
			pending = controller;
			grid.setAttribute( 'aria-busy', 'true' );

			var body = new URLSearchParams( {
				action: 'aaa_filter_properties',
				nonce: data.filterNonce,
				type: state.type,
				deal: state.deal,
				per_page: grid.dataset.perPage || '4'
			} );

			window.fetch( data.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: body.toString(),
				signal: controller.signal
			} )
				.then( function ( response ) {
					return response.json();
				} )
				.then( function ( payload ) {
					if ( ! payload || ! payload.success ) {
						throw new Error( 'bad response' );
					}
					grid.innerHTML = payload.data.html;
					grid.setAttribute( 'aria-busy', 'false' );
					// Freshly injected cards should be visible, not waiting on a scroll.
					grid.querySelectorAll( '.sr' ).forEach( function ( el ) {
						el.classList.add( 'is-visible' );
					} );
				} )
				.catch( function ( error ) {
					if ( 'AbortError' === error.name ) {
						return;
					}
					grid.setAttribute( 'aria-busy', 'false' );
					// Fall back to a full page load of the archive.
					window.location.href = bar.querySelector( '.filter-btn' ).href;
				} );
		}

		bar.addEventListener( 'click', function ( event ) {
			var btn = event.target.closest( '.filter-btn' );
			if ( ! btn || ! bar.contains( btn ) ) {
				return;
			}
			// Let modified clicks open the archive in a new tab as usual.
			if ( event.metaKey || event.ctrlKey || event.shiftKey || 1 === event.button ) {
				return;
			}

			event.preventDefault();
			state[ btn.dataset.group ] = btn.dataset.val;
			paint( btn.dataset.group, btn.dataset.val );
			load();
		} );
	}

	/* ── Lead form ──────────────────────────────────────────── */
	function initLeadForm() {
		var form = document.getElementById( 'aaa-lead-form' );
		var feedback = document.getElementById( 'cf-feedback' );
		if ( ! form || ! feedback || ! data.ajaxUrl ) {
			return;
		}

		var button = form.querySelector( '.cf-submit' );

		function say( message, isError ) {
			feedback.textContent = message;
			feedback.classList.add( 'show' );
			feedback.classList.toggle( 'is-error', !! isError );
		}

		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();

			var name = form.querySelector( '#cf-name' );
			if ( ! name.value.trim() ) {
				name.classList.add( 'is-invalid' );
				name.focus();
				window.setTimeout( function () {
					name.classList.remove( 'is-invalid' );
				}, 2000 );
				return;
			}

			button.disabled = true;

			var body = new URLSearchParams( new FormData( form ) );
			body.set( 'action', 'aaa_submit_lead' );

			window.fetch( data.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: body.toString()
			} )
				.then( function ( response ) {
					return response.json();
				} )
				.then( function ( payload ) {
					say( payload.message, ! payload.ok );
					if ( payload.ok ) {
						form.reset();
					}
				} )
				.catch( function () {
					// Network trouble: hand the submission back to the browser.
					form.submit();
				} )
				.finally( function () {
					button.disabled = false;
				} );
		} );
	}


	/* ── Property gallery slider ────────────────────────────────
	 * The track is a native scroll-snap container, so swipe and keyboard
	 * scrolling already work. This adds the arrows, the thumbnails, the
	 * counter and arrow-key support, and reveals the controls only once it
	 * has run.
	 */
	function initGallery() {
		var slider = document.getElementById( 'sp-slider' );
		var track = document.getElementById( 'sp-slider-track' );
		if ( ! slider || ! track ) {
			return;
		}

		var slides = Array.prototype.slice.call( track.querySelectorAll( '.sp-slide' ) );
		var thumbs = Array.prototype.slice.call( document.querySelectorAll( '.sp-thumb' ) );
		var prev = slider.querySelector( '.sp-prev' );
		var next = slider.querySelector( '.sp-next' );
		var counter = slider.querySelector( '.sp-slider-current' );
		var index = 0;

		if ( slides.length < 2 ) {
			return;
		}

		prev.hidden = false;
		next.hidden = false;
		slider.classList.add( 'is-enhanced' );

		function goTo( i, smooth ) {
			index = Math.max( 0, Math.min( i, slides.length - 1 ) );
			track.scrollTo( {
				left: slides[ index ].offsetLeft - track.offsetLeft,
				behavior: ( smooth === false || reduceMotion ) ? 'auto' : 'smooth'
			} );
		}

		function paint() {
			if ( counter ) {
				counter.textContent = String( index + 1 );
			}
			thumbs.forEach( function ( thumb, i ) {
				var on = i === index;
				thumb.classList.toggle( 'is-active', on );
				thumb.setAttribute( 'aria-selected', on ? 'true' : 'false' );
			} );
			prev.disabled = index === 0;
			next.disabled = index === slides.length - 1;
		}

		// Derive the active slide from where the track actually is, so a
		// swipe updates the thumbnails the same way a click does.
		if ( 'IntersectionObserver' in window ) {
			var spy = new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						index = slides.indexOf( entry.target );
						paint();
					}
				} );
			}, { root: track, threshold: 0.6 } );

			slides.forEach( function ( slide ) {
				spy.observe( slide );
			} );
		}

		prev.addEventListener( 'click', function () {
			goTo( index - 1 );
		} );
		next.addEventListener( 'click', function () {
			goTo( index + 1 );
		} );

		thumbs.forEach( function ( thumb ) {
			thumb.addEventListener( 'click', function () {
				goTo( parseInt( thumb.dataset.index, 10 ) || 0 );
			} );
		} );

		track.addEventListener( 'keydown', function ( event ) {
			if ( 'ArrowLeft' === event.key ) {
				event.preventDefault();
				goTo( index - 1 );
			}
			if ( 'ArrowRight' === event.key ) {
				event.preventDefault();
				goTo( index + 1 );
			}
		} );

		paint();
	}

	/* ── Highlight the section currently in view ────────────── */
	function initActiveNav() {
		var links = document.querySelectorAll( '.nav-links a[href*="#"]' );
		if ( ! links.length || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		var map = {};
		links.forEach( function ( link ) {
			var hash = link.href.split( '#' )[ 1 ];
			if ( ! hash ) {
				return;
			}
			var section = document.getElementById( hash );
			if ( section ) {
				map[ hash ] = link;
			}
		} );

		var ids = Object.keys( map );
		if ( ! ids.length ) {
			return;
		}

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) {
					return;
				}
				ids.forEach( function ( id ) {
					map[ id ].classList.toggle( 'is-active', id === entry.target.id );
				} );
			} );
		}, { rootMargin: '-45% 0px -50% 0px' } );

		ids.forEach( function ( id ) {
			observer.observe( document.getElementById( id ) );
		} );
	}

	function init() {
		initNavScroll();
		initMobileMenu();
		initScrollReveal();
		initFilters();
		initLeadForm();
		initGallery();
		initActiveNav();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
