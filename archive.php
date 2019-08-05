<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Saxon
 */

get_header();

$archive_sidebarposition = get_theme_mod('sidebar_archive', 'right');

if(is_active_sidebar( 'main-sidebar' ) && ($archive_sidebarposition !== 'disable') ) {
	$span_class = 'col-md-8';
	$is_sidebar = true;
}
else {
	$span_class = 'col-md-12';
	$is_sidebar = false;
}

// Blog layout
$blog_layout = get_theme_mod('blog_layout', 'standard');

// Demo settings
if ( defined('DEMO_MODE') && isset($_GET['blog_layout']) ) {
    $blog_layout = $_GET['blog_layout'];
}

// Load masonry layout script
if($blog_layout == 'masonry') {

wp_enqueue_script('masonry');
wp_add_inline_script( 'masonry', '(function($){
$(document).ready(function() {
	"use strict";

	$(window).load(function()
    {
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

// Get current category image
$header_background_image_style = '';
$header_background_class = '';

if ( is_category() ) {

	$category = get_category( get_query_var( 'cat' ) );
	$category_posts = $category->category_count;

	if(isset($category->cat_ID)) {

		// Get the image  for the category
		$category_image = get_term_meta( $category->cat_ID, '_saxon_category_image', true );

		$header_background_image = $category_image;

		if(isset($header_background_image) && ($header_background_image !== '')) {
		  $header_background_image_style = 'background-image: url('.$header_background_image.');';
		  $header_background_class = ' with-bg';
		}
	}
}

// Header width
if(get_theme_mod('blog_header_width', 'fullwidth') == 'fullwidth') {
	$container_class = 'container-fluid';
} else {
	$container_class = 'container';
}
?>
<div class="content-block">
<div class="<?php echo esc_attr($container_class); ?> container-page-item-title<?php echo esc_attr($header_background_class); ?>" data-style="<?php echo esc_attr($header_background_image_style); ?>"<?php saxon_add_aos(true);?>>
	<div class="row">
		<div class="col-md-12 col-overlay">
			<div class="page-item-title-archive page-item-title-single">

			      <?php
					if ( is_category() ) :
						echo '<p>'.esc_html__( 'Browsing category', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . single_cat_title( '', false ) . '</h1>';
						echo '<div class="category-posts-count">'.esc_html($category_posts).' '.esc_html__('posts', 'saxon').'</div>';

					elseif ( is_tag() ) :

						echo '<p>'.esc_html__( 'Browsing tag', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . single_tag_title( '', false ) . '</h1>';

					elseif ( is_author() ) :
						/* Queue the first post, that way we know
						 * what author we're dealing with (if that is the case).
						*/
						the_post();

						echo '<p>'.esc_html__( 'Author', 'saxon' ).'</p>';
						echo '<div class="author-avatar">'.get_avatar( get_the_author_meta('email'), '100' ).'</div>';
						echo '<h1 class="page-title">' . '<span class="vcard">' . get_the_author() . '</span>' . '</h1>';
						echo '<div class="category-posts-count">'.count_user_posts( get_the_author_meta('ID') ).' '.esc_html__('posts', 'saxon').'</div>';

						rewind_posts();

					elseif ( is_day() ) :
						echo '<p>'.esc_html__( 'Daily Archives', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . get_the_date() . '</h1>';

					elseif ( is_month() ) :
						echo '<p>'.esc_html__( 'Monthly Archives', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . get_the_date( 'F Y' ) . '</h1>';

					elseif ( is_year() ) :
						echo '<p>'.esc_html__( 'Yearly Archives', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . get_the_date( 'Y' ) . '</h1>';

					elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
						echo '<p>'.esc_html__( 'Post format', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . esc_html_e( 'Aside', 'saxon' ) . '</h1>';

					elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
						echo '<p>'.esc_html__( 'Post format', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . esc_html_e( 'Images', 'saxon' ) . '</h1>';

					elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
						echo '<p>'.esc_html__( 'Post format', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . esc_html_e( 'Videos', 'saxon' ) . '</h1>';

					elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
						echo '<p>'.esc_html__( 'Post format', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . esc_html_e( 'Quotes', 'saxon' ) . '</h1>';

					elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
						echo '<p>'.esc_html__( 'Post format', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . esc_html_e( 'Links', 'saxon' ) . '</h1>';

					else :
						echo '<p>'.esc_html__( 'Posts', 'saxon' ).'</p>';
						echo '<h1 class="page-title">' . esc_html_e( 'Archives', 'saxon' ) . '</h1>';

					endif;
				?>

			</div>
		</div>
	</div>
</div>
<div class="container page-container">
	<div class="row">
<?php if ( is_active_sidebar( 'main-sidebar' ) && ( $archive_sidebarposition == 'left')) : ?>
		<div class="col-md-4 main-sidebar sidebar sidebar-left"<?php saxon_add_aos(true);?> role="complementary">
		<ul id="main-sidebar">
		  <?php dynamic_sidebar( 'main-sidebar' ); ?>
		</ul>
		</div>
		<?php endif; ?>
		<div class="<?php echo esc_attr($span_class); ?>">
		<?php
			if ( is_category() ) :
				// show an optional category description
				$category_description = category_description();
				if ( ! empty( $category_description ) ) :
					echo '<div class="container-fluid taxonomy-description-container"'.saxon_add_aos(false).'>
			'.apply_filters( 'category_archive_meta', '<div class="taxonomy-description">' . wp_kses_post($category_description) . '</div>' ).'
				</div>';
				endif;

			elseif ( is_tag() ) :
				// show an optional tag description
				$tag_description = tag_description();
				if ( ! empty( $tag_description ) ) :
					echo '<div class="container-fluid category-description-container"'.saxon_add_aos(false).'>
			'.apply_filters( 'tag_archive_meta', '<div class="category-description">' . wp_kses_post($tag_description) . '</div>' ).'
				</div>';
				endif;

			endif;
		?>
		<div class="blog-posts-list blog-layout-<?php echo esc_attr($blog_layout);?><?php echo esc_attr(saxon_get_blog_col_class($blog_layout, $is_sidebar));?>" id="content" role="main">

			<?php if ( have_posts() ) : ?>
				<?php /* Start the Loop */
				$post_loop_id = 1;
				?>
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php

						$post_loop_details['post_loop_id'] = $post_loop_id;
						$post_loop_details['span_class'] = $span_class;

						saxon_set_post_details($post_loop_details);

						get_template_part( 'content', get_post_format() );

						$post_loop_id++;
					?>

				<?php endwhile; ?>



			<?php else : ?>

				<?php get_template_part( 'no-results', 'archive' ); ?>

			<?php endif; ?>
		</div>
		<?php
		// Post Loops Bottom Banner
		saxon_banner_display('posts_loop_bottom');
		?>
		<?php saxon_content_nav( 'nav-below' ); ?>
		</div>
		<?php if ( is_active_sidebar( 'main-sidebar' ) && ( $archive_sidebarposition == 'right')) : ?>
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
