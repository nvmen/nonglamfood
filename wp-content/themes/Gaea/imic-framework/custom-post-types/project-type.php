<?php
/* ==================================================
  Project Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('init', 'imic_project_register');
function imic_project_register() {
    $args_c = array(
    "label" => __('Project Categories','framework'),
    "singular_label" => __('Project Category','framework'),
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'args' => array('orderby' => 'term_order'),
    'rewrite' => false,
   'query_var' => true,
   'show_admin_column' => true,
);
register_taxonomy('project-category', 'project',$args_c);
    $labels = array(
        'name' => __('Projects', 'framework'),
        'singular_name' => __('Project','framework'),
        'add_new' => __('Add New', 'framework'),
        'add_new_item' => __('Add New Projects', 'framework'),
        'edit_item' => __('Edit Project', 'framework'),
        'new_item' => __('New Projects', 'framework'),
        'view_item' => __('View Project', 'framework'),
        'search_items' => __('Search Projects', 'framework'),
        'not_found' => __('No projects have been added yet', 'framework'),
        'not_found_in_trash' => __('Nothing found in Trash', 'framework'),
        'parent_item_colon' => '',
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'hierarchical' => false,
        'rewrite' => true,
        'supports' => array('title', 'editor', 'thumbnail','excerpt','author'),
        'has_archive' => true,
        'taxonomies' => array('project-category')
    );
     register_post_type('project', $args);
     register_taxonomy_for_object_type('project-category','project');
}
?>