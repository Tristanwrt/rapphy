<?php
/**
 * Section « Questions fréquentes ».
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_questions = vr_contenus( 'vr_faq' );

if ( ! $vr_questions ) {
	return;
}
?>

<section class="vr-section vr-wrap" id="faq">
	<div class="vr-faq">

		<div class="vr-reveal vr-center" style="margin-bottom:40px">
			<p class="vr-eyebrow">Questions fréquentes</p>
			<h2 class="vr-h2"><?php echo esc_html( get_theme_mod( 'vr_faq_titre', '' ) ); ?></h2>
		</div>

		<div class="vr-faq__list">
			<?php foreach ( $vr_questions as $vr_i => $vr_question ) : ?>
				<?php $vr_id = 'vr-faq-' . $vr_question->ID; ?>
				<div class="vr-faq__item vr-reveal<?php echo 0 === $vr_i ? ' is-open' : ''; ?>" style="transition-delay:<?php echo esc_attr( $vr_i * 0.05 ); ?>s">
					<button type="button"
						class="vr-faq__q"
						aria-expanded="<?php echo 0 === $vr_i ? 'true' : 'false'; ?>"
						aria-controls="<?php echo esc_attr( $vr_id ); ?>">
						<span><?php echo esc_html( get_the_title( $vr_question ) ); ?></span>
						<span class="vr-faq__sign"><?php vr_icone( 'plus', 'vr-icon vr-icon--sm' ); ?></span>
					</button>
					<div class="vr-faq__a" id="<?php echo esc_attr( $vr_id ); ?>">
						<div>
							<p><?php echo esc_html( wp_strip_all_tags( $vr_question->post_content ) ); ?></p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
