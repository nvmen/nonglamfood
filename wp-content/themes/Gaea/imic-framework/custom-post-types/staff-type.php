<?php
/* ==================================================
  Staff Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('init', 'imic_staff_register');
function imic_staff_register() {
    $labels = array(
        'name' => __('Staff', 'framework'),
        'singular_name' => __('Staff', 'framework'),
        'all_items'=> __('Staff Members', 'framework'),
        'add_new' => __('Add New', 'framework'),
        'add_new_item' => __('Add New Staff', 'framework'),
        'edit_item' => __('Edit Staff', 'framework'),
        'new_item' => __('New Staff', 'framework'),
        'view_item' => __('View Staff', 'framework'),
        'search_items' => __('Search Staff', 'framework'),
        'not_found' => __('No staff have been added yet', 'framework'),
        'not_found_in_trash' => __('Nothing found in Trash', 'framework'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
		'capability_type' => 'page',
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'rewrite' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes','excerpt','author'),
        'has_archive' => true,
    );
    register_post_type('staff', $args);
}
?>