<?php
/**
 * Post template: List Small
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

$post_class .= ' format-'.$current_post_format;

echo '<div class="saxon-list-post saxon-list-small-post saxon-post'.esc_attr($post_class).'"'.saxon_add_aos(false).'>';

// Post details
echo '<div class="saxon-post-details">';

echo '<div class="post-categories">'.wp_kses($categories_list, saxon_esc_data()).'</div>';

echo '<h3 class="post-title"><a href="'.esc_url(get_permalink($post->ID)).'">'.wp_kses_post($post->post_title).'</a></h3>';

?>
</div>
<?php
if(has_post_thumbnail( $post->ID )) {
  echo '<div class="saxon-post-image-wrapper"><a href="'.esc_url(get_permalink($post->ID)).'"><div class="saxon-post-image" data-style="'.esc_attr($image_bg).'"></div></a></div>';
}
?>
</div>
