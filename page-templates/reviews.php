<?php
/**
 * Template Name: Reviews
 * Template Post Type: page
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) {
	the_post();

	$page_id          = get_the_ID();
	$field            = static fn( $name, $default = '' ) => function_exists( 'get_field' ) ? ( get_field( $name, $page_id ) ?? $default ) : $default;
	$intro            = sanitize_text_field( $field( 'kanapka_reviews_intro', '' ) ) ?: __( 'Your feedback helps us improve. Thank you for choosing Kanapka!', 'kanapka-theme' );
	$per_page         = min( 20, max( 1, absint( $field( 'kanapka_reviews_per_page', 8 ) ) ) );
	$form_enabled     = (bool) $field( 'kanapka_reviews_form_enabled', true );
	$benefits_enabled = (bool) $field( 'kanapka_reviews_benefits_enabled', true );
	$current_page     = max( 1, absint( get_query_var( 'cpage', 1 ) ) );
	$total_reviews    = (int) get_comments(
		array(
			'post_id' => $page_id,
			'status'  => 'approve',
			'parent'  => 0,
			'count'   => true,
		)
	);
	$reviews          = get_comments(
		array(
			'post_id' => $page_id,
			'status'  => 'approve',
			'parent'  => 0,
			'number'  => $per_page,
			'offset'  => ( $current_page - 1 ) * $per_page,
			'orderby' => 'comment_date_gmt',
			'order'   => 'DESC',
		)
	);
	$all_review_ids   = get_comments(
		array(
			'post_id' => $page_id,
			'status'  => 'approve',
			'parent'  => 0,
			'fields'  => 'ids',
			'number'  => 0,
		)
	);
	$average_rating   = $all_review_ids ? array_sum( array_map( 'kanapka_theme_review_rating', $all_review_ids ) ) / count( $all_review_ids ) : 0;
	?>
	<main id="main-content" class="site-main reviews-page">
		<div class="container reviews-page__breadcrumbs"><?php kanapka_theme_breadcrumb(); ?></div>

		<header class="container reviews-page__header">
			<div>
				<h1><?php the_title(); ?></h1>
				<p><?php echo esc_html( $intro ); ?></p>
			</div>
			<?php if ( $total_reviews ) : ?>
				<div class="reviews-summary" aria-label="<?php esc_attr_e( 'Average rating', 'kanapka-theme' ); ?>">
					<span class="reviews-summary__star" aria-hidden="true">★</span>
					<strong><?php echo esc_html( number_format_i18n( $average_rating, 1 ) ); ?></strong>
					<span><?php esc_html_e( 'Average rating', 'kanapka-theme' ); ?><small><?php echo esc_html( sprintf( _n( 'Based on %d review', 'Based on %d reviews', $total_reviews, 'kanapka-theme' ), $total_reviews ) ); ?></small></span>
				</div>
			<?php endif; ?>
		</header>

		<section class="container reviews-list" aria-label="<?php esc_attr_e( 'Customer reviews', 'kanapka-theme' ); ?>">
			<?php foreach ( $reviews as $review ) : ?>
				<?php
				$replies = get_comments(
					array(
						'post_id' => $page_id,
						'status'  => 'approve',
						'parent'  => $review->comment_ID,
						'orderby' => 'comment_date_gmt',
						'order'   => 'ASC',
					)
				);
				?>
				<article class="review-card" id="comment-<?php echo esc_attr( $review->comment_ID ); ?>">
					<div class="review-card__author">
						<span class="review-card__avatar review-avatar--<?php echo esc_attr( absint( crc32( $review->comment_author ) ) % 5 ); ?>" aria-hidden="true"><?php echo esc_html( kanapka_theme_review_initials( $review->comment_author ) ); ?></span>
						<div><strong><?php echo esc_html( $review->comment_author ); ?></strong><time datetime="<?php echo esc_attr( get_comment_date( DATE_W3C, $review ) ); ?>"><?php echo esc_html( get_comment_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $review ) ); ?></time><?php echo kanapka_theme_review_stars( kanapka_theme_review_rating( $review->comment_ID ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					</div>
					<div class="review-card__content"><?php echo wp_kses_post( wpautop( $review->comment_content ) ); ?></div>
					<div class="review-card__action"><?php comment_reply_link( array( 'reply_text' => __( 'Reply', 'kanapka-theme' ), 'depth' => 1, 'max_depth' => 2 ), $review ); ?></div>

					<?php foreach ( $replies as $reply ) : ?>
						<div class="review-reply" id="comment-<?php echo esc_attr( $reply->comment_ID ); ?>">
							<span class="review-reply__avatar review-avatar--<?php echo esc_attr( absint( crc32( $reply->comment_author ) ) % 5 ); ?>" aria-hidden="true"><?php echo esc_html( kanapka_theme_review_initials( $reply->comment_author ) ); ?></span>
							<div><div class="review-reply__meta"><strong><?php echo esc_html( $reply->comment_author ); ?></strong><time datetime="<?php echo esc_attr( get_comment_date( DATE_W3C, $reply ) ); ?>"><?php echo esc_html( get_comment_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $reply ) ); ?></time></div><?php echo wp_kses_post( wpautop( $reply->comment_content ) ); ?></div>
						</div>
					<?php endforeach; ?>
				</article>
			<?php endforeach; ?>
		</section>

		<?php if ( $total_reviews > $per_page ) : ?>
			<nav class="container reviews-pagination" aria-label="<?php esc_attr_e( 'Reviews pagination', 'kanapka-theme' ); ?>"><?php echo wp_kses_post( paginate_comments_links( array( 'total' => (int) ceil( $total_reviews / $per_page ), 'current' => $current_page, 'echo' => false, 'prev_text' => kanapka_theme_ui_icon( 'chevron-left', 18 ), 'next_text' => kanapka_theme_ui_icon( 'chevron-right', 18 ), 'type' => 'list' ) ) ); ?></nav>
		<?php endif; ?>

		<?php if ( $form_enabled && comments_open() ) : ?>
			<section class="container reviews-form-section" id="respond">
				<?php
				$rating_field = '<fieldset class="review-rating-field"><legend>' . esc_html__( 'Your rating', 'kanapka-theme' ) . '</legend><div class="review-rating-field__stars">';
				for ( $rating = 5; $rating >= 1; $rating-- ) {
					$rating_field .= '<input type="radio" id="review-rating-' . $rating . '" name="kanapka_review_rating" value="' . $rating . '"' . checked( 5, $rating, false ) . '><label for="review-rating-' . $rating . '" aria-label="' . esc_attr( sprintf( __( '%d stars', 'kanapka-theme' ), $rating ) ) . '">★</label>';
				}
				$rating_field .= '</div></fieldset>';

				comment_form(
					array(
						'title_reply'          => __( 'Leave a review', 'kanapka-theme' ),
						'title_reply_to'       => __( 'Reply to %s', 'kanapka-theme' ),
						'cancel_reply_link'    => __( 'Cancel reply', 'kanapka-theme' ),
						'label_submit'         => __( 'Submit review', 'kanapka-theme' ),
						'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Your review helps us improve.', 'kanapka-theme' ) . '</p>',
						'comment_notes_after'  => '',
						'fields'               => array(
							'author'  => '<p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'kanapka-theme' ) . ' *</label><input id="author" name="author" type="text" autocomplete="name" required></p>',
							'email'   => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email' ) . ' *</label><input id="email" name="email" type="email" autocomplete="email" required></p>',
							'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"><label for="wp-comment-cookies-consent">' . esc_html__( 'Save my details for the next review.', 'kanapka-theme' ) . '</label></p>',
						),
						'comment_field'        => $rating_field . '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'kanapka-theme' ) . ' *</label><textarea id="comment" name="comment" rows="6" required></textarea></p>',
						'class_submit'         => 'button reviews-form__submit',
						'format'               => 'html5',
					),
					$page_id
				);
				?>
			</section>
		<?php endif; ?>

		<?php if ( $benefits_enabled ) : ?><?php get_template_part( 'template-parts/front-page/benefits' ); ?><?php endif; ?>
	</main>
	<?php
}

get_footer();
