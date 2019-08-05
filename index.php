<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Saxon
 */

get_header();

?>

<div class="content-block">
	<?php
	// Load homepage layout
	if(is_front_page()) {

		$homepage_blocks = get_theme_mod('homepage_blocks', array());

		// Display at least blog block
		if(count($homepage_blocks) == 0) {
			saxon_block_blog_display();
		}

	    foreach( $homepage_blocks as $block ) {

	    	$block_function_name = 'saxon_block_'.esc_attr($block['block_type']).'_display';

		    // If blog page is paged don't show blocks depending on settings
	    	if(!(is_paged() && $block['block_hide'] == 'yes') || $block['block_type'] == 'blog') {
	    		$block_function_name($block);
	    	}

	    }
	} else {
		// Show just blog if not a homepage
		saxon_block_blog_display();
	}
	?>
</div>
<?php get_footer(); ?>
