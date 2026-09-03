<?php
/**
 * Section « Deux façons de séjourner » : villa complète ou formule cocooning.
 * Les prix viennent des réglages « Tarifs & saisons » de l'extension de réservation.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_haute = null;
$vr_basse = null;
$vr_caps  = array( 'complete' => (int) get_theme_mod( 'vr_capacite_max', 8 ), 'cocooning' => 4 );

if ( function_exists( 'vrr_saisons' ) ) {
	foreach ( vrr_saisons() as $vr_saison ) {
		if ( 'haute' === $vr_saison['type'] && null === $vr_haute ) {
			$vr_haute = $vr_saison;
		}
		if ( 'basse' === $vr_saison['type'] && null === $vr_basse ) {
			$vr_basse = $vr_saison;
		}
	}
	if ( function_exists( 'vrr_formules' ) ) {
		$vr_formules = vrr_formules();
		$vr_caps     = array( 'complete' => $vr_formules['complete']['capacite'], 'cocooning' => $vr_formules['cocooning']['capacite'] );
	}
}

$vr_prix = function ( $saison, $formule ) {
	return ( $saison && '' !== $saison[ 'prix_' . $formule ] && null !== $saison[ 'prix_' . $formule ] ) ? (int) $saison[ 'prix_' . $formule ] : null;
};
$vr_p_haute     = $vr_prix( $vr_haute, 'complete' );
$vr_p_basse     = $vr_prix( $vr_basse, 'complete' );
$vr_p_cocooning = $vr_prix( $vr_basse, 'cocooning' );
$vr_semaine     = $vr_p_haute ? $vr_p_haute * 7 : null;
?>

<section class="vr-section vr-wrap" id="formules">

	<div class="vr-reveal vr-center" style="margin-bottom:48px">
		<p class="vr-eyebrow">Deux façons de séjourner</p>
		<h2 class="vr-h2 vr-mx-auto vr-maxw-2xl"><?php echo esc_html( get_theme_mod( 'vr_sejours_titre', 'La villa entière, ou la version cocooning' ) ); ?></h2>
	</div>

	<div class="vr-formules">

		<div class="vr-formule vr-formule--dark vr-reveal">
			<p class="vr-eyebrow">Villa complète · toute la saison</p>
			<h3 class="font-display" style="font-size:1.5rem">La villa complète</h3>
			<p class="vr-lead" style="margin-top:16px"><?php echo esc_html( get_theme_mod( 'vr_sejours_haute_texte', '' ) ); ?></p>

			<?php if ( $vr_p_haute || $vr_p_basse ) : ?>
				<div class="vr-formule__prix">
					<?php if ( $vr_p_basse ) : ?>
						<div>
							<span class="vr-formule__montant"><?php echo esc_html( number_format_i18n( $vr_p_basse ) ); ?> €</span>
							<span class="vr-formule__unite">par nuit en basse saison<br />arrivées et départs libres</span>
						</div>
					<?php endif; ?>
					<?php if ( $vr_p_haute ) : ?>
						<div>
							<span class="vr-formule__montant"><?php echo esc_html( number_format_i18n( $vr_p_haute ) ); ?> €</span>
							<span class="vr-formule__unite">par nuit en haute saison<br />du samedi au samedi<?php echo $vr_semaine ? ', soit ' . esc_html( number_format_i18n( $vr_semaine ) ) . ' € la semaine' : ''; ?></span>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<ul>
				<li><?php vr_icone( 'check', 'vr-icon vr-icon--sm' ); ?>Jusqu'à <?php echo esc_html( $vr_caps['complete'] ); ?> voyageurs · 4 chambres</li>
				<li><?php vr_icone( 'check', 'vr-icon vr-icon--sm' ); ?>Toutes les prestations incluses</li>
				<li><?php vr_icone( 'check', 'vr-icon vr-icon--sm' ); ?>Grandes tablées, soirées piscine, pétanque</li>
			</ul>
			<a class="vr-btn vr-btn--primary" href="#reserver" data-formule="complete">Réserver la villa complète<?php vr_icone( 'arrow', 'vr-icon vr-icon--sm' ); ?></a>
		</div>

		<div class="vr-formule vr-formule--light vr-reveal" style="transition-delay:0.12s">
			<p class="vr-eyebrow">Formule Cocooning · basse saison</p>
			<h3 class="font-display" style="font-size:1.5rem">La version cocooning</h3>
			<p class="vr-lead" style="margin-top:16px"><?php echo esc_html( get_theme_mod( 'vr_sejours_basse_texte', '' ) ); ?></p>

			<?php if ( $vr_p_cocooning ) : ?>
				<div class="vr-formule__prix">
					<div>
						<span class="vr-formule__montant"><?php echo esc_html( number_format_i18n( $vr_p_cocooning ) ); ?> €</span>
						<span class="vr-formule__unite">par nuit, mai, juin et septembre<br />arrivées et départs libres</span>
					</div>
				</div>
			<?php endif; ?>

			<ul>
				<li><?php vr_icone( 'check', 'vr-icon vr-icon--sm' ); ?>Jusqu'à <?php echo esc_html( $vr_caps['cocooning'] ); ?> voyageurs · 2 chambres ouvertes</li>
				<li><?php vr_icone( 'check', 'vr-icon vr-icon--sm' ); ?>Villa et extérieurs 100 % privatisés</li>
				<li><?php vr_icone( 'check', 'vr-icon vr-icon--sm' ); ?>Parfaite pour les couples et petits comités</li>
			</ul>
			<a class="vr-btn vr-btn--outline" href="#reserver" data-formule="cocooning">Réserver en cocooning<?php vr_icone( 'arrow', 'vr-icon vr-icon--sm' ); ?></a>
		</div>

	</div>
</section>
