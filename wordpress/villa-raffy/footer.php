<?php
/**
 * Pied de page : trois colonnes symétriques (contact · la villa · plan du site).
 * Les textes se modifient dans Personnaliser → Pied de page, les liens dans
 * Apparence → Menus (emplacement « Menu du pied de page »).
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
$vr_adresse  = get_theme_mod( 'vr_adresse', '' );
$vr_label    = get_theme_mod( 'vr_classement', '' );
$vr_airbnb   = get_theme_mod( 'vr_url_airbnb', '' );
$vr_google   = get_theme_mod( 'vr_url_google', '' );
$vr_hotes    = get_theme_mod( 'vr_hotes', 'Vos hôtes' );

$vr_menu_secours = array(
	array( 'Le séjour & la cuisine', '/#villa' ),
	array( 'Les chambres', '/#chambres' ),
	array( 'Formules & tarifs', '/#formules' ),
	array( 'Piscine, jacuzzi & jardin', '/#exterieurs' ),
	array( 'Avis des voyageurs', '/#avis' ),
	array( 'Réserver en direct', '/#reserver' ),
	array( 'Questions fréquentes', '/#faq' ),
);
?>
</main>

<footer class="vr-footer" id="contact">

	<div class="vr-footer__cta">
		<div class="vr-wrap vr-reveal">
			<h2><?php echo esc_html( get_theme_mod( 'vr_pied_cta_titre', '' ) ); ?></h2>
			<p><?php echo esc_html( str_replace( '{hotes}', $vr_hotes, get_theme_mod( 'vr_pied_cta_texte', '' ) ) ); ?></p>
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

		<div class="vr-footer__col vr-footer__col--gauche">
			<h3>Contact</h3>
			<ul>
				<?php if ( $vr_tel ) : ?>
					<li>
						<?php vr_icone( 'phone', 'vr-icon vr-icon--sm' ); ?>
						<a href="tel:+<?php echo esc_attr( $vr_tel_lien ); ?>"><?php echo esc_html( $vr_tel ); ?></a>
					</li>
				<?php endif; ?>
				<?php if ( $vr_whatsapp ) : ?>
					<li>
						<?php vr_icone( 'whatsapp', 'vr-icon vr-icon--sm' ); ?>
						<a href="https://wa.me/<?php echo esc_attr( $vr_whatsapp ); ?>" target="_blank" rel="noopener">Écrire sur WhatsApp</a>
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

		<div class="vr-footer__col vr-footer__col--centre">
			<div class="vr-footer__brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'medium', false, array( 'class' => 'vr-footer__logo', 'alt' => get_bloginfo( 'name' ) ) ); ?>
				<?php else : ?>
					<div class="vr-footer__nom"><?php bloginfo( 'name' ); ?></div>
				<?php endif; ?>
				<?php if ( get_bloginfo( 'description' ) ) : ?>
					<p class="vr-footer__tag"><?php bloginfo( 'description' ); ?></p>
				<?php endif; ?>
				<p class="vr-footer__texte"><?php echo esc_html( get_theme_mod( 'vr_pied_texte', '' ) ); ?></p>
			</div>

			<?php if ( $vr_google || $vr_airbnb ) : ?>
				<div class="vr-footer__plateformes">
					<?php if ( $vr_google ) : ?>
						<a href="<?php echo esc_url( $vr_google ); ?>" target="_blank" rel="noopener" aria-label="Nos avis Google (nouvelle fenêtre)"><?php vr_logo_plateforme( 'google' ); ?></a>
					<?php endif; ?>
					<?php if ( $vr_airbnb ) : ?>
						<a href="<?php echo esc_url( $vr_airbnb ); ?>" target="_blank" rel="noopener" aria-label="Notre annonce Airbnb (nouvelle fenêtre)"><?php vr_logo_plateforme( 'airbnb' ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="vr-footer__col vr-footer__col--droite">
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
				echo '<ul>';
				foreach ( $vr_menu_secours as $vr_lien ) {
					printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( $vr_lien[1] ) ), esc_html( $vr_lien[0] ) );
				}
				echo '</ul>';
			}
			?>
		</div>

	</div>

	<div class="vr-footer__legal">
		<div class="vr-wrap vr-footer__legal-inner">
			<p>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?><?php echo $vr_adresse ? ' · ' . esc_html( $vr_adresse ) : ''; ?></p>
			<p><?php echo esc_html( get_theme_mod( 'vr_pied_legal', '' ) ); ?><?php echo $vr_label ? ' · ' . esc_html( $vr_label ) : ''; ?></p>
		</div>
	</div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
