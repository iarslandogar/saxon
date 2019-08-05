<?php
/*
*	Posts Author biography template
*/
?>
<div class="author-bio" <?php saxon_add_aos(); ?>>
	<div class="author-image">
		<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ))); ?>"><?php echo get_avatar( get_the_author_meta('email'), '170', '200' ); ?></a>
	</div>
	<div class="author-info">
		<h5><?php esc_html_e( 'About author', 'saxon' ); ?></h5>
		<h3><?php the_author_posts_link();?></h3>
		<div class="author-description"><?php the_author_meta('description'); ?></div>
		<?php do_action('saxon_author_social_links_display'); // This action called from plugin ?>
	</div>
	<div class="clear"></div>
</div>
