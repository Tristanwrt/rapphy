<?php
/**
 * Section « Réservation » — le calendrier public.
 * Les dates indisponibles viennent de l'extension Villa Raffy Réservations ;
 * si elle n'est pas active, le calendrier fonctionne quand même, sans blocages.
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
?>

<section class="vr-section vr-booking vr-wrap" id="reserver">

	<div class="vr-reveal vr-center" style="margin-bottom:48px">
		<p class="vr-eyebrow">Réservation</p>
		<h2 class="vr-h2 vr-mx-auto vr-maxw-2xl">Choisissez vos dates, la villa vous attend</h2>
		<p class="vr-lead vr-mx-auto" style="margin-top:20px;max-width:36rem">
			Sélectionnez votre arrivée puis votre départ. Votre demande part directement
			chez vos hôtes — par téléphone, WhatsApp ou email — sans aucune commission.
		</p>
	</div>

	<div class="vr-booking__panel vr-reveal" id="vr-calendrier">

		<div class="vr-cal__nav">
			<button type="button" class="vr-cal__arrow" id="vr-cal-prec" aria-label="Mois précédent">
				<?php vr_icone( 'left' ); ?>
			</button>

			<div class="vr-cal__legend">
				<span class="vr-cal__chip vr-cal__chip--sel"></span> sélection
				<span class="vr-cal__chip vr-cal__chip--off" style="margin-left:12px"></span> indisponible
			</div>

			<button type="button" class="vr-cal__arrow" id="vr-cal-suiv" aria-label="Mois suivant">
				<?php vr_icone( 'right' ); ?>
			</button>
		</div>

		<div class="vr-cal__months" id="vr-cal-months"></div>

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
						<output id="vr-voyageurs" data-max="<?php echo esc_attr( $vr_capacite ); ?>">2</output>
						<button type="button" id="vr-plus" aria-label="Plus de voyageurs">+</button>
					</div>
				</div>
				<div id="vr-recap-duree-bloc" hidden>
					<div class="vr-recap__label">Durée</div>
					<div class="vr-recap__value vr-recap__value--brass" id="vr-recap-duree"></div>
				</div>
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

			<p class="vr-booking__note">
				Réponse rapide et personnelle de vos hôtes. Réserver en direct, c'est le
				meilleur tarif garanti — sans les frais de service des plateformes.
			</p>
		</div>

	</div>
</section>
