<?php
/**
 * Reviews page helpers.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return a validated review rating.
 *
 * Legacy reviews did not store a rating and are treated as five-star reviews.
 *
 * @param int $comment_id Comment ID.
 * @return int
 */
function kanapka_theme_review_rating( $comment_id ) {
	$rating = absint( get_comment_meta( $comment_id, 'kanapka_review_rating', true ) );

	return $rating >= 1 && $rating <= 5 ? $rating : 5;
}

/**
 * Render five review stars.
 *
 * @param int $rating Rating from one to five.
 * @return string
 */
function kanapka_theme_review_stars( $rating ) {
	$rating = min( 5, max( 1, absint( $rating ) ) );
	$html   = '<span class="review-stars" aria-label="' . esc_attr( sprintf( __( '%d out of 5 stars', 'kanapka-theme' ), $rating ) ) . '">';

	for ( $star = 1; $star <= 5; $star++ ) {
		$html .= '<span class="review-stars__star' . ( $star <= $rating ? ' is-active' : '' ) . '" aria-hidden="true">★</span>';
	}

	return $html . '</span>';
}

/**
 * Build short initials for the local review avatar.
 *
 * @param string $name Author name.
 * @return string
 */
function kanapka_theme_review_initials( $name ) {
	$words    = preg_split( '/\s+/u', trim( wp_strip_all_tags( $name ) ) );
	$initials = '';

	foreach ( array_slice( array_filter( (array) $words ), 0, 2 ) as $word ) {
		$initials .= function_exists( 'mb_substr' ) ? mb_substr( $word, 0, 1 ) : substr( $word, 0, 1 );
	}

	return $initials ?: '?';
}

/**
 * Save the rating submitted with a review.
 *
 * @param int $comment_id Comment ID.
 */
function kanapka_theme_save_review_rating( $comment_id ) {
	$comment = get_comment( $comment_id );

	if ( ! $comment || 'page-templates/reviews.php' !== get_page_template_slug( $comment->comment_post_ID ) || $comment->comment_parent ) {
		return;
	}

	$rating = isset( $_POST['kanapka_review_rating'] ) ? absint( wp_unslash( $_POST['kanapka_review_rating'] ) ) : 5;
	update_comment_meta( $comment_id, 'kanapka_review_rating', min( 5, max( 1, $rating ) ) );
}
add_action( 'comment_post', 'kanapka_theme_save_review_rating' );
