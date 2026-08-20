<?php
/**
 * Le calendrier de blocage : une année entière, un clic par jour.
 *
 * @package VillaRaffyReservations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajoute la page sous le menu Réservations.
 */
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

/**
 * Affiche et enregistre le calendrier.
 */
function vrr_page_calendrier() {
	$annee_courante = (int) wp_date( 'Y' );
	$annee          = isset( $_GET['annee'] ) ? absint( $_GET['annee'] ) : $annee_courante;

	if ( $annee < $annee_courante || $annee > $annee_courante + 3 ) {
		$annee = $annee_courante;
	}

	// Enregistrement.
	if ( isset( $_POST['vrr_calendrier_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['vrr_calendrier_nonce'] ), 'vrr_calendrier' ) && current_user_can( 'edit_posts' ) ) {

		$soumises = isset( $_POST['vrr_bloquees'] ) ? sanitize_text_field( wp_unslash( $_POST['vrr_bloquees'] ) ) : '';
		$soumises = array_filter( array_map( 'trim', explode( ',', $soumises ) ), 'vrr_date_valide' );

		// On ne touche qu'aux dates de l'année affichée : les autres années sont conservées.
		$conservees = array_filter( vrr_dates_bloquees(), function ( $date ) use ( $annee ) {
			return substr( $date, 0, 4 ) !== (string) $annee;
		} );

		vrr_enregistrer_dates_bloquees( array_merge( $conservees, $soumises ) );

		echo '<div class="notice notice-success is-dismissible"><p>Vos disponibilités ont bien été enregistrées.</p></div>';
	}

	$bloquees   = vrr_dates_bloquees();
	$aujourdhui = wp_date( 'Y-m-d' );
	$mois_noms  = array( 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' );
	$jours_noms = array( 'L', 'M', 'M', 'J', 'V', 'S', 'D' );
	?>

	<div class="wrap vr-cal-admin">
		<h1>Calendrier des disponibilités</h1>

		<div class="vr-cal-admin__rappel">
			<strong>Comment ça marche :</strong> cliquez sur un jour pour le bloquer, cliquez à nouveau pour le libérer.
			Les jours en vert foncé correspondent à une réservation déjà saisie dans votre carnet — ils se bloquent tout seuls.
			<br />
			<strong>À ne pas oublier :</strong> si vous acceptez une réservation sur Airbnb ou Booking, pensez à bloquer les dates ici aussi.
			N'oubliez pas d'enregistrer en bas de page.
		</div>

		<form method="post">
			<?php wp_nonce_field( 'vrr_calendrier', 'vrr_calendrier_nonce' ); ?>
			<input type="hidden" name="vrr_bloquees" id="vrr-bloquees" value="" />

			<div class="vr-cal-admin__annee">
				<a class="button" href="<?php echo esc_url( add_query_arg( 'annee', $annee - 1 ) ); ?>"<?php disabled( $annee <= $annee_courante ); ?>>← <?php echo esc_html( $annee - 1 ); ?></a>
				<h2><?php echo esc_html( $annee ); ?></h2>
				<a class="button" href="<?php echo esc_url( add_query_arg( 'annee', $annee + 1 ) ); ?>"><?php echo esc_html( $annee + 1 ); ?> →</a>
			</div>

			<div class="vr-cal-admin__grille">
				<?php for ( $mois = 1; $mois <= 12; $mois++ ) : ?>
					<?php
					$premier  = new DateTimeImmutable( sprintf( '%04d-%02d-01', $annee, $mois ) );
					$decalage = ( (int) $premier->format( 'N' ) ) - 1; // lundi = 0
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
								<button type="button" class="vr-cal-admin__jour is-vide" disabled></button>
							<?php endfor; ?>

							<?php for ( $jour = 1; $jour <= $nb_jours; $jour++ ) : ?>
								<?php
								$date        = sprintf( '%04d-%02d-%02d', $annee, $mois, $jour );
								$passe       = $date < $aujourdhui;
								$reservation = vrr_reservation_du_jour( $date );
								$bloquee     = in_array( $date, $bloquees, true );

								$classes = 'vr-cal-admin__jour';
								$titre   = '';

								if ( $reservation ) {
									$classes .= ' is-reservee';
									$titre    = get_the_title( $reservation ) . ' — réservation enregistrée';
								} elseif ( $bloquee ) {
									$classes .= ' is-bloquee';
									$titre    = 'Date bloquée. Cliquez pour libérer.';
								} else {
									$titre = 'Date libre. Cliquez pour bloquer.';
								}

								if ( $passe ) {
									$classes .= ' is-passe';
								}
								?>
								<button type="button"
									class="<?php echo esc_attr( $classes ); ?>"
									data-date="<?php echo esc_attr( $date ); ?>"
									title="<?php echo esc_attr( $titre ); ?>"
									<?php disabled( $passe || $reservation ); ?>><?php echo esc_html( $jour ); ?></button>
							<?php endfor; ?>
						</div>
					</div>
				<?php endfor; ?>
			</div>

			<div class="vr-cal-admin__legende">
				<span><span class="vr-cal-admin__puce vr-cal-admin__puce--libre"></span>Libre</span>
				<span><span class="vr-cal-admin__puce vr-cal-admin__puce--bloquee"></span>Bloquée par vous</span>
				<span><span class="vr-cal-admin__puce vr-cal-admin__puce--reservee"></span>Réservation enregistrée</span>
			</div>

			<p style="margin-top:24px">
				<button type="submit" class="button button-primary button-hero">Enregistrer mes disponibilités</button>
			</p>
		</form>
	</div>

	<script>
	( function () {
		var champ = document.getElementById( 'vrr-bloquees' );
		var jours = document.querySelectorAll( '.vr-cal-admin__jour[data-date]' );

		function majChamp() {
			var dates = [];
			jours.forEach( function ( jour ) {
				if ( jour.classList.contains( 'is-bloquee' ) ) {
					dates.push( jour.dataset.date );
				}
			} );
			champ.value = dates.join( ',' );
		}

		jours.forEach( function ( jour ) {
			if ( jour.disabled ) {
				return;
			}
			jour.addEventListener( 'click', function () {
				jour.classList.toggle( 'is-bloquee' );
				jour.title = jour.classList.contains( 'is-bloquee' )
					? 'Date bloquée. Cliquez pour libérer.'
					: 'Date libre. Cliquez pour bloquer.';
				majChamp();
			} );
		} );

		majChamp();
	} )();
	</script>

	<?php
}

/**
 * Petit tableau de bord au-dessus du carnet de réservations.
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

	$occupation = round( ( $nuits / 365 ) * 100 );

	printf(
		'<div class="notice notice-info" style="padding:12px 16px"><strong>%d séjour%s à venir</strong> · %d nuits louées en %s (%d %% d\'occupation) · %s € de chiffre d\'affaires</div>',
		(int) $a_venir,
		$a_venir > 1 ? 's' : '',
		(int) $nuits,
		esc_html( $annee ),
		(int) $occupation,
		esc_html( number_format_i18n( $revenus ) )
	);
}
add_action( 'admin_notices', 'vrr_resume_reservations' );
