<?php
/**
 * Page classique (mentions légales, conditions de location…).
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
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<div class="vr-section vr-wrap">
		<div class="vr-article">
			<div class="vr-article__content">
				<?php the_content(); ?>
			</div>
		</div>
	</div>

	<?php
endwhile;

get_footer();
