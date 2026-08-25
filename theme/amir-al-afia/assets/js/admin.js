/**
 * Gallery picker for the Property Details meta box.
 */
( function ( $ ) {
	'use strict';

	$( function () {
		var field = document.getElementById( 'aaa_gallery' );
		var preview = document.getElementById( 'aaa-gallery-preview' );
		var pick = document.getElementById( 'aaa-gallery-pick' );
		var clear = document.getElementById( 'aaa-gallery-clear' );

		if ( ! field || ! preview || ! pick ) {
			return;
		}

		var frame = null;

		function ids() {
			return field.value
				.split( ',' )
				.map( function ( id ) {
					return parseInt( id, 10 );
				} )
				.filter( Boolean );
		}

		function render() {
			preview.innerHTML = '';
			ids().forEach( function ( id ) {
				var attachment = wp.media.attachment( id );
				attachment.fetch().done( function () {
					var url = attachment.get( 'sizes' ) && attachment.get( 'sizes' ).thumbnail
						? attachment.get( 'sizes' ).thumbnail.url
						: attachment.get( 'url' );
					var img = document.createElement( 'img' );
					img.src = url;
					img.alt = attachment.get( 'alt' ) || '';
					preview.appendChild( img );
				} );
			} );
		}

		pick.addEventListener( 'click', function () {
			if ( ! frame ) {
				frame = wp.media( {
					title: pick.textContent,
					multiple: 'add',
					library: { type: 'image' }
				} );

				frame.on( 'select', function () {
					var selected = frame.state().get( 'selection' ).map( function ( item ) {
						return item.id;
					} );
					var merged = ids().concat( selected ).filter( function ( id, index, all ) {
						return all.indexOf( id ) === index;
					} );
					field.value = merged.join( ',' );
					render();
				} );
			}
			frame.open();
		} );

		if ( clear ) {
			clear.addEventListener( 'click', function () {
				field.value = '';
				render();
			} );
		}

		render();
	} );
}( window.jQuery ) );
