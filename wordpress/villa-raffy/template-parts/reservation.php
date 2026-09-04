<?php
/**
 * Section « Réservation » — le calendrier public.
 * Les disponibilités, les prix et les règles de séjour viennent de l'extension
 * Villa Raffy Réservations (menu « Tarifs & saisons »). Sans l'extension,
 * le calendrier fonctionne quand même : toutes les dates sont ouvertes, sans prix.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_tel      = get_theme_mod( 'vr_telephone', '' );
$vr_tel_lien = vr_tel_brut( $vr_tel );
$vr_whatsapp = vr_tel_brut( get_theme_mod( 'vr_whatsapp', '' ) );
$vr_email    = get_theme_mod( 'vr_email', '' );
$vr_capacite = (int) get_theme_mod( 'vr_capacite_max', 8 );

$vr_caps   = array( 'complete' => $vr_capacite, 'cocooning' => 4 );
$vr_regles = array();

if ( function_exists( 'vrr_formules' ) ) {
	$vr_formules = vrr_formules();
	$vr_caps     = array(
		'complete'  => (int) $vr_formules['complete']['capacite'],
		'cocooning' => (int) $vr_formules['cocooning']['capacite'],
	);
}

// Phrase de règle générée depuis les saisons (« Du 1er juillet au 31 août : … »).
if ( function_exists( 'vrr_saisons' ) ) {
	$vr_jours_semaine = array( 1 => 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche' );
	$vr_mois_noms     = array( 1 => 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre' );

	$vr_date_lisible = function ( $mmjj ) use ( $vr_mois_noms ) {
		$p = explode( '-', (string) $mmjj );
		if ( 2 !== count( $p ) ) {
			return $mmjj;
		}
		$j = (int) $p[1];
		$m = (int) $p[0];
		return ( 1 === $j ? '1er' : $j ) . ' ' . ( isset( $vr_mois_noms[ $m ] ) ? $vr_mois_noms[ $m ] : '' );
	};

	foreach ( vrr_saisons() as $vr_saison ) {
		$vr_arrivees = array_map( 'intval', (array) $vr_saison['arrivee'] );
		$vr_departs  = array_map( 'intval', (array) $vr_saison['depart'] );
		$vr_min      = max( 1, (int) $vr_saison['min_nuits'] );
		$vr_libre    = 7 === count( $vr_arrivees ) && 7 === count( $vr_departs );

		if ( $vr_libre && $vr_min <= 2 ) {
			continue; // Rien de particulier à signaler.
		}

		$vr_phrase = 'Du ' . $vr_date_lisible( $vr_saison['debut'] ) . ' au ' . $vr_date_lisible( $vr_saison['fin'] ) . ' : ';

		if ( ! $vr_libre ) {
			$vr_noms_a = array();
			foreach ( $vr_arrivees as $vr_j ) {
				if ( isset( $vr_jours_semaine[ $vr_j ] ) ) {
					$vr_noms_a[] = $vr_jours_semaine[ $vr_j ];
				}
			}
			$vr_noms_d = array();
			foreach ( $vr_departs as $vr_j ) {
				if ( isset( $vr_jours_semaine[ $vr_j ] ) ) {
					$vr_noms_d[] = $vr_jours_semaine[ $vr_j ];
				}
			}
			if ( $vr_noms_a === $vr_noms_d ) {
				$vr_phrase .= 'arrivées et départs le ' . implode( ' ou le ', $vr_noms_a );
			} else {
				$vr_phrase .= 'arrivée le ' . implode( ' ou le ', $vr_noms_a ) . ', départ le ' . implode( ' ou le ', $vr_noms_d );
			}
			$vr_phrase .= ', ';
		}

		$vr_phrase   .= $vr_min . ' nuit' . ( $vr_min > 1 ? 's' : '' ) . ' minimum.';
		$vr_regles[]  = $vr_phrase;
	}
}
?>

<section class="vr-section vr-booking" id="reserver">
<div class="vr-wrap">

	<div class="vr-reveal vr-center" style="margin-bottom:48px">
		<p class="vr-eyebrow">Réservation</p>
		<h2 class="vr-h2 vr-mx-auto vr-maxw-2xl"><?php echo esc_html( get_theme_mod( 'vr_resa_titre', '' ) ); ?></h2>
		<p class="vr-lead vr-mx-auto" style="margin-top:20px;max-width:36rem"><?php echo esc_html( get_theme_mod( 'vr_resa_texte', '' ) ); ?></p>
	</div>

	<div class="vr-booking__panel vr-reveal" id="vr-calendrier" data-formule="complete">

		<div class="vr-formules-choix" role="radiogroup" aria-label="Formule de séjour">
			<button type="button" class="vr-formule-btn is-active" role="radio" aria-checked="true" data-choix="complete" data-capacite="<?php echo esc_attr( $vr_caps['complete'] ); ?>">
				<span class="vr-formule-btn__nom">Villa complète</span>
				<span class="vr-formule-btn__info">4 chambres · jusqu'à <?php echo esc_html( $vr_caps['complete'] ); ?> voyageurs</span>
			</button>
			<button type="button" class="vr-formule-btn" role="radio" aria-checked="false" data-choix="cocooning" data-capacite="<?php echo esc_attr( $vr_caps['cocooning'] ); ?>">
				<span class="vr-formule-btn__nom">Formule Cocooning</span>
				<span class="vr-formule-btn__info">2 chambres · jusqu'à <?php echo esc_html( $vr_caps['cocooning'] ); ?> voyageurs · basse saison</span>
			</button>
		</div>

		<div class="vr-cal__nav">
			<button type="button" class="vr-cal__arrow" id="vr-cal-prec" aria-label="Mois précédent">
				<?php vr_icone( 'left' ); ?>
			</button>

			<div class="vr-cal__legend">
				<span class="vr-cal__chip vr-cal__chip--sel"></span> sélection
				<span class="vr-cal__chip vr-cal__chip--off" style="margin-left:12px"></span> réservé
				<span class="vr-cal__chip vr-cal__chip--closed" style="margin-left:12px"></span> fermé
			</div>

			<button type="button" class="vr-cal__arrow" id="vr-cal-suiv" aria-label="Mois suivant">
				<?php vr_icone( 'right' ); ?>
			</button>
		</div>

		<div class="vr-cal__months" id="vr-cal-months" aria-live="polite"></div>

		<?php if ( $vr_regles ) : ?>
			<p class="vr-cal__regle" id="vr-cal-regle"><?php echo esc_html( implode( ' ', $vr_regles ) ); ?></p>
		<?php endif; ?>

		<p class="vr-cal__error" id="vr-cal-erreur" role="status" hidden></p>

		<div class="vr-recap">
			<div class="vr-recap__row">
				<div>
					<div class="vr-recap__label">Arrivée</div>
					<div class="vr-recap__value" id="vr-recap-arrivee">— choisissez une date —</div>
				</div>
				<div>
					<div class="vr-recap__label">Départ</div>
					<div class="vr-recap__value" id="vr-recap-depart">—</div>
				</div>
				<div>
					<div class="vr-recap__label">Voyageurs</div>
					<div class="vr-stepper">
						<button type="button" id="vr-moins" aria-label="Moins de voyageurs">−</button>
						<output id="vr-voyageurs" data-max="<?php echo esc_attr( $vr_caps['complete'] ); ?>">2</output>
						<button type="button" id="vr-plus" aria-label="Plus de voyageurs">+</button>
					</div>
				</div>
				<div id="vr-recap-duree-bloc" hidden>
					<div class="vr-recap__label">Durée</div>
					<div class="vr-recap__value vr-recap__value--brass" id="vr-recap-duree"></div>
				</div>
			</div>

			<div class="vr-total" id="vr-total" hidden aria-live="polite">
				<div class="vr-total__calcul" id="vr-total-calcul"></div>
				<div class="vr-total__montant" id="vr-total-montant"></div>
				<div class="vr-total__note">Tarif indicatif pour la formule <span id="vr-total-formule">Villa complète</span>, confirmé par vos hôtes. Aucune commission, aucun frais de service.</div>
			</div>

			<div class="vr-actions">
				<?php if ( $vr_whatsapp ) : ?>
					<a class="vr-btn vr-btn--primary" id="vr-lien-whatsapp" href="#" target="_blank" rel="noopener" aria-disabled="true">
						<?php vr_icone( 'whatsapp', 'vr-icon vr-icon--sm' ); ?>
						Demander sur WhatsApp
					</a>
				<?php endif; ?>

				<?php if ( $vr_email ) : ?>
					<a class="vr-btn vr-btn--outline" id="vr-lien-email" href="#" aria-disabled="true">
						<?php vr_icone( 'mail', 'vr-icon vr-icon--sm' ); ?>
						Envoyer par email
					</a>
				<?php endif; ?>

				<?php if ( $vr_tel ) : ?>
					<a class="vr-btn" style="border-color:rgba(33,26,19,0.15)" href="tel:+<?php echo esc_attr( $vr_tel_lien ); ?>">
						<?php vr_icone( 'phone', 'vr-icon vr-icon--sm' ); ?>
						<?php echo esc_html( $vr_tel ); ?>
					</a>
				<?php endif; ?>
			</div>

			<p class="vr-booking__note"><?php echo esc_html( get_theme_mod( 'vr_resa_note', '' ) ); ?></p>
		</div>

	</div>
</div>
</section>
