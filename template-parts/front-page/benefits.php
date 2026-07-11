<?php
/**
 * Company benefits.
 *
 * @package Kanapka_Theme
 */

$benefits = array(
	__( 'Свіжі продукти', 'kanapka-theme' ),
	__( 'Різноманітне меню', 'kanapka-theme' ),
	__( 'Швидка доставка', 'kanapka-theme' ),
	__( 'Прозорі знижки', 'kanapka-theme' ),
	__( 'Багаторічний досвід', 'kanapka-theme' ),
	__( 'Зручне онлайн-замовлення', 'kanapka-theme' ),
);
?>
<section class="home-section section" aria-labelledby="benefits-title">
	<div class="container">
		<div class="section-heading"><h2 id="benefits-title"><?php esc_html_e( 'Чому клієнти обирають Kanapka', 'kanapka-theme' ); ?></h2></div>
		<ul class="benefit-grid">
			<?php foreach ( $benefits as $index => $benefit ) : ?>
				<li><span aria-hidden="true"><?php echo esc_html( $index + 1 ); ?></span><?php echo esc_html( $benefit ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
