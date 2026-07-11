<?php
/**
 * Homepage turnkey service cards.
 *
 * @package Kanapka_Theme
 */

$services = kanapka_theme_get_home_services();

if ( ! $services ) {
	return;
}
?>
<section class="home-section section turnkey-services" aria-labelledby="services-title">
	<div class="container">
		<div class="section-heading">
			<h2 id="services-title"><?php esc_html_e( 'Організуємо все під ключ', 'kanapka-theme' ); ?></h2>
		</div>
		<div class="service-grid">
			<?php foreach ( $services as $service ) : ?>
				<article class="service-card">
					<?php if ( $service['image_id'] ) : ?>
						<?php echo wp_get_attachment_image( $service['image_id'], 'kanapka-service', false, array( 'class' => 'service-card__image', 'loading' => 'lazy', 'sizes' => '(max-width: 780px) 90vw, 33vw' ) ); ?>
					<?php endif; ?>
					<div class="service-card__content">
						<?php if ( $service['title'] ) : ?>
							<h3><?php echo esc_html( $service['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( $service['text'] ) : ?>
							<p><?php echo esc_html( $service['text'] ); ?></p>
						<?php endif; ?>
						<?php if ( $service['button_label'] && $service['button_url'] ) : ?>
							<a class="service-card__button" href="<?php echo esc_url( $service['button_url'] ); ?>"<?php echo '_blank' === $service['button_target'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
								<?php echo esc_html( $service['button_label'] ); ?>
							</a>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
