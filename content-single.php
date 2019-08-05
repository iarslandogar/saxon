<?php
/**
 * Single blog post page
 *
 * @package Saxon
 */

$current_post_format = get_post_format($post->ID);

$post_formats_media = array('audio', 'video', 'gallery');

$post_id = get_the_ID();

// Post settings
$post_sidebarposition = get_post_meta( get_the_ID(), '_saxon_post_sidebar_position', true );
$post_social_disable = get_post_meta( get_the_ID(), '_saxon_post_social_disable', true );
$post_image_disable = get_post_meta( get_the_ID(), '_saxon_post_image_disable', true );

// Post sidebar position
if(!isset($post_sidebarposition) || ($post_sidebarposition == '')) {
	$post_sidebarposition = 0;
}

if($post_sidebarposition == "0") {
	$post_sidebarposition = get_theme_mod('sidebar_post', 'disable');
}

// Demo settings
if ( defined('DEMO_MODE') && isset($_GET['sidebar_post']) ) {
  $post_sidebarposition = $_GET['sidebar_post'];
}

if(is_active_sidebar( 'post-sidebar' ) && ($post_sidebarposition !== 'disable') ) {
	$span_class = 'col-md-8';

	saxon_set_content_width(750);
}
else {
	$span_class = 'col-md-12 post-single-content';

	if(get_theme_mod('blog_post_smallwidth', false)) {
		saxon_set_content_width(850);
	}
}

// Post media
$post_embed_video = get_post_meta( $post_id, '_saxon_video_embed', true );

if($post_embed_video !== '') {

	$post_embed_video_output = wp_oembed_get($post_embed_video);
} else {
	$post_embed_video_output = '';
}

$post_embed_audio = get_post_meta( $post_id, '_saxon_audio_embed', true );

if($post_embed_audio !== '') {

	$post_embed_audio_output = wp_oembed_get($post_embed_audio);

} else {
	$post_embed_audio_output = '';
}

// Gallery post type
$post_embed_gallery_output = '';

if($current_post_format == 'gallery') {

	$gallery_images_data = saxon_cmb2_get_images_src( $post_id, '_saxon_gallery_file_list', 'saxon-blog-thumb' );

	if(count($gallery_images_data) > 0) {

		$post_gallery_id = 'blog-post-gallery-'.$post_id;
		$post_embed_gallery_output = '<div class="blog-post-gallery-wrapper owl-carousel" id="'.$post_gallery_id.'" style="display: none;">';

		$post_title = the_title_attribute(array('echo' => false));

		foreach ($gallery_images_data as $gallery_image) {
			$post_embed_gallery_output .= '<div class="blog-post-gallery-image"><a href="'.esc_url($gallery_image).'" rel="lightbox" title="'.esc_attr($post_title).'"><img src="'.esc_url($gallery_image).'" alt="'.esc_attr($post_title).'"/></a></div>';
		}

		$post_embed_gallery_output .= '</div>';

		wp_add_inline_script( 'saxon-script', '(function($){
	            $(document).ready(function() {

	            	"use strict";

	                $("#'.esc_js($post_gallery_id).'").owlCarousel({
	                    items: 1,
	                    autoplay: true,
	                    autowidth: false,
	                    autoplayTimeout:2000,
	                    autoplaySpeed: 1000,
	                    navSpeed: 1000,
	                    nav: true,
	                    dots: false,
	                    loop: true,
	                    navText: false,
	                    responsive: {
	                        1199:{
	                            items:1
	                        },
	                        979:{
	                            items:1
	                        },
	                        768:{
	                            items:1
	                        },
	                        479:{
	                            items:1
	                        },
	                        0:{
	                            items:1
	                        }
	                    }
	                });

	            });})(jQuery);');

	}
}

// Header image
$header_background_image = get_post_meta( $post_id, '_saxon_header_image', true );

if(isset($header_background_image) && ($header_background_image!== '')) {
	$header_background_image_style = 'background-image: url('.$header_background_image.');';
	$header_background_class = ' with-bg';

} else {
	$header_background_image_style = '';
	$header_background_class = '';
}

// Header width
$blog_post_header_width = get_theme_mod('blog_post_header_width', 'fullwidth');

// Demo settings
if ( defined('DEMO_MODE') && isset($_GET['blog_post_header_width']) ) {
	$blog_post_header_width = $_GET['blog_post_header_width'];
}

if($blog_post_header_width == 'fullwidth') {
	$container_class = 'container-fluid';
} else {
	$container_class = 'container';
}

// Don't allow boxed header for transparent image
$blog_post_transparent_header = get_theme_mod('blog_post_transparent_header', false);

// Demo settings
if ( defined('DEMO_MODE') && isset($_GET['blog_post_transparent_header']) ) {
	if($_GET['blog_post_transparent_header'] == 0) {
	  $blog_post_transparent_header = false;
	}
	if($_GET['blog_post_transparent_header'] == 1) {
	  $blog_post_transparent_header = true;
	}
}

if($header_background_class !== '' && $blog_post_transparent_header) {
	$container_class = 'container-fluid';
}
?>

<div class="content-block">
<div class="<?php echo esc_attr($container_class); ?> container-page-item-title<?php echo esc_attr($header_background_class); ?>" data-style="<?php echo esc_attr($header_background_image_style); ?>"<?php saxon_add_aos(true);?>>
	<div class="row">
		<div class="col-md-12 col-overlay">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="page-item-title-single">
							<?php
							$categories_list = saxon_get_the_category_list();
							?>
							<div class="saxon-post-single saxon-post"<?php saxon_add_aos(true);?>>
								<div class="post-categories"><?php echo wp_kses($categories_list, saxon_esc_data()); ?></div>
								<div class="saxon-post-details">
									<h1 class="post-title"><?php the_title(); ?></h1>
									<?php
									if(get_theme_mod('blog_posts_author', false)):
									?>
									<div class="post-author">
										<div class="post-author-image"><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ))); ?>"><?php echo get_avatar( get_the_author_meta('email'), '28', '28' ); ?></a></div><?php echo esc_html__('By', 'saxon'); ?> <?php echo get_the_author_posts_link(); ?>
									</div>
									<?php
									endif;
									?>
									<div class="post-date"><?php echo get_the_time( get_option( 'date_format' ) ); ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="post-container container <?php echo esc_attr('span-'.$span_class); ?>">
	<div class="row">
<?php if ( is_active_sidebar( 'post-sidebar' ) && ( $post_sidebarposition == 'left')) : ?>
		<div class="col-md-4 post-sidebar sidebar sidebar-left" role="complementary">
		<ul id="post-sidebar">
		  <?php dynamic_sidebar( 'post-sidebar' ); ?>
		</ul>
		</div>
		<?php endif; ?>
		<div class="<?php echo esc_attr($span_class); ?>">
			<div class="blog-post blog-post-single clearfix">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="main">
					<div class="post-content-wrapper">

						<div class="post-content clearfix">
							<?php
							if ( has_post_thumbnail()&&!in_array($current_post_format, $post_formats_media)&&(!$post_image_disable ) && (get_theme_mod('blog_post_featured_image', true) == true )):

							?>
							<div class="blog-post-thumb">

								<?php the_post_thumbnail('saxon-blog-thumb'); ?>

							</div>
							<?php endif; ?>
							<?php

							if(in_array($current_post_format, $post_formats_media) && (wp_kses($post_embed_video_output, saxon_esc_data()) !== '' || wp_kses($post_embed_audio_output, saxon_esc_data()) !== '' || wp_kses_post($post_embed_gallery_output) !== '')) {
								echo '<div class="blog-post-thumb blog-post-thumb-media">';

							// Post media
							if($current_post_format == 'video') {
								echo '<div class="blog-post-media blog-post-media-video">';
								echo wp_kses($post_embed_video_output, saxon_esc_data());
								echo '</div>';
							}
							elseif($current_post_format == 'audio') {
								echo '<div class="blog-post-media blog-post-media-audio">';
								echo wp_kses($post_embed_audio_output, saxon_esc_data());
								echo '</div>';
							}
							elseif($current_post_format == 'gallery') {
								echo '<div class="blog-post-media blog-post-media-gallery">';
								echo wp_kses_post($post_embed_gallery_output);
								echo '</div>';
							}
								echo '</div>';
							}
							?>
							<?php
							// Single Blog Post page Top banner
							saxon_banner_display('single_post_top');
							?>
							<?php if ( is_search() ) : // Only display Excerpts for Search ?>
							<div class="entry-summary">
								<?php the_excerpt(); ?>
							</div><!-- .entry-summary -->
							<?php else : ?>
							<div class="entry-content">

							<?php the_content('<div class="more-link">'.esc_html__( 'View more', 'saxon' ).'</div>' ); ?>

							<?php

								wp_link_pages( array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'saxon' ),
									'after'  => '</div>',
								) );
							?>
							</div><!-- .entry-content -->

							<?php if(get_theme_mod('blog_post_share_fixed', false)): ?>
							<div class="saxon-social-share-fixed sidebar-position-<?php echo esc_attr($post_sidebarposition); ?>">
								<?php do_action('saxon_social_share'); // this action called from plugin ?>
							</div>
							<?php endif; ?>

							<?php
							// Single Blog Post page Bottom banner
							saxon_banner_display('single_post_bottom');
							?>

							<?php endif; ?>
							</div>

					</div>
				</article>

				<?php if(get_theme_mod('blog_post_info_bottom', true) == true): ?>
				<div class="saxon-post saxon-post-bottom">
					<div class="post-details-bottom">
						<div class="post-info-tags">
							<?php if(get_theme_mod('blog_post_tags', true) == true): ?>
							<?php
								/* translators: used between list items, there is a space after the comma */
								$tags_list = get_the_tag_list( '', ' ' );
								if ( $tags_list ) :
							?>
							<div class="tags clearfix">
								<?php echo wp_kses_post($tags_list); ?>
							</div>
							<?php endif; // End if $tags_list ?>
							<?php endif; ?>
						</div>
						<?php
						// Show social share?
						$show_social_share = false;

						if(get_theme_mod('blog_post_share', true)) {
							if(function_exists('saxon_social_share_links')) {
								if(!isset($post_socialshare_disable) || !$post_socialshare_disable) {
									$show_social_share = true;
								}
							}
						}
						?>

						<div class="post-info-wrapper<?php if(!$show_social_share) { echo ' social-share-disabled'; } ?>">
						<?php if(get_theme_mod('blog_post_comments', true) == true): ?>
						<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
						<div class="post-info-comments"><i class="fa fa-comment-o" aria-hidden="true"></i><a href="<?php echo esc_url(get_comments_link( $post->ID )); ?>"><?php echo wp_kses(get_comments_number_text( esc_html__('0', 'saxon'), esc_html__('1', 'saxon'), esc_html__('%', 'saxon') ), saxon_esc_data()); ?></a></div>
						<?php endif; ?>
						<?php endif; ?>

						<?php if(get_theme_mod('blog_post_views', true) && function_exists('saxon_post_views_display')): ?>
						<div class="post-info-views"><?php do_action('saxon_post_views'); // this action called from plugin ?></div>
						<?php endif; ?>
						<?php if(get_theme_mod('blog_post_likes', false) && function_exists('saxon_post_likes_display')): ?>
						<div class="post-info-likes"><?php do_action('saxon_post_likes'); // this action called from plugin ?></div>
						<?php endif; ?>


						</div>

						<?php if($show_social_share): ?>
						<div class="post-info-share">
						  <?php do_action('saxon_social_share'); // this action called from plugin ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<?php if(get_theme_mod('blog_post_author', true) == true): ?>
				<?php if ( is_single() && get_the_author_meta( 'description' ) ) : ?>
					<?php get_template_part( 'author-bio' ); ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php
			if(get_theme_mod('blog_post_nav', true) == true) {
				saxon_content_nav( 'nav-below' );
			}
			?>

			<?php if(get_theme_mod('blog_post_related', false) == true): ?>
			<?php get_template_part( 'related-posts-loop' ); ?>
			<?php endif; ?>


			<?php
			if(get_theme_mod('blog_post_subscribe', false)) {
				saxon_block_subscribe_display();
			}
			?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )

					comments_template();
			?>

		</div>
		<?php if ( is_active_sidebar( 'post-sidebar' ) && ( $post_sidebarposition == 'right')) : ?>
		<div class="col-md-4 post-sidebar sidebar sidebar-right" role="complementary">
		<ul id="post-sidebar">
		  <?php dynamic_sidebar( 'post-sidebar' ); ?>
		</ul>
		</div>
		<?php endif; ?>
	</div>
	</div>
</div>
<?php
// Worth reading posts
if(get_theme_mod('blog_post_worthreading', false)) {

	// Get worth reading posts
	$post_worthreading_posts = get_post_meta( $post_id, '_saxon_worthreading_posts', true );

	if(!empty($post_worthreading_posts)) {

		$post_worthreading_post_count = count($post_worthreading_posts);

		$post_worthreading_post_arr_id = rand(0, $post_worthreading_post_count-1);

		$post_worthreading_post_id = $post_worthreading_posts[$post_worthreading_post_arr_id];

		$post_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_worthreading_post_id ), 'saxon-blog-thumb-grid');

		if(has_post_thumbnail( $post_worthreading_post_id )) {
	        $image_bg ='background-image: url('.$post_image[0].');';
	        $post_image_html ='<div class="saxon-post-image-wrapper"><a href="'.esc_url(get_permalink($post_worthreading_post_id)).'"><div class="saxon-post-image" data-style="'.esc_attr($image_bg).'"></div></a></div>';
	    }
	    else {
	        $image_bg = '';
	        $post_image_html ='';
	    }

	    $post_title = the_title_attribute(array('echo' => false, 'post' => $post_worthreading_post_id));

		echo '<div class="post-worthreading-post-wrapper">';
		echo '<div class="post-worthreading-post-button"><i class="fa fa-file-text-o" aria-hidden="true"></i><a href="'.esc_url(get_permalink($post_worthreading_post_id)).'">'.esc_html__('Worth reading...', 'saxon').'</a></div>';
		echo '<div class="post-worthreading-post-container">';
		echo '<div class="post-worthreading-post saxon-post">';
		echo wp_kses($post_image_html, saxon_esc_data());
		echo '<div class="post-worthreading-post-title"><a href="'.esc_url(get_permalink($post_worthreading_post_id)).'">'.esc_html($post_title).'</a></div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';

	}

}
?>
