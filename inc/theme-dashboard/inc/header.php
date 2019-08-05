<?php
/**
 * Theme dashboard header section.
 *
 * @package Saxon
 */

$current_theme = wp_get_theme();
?>
<h1>
	<?php
	// Translators: %1$s - Theme name, %2$s - Theme version.
	echo esc_html( sprintf( __( 'Welcome to %1$s - Version %2$s', 'saxon' ), 'Saxon', $current_theme->version ) );
	?>
</h1>
<div class="about-text"><?php echo esc_html( "Thank you! You just purchased one of our best WordPress themes and we hope you'll like it!" ); ?>

</div>
<a target="_blank" href="<?php echo esc_url( 'http://magniumthemes.com/' ); ?>" class="wp-badge">MagniumThemes</a>
