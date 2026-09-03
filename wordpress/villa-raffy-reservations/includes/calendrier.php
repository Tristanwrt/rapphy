<?php
/**
 * Le calendrier de gestion : une année entière, on sélectionne une plage
 * puis on choisit quoi en faire — bloquer, libérer, fixer un tarif spécial.
 *
 * @package VillaRaffyReservations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vrr_menu_calendrier() {
	add_submenu_page(
		'edit.php?post_type=vr_reservation',
		'Calendrier des disponibilités',
		'Calendrier',
		'edit_posts',
		'vr-calendrier',
		'vrr_page_calendrier'
	);
}
add_action( 'admin_menu', 'vrr_menu_calendrier' );

function vrr_page_calendrier() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	$annee_courante = (int) wp_date( 'Y' );
	$annee          = isset( $_GET['annee'] ) ? absint( $_GET['annee'] ) : $annee_courante;
	if ( $annee < $annee_courante || $annee > $annee_courante + 3 ) {
		$annee = $annee_courante;
	}

	// ─── Action sur une plage ───
	if ( isset( $_POST['vrr_calendrier_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['vrr_calendrier_nonce'] ), 'vrr_calendrier' ) ) {
		$action = isset( $_POST['vrr_action'] ) ? sanitize_key( $_POST['vrr_action'] ) : '';
		$debut  = isset( $_POST['vrr_debut'] ) ? sanitize_text_field( wp_unslash( $_POST['vrr_debut'] ) ) : '';
		$fin    = isset( $_POST['vrr_fin'] ) ? sanitize_text_field( wp_unslash( $_POST['vrr_fin'] ) ) : '';

		if ( vrr_date_valide( $debut ) && vrr_date_valide( $fin ) ) {
			$message = '';

			switch ( $action ) {
				case 'bloquer':
					vrr_bloquer_plage( $debut, $fin );
					$message = 'Les dates ont été bloquées.';
					break;
				case 'debloquer':
					vrr_debloquer_plage( $debut, $fin );
					$message = 'Les dates ont été libérées.';
					break;
				case 'tarif':
					$complete  = isset( $_POST['vrr_prix_complete'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['vrr_prix_complete'] ) ) ) : '';
					$cocooning = isset( $_POST['vrr_prix_cocooning'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['vrr_prix_cocooning'] ) ) ) : '';
					vrr_fixer_tarif_plage( $debut, $fin, $complete, $cocooning );
					$message = 'Le tarif spécial a été appliqué.';
					break;
				case 'retirer_tarif':
					vrr_retirer_tarif_plage( $debut, $fin );
					$message = 'Le tarif spécial a été retiré : ces dates reprennent le tarif de leur saison.';
					break;
			}

			if ( $message ) {
				echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
			}
		}
	}

	$aujourdhui = wp_date( 'Y-m-d' );
	$mois_noms  = array( 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' );
	$jours_noms = array( 'L', 'M', 'M', 'J', 'V', 'S', 'D' );
	?>

	<div class="wrap vr-cal-admin">
		<h1>Calendrier des disponibilités — <?php echo esc_html( $annee ); ?></h1>

		<div class="vr-cal-admin__rappel">
			<strong>Comment ça marche :</strong> cliquez sur un premier jour, puis sur un second pour sélectionner une plage (ou deux fois sur le même jour pour une seule date).
			Choisissez ensuite l'action à appliquer dans le bandeau qui apparaît.
			<br /><strong>À ne pas oublier :</strong> si vous acceptez une réservation sur Airbnb ou Booking, bloquez les dates ici. Le prix affiché sous chaque jour est celui de la villa complète.
		</div>

		<div class="vr-cal-admin__annee">
			<a class="button" href="<?php echo esc_url( add_query_arg( 'annee', $annee - 1 ) ); ?>"<?php disabled( $annee <= $annee_courante ); ?>>← <?php echo esc_html( $annee - 1 ); ?></a>
			<a class="button" href="<?php echo esc_url( add_query_arg( 'annee', $annee + 1 ) ); ?>"><?php echo esc_html( $annee + 1 ); ?> →</a>
			<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=vr_reservation&page=vr-tarifs' ) ); ?>" style="margin-left:auto">Modifier les tarifs et saisons</a>
		</div>

		<!-- Bandeau d'action sur la plage sélectionnée -->
		<form method="post" class="vr-plage" id="vr-plage" hidden>
			<?php wp_nonce_field( 'vrr_calendrier', 'vrr_calendrier_nonce' ); ?>
			<input type="hidden" name="vrr_debut" id="vrr-debut" value="" />
			<input type="hidden" name="vrr_fin" id="vrr-fin" value="" />
			<input type="hidden" name="vrr_action" id="vrr-action" value="" />

			<div class="vr-plage__resume" id="vr-plage-resume"></div>

			<div class="vr-plage__actions">
				<button type="submit" class="button button-primary" data-action="bloquer">Bloquer ces dates</button>
				<button type="submit" class="button" data-action="debloquer">Libérer ces dates</button>
				<span class="vr-plage__sep"></span>
				<label>Tarif spécial villa <input type="number" name="vrr_prix_complete" min="0" class="small-text" placeholder="350" /></label>
				<label>Cocooning <input type="number" name="vrr_prix_cocooning" min="0" class="small-text" placeholder="190" /></label>
				<button type="submit" class="button" data-action="tarif">Appliquer ce tarif</button>
				<button type="submit" class="button-link" data-action="retirer_tarif">Retirer le tarif spécial</button>
				<button type="button" class="button-link" id="vr-plage-annuler">Annuler</button>
			</div>
		</form>

		<div class="vr-cal-admin__grille">
			<?php for ( $mois = 1; $mois <= 12; $mois++ ) : ?>
				<?php
				$premier  = new DateTimeImmutable( sprintf( '%04d-%02d-01', $annee, $mois ) );
				$decalage = ( (int) $premier->format( 'N' ) ) - 1;
				$nb_jours = (int) $premier->format( 't' );
				?>
				<div class="vr-cal-admin__mois">
					<h3><?php echo esc_html( $mois_noms[ $mois - 1 ] ); ?></h3>

					<div class="vr-cal-admin__dow">
						<?php foreach ( $jours_noms as $jour_nom ) : ?>
							<span><?php echo esc_html( $jour_nom ); ?></span>
						<?php endforeach; ?>
					</div>

					<div class="vr-cal-admin__jours">
						<?php for ( $v = 0; $v < $decalage; $v++ ) : ?>
							<span class="vr-cal-admin__jour is-vide"></span>
						<?php endfor; ?>

						<?php for ( $jour = 1; $jour <= $nb_jours; $jour++ ) : ?>
							<?php
							$date  = sprintf( '%04d-%02d-%02d', $annee, $mois, $jour );
							$infos = vrr_jour( $date );
							$passe = $date < $aujourdhui;

							$classes = 'vr-cal-admin__jour is-' . $infos['type'];
							$titre   = $infos['saison'];

							if ( $infos['reservee'] ) {
								$classes .= ' is-reservee';
								$titre    = get_the_title( vrr_reservation_du_jour( $date ) ) . ' — réservation enregistrée';
							} elseif ( $infos['bloquee'] ) {
								$classes .= ' is-bloquee';
								$titre   .= ' — bloquée par vous';
							}
							if ( $infos['special'] ) {
								$classes .= ' is-special';
								$titre   .= ' — tarif spécial';
							}
							if ( $passe ) {
								$classes .= ' is-passe';
							}

							$prix = $infos['prix']['complete'];
							?>
							<button type="button"
								class="<?php echo esc_attr( $classes ); ?>"
								data-date="<?php echo esc_attr( $date ); ?>"
								title="<?php echo esc_attr( $titre ); ?>"
								<?php disabled( $passe ); ?>>
								<span><?php echo esc_html( $jour ); ?></span>
								<?php if ( null !== $prix && ! $infos['reservee'] && ! $infos['bloquee'] ) : ?>
									<small><?php echo esc_html( $prix ); ?>€</small>
								<?php endif; ?>
							</button>
						<?php endfor; ?>
					</div>
				</div>
			<?php endfor; ?>
		</div>

		<div class="vr-cal-admin__legende">
			<span><span class="vr-cal-admin__puce vr-cal-admin__puce--basse"></span>Basse saison</span>
			<span><span class="vr-cal-admin__puce vr-cal-admin__puce--haute"></span>Haute saison</span>
			<span><span class="vr-cal-admin__puce vr-cal-admin__puce--fermee"></span>Fermé</span>
			<span><span class="vr-cal-admin__puce vr-cal-admin__puce--bloquee"></span>Bloqué par vous</span>
			<span><span class="vr-cal-admin__puce vr-cal-admin__puce--reservee"></span>Réservation enregistrée</span>
			<span><span class="vr-cal-admin__puce vr-cal-admin__puce--special"></span>Tarif spécial</span>
		</div>
	</div>

	<script>
	( function () {
		var jours = Array.prototype.slice.call( document.querySelectorAll( '.vr-cal-admin__jour[data-date]' ) );
		var bandeau = document.getElementById( 'vr-plage' );
		var resume = document.getElementById( 'vr-plage-resume' );
		var champDebut = document.getElementById( 'vrr-debut' );
		var champFin = document.getElementById( 'vrr-fin' );
		var champAction = document.getElementById( 'vrr-action' );
		var debut = null;
		var fin = null;

		function formater( iso ) {
			var p = iso.split( '-' );
			return p[2] + '/' + p[1] + '/' + p[0];
		}

		function rafraichir() {
			jours.forEach( function ( jour ) {
				var d = jour.dataset.date;
				var dans = debut && fin && d >= debut && d <= fin;
				jour.classList.toggle( 'is-selection', !! dans );
				jour.classList.toggle( 'is-borne', d === debut || d === fin );
			} );

			if ( debut && fin ) {
				var nb = Math.round( ( new Date( fin ) - new Date( debut ) ) / 86400000 ) + 1;
				resume.textContent = ( nb === 1 ? 'Le ' + formater( debut ) : 'Du ' + formater( debut ) + ' au ' + formater( fin ) ) +
					' — ' + nb + ' jour' + ( nb > 1 ? 's' : '' ) + ' sélectionné' + ( nb > 1 ? 's' : '' );
				champDebut.value = debut;
				champFin.value = fin;
				bandeau.hidden = false;
				bandeau.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
			} else if ( debut ) {
				resume.textContent = 'Premier jour : ' + formater( debut ) + ' — cliquez sur le dernier jour de la plage.';
				bandeau.hidden = false;
			} else {
				bandeau.hidden = true;
			}
		}

		jours.forEach( function ( jour ) {
			if ( jour.disabled ) {
				return;
			}
			jour.addEventListener( 'click', function () {
				var d = jour.dataset.date;
				if ( ! debut || ( debut && fin ) ) {
					debut = d;
					fin = null;
				} else {
					if ( d < debut ) {
						fin = debut;
						debut = d;
					} else {
						fin = d;
					}
				}
				rafraichir();
			} );
		} );

		document.getElementById( 'vr-plage-annuler' ).addEventListener( 'click', function () {
			debut = null;
			fin = null;
			rafraichir();
		} );

		bandeau.querySelectorAll( '[data-action]' ).forEach( function ( bouton ) {
			bouton.addEventListener( 'click', function () {
				champAction.value = bouton.dataset.action;
			} );
		} );
	} )();
	</script>
	<?php
}

/**
 * Résumé au-dessus du carnet de réservations.
 */
function vrr_resume_reservations() {
	$ecran = get_current_screen();
	if ( ! $ecran || 'edit-vr_reservation' !== $ecran->id ) {
		return;
	}

	$reservations = get_posts( array(
		'post_type'      => 'vr_reservation',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	) );

	$aujourdhui = wp_date( 'Y-m-d' );
	$annee      = wp_date( 'Y' );
	$a_venir    = 0;
	$nuits      = 0;
	$revenus    = 0;

	foreach ( $reservations as $reservation ) {
		$arrivee = get_post_meta( $reservation->ID, 'vrr_arrivee', true );
		$depart  = get_post_meta( $reservation->ID, 'vrr_depart', true );
		$statut  = get_post_meta( $reservation->ID, 'vrr_statut', true );

		if ( 'annulee' === $statut || ! vrr_date_valide( $arrivee ) || ! vrr_date_valide( $depart ) ) {
			continue;
		}
		if ( $arrivee >= $aujourdhui ) {
			$a_venir++;
		}
		if ( substr( $arrivee, 0, 4 ) === $annee ) {
			$nuits   += (int) ( new DateTimeImmutable( $arrivee ) )->diff( new DateTimeImmutable( $depart ) )->days;
			$revenus += (float) get_post_meta( $reservation->ID, 'vrr_tarif', true );
		}
	}

	// Le taux d'occupation se calcule sur les nuits réellement ouvertes à la location.
	$ouvertes = 0;
	foreach ( vrr_calendrier( $annee . '-01-01', $annee . '-12-31' ) as $jour ) {
		if ( 'fermee' !== $jour['type'] ) {
			$ouvertes++;
		}
	}
	$occupation = $ouvertes ? round( ( $nuits / $ouvertes ) * 100 ) : 0;

	printf(
		'<div class="notice notice-info" style="padding:12px 16px"><strong>%d séjour%s à venir</strong> · %d nuits louées en %s sur %d nuits ouvertes (%d %% d\'occupation) · %s € de chiffre d\'affaires</div>',
		(int) $a_venir,
		$a_venir > 1 ? 's' : '',
		(int) $nuits,
		esc_html( $annee ),
		(int) $ouvertes,
		(int) $occupation,
		esc_html( number_format_i18n( $revenus ) )
	);
}
add_action( 'admin_notices', 'vrr_resume_reservations' );
