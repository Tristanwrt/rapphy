<?php
/**
 * Page introuvable.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="vr-page-head">
	<div class="vr-wrap">
		<h1>Cette page n'existe pas</h1>
		<p>Le lien que vous avez suivi n'est plus valable, mais la villa, elle, est toujours là.</p>
	</div>
</div>

<div class="vr-section vr-wrap vr-center">
	<div class="vr-footer__buttons" style="justify-content:center">
		<a class="vr-btn vr-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à l'accueil</a>
		<a class="vr-btn vr-btn--outline" href="<?php echo esc_url( home_url( '/#reserver' ) ); ?>">Vérifier les disponibilités</a>
	</div>
</div>

<?php
get_footer();
