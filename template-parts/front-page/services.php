<?php
/**
 * Catering service cards.
 *
 * @package Kanapka_Theme
 */

$services = array(
	array( 'title' => __( 'Buffet catering', 'kanapka-theme' ), 'text' => __( 'A flexible menu for business and private events.', 'kanapka-theme' ) ),
	array( 'title' => __( 'Coffee breaks', 'kanapka-theme' ), 'text' => __( 'Convenient sets for conferences, meetings and presentations.', 'kanapka-theme' ) ),
	array( 'title' => __( 'Banquets', 'kanapka-theme' ), 'text' => __( 'A complete celebration menu prepared and delivered on time.', 'kanapka-theme' ) ),
);
?>
<section class="home-section section section--soft" aria-labelledby="services-title">
	<div class="container">
		<div class="section-heading"><h2 id="services-title"><?php esc_html_e( 'We organise everything', 'kanapka-theme' ); ?></h2></div>
		<div class="service-grid">
			<?php foreach ( $services as $service ) : ?>
				<article class="service-card card">
					<div>
						<h3><?php echo esc_html( $service['title'] ); ?></h3>
						<p><?php echo esc_html( $service['text'] ); ?></p>
						<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Learn more', 'kanapka-theme' ); ?> →</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

