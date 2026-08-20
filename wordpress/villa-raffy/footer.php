<?php
/**
 * Pied de page.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_tel      = get_theme_mod( 'vr_telephone', '' );
$vr_tel_lien = vr_tel_brut( $vr_tel );
$vr_email    = get_theme_mod( 'vr_email', '' );
$vr_adresse  = get_theme_mod( 'vr_adresse', '' );
$vr_label    = get_theme_mod( 'vr_classement', '' );
$vr_airbnb   = get_theme_mod( 'vr_url_airbnb', '' );
$vr_hotes    = get_theme_mod( 'vr_hotes', 'Vos hôtes' );
?>
</main>

<footer class="vr-footer" id="contact">

	<div class="vr-footer__cta">
		<div class="vr-wrap vr-reveal">
			<h2><?php echo esc_html( get_theme_mod( 'vr_pied_cta_titre', 'Votre prochain séjour d\'exception commence ici' ) ); ?></h2>
			<p>Un appel, un message — et la villa est à vous. <?php echo esc_html( $vr_hotes ); ?> vous répondent personnellement.</p>
			<div class="vr-footer__buttons">
				<a class="vr-btn vr-btn--primary" href="<?php echo esc_url( home_url( '/#reserver' ) ); ?>">Vérifier les disponibilités</a>
				<?php if ( $vr_tel ) : ?>
					<a class="vr-btn vr-btn--ghost" href="tel:+<?php echo esc_attr( $vr_tel_lien ); ?>">
						<?php vr_icone( 'phone', 'vr-icon vr-icon--sm' ); ?>
						<?php echo esc_html( $vr_tel ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="vr-wrap vr-footer__cols">

		<div class="vr-footer__brand">
			<div class="font-display" style="font-size:1.25rem"><?php bloginfo( 'name' ); ?></div>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="vr-footer__tag"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
			<p><?php echo esc_html( get_theme_mod( 'vr_pied_texte', '' ) ); ?></p>
		</div>

		<div>
			<h3>Contact</h3>
			<ul>
				<?php if ( $vr_tel ) : ?>
					<li>
						<?php vr_icone( 'phone', 'vr-icon vr-icon--sm' ); ?>
						<a href="tel:+<?php echo esc_attr( $vr_tel_lien ); ?>"><?php echo esc_html( $vr_tel ); ?></a>
					</li>
				<?php endif; ?>
				<?php if ( $vr_email ) : ?>
					<li>
						<?php vr_icone( 'mail', 'vr-icon vr-icon--sm' ); ?>
						<a href="mailto:<?php echo esc_attr( $vr_email ); ?>"><?php echo esc_html( $vr_email ); ?></a>
					</li>
				<?php endif; ?>
				<?php if ( $vr_adresse ) : ?>
					<li>
						<?php vr_icone( 'pin', 'vr-icon vr-icon--sm' ); ?>
						<span><?php echo esc_html( $vr_adresse ); ?></span>
					</li>
				<?php endif; ?>
			</ul>
		</div>

		<div>
			<h3>La villa</h3>
			<?php
			if ( has_nav_menu( 'pied' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'pied',
					'container'      => false,
					'menu_class'     => '',
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
			} else {
				?>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/#villa' ) ); ?>">Le séjour &amp; la cuisine</a></li>
					<li><a href="<?php echo esc_url( home_url( '/#chambres' ) ); ?>">Les chambres</a></li>
					<li><a href="<?php echo esc_url( home_url( '/#exterieurs' ) ); ?>">Piscine, jacuzzi &amp; jardin</a></li>
					<li><a href="<?php echo esc_url( home_url( '/#avis' ) ); ?>">Avis des voyageurs</a></li>
					<li><a href="<?php echo esc_url( home_url( '/#reserver' ) ); ?>">Réserver en direct</a></li>
				</ul>
				<?php
			}
			?>
		</div>

		<div>
			<h3>Confiance</h3>
			<ul>
				<?php if ( $vr_label ) : ?>
					<li><?php vr_icone( 'shield', 'vr-icon vr-icon--sm' ); ?><span><?php echo esc_html( $vr_label ); ?></span></li>
				<?php endif; ?>
				<li><?php vr_icone( 'star', 'vr-icon vr-icon--sm' ); ?><span><?php echo esc_html( get_theme_mod( 'vr_preuve_1', '' ) ); ?></span></li>
				<?php if ( $vr_airbnb ) : ?>
					<li>
						<?php vr_icone( 'arrow', 'vr-icon vr-icon--sm' ); ?>
						<a href="<?php echo esc_url( $vr_airbnb ); ?>" target="_blank" rel="noopener">Notre annonce Airbnb</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>

	</div>

	<div class="vr-footer__legal">
		<div class="vr-wrap vr-footer__legal-inner">
			<p>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?><?php echo $vr_adresse ? ' — ' . esc_html( $vr_adresse ) : ''; ?>. Tous droits réservés.</p>
			<p>Location saisonnière de standing<?php echo $vr_label ? ' · ' . esc_html( $vr_label ) : ''; ?></p>
		</div>
	</div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
