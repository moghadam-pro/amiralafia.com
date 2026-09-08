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
				per_page: grid.dataset.perPage || '8'
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
		// data-section is printed by the menu, because two entries point at
		// real archives rather than at a fragment and could not be matched on
		// the href. Both menus are collected: the mobile drawer shows the same
		// items and should agree with the bar behind it.
		var links = document.querySelectorAll( '.nav-links a[data-section], .nav-mobile-links a[data-section]' );
		if ( ! links.length ) {
			return;
		}

		// One section can own more than one link, so the map holds arrays.
		var map = {};
		links.forEach( function ( link ) {
			var id = link.getAttribute( 'data-section' );
			if ( ! id || ! document.getElementById( id ) ) {
				return;
			}
			( map[ id ] = map[ id ] || [] ).push( link );
		} );

		var ids = Object.keys( map );
		if ( ! ids.length ) {
			return;
		}

		var current = null;

		function light( id ) {
			if ( id === current ) {
				return;
			}
			current = id;

			ids.forEach( function ( other ) {
				map[ other ].forEach( function ( link ) {
					link.classList.toggle( 'is-active', other === id );
					if ( other === id ) {
						link.setAttribute( 'aria-current', 'true' );
					} else {
						link.removeAttribute( 'aria-current' );
					}
				} );
			} );
		}

		// A band across the middle of the viewport. A section counts as being
		// read while it crosses this, not while it is merely on screen.
		var BAND_TOP = 0.40;
		var BAND_BOTTOM = 0.55;

		// Measured on scroll rather than by IntersectionObserver. An observer
		// only reports threshold crossings, and at every boundary two sections
		// share the band without either crossing anything - scrolling from the
		// middle of one to the middle of the next fires no entry at all, and
		// the bar would keep the first one lit the whole way.
		function update() {
			var top = window.innerHeight * BAND_TOP;
			var bottom = window.innerHeight * BAND_BOTTOM;
			var best = null;
			var bestOverlap = 0;

			ids.forEach( function ( id ) {
				var rect = document.getElementById( id ).getBoundingClientRect();
				// Whichever fills more of the band is the one being read.
				var overlap = Math.min( rect.bottom, bottom ) - Math.max( rect.top, top );

				if ( overlap > bestOverlap ) {
					bestOverlap = overlap;
					best = id;
				}
			} );

			// Nothing in the band - the hero, or a footer past the last
			// section. Leave the previous item lit rather than blanking the bar.
			if ( best ) {
				light( best );
			}
		}

		var queued = false;

		function onScroll() {
			if ( queued ) {
				return;
			}
			queued = true;
			window.requestAnimationFrame( function () {
				queued = false;
				update();
			} );
		}

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		window.addEventListener( 'resize', onScroll, { passive: true } );
		update();
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
