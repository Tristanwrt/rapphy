/**
 * Galerie de photos dans les fiches Chambres et Visite guidée :
 * choisir plusieurs images dans la médiathèque, les retirer d'un clic.
 */
( function ( $ ) {
	'use strict';

	var max = ( window.vrGalerie && window.vrGalerie.max ) || 3;

	$( '[data-galerie]' ).each( function () {
		var bloc = $( this );
		var liste = bloc.find( '.vr-galerie-admin__liste' );
		var champ = bloc.find( '.vr-galerie-admin__valeur' );
		var cadre = null;

		function ids() {
			return liste.find( '.vr-galerie-admin__item' ).map( function () {
				return $( this ).data( 'id' );
			} ).get();
		}

		function sauver() {
			champ.val( ids().join( ',' ) );
		}

		bloc.on( 'click', '.vr-galerie-admin__ajouter', function ( e ) {
			e.preventDefault();

			if ( ids().length >= max ) {
				window.alert( 'Vous pouvez ajouter au maximum ' + max + ' photos supplémentaires. Retirez-en une d\'abord.' );
				return;
			}

			if ( ! cadre ) {
				cadre = wp.media( {
					title: 'Choisir des photos',
					button: { text: 'Ajouter à la fiche' },
					library: { type: 'image' },
					multiple: 'add'
				} );

				cadre.on( 'select', function () {
					cadre.state().get( 'selection' ).each( function ( media ) {
						var m = media.toJSON();
						if ( ids().length >= max || ids().indexOf( m.id ) >= 0 ) {
							return;
						}
						var url = ( m.sizes && m.sizes.thumbnail ) ? m.sizes.thumbnail.url : m.url;
						liste.append(
							'<span class="vr-galerie-admin__item" data-id="' + m.id + '"><img src="' + url + '" alt="" /><button type="button" class="vr-galerie-admin__retirer" aria-label="Retirer cette photo">×</button></span>'
						);
					} );
					sauver();
				} );
			}

			cadre.open();
		} );

		bloc.on( 'click', '.vr-galerie-admin__retirer', function ( e ) {
			e.preventDefault();
			$( this ).closest( '.vr-galerie-admin__item' ).remove();
			sauver();
		} );
	} );

} )( window.jQuery );
