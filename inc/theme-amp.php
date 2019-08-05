<?php
/**
 * Theme AMP Functions
 */

/*
* Load Google Fonts for AMP
*/
if(!function_exists('saxon_ampforwp_add_fonts')):
function saxon_ampforwp_add_fonts() {
  if(function_exists('amp_font')) {
    amp_font('//fonts.googleapis.com/css?family=Barlow:500,700');
    amp_font('//fonts.googleapis.com/css?family=Roboto:400,700');
  }
}
endif;
saxon_ampforwp_add_fonts();

/*
* AMP CSS styles for theme
*/
if(!function_exists('saxon_ampforwp_add_custom_css')):
function saxon_ampforwp_add_custom_css() {

  $color_main = get_theme_mod('color_main', '#1F5DEA');

  ?>
  /* AMP Custom CSS */
  body,
  .content-wrapper {
    font-family: "Roboto";
  }
  h1, h2, h3, h4, h5, h6 {
    font-family: "Barlow";
  }
  pre {
    border-left: none;
  }
  hr {
    border-color: #eeeeee;
  }
  blockquote {
    font-family: "Barlow";
    padding-left: 50px;
    padding-right: 50px;
    padding-top: 0;
    padding-bottom: 0;
    margin-top: 40px;
    margin-bottom: 40px;
    font-size: 28px;
    font-weight: bold;
    line-height: 1.3;
    position: relative;
    border: none;
  }
  blockquote p:before {
    display: none;
  }
  blockquote:before {
    font-family: "Barlow";
    content: "“";
    line-height: 84px;
    font-size: 140px;
    display: block;
    text-align: center;
    color: #EFF0F4;
    font-weight: 500;
    margin-bottom: -40px;
  }
  blockquote cite {
      font-size: 18px;
      font-weight: normal;
      text-align: center;
      font-style: normal;
      display: block;
      margin-top: 30px;
      color: #000000;
  }
  blockquote cite a {
      text-decoration: none;
  }
  .h_m {
    border-bottom: none;
  }
  .p-m-fl {
    background: none;
  }
  .p-menu ul li {
    font-family: "Barlow";
    font-size: 18px;
    font-weight: 500;
  }
  .cntn-wrp a,
  .cntn-wrp a:active,
  .cntn-wrp a:visited {
    color: <?php echo esc_attr($color_main); ?>;
  }
  .breadcrumbs {
    border-bottom: none;
    padding-bottom: 0;
    text-align: center;
  }
  .breadcrumb ul li a:hover,
  .breadcrumbs span a:hover,
  #breadcrumbs a:hover {
    color: #000000;
  }
  #breadcrumbs a {
    color: #999;
    letter-spacing: 1px;
  }
  .shr-txt,
  .athr-tx,
  .r-pf h3,
  .amp-tags > span:nth-child(1),
  .amp-related-posts-title,
  .related-title,
  .cmts h3 {
    text-transform: none;
    font-size: 20px;
    color: #000;
    font-weight: bold;
    font-family: "Barlow";
  }
  .related_link a {
    font-weight: bold;
  }
  .rlp-image {
    margin-bottom: 15px;
  }
  .shr-txt {
    display: block;
    margin-bottom: 15px;
  }
  .r-pf h3,
  .amp-related-posts-title {
    margin-bottom: 20px;
  }
  .ss-ic {
    border-bottom: 0;
  }
  .sp.sgl .cntr .breadcrumb,
  .sp.sgl .cntr .amp-category,
  .sp.sgl .cntr .amp-post-title,
  .arch-tlt {
    text-align: center;
  }
  .arch-tlt .amp-loop-label {
    margin-top: 10px;
    margin-bottom: 15px;
  }
  .amp-post-title + .pg {
    margin-top: 30px;
  }
  .amp-post-title,
  .amp-archive-title,
  .arch-tlt,
  .amp-loop-label {
    text-align: center;
    color: #000000;
    font-size: 30px;
    line-height: 38px;
  }
  .amp-archive-title {
    margin-top: 10px;
    margin-bottom: 15px;
  }
  .pt-dt,
  .post-date {
    font-family: "Barlow";
    font-size: 14px;
    color: #868686;
    text-transform: none;
  }
  .sp-rt .amp-author {
    border: none;
    text-align: center;
    background: #EFF0F4;
    padding: 30px;
  }
  .sp-rt .amp-author .author-name {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
    font-size: 20px;
    margin-bottom: 15px;
  }
  .author-details p {
    font-size: 16px;
    line-height: 26px;
    color: #000000;
  }
  .fsp {
    margin-bottom: 30px;
  }
  .fsp h2 {
    font-weight: bold;
    margin-bottom: 15px;
  }
  .fsp h2 a,
  .fsp:hover h2 a,
  .fsp h2 a:hover,
  .fsp h2 a:visited,
  .fsp h2 a:active,
  .fbp:hover h2 a {
    color: #000000;
  }
  .loop-category li {
    margin-right: 0;
  }
  .loop-category li a,
  .loop-category li a:active,
  .loop-category li a:visited,
  .loop-category li a:hover,
  .amp-category span a {
    font-family: "Barlow";
    letter-spacing: 1px;
    padding: 3px 8px;
    background-color:<?php echo esc_attr($color_main); ?>;
    color: #ffffff;
    margin-bottom: 3px;
    margin-right: 5px;
  }
  .fsp-img {
    margin-bottom: 30px;
  }
  @media (max-width: 767px)  {
    .fbp-img {
      margin-bottom: 20px;
    }
  }
  .fbp-cnt p, .fsp-cnt p {
    font-size: 14px;
  }
  #pagination {
    padding-top: 40px;
  }
  .right a,
  .left a {
    border-radius: 0;
  }
  .right a:after,
  .left a:before {
    display: none;
  }
  .loop-pagination {
    margin-top: 50px;
  }
  .prev span,
  .next span {
      color: #000;
      font-weight: bold;
      letter-spacing: 1px;
  }
  .prev:after {
    display: none;
  }
  #pagination,
  .ss-ic,
  .sp-athr,
  .amp-tags,
  .post-date {
    border-style: solid;
    border-color: #eeeeee;
  }
  .m-srch #s,
  .m-srch .amp-search-wrapper {
    border-radius: 0;
  }
  .m-srch #s,
  .lb-btn #s {
    font-size: 14px;
    border-radius: 0;
    -webkit-appearance: none;
  }
  .cp-rgt,
  .m-s-i {
    text-align: left;
    border-top: none;
    padding-left: 30px;
    padding-right: 30px;
  }
  .cp-rgt {
      font-size: 14px;
  }
  .tg:checked + .hamb-mnu > .m-ctr .c-btn {
    z-index: 10;
    top: 5px;
  }
  .c-btn:after {
    font-size: 30px;
  }
  .amp-menu li {
    font-size: 20px;
  }
  .f-menu ul li:last-child,
  .p-menu ul li:last-child {
    margin-right: 0;
  }

  /* Gutenberg galleries and images */
  .wp-block-image {
    margin-bottom: 30px;
  }
  .wp-block-gallery {
      list-style: none;
  }
  .wp-block-gallery .blocks-gallery-item {
      margin-bottom: 30px;
  }
  figcaption {
      text-align: center;
      padding: 5px;
      font-size: 14px;
      color: #B7B7B7;
  }

  /* Gutenberg columns */
  .has-4-columns,
  .has-2-columns {
    display: flex;
  }
  @media (max-width: 979px)  {
    .has-4-columns,
    .has-2-columns {
      display: block;
    }
  }
  .wp-block-column {
    margin-left: 5px;
    margin-right: 5px;
    flex-grow: 1;
  }
  .wp-block-column .wp-block-image {
    text-align: center;
  }
  .wp-block-column .wp-block-image amp-img {
    display: inline-block;
  }

  /* Gutenberg and AMP buttons */
  .wp-block-button {
      text-align: center;
      margin-bottom: 30px;
  }
  .wp-block-button a,
  .wp-block-button a:visited,
  .wp-block-button a:active,
  .amp-comment-button,
  .right a,
  .left a {
    font-family: "Barlow";
    padding: 13px 30px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    background: <?php echo esc_attr($color_main); ?>;
    color: #ffffff;
    display: inline-block;
  }
  .amp-comment-button {
    border-radius: 0;
    width: auto;
    display: table;
  }
  .amp-comment-button a {
    color: #ffffff;
  }
  .amp-category span:after {
    display: none;
  }
  .amp-tags span a {
    display: inline-block;
    background: #eeeeee;
    padding: 5px 10px;
    margin-right: 3px;
    margin-bottom: 3px;
    color: #000000;
  }
  .amp-tags .amp-tag:after {
    display: none;
  }
  .amp-author-image {
    float: none;
    text-align: center;
    display: inline-block;
  }
  .sp-rt .amp-author-image {
    float: none;
  }
  .amp-author-image amp-img {
    margin-right: 0;
    margin-bottom: 15px;
  }
  .cmts h3 {
    border-bottom: none;
  }
  .cmts h3:after {
    display: none;
  }

  /* Gutenberg block */
  .wp-block-cover {
      text-align: center;
      font-size: 25px;
  }
  .wp-block-embed {
      margin-bottom: 30px;
  }
  /* Gutenberg misc styles */
  .has-background {
      padding: 15px;
  }
  .alignleft {
      margin-right: 30px;
  }
  .alignright {
      margin-left: 30px;
  }
  .wp-block-image .aligncenter {
    text-align: center;
  }
  .wp-block-image .aligncenter > * {
    display: inline-block;
  }
  .btt {
    margin-left: 10px;
  }
  .t-btn:after {
    font-size: 20px;
  }
  .h-nav {
      border: 1px solid #E2E3E7;
      padding: 5px 5px 3px;
      border-radius: 5px;
  }
  .tg:checked + .hamb-mnu > .fsc {
    background: #ffffff;
  }
  @media (max-width: 480px)  {
    .loop-wrapper {
        margin: 0;
    }
    .loop-wrapper .fsp-cnt,
    .loop-wrapper .fbp-cnt {
      padding: 0;
    }
    .archive .loop-wrapper {
      margin: -15px;
    }
  }

  /* Gutenberg colors */
  .has-pale-pink-background-color.has-pale-pink-background-color {
    background-color: #f78da7;
  }
  .has-vivid-red-background-color.has-vivid-red-background-color {
    background-color: #cf2e2e;
  }
  .has-luminous-vivid-orange-background-color.has-luminous-vivid-orange-background-color {
    background-color: #ff6900;
  }
  .has-luminous-vivid-amber-background-color.has-luminous-vivid-amber-background-color {
    background-color: #fcb900;
  }
  .has-light-green-cyan-background-color.has-light-green-cyan-background-color {
    background-color: #7bdcb5;
  }
  .has-vivid-green-cyan-background-color.has-vivid-green-cyan-background-color {
    background-color: #00d084;
  }
  .has-pale-cyan-blue-background-color.has-pale-cyan-blue-background-color {
    background-color: #8ed1fc;
  }
  .has-vivid-cyan-blue-background-color.has-vivid-cyan-blue-background-color {
    background-color: #0693e3;
  }
  .has-very-light-gray-background-color.has-very-light-gray-background-color {
    background-color: #eee;
  }
  .has-cyan-bluish-gray-background-color.has-cyan-bluish-gray-background-color {
    background-color: #abb8c3;
  }
  .has-very-dark-gray-background-color.has-very-dark-gray-background-color {
    background-color: #313131;
  }
  .has-pale-pink-color.has-pale-pink-color {
    color: #f78da7;
  }
  .has-vivid-red-color.has-vivid-red-color {
    color: #cf2e2e;
  }
  .has-luminous-vivid-orange-color.has-luminous-vivid-orange-color {
    color: #ff6900;
  }
  .has-luminous-vivid-amber-color.has-luminous-vivid-amber-color {
    color: #fcb900;
  }
  .has-light-green-cyan-color.has-light-green-cyan-color {
    color: #7bdcb5;
  }
  .has-vivid-green-cyan-color.has-vivid-green-cyan-color {
    color: #00d084;
  }
  .has-pale-cyan-blue-color.has-pale-cyan-blue-color {
    color: #8ed1fc;
  }
  .has-vivid-cyan-blue-color.has-vivid-cyan-blue-color {
    color: #0693e3;
  }
  .has-very-light-gray-color.has-very-light-gray-color {
    color: #eee;
  }
  .has-cyan-bluish-gray-color.has-cyan-bluish-gray-color {
    color: #abb8c3;
  }
  .has-very-dark-gray-color.has-very-dark-gray-color {
    color: #313131;
  }
  .has-small-font-size {
    font-size: 13px;
  }
  .has-regular-font-size, // not used now, kept because of backward compatibility.
  .has-normal-font-size {
    font-size: 16px;
  }
  .has-medium-font-size {
    font-size: 20px;
  }
  .has-large-font-size {
    font-size: 36px;
  }
  .has-larger-font-size, // not used now, kept because of backward compatibility.
  .has-huge-font-size {
    font-size: 42px;
  }
  <?php
}
endif;
add_action('amp_post_template_css','saxon_ampforwp_add_custom_css', 11);
