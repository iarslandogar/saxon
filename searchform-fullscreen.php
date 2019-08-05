<?php
/**
 * The template for displaying fullscreen search form
 *
 * @package Saxon
 */
?>
<form method="get" role="search" id="searchform_p" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="search" aria-label="<?php echo esc_attr__( 'Search', 'saxon' ); ?>" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s_p" placeholder="<?php echo esc_attr__('Type keyword(s) here and hit Enter &hellip;', 'saxon' ); ?>" />
	<input type="submit" class="submit btn" id="searchsubmit_p" value="<?php echo esc_attr__( 'Search', 'saxon' ); ?>" />
</form>
