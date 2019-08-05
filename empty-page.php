<?php
/**
 * Template Name: No Header/Footer
 *
 * @package Saxon
 */

get_header('empty');

?>

<?php while ( have_posts() ) : the_post(); ?>
<div class="empty-page" role="main">
	<?php get_template_part( 'content', 'page' ); ?>
</div>
<?php endwhile; // end of the loop. ?>

<?php get_footer('empty'); ?>
