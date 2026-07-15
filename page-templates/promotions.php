<?php
/**
 * Template Name: Promotions
 * Template Post Type: page
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) {
	the_post();

	$promotions       = function_exists( 'get_field' ) ? get_field( 'kanapka_promotions', get_the_ID() ) : array();
	$benefits_enabled = ! function_exists( 'get_field' ) || get_field( 'kanapka_promotions_benefits_enabled', get_the_ID() );
	?>
	<main id="main-content" class="site-main promotions-page">
		<header class="promotions-page__header container">
			<?php kanapka_theme_breadcrumb(); ?>
			<h1><?php the_title(); ?></h1>
		</header>

		<div class="promotions-list container">
			<?php if ( is_array( $promotions ) && $promotions ) : ?>
				<?php foreach ( $promotions as $index => $promotion ) : ?>
					<?php
					$image_id = absint( $promotion['image'] ?? 0 );
					$title    = sanitize_text_field( $promotion['title'] ?? '' );
					$content  = wp_kses_post( $promotion['content'] ?? '' );
					$link     = is_array( $promotion['link'] ?? null ) ? $promotion['link'] : array();
					$items    = is_array( $promotion['items'] ?? null ) ? $promotion['items'] : array();
					$classes  = 'promotion-card' . ( $index % 2 ? ' promotion-card--soft' : '' );
					?>
					<article class="<?php echo esc_attr( $classes ); ?>">
						<div class="promotion-card__grid">
							<div class="promotion-card__content">
								<?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
								<?php if ( $content ) : ?><div class="promotion-card__editor"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php endif; ?>

								<?php if ( $items ) : ?>
									<ul class="promotion-card__items">
										<?php foreach ( $items as $item ) : ?>
											<?php
											$item_text = sanitize_text_field( $item['text'] ?? '' );
											$item_icon = sanitize_key( $item['icon'] ?? 'circle-check' );
											if ( ! $item_text ) {
												continue;
											}
											?>
											<li><span class="promotion-card__item-icon"><?php echo kanapka_theme_ui_icon( $item_icon, 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><span><?php echo esc_html( $item_text ); ?></span></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>

								<?php if ( ! empty( $link['url'] ) ) : ?>
									<?php
									$link_target = '_blank' === ( $link['target'] ?? '' ) ? '_blank' : '';
									$link_title  = sanitize_text_field( $link['title'] ?? '' ) ?: __( 'Learn more', 'kanapka-theme' );
									?>
									<a class="promotion-card__link" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link_target ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $link_title ); ?></a>
								<?php endif; ?>
							</div>

							<?php if ( $image_id ) : ?>
								<div class="promotion-card__media">
									<?php echo wp_get_attachment_image( $image_id, 'large', false, array( 'loading' => 'lazy', 'sizes' => '(max-width: 48rem) 100vw, 60vw' ) ); ?>
								</div>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="promotions-page__legacy-content"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>

		<?php if ( $benefits_enabled ) : ?><?php get_template_part( 'template-parts/front-page/benefits' ); ?><?php endif; ?>
	</main>
	<?php
}

get_footer();
