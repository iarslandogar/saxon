<?php
/*
*	Related posts
*/
?>
<?php

$tags = wp_get_post_tags(get_the_ID ());
$cats = wp_get_post_categories(get_the_ID ());

$args = '';

$blog_posts_related_by = get_theme_mod('blog_posts_related_by', 'tags');

if(is_single()) {
	$posts_per_page = 4;
} else {
	$posts_per_page = 2;
}

// If by tags
if($blog_posts_related_by == 'tags' && $tags) {

	$intags = array();

	foreach ($tags as $tag) {
		$intags[] = $tag->term_id;
	}

	$args = array(
		'tag__in' => $intags,
		'post__not_in' => array(get_the_ID ()),
		'posts_per_page'=> $posts_per_page
	);
}

// If by categories
if($blog_posts_related_by == 'categories' && $cats) {

	$args = array(
		'category__in' => $cats,
		'post__not_in' => array(get_the_ID ()),
		'posts_per_page'=> $posts_per_page
	);
}

$posts_query = new WP_Query($args);

if( $posts_query->have_posts() ) {

	echo '<div class="blog-post-related-wrapper clearfix">';

	if(is_single()) {
		echo '<h5>'.esc_html__('Related posts', 'saxon').'</h5>';
	}

	while ($posts_query->have_posts()) {
		$posts_query->the_post();

		if(is_single()) {
			get_template_part( 'inc/templates/block/content', 'postsmasonry1-2' );
		} else {
			get_template_part( 'inc/templates/post/content', 'shortline' );
		}

	}

	echo '</div>';
}

wp_reset_postdata();

?>
