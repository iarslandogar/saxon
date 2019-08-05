<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Saxon
 */

get_header();

$search_sidebarposition = get_theme_mod('sidebar_search', 'right');

if(is_active_sidebar( 'main-sidebar' ) && ($search_sidebarposition !== 'disable') ) {
	$span_class = 'col-md-8';
	$is_sidebar = true;
}
else {
	$span_class = 'col-md-12';
	$is_sidebar = false;
}

// Blog layout
$blog_layout = get_theme_mod('blog_layout', 'standard');

if ( defined('DEMO_MODE') && isset($_GET['blog_layout']) ) {
    $blog_layout = $_GET['blog_layout'];
}

// Load masonry layout script
if($blog_layout == 'masonry') {

wp_enqueue_script('masonry');
wp_add_inline_script( 'masonry', '(function($){
$(document).ready(function() {
	"use strict";
	$(window).load(function() {
		var $container = $(".blog-layout-masonry");
		$container.imagesLoaded(function(){
		  $container.masonry({
		    itemSelector : ".blog-layout-masonry .blog-post"
		  });
		});

		AOS.refresh();
	});

});})(jQuery);');

}

?>
<div class="content-block">
	<div class="container-fluid container-page-item-title">
		<div class="row">
		<div class="col-md-12">
			<div class="page-item-title-archive page-item-title-single"<?php saxon_add_aos(true);?>>
			<?php
				echo '<p>'.esc_html__( 'Search Results', 'saxon' ).'</p>';
				echo '<h1 class="page-title">' . get_search_query() . '</h1>';
				echo '<div class="category-posts-count">'.esc_html($wp_query->found_posts).' '.esc_html__('posts found', 'saxon').'</div>';
			?>
			</div>
		</div>
		</div>
	</div>
<div class="container page-container">
<div class="row">

<?php if ( is_active_sidebar( 'main-sidebar' ) && ( $search_sidebarposition == 'left')) : ?>
		<div class="col-md-4 main-sidebar sidebar sidebar-left"<?php saxon_add_aos(true);?> role="complementary">
		<ul id="main-sidebar">
		  <?php dynamic_sidebar( 'main-sidebar' ); ?>
		</ul>
		</div>
		<?php endif; ?>
		<div class="<?php echo esc_attr($span_class); ?>">
		<div class="blog-posts-list blog-layout-<?php echo esc_attr($blog_layout);?><?php echo esc_attr(saxon_get_blog_col_class($blog_layout, $is_sidebar));?>" id="content" role="main">
		<?php /* Start the Loop */ ?>
				<?php if ( have_posts() ) : ?>
					<?php /* Start the Loop */
					$post_loop_id = 1;
					?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php

						$post_loop_details['post_loop_id'] = $post_loop_id;
						$post_loop_details['span_class'] = $span_class;

						saxon_set_post_details($post_loop_details);

						?>

						<?php get_template_part( 'content', 'search' );

						$post_loop_id++;
						?>

					<?php endwhile; ?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'search' ); ?>

				<?php endif; ?>
		</div>
		<?php
		// Post Loops Bottom Banner
		saxon_banner_display('posts_loop_bottom');
		?>
		<?php saxon_content_nav( 'nav-below' ); ?>
		</div>
		<?php if ( is_active_sidebar( 'main-sidebar' ) && ( $search_sidebarposition == 'right')) : ?>
		<div class="col-md-4 main-sidebar sidebar sidebar-right"<?php saxon_add_aos(true);?> role="complementary">
		<ul id="main-sidebar">
		  <?php dynamic_sidebar( 'main-sidebar' ); ?>
		</ul>
		</div>
		<?php endif; ?>
	</div>
</div>
</div>
<?php get_footer(); ?>
