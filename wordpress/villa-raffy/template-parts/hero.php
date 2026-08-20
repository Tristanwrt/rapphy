<?php
/**
 * Grande image d'accueil, avec la barre de recherche.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_image_id = (int) get_theme_mod( 'vr_hero_image', 0 );
$vr_capacite = (int) get_theme_mod( 'vr_capacite_max', 8 );
$vr_preuves  = array(
	'star'   => get_theme_mod( 'vr_preuve_1', '' ),
	'shield' => get_theme_mod( 'vr_preuve_2', '' ),
	'check'  => get_theme_mod( 'vr_preuve_3', '' ),
);
?>

<section class="vr-hero" id="accueil">

	<div class="vr-hero__bg" id="vr-hero-bg">
		<?php vr_image( $vr_image_id, 'vr-hero', 'La villa', '' ); ?>
	</div>
	<div class="vr-hero__veil"></div>

	<div class="vr-wrap vr-hero__inner">

		<?php if ( get_theme_mod( 'vr_hero_surtitre', '' ) ) : ?>
			<p class="vr-eyebrow"><?php echo esc_html( get_theme_mod( 'vr_hero_surtitre', '' ) ); ?></p>
		<?php endif; ?>

		<h1 class="vr-hero__title"><?php echo esc_html( get_theme_mod( 'vr_hero_titre', get_bloginfo( 'name' ) ) ); ?></h1>

		<?php if ( get_theme_mod( 'vr_hero_sous_titre', '' ) ) : ?>
			<p class="vr-hero__sub"><?php echo esc_html( get_theme_mod( 'vr_hero_sous_titre', '' ) ); ?></p>
		<?php endif; ?>

		<form class="vr-search" id="vr-search" action="<?php echo esc_url( home_url( '/#reserver' ) ); ?>">
			<label class="vr-search__field">
				<span class="vr-search__label">Arrivée</span>
				<input type="date" name="arrivee" id="vr-search-arrivee" min="<?php echo esc_attr( wp_date( 'Y-m-d' ) ); ?>" aria-label="Date d'arrivée" />
			</label>

			<label class="vr-search__field">
				<span class="vr-search__label">Départ</span>
				<input type="date" name="depart" id="vr-search-depart" min="<?php echo esc_attr( wp_date( 'Y-m-d' ) ); ?>" aria-label="Date de départ" />
			</label>

			<label class="vr-search__field">
				<span class="vr-search__label">Voyageurs</span>
				<select name="voyageurs" id="vr-search-voyageurs" aria-label="Nombre de voyageurs">
					<?php for ( $i = 1; $i <= $vr_capacite; $i++ ) : ?>
						<option value="<?php echo esc_attr( $i ); ?>"<?php selected( $i, 2 ); ?>>
							<?php echo esc_html( $i . ' voyageur' . ( $i > 1 ? 's' : '' ) ); ?>
						</option>
					<?php endfor; ?>
				</select>
			</label>

			<div class="vr-search__submit">
				<button type="submit" class="vr-btn vr-btn--primary">
					<?php vr_icone( 'search', 'vr-icon vr-icon--sm' ); ?>
					Rechercher
				</button>
			</div>
		</form>

		<a class="vr-hero__scroll" href="#villa">
			Ou commencez par découvrir la villa
			<?php vr_icone( 'arrow', 'vr-icon vr-icon--sm' ); ?>
		</a>

		<div class="vr-hero__proof">
			<?php foreach ( $vr_preuves as $vr_icone_nom => $vr_texte ) : ?>
				<?php if ( ! $vr_texte ) { continue; } ?>
				<span>
					<?php if ( 'star' === $vr_icone_nom ) { vr_etoiles(); } else { vr_icone( $vr_icone_nom, 'vr-icon vr-icon--sm' ); } ?>
					<?php echo esc_html( $vr_texte ); ?>
				</span>
			<?php endforeach; ?>
		</div>

	</div>
</section>
