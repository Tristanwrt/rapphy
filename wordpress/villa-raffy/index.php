<?php
/**
 * Liste des articles (blog, archives, résultats de recherche).
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
		<?php if ( is_search() ) : ?>
			<h1>Résultats pour «&nbsp;<?php echo esc_html( get_search_query() ); ?>&nbsp;»</h1>
		<?php elseif ( is_category() || is_tag() || is_tax() ) : ?>
			<h1><?php echo esc_html( single_term_title( '', false ) ); ?></h1>
			<?php if ( term_description() ) : ?>
				<p><?php echo esc_html( wp_strip_all_tags( term_description() ) ); ?></p>
			<?php endif; ?>
		<?php else : ?>
			<h1><?php echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) ? get_the_title( get_option( 'page_for_posts' ) ) : 'Le journal de la villa' ); ?></h1>
			<p>Nos conseils pour profiter du Lot-et-Garonne, les nouvelles de la villa et les bonnes adresses du coin.</p>
		<?php endif; ?>
	</div>
</div>

<section class="vr-section vr-wrap">
	<?php if ( have_posts() ) : ?>

		<div class="vr-posts">
			<?php
			$vr_i = 0;
			while ( have_posts() ) :
				the_post();
				?>
				<article class="vr-tile vr-reveal" style="transition-delay:<?php echo esc_attr( ( $vr_i % 3 ) * 0.1 ); ?>s">
					<a href="<?php the_permalink(); ?>" style="display:flex;flex-direction:column;height:100%">
						<div class="vr-tile__media" style="height:192px">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'vr-carte' );
							} else {
								printf(
									'<div class="vr-photo-fallback" style="height:100%%" data-label="%s"></div>',
									esc_attr( get_the_title() )
								);
							}
							?>
						</div>
						<div class="vr-tile__body">
							<p class="vr-post__meta"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></p>
							<h2 class="vr-h3" style="margin-top:8px"><?php the_title(); ?></h2>
							<p class="vr-tile__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
						</div>
					</a>
				</article>
				<?php
				$vr_i++;
			endwhile;
			?>
		</div>

		<?php
		the_posts_pagination( array(
			'class'     => 'vr-pagination',
			'mid_size'  => 1,
			'prev_text' => '←',
			'next_text' => '→',
		) );
		?>

	<?php else : ?>
		<p class="vr-lead vr-center">Aucun article pour le moment.</p>
	<?php endif; ?>
</section>

<?php
get_footer();
