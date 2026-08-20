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
	   6. CALENDRIER DE RÉSERVATION
	   ═══════════════════════════════════════════════════ */

	var calendrier = document.getElementById( 'vr-calendrier' );

	if ( calendrier && window.vrData ) {

		var MOIS = [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ];
		var JOURS = [ 'L', 'M', 'M', 'J', 'V', 'S', 'D' ];

		var conteneurMois = document.getElementById( 'vr-cal-months' );
		var boutonPrec = document.getElementById( 'vr-cal-prec' );
		var boutonSuiv = document.getElementById( 'vr-cal-suiv' );
		var zoneErreur = document.getElementById( 'vr-cal-erreur' );

		var aujourdhui = new Date();
		aujourdhui.setHours( 0, 0, 0, 0 );

		var vue = { annee: aujourdhui.getFullYear(), mois: aujourdhui.getMonth() };
		var arrivee = null;
		var depart = null;
		var survol = null;
		var bloquees = [];   // Périodes indisponibles, au format { debut: Date, fin: Date }

		function cle( d ) {
			return d.getFullYear() + '-' + deux( d.getMonth() + 1 ) + '-' + deux( d.getDate() );
		}

		function deux( n ) {
			return n < 10 ? '0' + n : String( n );
		}

		function depuisCle( texte ) {
			var p = String( texte ).split( '-' );
			return new Date( parseInt( p[0], 10 ), parseInt( p[1], 10 ) - 1, parseInt( p[2], 10 ) );
		}

		function estBloquee( date ) {
			var t = date.getTime();
			return bloquees.some( function ( periode ) {
				return t >= periode.debut.getTime() && t < periode.fin.getTime();
			} );
		}

		function chevauche( du, au ) {
			var curseur = new Date( du );
			while ( curseur < au ) {
				if ( estBloquee( curseur ) ) {
					return true;
				}
				curseur.setDate( curseur.getDate() + 1 );
			}
			return false;
		}

		function erreur( message ) {
			if ( ! zoneErreur ) {
				return;
			}
			zoneErreur.textContent = message || '';
			zoneErreur.hidden = ! message;
		}

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
				var passe = date < aujourdhui;
				var occupe = estBloquee( date );
				var indispo = passe || occupe;

				html += '<button type="button" class="vr-cal__day" data-date="' + cle( date ) + '"' +
					( indispo ? ' disabled' : '' ) +
					' aria-label="' + j + ' ' + MOIS[ mois ] + ' ' + annee + ( indispo ? ' — indisponible' : '' ) + '">' +
					j + '</button>';
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

			// Le bouton « précédent » ne remonte pas avant le mois courant.
			if ( boutonPrec ) {
				boutonPrec.disabled = ( vue.annee === aujourdhui.getFullYear() && vue.mois === aujourdhui.getMonth() );
			}

			majSelection();
			majRecap();
		}

		function formater( d ) {
			return d.toLocaleDateString( 'fr-FR', { weekday: 'short', day: 'numeric', month: 'long', year: 'numeric' } );
		}

		function majRecap() {
			var champArrivee = document.getElementById( 'vr-recap-arrivee' );
			var champDepart = document.getElementById( 'vr-recap-depart' );
			var champDuree = document.getElementById( 'vr-recap-duree' );
			var blocDuree = document.getElementById( 'vr-recap-duree-bloc' );

			if ( champArrivee ) {
				champArrivee.textContent = arrivee ? formater( arrivee ) : '— choisissez une date —';
			}
			if ( champDepart ) {
				champDepart.textContent = depart ? formater( depart ) : '—';
			}

			var nuits = ( arrivee && depart ) ? Math.round( ( depart - arrivee ) / 86400000 ) : 0;

			if ( blocDuree ) {
				blocDuree.hidden = nuits < 1;
			}
			if ( champDuree && nuits > 0 ) {
				champDuree.textContent = nuits + ( nuits > 1 ? ' nuits' : ' nuit' );
			}

			majLiens( nuits );
		}

		function majLiens( nuits ) {
			var lienWhatsApp = document.getElementById( 'vr-lien-whatsapp' );
			var lienEmail = document.getElementById( 'vr-lien-email' );
			var voyageurs = document.getElementById( 'vr-voyageurs' );
			var nb = voyageurs ? parseInt( voyageurs.textContent, 10 ) : 2;
			var pret = !! ( arrivee && depart );

			var message = pret
				? 'Bonjour, je souhaite réserver ' + vrData.nomVilla + ' du ' + formater( arrivee ) +
				  ' au ' + formater( depart ) + ' (' + nuits + ( nuits > 1 ? ' nuits' : ' nuit' ) + ') pour ' +
				  nb + ' voyageur' + ( nb > 1 ? 's' : '' ) +
				  '. Merci de me confirmer la disponibilité et le tarif.'
				: '';

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

		function choisir( date ) {
			erreur( '' );

			if ( ! arrivee || ( arrivee && depart ) ) {
				arrivee = date;
				depart = null;
				survol = null;
				majSelection();
				majRecap();
				return;
			}

			if ( date <= arrivee ) {
				arrivee = date;
				majSelection();
				majRecap();
				return;
			}

			var nuits = Math.round( ( date - arrivee ) / 86400000 );

			if ( nuits < vrData.nuitsMinimum ) {
				erreur( 'Séjour minimum : ' + vrData.nuitsMinimum + ' nuits.' );
				return;
			}

			if ( chevauche( arrivee, date ) ) {
				erreur( 'Cette période contient des dates déjà réservées. Choisissez une autre plage.' );
				return;
			}

			depart = date;
			survol = null;
			majSelection();
			majRecap();
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
			var max = parseInt( sortieVoyageurs.dataset.max, 10 ) || 8;
			var valeur = parseInt( sortieVoyageurs.textContent, 10 ) + delta;
			valeur = Math.max( 1, Math.min( max, valeur ) );
			sortieVoyageurs.textContent = valeur;
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

		// Récupération des dates indisponibles auprès de l'extension.
		function chargerDisponibilites() {
			if ( ! vrData.restUrl ) {
				dessiner();
				return;
			}

			fetch( vrData.restUrl + 'indisponibilites' )
				.then( function ( reponse ) {
					return reponse.ok ? reponse.json() : [];
				} )
				.then( function ( periodes ) {
					if ( Array.isArray( periodes ) ) {
						bloquees = periodes.map( function ( periode ) {
							return {
								debut: depuisCle( periode.debut ),
								fin: depuisCle( periode.fin )
							};
						} );
					}
					dessiner();
				} )
				.catch( function () {
					dessiner();
				} );
		}

		chargerDisponibilites();

		/* ─── Barre de recherche du héro ─── */
		var recherche = document.getElementById( 'vr-search' );

		if ( recherche ) {
			recherche.addEventListener( 'submit', function ( e ) {
				e.preventDefault();

				var champA = document.getElementById( 'vr-search-arrivee' );
				var champD = document.getElementById( 'vr-search-depart' );
				var champV = document.getElementById( 'vr-search-voyageurs' );

				if ( champA && champA.value ) {
					arrivee = depuisCle( champA.value );
					depart = null;

					if ( champD && champD.value && champD.value > champA.value ) {
						var candidat = depuisCle( champD.value );
						var nuits = Math.round( ( candidat - arrivee ) / 86400000 );
						if ( nuits >= vrData.nuitsMinimum && ! chevauche( arrivee, candidat ) ) {
							depart = candidat;
						}
					}

					vue = { annee: arrivee.getFullYear(), mois: arrivee.getMonth() };
				}

				if ( champV && sortieVoyageurs ) {
					sortieVoyageurs.textContent = champV.value;
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
