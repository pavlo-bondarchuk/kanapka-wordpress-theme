<?php
/**
 * Company benefits.
 *
 * @package Kanapka_Theme
 */

$benefits = array(
	__( 'Fresh products', 'kanapka-theme' ),
	__( 'A varied menu', 'kanapka-theme' ),
	__( 'Fast delivery', 'kanapka-theme' ),
	__( 'Clear discounts', 'kanapka-theme' ),
	__( 'Years of experience', 'kanapka-theme' ),
	__( 'Easy online ordering', 'kanapka-theme' ),
);
?>
<section class="home-section section" aria-labelledby="benefits-title">
	<div class="container">
		<div class="section-heading"><h2 id="benefits-title"><?php esc_html_e( 'Why customers choose Kanapka', 'kanapka-theme' ); ?></h2></div>
		<ul class="benefit-grid">
			<?php foreach ( $benefits as $index => $benefit ) : ?>
				<li><span aria-hidden="true"><?php echo esc_html( $index + 1 ); ?></span><?php echo esc_html( $benefit ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

