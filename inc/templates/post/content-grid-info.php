<?php
/**
 * Post template: Grid Info
 */

?>
<?php

$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'saxon-blog-thumb-grid');

if(has_post_thumbnail( $post->ID )) {
    $image_bg ='background-image: url('.$image[0].');';
    $post_class = '';
}
else {
    $image_bg = '';
    $post_class = ' saxon-post-no-image';
}

$categories_list = saxon_get_the_category_list( $post->ID );

// Show post format
$current_post_format = get_post_format($post->ID);
$post_format_icon = '';

if(in_array($current_post_format, saxon_get_mediaformats())) {
    $post_format_icon = '<div class="saxon-post-format-icon"></div>';
}

$post_class .= ' format-'.$current_post_format;

echo '<div class="saxon-grid-post saxon-grid-info-post saxon-post'.esc_attr($post_class).'"'.saxon_add_aos(false).'>';

if(has_post_thumbnail( $post->ID )) {
  echo '<div class="saxon-post-image-wrapper"><a href="'.esc_url(get_permalink($post->ID)).'"><div class="saxon-post-image" data-style="'.esc_attr($image_bg).'">'.wp_kses_post($post_format_icon).'</div></a><div class="post-categories">'.wp_kses($categories_list, saxon_esc_data()).'</div></div>';
} else {
  echo '<div class="post-categories">'.wp_kses($categories_list, saxon_esc_data()).'</div>';
}

// Post details
echo '<div class="saxon-post-details">

     <h3 class="post-title"><a href="'.esc_url(get_permalink($post->ID)).'">'.wp_kses_post($post->post_title).'</a></h3>';
if(get_theme_mod('blog_posts_author', false)):
?>
<div class="post-author">
    <div class="post-author-image">
        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ))); ?>"><?php echo get_avatar( get_the_author_meta('email'), '28', '28' ); ?></a></div><?php echo esc_html__('By', 'saxon'); ?> <?php echo get_the_author_posts_link(); ?>
</div>
<?php
endif;

echo '<div class="post-date">'.get_the_time( get_option( 'date_format' ), $post->ID ).'</div>';

echo '</div>';
// END - Post details
?>
<?php if(get_theme_mod('blog_posts_excerpt', 'content') !== 'none'): ?>
<?php echo '<div class="post-excerpt">'.wp_kses_post( saxon_get_the_excerpt(275, $post->ID) ).'</div>'; ?>
<?php endif; ?>

<?php if(!get_theme_mod('blog_posts_comments', true) && !get_theme_mod('blog_posts_views', false) && !get_theme_mod('blog_posts_share', true)):
// show nothing
else:
?>

<div class="post-details-bottom">

<div class="post-info-wrapper">

<?php if(get_theme_mod('blog_posts_comments', true)):?>
<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
<div class="post-info-comments"><i class="fa fa-comment-o" aria-hidden="true"></i><a href="<?php echo esc_url(get_comments_link( $post->ID )); ?>"><?php echo wp_kses(get_comments_number_text(esc_html__('0', 'saxon'), esc_html__('1', 'saxon'), esc_html__('%', 'saxon') ), saxon_esc_data()); ?></a></div>
<?php endif; ?>
<?php endif; ?>

<?php if(get_theme_mod('blog_posts_views', false) && function_exists('saxon_post_views_display')): ?>
<div class="post-info-views"><?php do_action('saxon_post_views'); // this action called from plugin ?></div>
<?php endif; ?>
<?php if(get_theme_mod('blog_posts_likes', false) && function_exists('saxon_post_likes_display')): ?>
<div class="post-info-likes"><?php do_action('saxon_post_likes'); // this action called from plugin ?></div>
<?php endif; ?>
</div>

<?php if(get_theme_mod('blog_posts_share', true)): ?>
<?php if(!isset($post_socialshare_disable) || !$post_socialshare_disable): ?>
<div class="post-info-share">
  <?php do_action('saxon_social_share'); // this action called from plugin ?>
</div>
<?php endif; ?>
<?php endif; ?>

</div>

<?php endif; // after post details bottom ?>

</div>
