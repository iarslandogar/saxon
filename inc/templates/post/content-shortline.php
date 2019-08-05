<?php
/**
 * Post template: Shortline
 */

?>
<?php

$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'saxon-blog-thumb-widget');

if(has_post_thumbnail( $post->ID )) {
    $image_bg ='background-image: url('.$image[0].');';
    $post_class = '';
}
else {
    $image_bg = '';
    $post_class = ' saxon-post-no-image';
}

$categories_list = saxon_get_the_category_list( $post->ID );

echo '<div class="saxon-shortline-post saxon-post'.esc_attr($post_class).'"'.saxon_add_aos(false).'>';

if(has_post_thumbnail( $post->ID )) {
  echo '<div class="saxon-post-image-wrapper"><a href="'.esc_url(get_permalink($post->ID)).'"><div class="saxon-post-image" data-style="'.esc_attr($image_bg).'"></div></a></div>';
}

// Post details
echo '<div class="saxon-post-details">

     <h3 class="post-title"><a href="'.esc_url(get_permalink($post->ID)).'">'.wp_kses_post($post->post_title).'</a></h3>';

echo '<div class="post-date">'.get_the_time( get_option( 'date_format' ), $post->ID ).'</div>';

echo '</div>';
// END - Post details


echo '</div>';
