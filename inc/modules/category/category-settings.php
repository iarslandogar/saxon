<?php
/**
 * Adding category settings (color, image)
 **/
add_action( 'cmb2_admin_init', 'saxon_category_metabox' );
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function saxon_category_metabox() {
  $prefix = '_saxon_';
  /**
   * Metabox to add fields to categories and tags
   */
  $cmb_term = new_cmb2_box( array(
    'id'               => $prefix . 'edit',
    'title'            => esc_html__( 'Category color', 'saxon' ), // Doesn't output for term boxes
    'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
    'taxonomies'       => array( 'category'), // Tells CMB2 which taxonomies should have these fields
    'new_term_section' => true, // Will display in the "Add New Category" section
  ) );
  $cmb_term->add_field( array(
    'name'    => esc_html__('Category badge color', 'saxon'),
    'id'      => $prefix . 'category_color' ,
    'type'    => 'colorpicker',
    'default' => '#000000',
  ) );
  $cmb_term->add_field( array(
    'name'    => 'Category image',
    'desc'    => esc_html__('Upload an image or enter an URL.', 'saxon'),
    'id'      => $prefix.'category_image',
    'type'    => 'file',
    // Optional:
    'options' => array(
      'url' => false, // Hide the text input for the url
    ),
    'text'    => array(
      'add_upload_file_text' => esc_html__('Add category image', 'saxon'), // Change upload button text. Default: "Add or Upload File"
    ),
    // query_args are passed to wp.media's library query.
    'query_args' => array(
      'type' => array(
      'image/gif',
      'image/jpeg',
      'image/png',
      ),
    ),
    'preview_size' => 'medium', // Image size to use when previewing in the admin.
  ) );
}

?>
