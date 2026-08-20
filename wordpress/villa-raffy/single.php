<?php
/**
 * Article de blog.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<div class="vr-page-head">
		<div class="vr-wrap">
			<p class="vr-eyebrow"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></p>
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<article class="vr-section vr-wrap">
		<div class="vr-article">

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="vr-media" style="margin-bottom:40px;box-shadow:var(--shadow-card)">
					<?php the_post_thumbnail( 'full' ); ?>
				</div>
			<?php endif; ?>

			<div class="vr-article__content">
				<?php the_content(); ?>
			</div>

			<div style="margin-top:56px;padding-top:32px;border-top:1px solid rgba(33,26,19,0.1);text-align:center">
				<p class="vr-lead" style="margin-bottom:20px">Envie de découvrir la villa par vous-même ?</p>
				<a class="vr-btn vr-btn--primary" href="<?php echo esc_url( home_url( '/#reserver' ) ); ?>">Vérifier les disponibilités</a>
			</div>

		</div>
	</article>

	<?php
endwhile;

get_footer();
