/**
 * Villa Raffy — interactions du site.
 * Aucune bibliothèque externe : tout est en JavaScript natif.
 */
( function () {
	'use strict';

	var reduit = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* ═══════════════════════════════════════════════════
	   1. EN-TÊTE — fond au défilement
	   ═══════════════════════════════════════════════════ */

	var entete = document.getElementById( 'vr-header' );
	var heroBg = document.getElementById( 'vr-hero-bg' );
	var estAccueil = document.body.classList.contains( 'home' );

	function surDefilement() {
		if ( entete && estAccueil ) {
			entete.classList.toggle( 'is-scrolled', window.scrollY > 40 );
		}
		// Parallaxe léger de la grande image.
		if ( heroBg && ! reduit && window.scrollY < window.innerHeight ) {
			heroBg.style.transform = 'scale(1.1) translateY(' + ( window.scrollY * 0.18 ) + 'px)';
		}
	}

	window.addEventListener( 'scroll', surDefilement, { passive: true } );
	surDefilement();

	/* ═══════════════════════════════════════════════════
	   2. MENU MOBILE
	   ═══════════════════════════════════════════════════ */

	var burger = document.getElementById( 'vr-burger' );
	var mobile = document.getElementById( 'vr-mobile' );

	if ( burger && mobile ) {
		burger.addEventListener( 'click', function () {
			var ouvert = mobile.classList.toggle( 'is-open' );
			burger.setAttribute( 'aria-expanded', ouvert ? 'true' : 'false' );
			burger.setAttribute( 'aria-label', ouvert ? 'Fermer le menu' : 'Ouvrir le menu' );
		} );

		mobile.addEventListener( 'click', function ( e ) {
			if ( 'A' === e.target.tagName ) {
				mobile.classList.remove( 'is-open' );
				burger.setAttribute( 'aria-expanded', 'false' );
			}
		} );
	}

	/* ═══════════════════════════════════════════════════
	   3. RÉVÉLATION AU DÉFILEMENT
	   ═══════════════════════════════════════════════════ */

	var aReveler = document.querySelectorAll( '.vr-reveal' );

	if ( aReveler.length ) {
		if ( reduit || ! ( 'IntersectionObserver' in window ) ) {
			aReveler.forEach( function ( el ) {
				el.classList.add( 'is-visible' );
			} );
		} else {
			var observateur = new IntersectionObserver( function ( entrees ) {
				entrees.forEach( function ( entree ) {
					if ( entree.isIntersecting ) {
						entree.target.classList.add( 'is-visible' );
						observateur.unobserve( entree.target );
					}
				} );
			}, { rootMargin: '0px 0px -80px 0px' } );

			aReveler.forEach( function ( el ) {
				observateur.observe( el );
			} );
		}
	}

	/* ═══════════════════════════════════════════════════
	   4. VISITE GUIDÉE — parcours serpentin
	   ═══════════════════════════════════════════════════ */

	var visite = document.getElementById( 'visite' );
	var plan = document.getElementById( 'vr-tour-plan' );

	if ( visite && plan ) {
		var etapes = Array.prototype.slice.call( plan.querySelectorAll( '.vr-tour__step' ) );
		var total = etapes.length;
		var compteur = document.getElementById( 'vr-tour-compteur' );
		var barre = document.getElementById( 'vr-tour-barre' );
		var zoneTexte = document.getElementById( 'vr-tour-zone' );
		var suivant = document.getElementById( 'vr-tour-suivant' );
		var suivantTexte = document.getElementById( 'vr-tour-suivant-texte' );
		var etapeCourante = -1;

		// Positions lues depuis le style en ligne posé par PHP.
		var positions = etapes.map( function ( etape ) {
			return {
				x: parseFloat( etape.style.left ) || 0,
				y: parseFloat( etape.style.top ) || 0
			};
		} );

		function deuxChiffres( n ) {
			return n < 10 ? '0' + n : String( n );
		}

		function animerVisite() {
			var rect = visite.getBoundingClientRect();
			var parcours = visite.offsetHeight - window.innerHeight;

			if ( parcours <= 0 ) {
				return;
			}

			var avancement = Math.min( 1, Math.max( 0, -rect.top / parcours ) );

			// Position interpolée entre les étapes.
			var curseur = avancement * ( total - 1 );
			var index = Math.min( total - 2, Math.floor( curseur ) );
			var fraction = curseur - index;

			var depart = positions[ index ];
			var arrivee = positions[ index + 1 ] || depart;

			var x = depart.x + ( arrivee.x - depart.x ) * fraction;
			var y = depart.y + ( arrivee.y - depart.y ) * fraction;

			plan.style.transform = 'translate(' + ( -x ) + 'vw, ' + ( -y ) + 'vh)';

			if ( barre ) {
				barre.style.width = ( avancement * 100 ) + '%';
			}

			// Étape courante (celle dont on est le plus proche).
			var courante = Math.min( total - 1, Math.round( curseur ) );

			if ( courante !== etapeCourante ) {
				etapeCourante = courante;

				if ( compteur ) {
					compteur.textContent = deuxChiffres( courante + 1 ) + ' / ' + deuxChiffres( total );
				}

				if ( zoneTexte ) {
					var zone = etapes[ courante ].getAttribute( 'data-zone' );
					zoneTexte.textContent = zone ? ' · ' + zone : '';
				}

				if ( suivant && suivantTexte ) {
					if ( courante < total - 1 ) {
						var prochaine = etapes[ courante + 1 ];
						suivantTexte.textContent = 'Suivant : ' + prochaine.getAttribute( 'data-titre' );
						suivant.classList.toggle( 'is-down', 'bas' === prochaine.getAttribute( 'data-direction' ) );
						suivant.hidden = false;
					} else {
						suivant.hidden = true;
					}
				}
			}
		}

		window.addEventListener( 'scroll', animerVisite, { passive: true } );
		window.addEventListener( 'resize', animerVisite );
		animerVisite();
	}

	/* ═══════════════════════════════════════════════════
	   5. QUESTIONS FRÉQUENTES
	   ═══════════════════════════════════════════════════ */

	document.querySelectorAll( '.vr-faq__q' ).forEach( function ( bouton ) {
		bouton.addEventListener( 'click', function () {
			var item = bouton.closest( '.vr-faq__item' );
			var ouvert = item.classList.contains( 'is-open' );

			document.querySelectorAll( '.vr-faq__item' ).forEach( function ( autre ) {
				autre.classList.remove( 'is-open' );
				autre.querySelector( '.vr-faq__q' ).setAttribute( 'aria-expanded', 'false' );
			} );

			if ( ! ouvert ) {
				item.classList.add( 'is-open' );
				bouton.setAttribute( 'aria-expanded', 'true' );
			}
		} );
	} );

	/* ═══════════════════════════════════════════════════
	   6. DIAPORAMA (section « La villa en mouvement »)
	   ═══════════════════════════════════════════════════ */

	var diapo = document.getElementById( 'vr-diapo' );

	if ( diapo ) {
		var vues = Array.prototype.slice.call( diapo.querySelectorAll( '.vr-diapo__vue' ) );
		var vueActive = 0;

		if ( vues.length > 1 && ! reduit ) {
			window.setInterval( function () {
				vues[ vueActive ].classList.remove( 'is-active' );
				vueActive = ( vueActive + 1 ) % vues.length;
				vues[ vueActive ].classList.add( 'is-active' );
			}, 4500 );
		}
	}

	/* ═══════════════════════════════════════════════════
	   7. CALENDRIER DE RÉSERVATION
	   ═══════════════════════════════════════════════════ */

	var calendrier = document.getElementById( 'vr-calendrier' );

	if ( calendrier && window.vrData ) {

		var MOIS = [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ];
		var JOURS = [ 'L', 'M', 'M', 'J', 'V', 'S', 'D' ];
		var NOMS_JOURS = [ 'dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi' ];
		var NOMS_FORMULES = { complete: 'Villa complète', cocooning: 'Formule Cocooning' };

		var conteneurMois = document.getElementById( 'vr-cal-months' );
		var boutonPrec = document.getElementById( 'vr-cal-prec' );
		var boutonSuiv = document.getElementById( 'vr-cal-suiv' );
		var zoneErreur = document.getElementById( 'vr-cal-erreur' );
		var boutonsFormule = Array.prototype.slice.call( calendrier.querySelectorAll( '.vr-formule-btn' ) );

		var aujourdhui = new Date();
		aujourdhui.setHours( 0, 0, 0, 0 );

		var vue = { annee: aujourdhui.getFullYear(), mois: aujourdhui.getMonth() };
		var formule = 'complete';
		var arrivee = null;
		var depart = null;
		var survol = null;

		// Données de l'extension : une entrée par jour, indexée par « AAAA-MM-JJ ».
		var jours = {};
		var moteurActif = false;   // Vrai quand l'extension a répondu.
		var capacites = { complete: vrData.capaciteMax || 8, cocooning: 4 };
		var derniereDate = null;   // Dernier jour couvert par le calendrier de l'extension.

		boutonsFormule.forEach( function ( bouton ) {
			var cap = parseInt( bouton.dataset.capacite, 10 );
			if ( cap ) {
				capacites[ bouton.dataset.choix ] = cap;
			}
		} );

		/* ─── Outils ─── */

		function deux( n ) {
			return n < 10 ? '0' + n : String( n );
		}

		function cle( d ) {
			return d.getFullYear() + '-' + deux( d.getMonth() + 1 ) + '-' + deux( d.getDate() );
		}

		function depuisCle( texte ) {
			var p = String( texte ).split( '-' );
			return new Date( parseInt( p[0], 10 ), parseInt( p[1], 10 ) - 1, parseInt( p[2], 10 ) );
		}

		function lendemain( d ) {
			var n = new Date( d );
			n.setDate( n.getDate() + 1 );
			return n;
		}

		function veille( d ) {
			var n = new Date( d );
			n.setDate( n.getDate() - 1 );
			return n;
		}

		function euros( n ) {
			return String( Math.round( n ) ).replace( /\B(?=(\d{3})+(?!\d))/g, ' ' ) + ' €';
		}

		function pluriel( n, mot ) {
			return n + ' ' + mot + ( n > 1 ? 's' : '' );
		}

		function formater( d ) {
			return d.toLocaleDateString( 'fr-FR', { weekday: 'short', day: 'numeric', month: 'long', year: 'numeric' } );
		}

		/**
		 * Les informations d'un jour. Sans extension, tout est ouvert et sans prix.
		 */
		function info( d ) {
			var k = cle( d );

			if ( jours[ k ] ) {
				return jours[ k ];
			}

			if ( moteurActif ) {
				// Au-delà de la période couverte : considéré comme fermé.
				return { t: 'fermee', ok: false, p: { complete: null, cocooning: null }, a: false, r: false, m: 1 };
			}

			return { t: 'basse', ok: true, p: { complete: null, cocooning: null }, a: true, r: true, m: vrData.nuitsMinimum || 1 };
		}

		function prixDe( d ) {
			var p = info( d ).p;
			return ( p && null !== p[ formule ] && undefined !== p[ formule ] ) ? p[ formule ] : null;
		}

		/**
		 * Une nuit se réserve si le jour est ouvert, libre, et proposé dans la formule choisie.
		 */
		function nuitPossible( d ) {
			var i = info( d );
			if ( ! i.ok ) {
				return false;
			}
			if ( moteurActif && null === prixDe( d ) ) {
				return false;
			}
			return true;
		}

		function arriveePossible( d ) {
			return d >= aujourdhui && nuitPossible( d ) && info( d ).a;
		}

		/**
		 * On peut partir un jour « fermé » si la veille était une nuit possible
		 * (le dernier jour d'une saison), sinon le jour doit accepter les départs.
		 */
		function departPossible( d ) {
			var i = info( d );
			if ( 'fermee' === i.t ) {
				return nuitPossible( veille( d ) );
			}
			return i.r;
		}

		function erreur( message ) {
			if ( ! zoneErreur ) {
				return;
			}
			zoneErreur.textContent = message || '';
			zoneErreur.hidden = ! message;
		}

		/**
		 * Vérifie un séjour complet. Renvoie null si tout va bien, sinon { code, message } :
		 * « croise » quand la plage traverse des dates impossibles (on repart alors de la date cliquée),
		 * « regle » quand c'est une règle de séjour qui bloque (on explique).
		 */
		function verifier( du, au ) {
			var nuits = Math.round( ( au - du ) / 86400000 );
			var minimum = 1;
			var curseur = new Date( du );

			if ( nuits < 1 ) {
				return { code: 'regle', message: 'Le départ doit être après l\'arrivée.' };
			}

			while ( curseur < au ) {
				var i = info( curseur );
				if ( ! i.ok ) {
					return { code: 'croise', message: 'fermee' === i.t
						? 'La villa est fermée sur une partie de ces dates.'
						: 'Cette période contient des dates déjà réservées. Choisissez une autre plage.' };
				}
				if ( moteurActif && null === prixDe( curseur ) ) {
					return { code: 'croise', message: 'La ' + NOMS_FORMULES[ formule ] + ' n\'est pas proposée sur une partie de ces dates. Choisissez la villa complète, ou d\'autres dates.' };
				}
				minimum = Math.max( minimum, i.m || 1 );
				curseur = lendemain( curseur );
			}

			if ( ! departPossible( au ) ) {
				return { code: 'regle', message: 'Sur cette période, les départs se font le ' + deviner( veille( au ), 'r' ) + '.' };
			}

			if ( nuits < minimum ) {
				return { code: 'regle', message: 'Sur cette période, le séjour minimum est de ' + pluriel( minimum, 'nuit' ) + '.' };
			}

			return null;
		}

		/**
		 * Nom des jours autorisés pour l'arrivée (« a ») ou le départ (« r ») autour d'une date :
		 * l'API ne donne que vrai/faux par jour, on regarde donc la semaine qui suit pour les nommer.
		 */
		function deviner( depuis, sens ) {
			var noms = [];
			var d = new Date( depuis );
			for ( var n = 0; n < 7; n++ ) {
				var j = info( d );
				if ( j[ sens ] && 'fermee' !== j.t && noms.indexOf( NOMS_JOURS[ d.getDay() ] ) < 0 ) {
					noms.push( NOMS_JOURS[ d.getDay() ] );
				}
				d = lendemain( d );
			}
			return noms.length ? noms.join( ' ou le ' ) : 'jour prévu';
		}

		/* ─── Dessin ─── */

		function dessinerMois( annee, mois ) {
			var premier = new Date( annee, mois, 1 );
			var decalage = ( premier.getDay() + 6 ) % 7; // lundi en premier
			var nbJours = new Date( annee, mois + 1, 0 ).getDate();

			var html = '<div class="vr-cal__month">';
			html += '<div class="vr-cal__title">' + MOIS[ mois ] + ' ' + annee + '</div>';
			html += '<div class="vr-cal__dow">';
			JOURS.forEach( function ( jour ) {
				html += '<span>' + jour + '</span>';
			} );
			html += '</div><div class="vr-cal__days">';

			for ( var v = 0; v < decalage; v++ ) {
				html += '<span class="vr-cal__day is-empty"></span>';
			}

			for ( var j = 1; j <= nbJours; j++ ) {
				var date = new Date( annee, mois, j );
				var i = info( date );
				var passe = date < aujourdhui;
				var prix = prixDe( date );
				var ferme = 'fermee' === i.t;
				var occupe = ! passe && ! ferme && ! i.ok;
				var sansFormule = moteurActif && ! ferme && i.ok && null === prix;
				// Un jour fermé ou réservé reste un jour de départ possible si la veille se réserve.
				var departSeul = ! passe && ( ferme || occupe ) && departPossible( date ) && nuitPossible( veille( date ) );
				var desactive = passe || ( ( ferme || occupe ) && ! departSeul );

				var classes = [ 'vr-cal__day' ];
				if ( ferme ) { classes.push( 'is-closed' ); }
				if ( occupe ) { classes.push( 'is-busy' ); }
				if ( departSeul ) { classes.push( 'is-checkout-only' ); }
				if ( 'haute' === i.t ) { classes.push( 'is-haute' ); }
				if ( sansFormule ) { classes.push( 'is-off' ); }
				if ( ! passe && ! desactive && ! departSeul && ! i.a ) { classes.push( 'is-no-arrival' ); }

				var etat = '';
				if ( passe ) { etat = ' — passé'; }
				else if ( departSeul ) { etat = ' — jour de départ uniquement'; }
				else if ( ferme ) { etat = ' — fermé'; }
				else if ( occupe ) { etat = ' — réservé'; }
				else if ( sansFormule ) { etat = ' — non proposé dans cette formule'; }
				else if ( null !== prix ) { etat = ' — ' + euros( prix ) + ' la nuit'; }

				var sousTexte = '';
				if ( ! passe && ! ferme && ! occupe ) {
					sousTexte = null !== prix ? euros( prix ).replace( ' €', '€' ) : ( sansFormule ? '—' : '' );
				}

				html += '<button type="button" class="' + classes.join( ' ' ) + '" data-date="' + cle( date ) + '"' +
					( desactive ? ' disabled' : '' ) +
					' aria-label="' + NOMS_JOURS[ date.getDay() ] + ' ' + j + ' ' + MOIS[ mois ] + ' ' + annee + etat + '">' +
					'<span class="vr-cal__num">' + j + '</span>' +
					( sousTexte ? '<span class="vr-cal__prix">' + sousTexte + '</span>' : '' ) +
					'</button>';
			}

			html += '</div></div>';
			return html;
		}

		/**
		 * Met à jour la surbrillance sans reconstruire le calendrier,
		 * pour que les boutons restent cliquables pendant le survol.
		 */
		function majSelection() {
			if ( ! conteneurMois ) {
				return;
			}

			var tA = arrivee ? arrivee.getTime() : null;
			var tD = depart ? depart.getTime() : null;
			var tS = survol ? survol.getTime() : null;
			var fin = tD || ( tA && tS && tS > tA ? tS : null );

			conteneurMois.querySelectorAll( '.vr-cal__day[data-date]' ).forEach( function ( bouton ) {
				var t = depuisCle( bouton.dataset.date ).getTime();
				bouton.classList.toggle( 'is-edge', tA === t || tD === t );
				bouton.classList.toggle( 'is-range', !! ( tA && fin && t > tA && t < fin ) );
			} );
		}

		function dessiner() {
			if ( ! conteneurMois ) {
				return;
			}

			var moisSuivant = vue.mois + 1;
			var anneeSuivante = vue.annee + ( moisSuivant > 11 ? 1 : 0 );
			moisSuivant = moisSuivant % 12;

			conteneurMois.innerHTML = dessinerMois( vue.annee, vue.mois ) + dessinerMois( anneeSuivante, moisSuivant );

			// Le bouton « précédent » ne remonte pas avant le mois courant,
			// le bouton « suivant » ne dépasse pas la période couverte.
			if ( boutonPrec ) {
				boutonPrec.disabled = ( vue.annee === aujourdhui.getFullYear() && vue.mois === aujourdhui.getMonth() );
			}
			if ( boutonSuiv ) {
				var limite = derniereDate ? derniereDate : new Date( aujourdhui.getFullYear() + 1, aujourdhui.getMonth() + 1, 0 );
				boutonSuiv.disabled = new Date( anneeSuivante, moisSuivant + 1, 1 ) > limite;
			}

			majSelection();
			majRecap();
		}

		/* ─── Récapitulatif et total ─── */

		function calculer() {
			if ( ! arrivee || ! depart ) {
				return null;
			}

			var lignes = [];   // [ { prix, nuits } ] dans l'ordre du séjour.
			var total = 0;
			var nuits = 0;
			var complet = true;
			var curseur = new Date( arrivee );

			while ( curseur < depart ) {
				var prix = prixDe( curseur );
				nuits++;
				if ( null === prix ) {
					complet = false;
				} else {
					total += prix;
					var derniere = lignes[ lignes.length - 1 ];
					if ( derniere && derniere.prix === prix ) {
						derniere.nuits++;
					} else {
						lignes.push( { prix: prix, nuits: 1 } );
					}
				}
				curseur = lendemain( curseur );
			}

			return { nuits: nuits, total: complet ? total : null, lignes: lignes };
		}

		function majRecap() {
			var champArrivee = document.getElementById( 'vr-recap-arrivee' );
			var champDepart = document.getElementById( 'vr-recap-depart' );
			var champDuree = document.getElementById( 'vr-recap-duree' );
			var blocDuree = document.getElementById( 'vr-recap-duree-bloc' );
			var blocTotal = document.getElementById( 'vr-total' );
			var champCalcul = document.getElementById( 'vr-total-calcul' );
			var champMontant = document.getElementById( 'vr-total-montant' );
			var champFormule = document.getElementById( 'vr-total-formule' );

			if ( champArrivee ) {
				champArrivee.textContent = arrivee ? formater( arrivee ) : '— choisissez une date —';
			}
			if ( champDepart ) {
				champDepart.textContent = depart ? formater( depart ) : '—';
			}

			var sejour = calculer();
			var nuits = sejour ? sejour.nuits : 0;

			if ( blocDuree ) {
				blocDuree.hidden = nuits < 1;
			}
			if ( champDuree && nuits > 0 ) {
				champDuree.textContent = pluriel( nuits, 'nuit' );
			}

			if ( blocTotal ) {
				var afficher = !! ( sejour && null !== sejour.total );
				blocTotal.hidden = ! afficher;

				if ( afficher ) {
					champCalcul.textContent = sejour.lignes.map( function ( ligne ) {
						return pluriel( ligne.nuits, 'nuit' ) + ' × ' + euros( ligne.prix );
					} ).join( ' + ' ) + ' =';
					champMontant.textContent = euros( sejour.total );
				}
				if ( champFormule ) {
					champFormule.textContent = NOMS_FORMULES[ formule ];
				}
			}

			majLiens( sejour );
		}

		function majLiens( sejour ) {
			var lienWhatsApp = document.getElementById( 'vr-lien-whatsapp' );
			var lienEmail = document.getElementById( 'vr-lien-email' );
			var voyageurs = document.getElementById( 'vr-voyageurs' );
			var nb = voyageurs ? parseInt( voyageurs.textContent, 10 ) : 2;
			var pret = !! ( arrivee && depart && sejour );

			var message = '';

			if ( pret ) {
				message = 'Bonjour, je souhaite réserver ' + vrData.nomVilla + ' en formule « ' + NOMS_FORMULES[ formule ] + ' » du ' +
					formater( arrivee ) + ' au ' + formater( depart ) + ' (' + pluriel( sejour.nuits, 'nuit' ) + ') pour ' +
					pluriel( nb, 'voyageur' ) + '.';

				if ( null !== sejour.total ) {
					message += ' Tarif affiché : ' + sejour.lignes.map( function ( ligne ) {
						return pluriel( ligne.nuits, 'nuit' ) + ' × ' + euros( ligne.prix );
					} ).join( ' + ' ) + ' = ' + euros( sejour.total ) + '.';
				}

				message += ' Merci de me confirmer la disponibilité.';
			}

			if ( lienWhatsApp ) {
				lienWhatsApp.setAttribute( 'aria-disabled', pret ? 'false' : 'true' );
				lienWhatsApp.href = pret
					? 'https://wa.me/' + vrData.whatsapp + '?text=' + encodeURIComponent( message )
					: '#';
			}

			if ( lienEmail ) {
				lienEmail.setAttribute( 'aria-disabled', pret ? 'false' : 'true' );
				lienEmail.href = pret
					? 'mailto:' + vrData.email +
					  '?subject=' + encodeURIComponent( 'Demande de réservation — ' + vrData.nomVilla ) +
					  '&body=' + encodeURIComponent( message )
					: '#';
			}
		}

		/* ─── Sélection ─── */

		/**
		 * Tente de poser l'arrivée sur une date ; explique pourquoi si c'est impossible.
		 */
		function tenterArrivee( date ) {
			if ( ! arriveePossible( date ) ) {
				var i = info( date );
				if ( 'fermee' === i.t ) {
					erreur( 'La villa est fermée à cette date : elle ne peut être qu\'un jour de départ.' );
				} else if ( ! i.ok ) {
					erreur( 'Cette date est déjà réservée : elle ne peut être qu\'un jour de départ.' );
				} else if ( moteurActif && null === prixDe( date ) ) {
					erreur( 'La ' + NOMS_FORMULES[ formule ] + ' n\'est pas proposée à cette date. Choisissez la villa complète, ou d\'autres dates.' );
				} else if ( ! i.a ) {
					erreur( 'Sur cette période, les arrivées se font le ' + deviner( date, 'a' ) + '.' );
				} else {
					erreur( 'Cette date n\'est pas disponible à l\'arrivée.' );
				}
				return false;
			}

			arrivee = date;
			depart = null;
			survol = null;
			majSelection();
			majRecap();
			return true;
		}

		function choisir( date ) {
			erreur( '' );

			// Premier clic, ou nouveau départ de sélection.
			if ( ! arrivee || ( arrivee && depart ) || date <= arrivee ) {
				tenterArrivee( date );
				return;
			}

			var probleme = verifier( arrivee, date );

			if ( ! probleme ) {
				depart = date;
				survol = null;
				majSelection();
				majRecap();
				return;
			}

			// La plage traverse des dates impossibles : on repart de la date cliquée.
			if ( 'croise' === probleme.code ) {
				tenterArrivee( date );
				return;
			}

			erreur( probleme.message );
		}

		function choisirFormule( choix ) {
			if ( ! NOMS_FORMULES[ choix ] || choix === formule ) {
				return;
			}

			formule = choix;
			calendrier.setAttribute( 'data-formule', choix );

			boutonsFormule.forEach( function ( bouton ) {
				var actif = bouton.dataset.choix === choix;
				bouton.classList.toggle( 'is-active', actif );
				bouton.setAttribute( 'aria-checked', actif ? 'true' : 'false' );
			} );

			// Le nombre de voyageurs suit la capacité de la formule.
			if ( sortieVoyageurs ) {
				sortieVoyageurs.dataset.max = capacites[ choix ];
				majVoyageurs( 0 );
			}

			// Une sélection devenue impossible est effacée.
			if ( arrivee && depart && verifier( arrivee, depart ) ) {
				arrivee = null;
				depart = null;
				erreur( 'Vos dates ont été effacées : elles ne sont pas proposées dans cette formule.' );
			} else if ( arrivee && ! depart && ! arriveePossible( arrivee ) ) {
				arrivee = null;
				erreur( '' );
			} else {
				erreur( '' );
			}

			dessiner();
		}

		// Clics et survol sur les jours.
		if ( conteneurMois ) {
			conteneurMois.addEventListener( 'click', function ( e ) {
				var bouton = e.target.closest( '.vr-cal__day' );
				if ( bouton && ! bouton.disabled && bouton.dataset.date ) {
					choisir( depuisCle( bouton.dataset.date ) );
				}
			} );

			conteneurMois.addEventListener( 'mouseover', function ( e ) {
				var bouton = e.target.closest( '.vr-cal__day' );
				if ( bouton && ! bouton.disabled && bouton.dataset.date && arrivee && ! depart ) {
					survol = depuisCle( bouton.dataset.date );
					majSelection();
				}
			} );

			conteneurMois.addEventListener( 'mouseleave', function () {
				if ( survol ) {
					survol = null;
					majSelection();
				}
			} );
		}

		// Choix de la formule dans le calendrier…
		boutonsFormule.forEach( function ( bouton ) {
			bouton.addEventListener( 'click', function () {
				choisirFormule( bouton.dataset.choix );
			} );
		} );

		// …et depuis les boutons « Réserver » de la section des formules.
		document.querySelectorAll( 'a[data-formule]' ).forEach( function ( lien ) {
			lien.addEventListener( 'click', function () {
				choisirFormule( lien.dataset.formule );
			} );
		} );

		function changerMois( sens ) {
			var m = vue.mois + sens;
			var a = vue.annee + ( m < 0 ? -1 : ( m > 11 ? 1 : 0 ) );
			m = ( ( m % 12 ) + 12 ) % 12;

			if ( new Date( a, m, 1 ) >= new Date( aujourdhui.getFullYear(), aujourdhui.getMonth(), 1 ) ) {
				vue = { annee: a, mois: m };
				dessiner();
			}
		}

		if ( boutonPrec ) {
			boutonPrec.addEventListener( 'click', function () {
				changerMois( -1 );
			} );
		}
		if ( boutonSuiv ) {
			boutonSuiv.addEventListener( 'click', function () {
				changerMois( 1 );
			} );
		}

		// Compteur de voyageurs.
		var sortieVoyageurs = document.getElementById( 'vr-voyageurs' );
		var boutonMoins = document.getElementById( 'vr-moins' );
		var boutonPlus = document.getElementById( 'vr-plus' );

		function majVoyageurs( delta ) {
			if ( ! sortieVoyageurs ) {
				return;
			}
			var max = parseInt( sortieVoyageurs.dataset.max, 10 ) || capacites[ formule ] || 8;
			var valeur = parseInt( sortieVoyageurs.textContent, 10 ) + delta;
			valeur = Math.max( 1, Math.min( max, valeur ) );
			sortieVoyageurs.textContent = valeur;
			if ( boutonPlus ) {
				boutonPlus.disabled = valeur >= max;
			}
			if ( boutonMoins ) {
				boutonMoins.disabled = valeur <= 1;
			}
			majRecap();
		}

		if ( boutonMoins ) {
			boutonMoins.addEventListener( 'click', function () {
				majVoyageurs( -1 );
			} );
		}
		if ( boutonPlus ) {
			boutonPlus.addEventListener( 'click', function () {
				majVoyageurs( 1 );
			} );
		}

		// Récupération du calendrier tarifaire auprès de l'extension.
		function chargerCalendrier() {
			if ( ! vrData.restUrl ) {
				dessiner();
				return;
			}

			fetch( vrData.restUrl + 'calendrier' )
				.then( function ( reponse ) {
					return reponse.ok ? reponse.json() : null;
				} )
				.then( function ( donnees ) {
					if ( donnees && Array.isArray( donnees.jours ) && donnees.jours.length ) {
						moteurActif = true;
						donnees.jours.forEach( function ( jour ) {
							jours[ jour.d ] = jour;
						} );
						derniereDate = depuisCle( donnees.jours[ donnees.jours.length - 1 ].d );

						if ( donnees.formules ) {
							Object.keys( donnees.formules ).forEach( function ( k ) {
								if ( donnees.formules[ k ].capacite ) {
									capacites[ k ] = parseInt( donnees.formules[ k ].capacite, 10 );
								}
							} );
							if ( sortieVoyageurs ) {
								sortieVoyageurs.dataset.max = capacites[ formule ];
								majVoyageurs( 0 );
							}
						}
					}
					dessiner();
				} )
				.catch( function () {
					dessiner();
				} );
		}

		chargerCalendrier();

		/* ─── Barre de recherche du héro ─── */
		var recherche = document.getElementById( 'vr-search' );

		if ( recherche ) {
			recherche.addEventListener( 'submit', function ( e ) {
				e.preventDefault();

				var champA = document.getElementById( 'vr-search-arrivee' );
				var champD = document.getElementById( 'vr-search-depart' );
				var champV = document.getElementById( 'vr-search-voyageurs' );

				erreur( '' );

				if ( champA && champA.value ) {
					var candidatA = depuisCle( champA.value );

					if ( arriveePossible( candidatA ) ) {
						arrivee = candidatA;
						depart = null;

						if ( champD && champD.value && champD.value > champA.value ) {
							var candidatD = depuisCle( champD.value );
							var probleme = verifier( arrivee, candidatD );
							if ( probleme ) {
								erreur( probleme.message );
							} else {
								depart = candidatD;
							}
						}
					} else {
						arrivee = null;
						depart = null;
						erreur( 'La date d\'arrivée choisie n\'est pas disponible. Choisissez une date dans le calendrier.' );
					}

					vue = { annee: candidatA.getFullYear(), mois: candidatA.getMonth() };
				}

				if ( champV && sortieVoyageurs ) {
					sortieVoyageurs.textContent = champV.value;
					majVoyageurs( 0 );
				}

				dessiner();

				var cible = document.getElementById( 'reserver' );
				if ( cible ) {
					cible.scrollIntoView( { behavior: reduit ? 'auto' : 'smooth' } );
				}
			} );
		}
	}

} )();
