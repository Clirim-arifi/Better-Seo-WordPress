<?php
/*
Plugin Name: Better Seo WordPress
Description: This plugin helps improve your website's SEO performance by checking post slugs and image alt text, which are important factors in search engine optimization.
Version: 1.0
Author: Çlirim Arifi
Author URI: https://linkedin.com/in/clirimarifi
*/

function admin_styles_enqueue()
{
  wp_enqueue_script('admin_scripts', plugin_dir_url(__FILE__) . 'admin/js/index.js', array(), '1.0', true);
  wp_enqueue_style('admin_styles', plugin_dir_url(__FILE__) . 'admin/css/index.css');
}
add_action('admin_enqueue_scripts', 'admin_styles_enqueue');

// Check Slug Length
function check_slug_length($new_status, $old_status, $post)
{
  if ($new_status != 'publish') return;
  $slug = $post->post_name;
  if (strlen($slug) > 65) {
    add_filter('redirect_post_location', function ($location) {
      return add_query_arg('message', 'slug-length-warning', $location);
    });
  }
}
add_action('transition_post_status', 'check_slug_length', 10, 3);

function slug_length_warning_notice()
{
  if (isset($_GET['message']) && $_GET['message'] == 'slug-length-warning') {
    echo '<div class="notice notice-error is-dismissible"> <p>Slug eshte me i gjate se 65 karaktere. Ju lutem shkurtoni slug.</p></div>';
  }
}
add_action('admin_notices', 'slug_length_warning_notice');

// Check If Image has alternative text
function check_image_alt_text()
{
  global $pagenow;
  if ('post-new.php' === $pagenow || 'post.php' === $pagenow) {
    global $post;
    $args = array(
      'post_type' => 'attachment',
      'numberposts' => -1,
      'post_status' => null,
      'post_parent' => $post->ID
    );
    $attachments = get_posts($args);
    if ($attachments) {
      foreach ($attachments as $attachment) {
        $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        if (empty($alt)) {
          echo '<div class="notice notice-warning"><p>Warning: The image ' . $attachment->post_title . ' does not have alt text.</p></div>';
        }
      }
    }
    $featured_image_id = get_post_thumbnail_id($post->ID);
    if ($featured_image_id) {
      $featured_image_alt = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);
      if (empty($featured_image_alt)) {
        echo '<div class="notice notice-warning"><p>Warning: The featured image does not have alt text.</p></div>';
      }
    }
    $gallery_ids = get_field('gallery', $post->ID);
    if (!empty($gallery_ids)) {
      foreach ($gallery_ids as $attachment) {
        $alt = $attachment['alt'];
        if (empty($alt)) {
          echo '<div class="notice notice-warning"><p>Warning: The image ' . $attachment['title'] . ' in the gallery does not have alt text.</p></div>';
        }
      }
    }
  }
}
add_action('admin_notices', 'check_image_alt_text');
