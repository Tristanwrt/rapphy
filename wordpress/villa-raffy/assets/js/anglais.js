/**
 * Version anglaise — textes générés par le navigateur.
 *
 * La page arrive déjà traduite par PHP. Ce script s'occupe de ce que le
 * thème construit ensuite en JavaScript : le calendrier de réservation
 * (mois, jours, prix, messages) et le texte pré-rempli des liens
 * WhatsApp / email. Aucune bibliothèque externe.
 */
( function () {
	'use strict';

	if ( ! window.vrAnglais || ! document.body.classList.contains( 'vr-en' ) ) {
		return;
	}

	var dico = vrAnglais.dico || {};

	var MOIS = {
		'janvier': 'January', 'février': 'February', 'mars': 'March', 'avril': 'April', 'mai': 'May', 'juin': 'June',
		'juillet': 'July', 'août': 'August', 'septembre': 'September', 'octobre': 'October', 'novembre': 'November', 'décembre': 'December'
	};
	var JOURS = {
		'lundi': 'Monday', 'mardi': 'Tuesday', 'mercredi': 'Wednesday', 'jeudi': 'Thursday', 'vendredi': 'Friday', 'samedi': 'Saturday', 'dimanche': 'Sunday',
		'lun.': 'Mon', 'mar.': 'Tue', 'mer.': 'Wed', 'jeu.': 'Thu', 'ven.': 'Fri', 'sam.': 'Sat', 'dim.': 'Sun'
	};

	function cle( t ) {
		return t.replace( /[  ]/g, ' ' ).replace( /’/g, "'" ).replace( /\s+/g, ' ' ).trim();
	}


	/**
	 * Prix : « 2 450 € » devient « €2,450 ».
	 */
	function prix( t ) {
		return t.replace( /(\d{1,3}(?: \d{3})*) €/g, function ( m, n ) { return '€' + n.replace( / /g, ',' ); } );
	}

	/**
	 * Remplace, dans une phrase, les mots français que le thème assemble
	 * lui-même : dates, nombres de nuits, formules, états des jours.
	 */
	function traduireMorceaux( t ) {
		var r = t;

		// « 12 septembre 2026 » (titre des mois) et « sam. 5 juillet 2026 » (dates choisies).
		r = r.replace( /(^|[\s(])(lun\.|mar\.|mer\.|jeu\.|ven\.|sam\.|dim\.)/gi, function ( m, avant, jour ) {
			return avant + ( JOURS[ jour.toLowerCase() ] || jour );
		} );
		r = r.replace( /\b(lundi|mardi|mercredi|jeudi|vendredi|samedi|dimanche)\b/gi, function ( m ) {
			return JOURS[ m.toLowerCase() ] || m;
		} );
		r = r.replace( /\b(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)\b/gi, function ( m ) {
			return MOIS[ m.toLowerCase() ] || m;
		} );

		// Nuits et voyageurs.
		r = r.replace( /(\d+)\s+nuits?\b/g, function ( m, n ) { return n + ( '1' === n ? ' night' : ' nights' ); } );
		r = r.replace( /(\d+)\s+voyageurs?\b/g, function ( m, n ) { return n + ( '1' === n ? ' guest' : ' guests' ); } );

		// Formules.
		r = r.replace( /Villa complète/g, 'Whole villa' ).replace( /Formule Cocooning/g, 'Cocooning option' );

		// États des jours (libellés d'accessibilité) et prix.
		r = r.replace( ' — passé', ' — past' )
			.replace( ' — jour de départ uniquement', ' — check-out day only' )
			.replace( ' — fermé', ' — closed' )
			.replace( ' — réservé', ' — booked' )
			.replace( ' — non proposé dans cette formule', ' — not offered with this option' )
			.replace( /(\d[\d ]*?) € la nuit/g, '$1 € per night' );

		r = prix( r );

		// Messages du calendrier.
		r = r.replace( 'Le départ doit être après l\'arrivée.', 'Check-out must come after check-in.' )
			.replace( 'La villa est fermée sur une partie de ces dates.', 'The villa is closed on part of these dates.' )
			.replace( 'Cette période contient des dates déjà réservées. Choisissez une autre plage.', 'This period includes dates that are already booked. Please choose another range.' )
			.replace( /La (Whole villa|Cocooning option) n'est pas proposée sur une partie de ces dates\. Choisissez la villa complète, ou d'autres dates\./, 'The $1 is not offered on part of these dates. Choose the whole villa, or other dates.' )
			.replace( /Sur cette période, les départs se font le (.+?)\./, 'During this period, check-out is on $1.' )
			.replace( /Sur cette période, le séjour minimum est de (.+?)\./, 'During this period, the minimum stay is $1.' )
			.replace( ' ou le ', ' or ' )
			.replace( 'jour prévu', 'the scheduled day' )
			.replace( 'La villa est fermée à cette date : elle ne peut être qu\'un jour de départ.', 'The villa is closed on this date: it can only be a check-out day.' )
			.replace( 'Cette date est déjà réservée : elle ne peut être qu\'un jour de départ.', 'This date is already booked: it can only be a check-out day.' )
			.replace( /La (Whole villa|Cocooning option) n'est pas proposée à cette date\. Choisissez la villa complète, ou d'autres dates\./, 'The $1 is not offered on this date. Choose the whole villa, or other dates.' )
			.replace( 'Cette date n\'est pas disponible à l\'arrivée.', 'This date is not available for check-in.' )
			.replace( 'Vos dates ont été effacées : elles ne sont pas proposées dans cette formule.', 'Your dates were cleared: they are not offered with this option.' )
			.replace( 'La date d\'arrivée choisie n\'est pas disponible. Choisissez une date dans le calendrier.', 'The chosen check-in date is not available. Please pick a date in the calendar.' );

		// Le message pré-rempli pour WhatsApp et l'email.
		r = r.replace( /^Bonjour, je souhaite réserver (.*?) en formule « (.+?) » du (.+?) au (.+?) \((.+?)\) pour (.+?)\./, function ( m, nom, formule, du, au, nuits, pour ) {
				return 'Hello, I would like to book ' + ( nom.trim() || 'the villa' ) + ' (“' + formule + '”) from ' + du + ' to ' + au + ' (' + nuits + ') for ' + pour + '.';
			} )
			.replace( ' Tarif affiché : ', ' Rate shown: ' )
			.replace( ' Merci de me confirmer la disponibilité.', ' Could you please confirm availability? Thank you.' )
			.replace( /^Demande de réservation — /, 'Booking request — ' );

		return r;
	}

	function traduire( t ) {
		var parts = t.match( /^(\s*)([\s\S]*?)(\s*)$/ );
		var k = cle( parts[ 2 ] );
		if ( ! k ) {
			return t;
		}
		if ( ! /[a-zà-ÿ]{2}/i.test( k ) ) {
			return parts[ 1 ] + prix( parts[ 2 ] ) + parts[ 3 ];
		}
		if ( dico[ k ] ) {
			return parts[ 1 ] + dico[ k ] + parts[ 3 ];
		}
		return parts[ 1 ] + traduireMorceaux( parts[ 2 ] ) + parts[ 3 ];
	}

	/* ─── Textes et attributs d'un élément et de ses descendants ─── */

	function traduireNoeud( noeud ) {
		if ( noeud.nodeType === 3 ) {
			var n = traduire( noeud.data );
			if ( n !== noeud.data ) {
				noeud.data = n;
			}
			return;
		}
		if ( noeud.nodeType !== 1 || 'SCRIPT' === noeud.tagName || 'STYLE' === noeud.tagName ) {
			return;
		}
		[ 'aria-label', 'title', 'alt', 'placeholder' ].forEach( function ( a ) {
			if ( noeud.hasAttribute( a ) ) {
				var v = noeud.getAttribute( a ), t = traduire( v );
				if ( t !== v ) {
					noeud.setAttribute( a, t );
				}
			}
		} );
		if ( 'A' === noeud.tagName ) {
			traduireLien( noeud );
		}
		for ( var i = 0; i < noeud.childNodes.length; i++ ) {
			traduireNoeud( noeud.childNodes[ i ] );
		}
	}

	/**
	 * Liens WhatsApp (…?text=) et email (mailto:…?subject=&body=) :
	 * le message est décodé, traduit, puis ré-encodé.
	 */
	function traduireLien( a ) {
		var href = a.getAttribute( 'href' ) || '';
		if ( ! /^(https:\/\/wa\.me\/|mailto:)/.test( href ) || a.dataset.vreFait === href ) {
			return;
		}
		var nouveau = href.replace( /([?&](?:text|subject|body)=)([^&]*)/g, function ( m, p, v ) {
			var d;
			try { d = decodeURIComponent( v ); } catch ( e ) { return m; }
			return p + encodeURIComponent( traduireMorceaux( d ) );
		} );
		if ( nouveau !== href ) {
			a.setAttribute( 'href', nouveau );
		}
		a.dataset.vreFait = a.getAttribute( 'href' );
	}

	/* ─── Surveillance : tout ce que le thème ajoute ou modifie est traduit ─── */

	var racine = document.body;

	traduireNoeud( racine );

	var observateur = new MutationObserver( function ( mutations ) {
		mutations.forEach( function ( m ) {
			if ( 'characterData' === m.type ) {
				traduireNoeud( m.target );
			} else if ( 'attributes' === m.type ) {
				traduireNoeud( m.target );
			} else {
				for ( var i = 0; i < m.addedNodes.length; i++ ) {
					traduireNoeud( m.addedNodes[ i ] );
				}
			}
		} );
	} );

	observateur.observe( racine, {
		childList: true,
		subtree: true,
		characterData: true,
		attributes: true,
		attributeFilter: [ 'href', 'aria-label', 'title' ]
	} );

	// Sécurité : un passage complet après le chargement du calendrier.
	window.setTimeout( function () { traduireNoeud( racine ); }, 1500 );
	window.setTimeout( function () { traduireNoeud( racine ); }, 4000 );
} )();
