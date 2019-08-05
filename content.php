<?php
/**
 * @package Saxon
 */

$post_classes = array();

$post_loop_details = saxon_get_post_details();
$post_loop_id = $post_loop_details['post_loop_id'];
$span_class = $post_loop_details['span_class'];

// Blog layouts

# Default
$blog_layout = get_theme_mod('blog_layout', 'standard');

if ( defined('DEMO_MODE') && isset($_GET['blog_layout']) ) {
    $blog_layout = $_GET['blog_layout'];
}

/* Posts loops position for advanced layouts */
if(!isset($post_loop_id)) {
	$post_loop_id = 1;
}

// Sticky post
if(is_sticky(get_the_ID())) {
	$post_classes[] = 'saxon-sticky-post';
}

// Blog layouts that does not have multicolumns - used for related posts display, middle banners display
$blog_layout_plain = array('standard', 'list', 'overlay-list', 'large-list');

// Post Loops Middle Banner - TODO: don't show on 2 col and masonry layouts
if(in_array($blog_layout, $blog_layout_plain)) {
	if($post_loop_id - 1 == floor(get_option('posts_per_page')/2)) {
		saxon_banner_display('posts_loop_middle');
	}
}

?>
<div class="blog-post saxon-block saxon-<?php echo esc_attr($blog_layout); ?>-post"<?php saxon_add_aos(); ?>>
	<article id="post-<?php the_ID(); ?>" <?php post_class($post_classes); ?>>
		<?php
		// Layout: First large then grid
		if($blog_layout == 'large-grid') {

			if($post_loop_id == 1) {

				get_template_part( 'inc/templates/post/content', 'grid-large' );

			} else {

				get_template_part( 'inc/templates/post/content', 'grid' );

			}

		// Layout: First large then grid
		} elseif($blog_layout == 'overlay-grid') {

			if($post_loop_id == 1) {

				get_template_part( 'inc/templates/post/content', 'overlay-short' );

			} else {

				get_template_part( 'inc/templates/post/content', 'grid' );

			}

		// Layout: First large then list
		} elseif($blog_layout == 'large-list') {

			if($post_loop_id == 1) {

				get_template_part( 'inc/templates/post/content', 'grid-large' );

			} else {

				get_template_part( 'inc/templates/post/content', 'list' );

			}

		// Layout: First overlay then list
		} elseif($blog_layout == 'overlay-list') {

			if($post_loop_id == 1) {

				get_template_part( 'inc/templates/post/content', 'overlay-short' );

			} else {

				get_template_part( 'inc/templates/post/content', 'list' );

			}

		// Layout: Grid
		} elseif($blog_layout == 'grid') {

			get_template_part( 'inc/templates/post/content', 'grid' );

		} elseif($blog_layout == 'overlay') {
		// Layout: Overlay

			get_template_part( 'inc/templates/post/content', 'overlay-short' );

		} elseif($blog_layout == 'list') {
		// Layout: List

			get_template_part( 'inc/templates/post/content', 'list' );

		} elseif($blog_layout == 'standard') {
		// Layout: Standard

			get_template_part( 'inc/templates/post/content', 'grid-large' );

		// Layout: Mixed overlays
		} elseif($blog_layout == 'mixed-overlays') {

			if(($post_loop_id - 1) % 5 == 0) {
				get_template_part( 'inc/templates/post/content', 'overlay-short' );

			} else {

				get_template_part( 'inc/templates/post/content', 'overlay' );

			}

		// Layout: Mixed large then grid
		} elseif($blog_layout == 'mixed-large-grid') {
			if(($post_loop_id - 1) % 3 == 0) {
				get_template_part( 'inc/templates/post/content', 'grid-large' );

			} else {

				get_template_part( 'inc/templates/post/content', 'grid' );

			}
		} elseif($blog_layout == 'masonry') {
		// Layout: Masonry

			get_template_part( 'inc/templates/post/content', 'masonry' );

		} else {
		// Layout: Default

			get_template_part( 'inc/templates/post/content', 'grid-large' );

		}

        ?>
	</article>
</div>
<?php if(get_theme_mod('blog_posts_related', false) && in_array($blog_layout, $blog_layout_plain)): ?>
	<?php get_template_part( 'related-posts-loop' ); ?>
<?php endif; ?>
