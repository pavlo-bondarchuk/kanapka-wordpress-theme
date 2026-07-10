<?php
/**
 * Default template.
 *
 * @package Kanapka_Theme
 */

get_header();
?>
<main id="main-content" class="site-main container">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			?>
			<article <?php post_class( 'content-entry' ); ?>>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>
			<?php
		}
	}
	?>
</main>
<?php
get_footer();

