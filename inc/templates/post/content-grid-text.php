<?php
/**
 * Post template: Grid Text
 */

?>
<?php

$categories_list = saxon_get_the_category_list( $post->ID );


echo '<div class="saxon-grid-post saxon-post"'.saxon_add_aos(false).'>';

echo '<div class="post-categories">'.wp_kses($categories_list, saxon_esc_data()).'</div>';

// Post details
echo '<div class="saxon-post-details">

      <h3 class="post-title"><a href="'.esc_url(get_permalink($post->ID)).'">'.wp_kses_post($post->post_title).'</a></h3>';

echo '<div class="post-date">'.get_the_time( get_option( 'date_format' ), $post->ID ).'</div>';

echo '</div>';
// END - Post details

echo '</div>';
