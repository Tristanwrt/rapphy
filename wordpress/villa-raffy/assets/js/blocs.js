/**
 * Villa Raffy — les sections de l'accueil dans l'éditeur WordPress.
 * Chaque bloc affiche un aperçu fidèle de la section et ses champs dans la colonne de droite.
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.blocks || ! window.vrBlocs ) {
		return;
	}

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var MediaUpload = wp.blockEditor.MediaUpload;
	var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var SelectControl = wp.components.SelectControl;
	var Button = wp.components.Button;
	var Notice = wp.components.Notice;
	var ServerSideRender = wp.serverSideRender;
	var useSelect = wp.data.useSelect;

	var blocs = window.vrBlocs.blocs || {};

	/**
	 * Champ « photo » ou « vidéo » : aperçu, bouton pour choisir, bouton pour revenir à l'origine.
	 */
	function ChampMedia( props ) {
		var id = props.valeur || 0;
		var estVideo = 'video' === props.type;

		var media = useSelect( function ( select ) {
			return id ? select( 'core' ).getMedia( id ) : null;
		}, [ id ] );

		var url = '';
		if ( id && media ) {
			if ( ! estVideo && media.media_details && media.media_details.sizes && media.media_details.sizes.medium ) {
				url = media.media_details.sizes.medium.source_url;
			} else {
				url = media.source_url;
			}
		} else if ( ! id && props.defaut ) {
			url = props.defaut.url || '';
		}

		var apercu;
		if ( url ) {
			apercu = estVideo
				? el( 'video', { src: url, muted: true, className: 'vr-champ-media__apercu' } )
				: el( 'img', { src: url, alt: '', className: 'vr-champ-media__apercu' } );
		} else {
			apercu = el( 'div', { className: 'vr-champ-media__vide' }, estVideo ? 'Aucune vidéo : le diaporama des photos s\'affiche.' : 'Aucune photo pour l\'instant.' );
		}

		return el( 'div', { className: 'vr-champ-media' },
			el( 'span', { className: 'vr-champ-media__label' }, props.label ),
			apercu,
			el( 'div', { className: 'vr-champ-media__boutons' },
				el( MediaUploadCheck, {},
					el( MediaUpload, {
						onSelect: function ( m ) {
							props.onChange( m.id );
						},
						allowedTypes: [ estVideo ? 'video' : 'image' ],
						value: id,
						render: function ( o ) {
							return el( Button, { variant: 'secondary', onClick: o.open }, id ? 'Changer' : ( estVideo ? 'Choisir une vidéo' : 'Choisir une photo' ) );
						}
					} )
				),
				id ? el( Button, {
					variant: 'link',
					isDestructive: true,
					onClick: function () {
						props.onChange( 0 );
					}
				}, 'Revenir à celle de Personnaliser' ) : null
			)
		);
	}

	Object.keys( blocs ).forEach( function ( cle ) {
		var def = blocs[ cle ];

		registerBlockType( 'villa-raffy/' + cle, {
			title: def.titre,
			description: def.description,
			icon: def.icone,
			category: 'villa-raffy',

			edit: function ( props ) {
				var controles = [];

				Object.keys( def.champs ).forEach( function ( attr ) {
					var champ = def.champs[ attr ];
					var valeur = props.attributes[ attr ];

					function changer( v ) {
						var o = {};
						o[ attr ] = v;
						props.setAttributes( o );
					}

					if ( 'image' === champ.type || 'video' === champ.type ) {
						controles.push( el( ChampMedia, {
							key: attr,
							label: champ.label,
							type: champ.type,
							valeur: valeur,
							defaut: champ.defaut,
							onChange: changer
						} ) );
						return;
					}

					var affiche = ( undefined !== valeur && '' !== valeur ) ? valeur : ( champ.defaut || '' );

					if ( 'select' === champ.type && champ.options ) {
						controles.push( el( SelectControl, {
							key: attr,
							label: champ.label,
							help: champ.aide || undefined,
							value: affiche,
							options: Object.keys( champ.options ).map( function ( k ) {
								return { value: k, label: champ.options[ k ] };
							} ),
							onChange: changer,
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true
						} ) );
						return;
					}

					var Composant = 'textarea' === champ.type ? TextareaControl : TextControl;

					controles.push( el( Composant, {
						key: attr,
						label: champ.label,
						help: champ.aide || undefined,
						value: affiche,
						rows: 'textarea' === champ.type ? 4 : undefined,
						onChange: changer,
						__nextHasNoMarginBottom: true,
						__next40pxDefaultSize: true
					} ) );
				} );

				return el( Fragment, {},
					el( InspectorControls, {},
						el( PanelBody, { title: 'Contenu de la section', initialOpen: true },
							def.note ? el( Notice, { status: 'info', isDismissible: false }, def.note ) : null,
							controles.length ? controles : el( 'p', {}, 'Cette section n\'a pas de texte à modifier ici.' )
						)
					),
					el( 'div', { className: 'vr-bloc' + ( props.isSelected ? ' is-selected' : '' ) },
						el( 'span', { className: 'vr-bloc__etiquette' }, def.titre ),
						el( ServerSideRender, { block: 'villa-raffy/' + cle, attributes: props.attributes } )
					)
				);
			},

			save: function () {
				return null; // Bloc dynamique : le rendu est fait par PHP.
			}
		} );
	} );

} )( window.wp );
