<?php
/**
 * Catering service cards.
 *
 * @package Kanapka_Theme
 */

$services = array(
	array( 'title' => __( 'Виїзний фуршет', 'kanapka-theme' ), 'text' => __( 'Гнучке меню для ділових і приватних подій.', 'kanapka-theme' ) ),
	array( 'title' => __( 'Кава-брейки', 'kanapka-theme' ), 'text' => __( 'Зручні набори для конференцій, зустрічей і презентацій.', 'kanapka-theme' ) ),
	array( 'title' => __( 'Банкети', 'kanapka-theme' ), 'text' => __( 'Повне святкове меню, підготовлене й доставлене вчасно.', 'kanapka-theme' ) ),
);
?>
<section class="home-section section section--soft" aria-labelledby="services-title">
	<div class="container">
		<div class="section-heading"><h2 id="services-title"><?php esc_html_e( 'Організуємо все під ключ', 'kanapka-theme' ); ?></h2></div>
		<div class="service-grid">
			<?php foreach ( $services as $service ) : ?>
				<article class="service-card card">
					<div>
						<h3><?php echo esc_html( $service['title'] ); ?></h3>
						<p><?php echo esc_html( $service['text'] ); ?></p>
						<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Докладніше', 'kanapka-theme' ); ?> →</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
