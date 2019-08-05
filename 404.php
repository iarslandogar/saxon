<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Saxon
 */

get_header(); ?>
<div class="content-block" role="main">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="page-404">
					<h1><?php esc_html_e("404", 'saxon'); ?></h1>
					<?php
					// Site 404 Banner
					saxon_banner_display('404');
					?>
					<p><?php esc_html_e( 'The page you were looking for could not be found.', 'saxon' ); ?></p>
					<p><?php esc_html_e( 'You may have typed the address incorrectly or you may have used an outdated link. Try search our site.', 'saxon' ); ?></p>
					<div class="search-form">
						<?php get_search_form(true); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
