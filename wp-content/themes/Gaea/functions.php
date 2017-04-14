<?php
/* -----------------------------------------------------------------------------------
  Here we have all the custom functions for the theme
  Please be extremely cautious editing this file,
  When things go wrong, they tend to go wrong in a big way.
  You have been warned!
  ----------------------------------------------------------------------------------- */
/*
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
  ----------------------------------------------------------------------------------- */
define('IMIC_THEME_PATH', get_template_directory_uri());
define('IMIC_FILEPATH', get_template_directory());

/* -------------------------------------------------------------------------------------
  Load Translation Text Domain
  ----------------------------------------------------------------------------------- */
add_action('after_setup_theme', 'imic_theme_setup');
function imic_theme_setup() {
    load_theme_textdomain('framework', IMIC_FILEPATH . '/language');
}
/* -------------------------------------------------------------------------------------
  Menu option
  ----------------------------------------------------------------------------------- */
function imic_register_menu() {
    register_nav_menu('primary-menu', __('Primary Menu', 'framework'));
    register_nav_menu('top-menu', __('Top Menu', 'framework'));
	register_nav_menu('footer-menu', __('Footer Menu', 'framework'));
}
add_action('init', 'imic_register_menu');
/* -------------------------------------------------------------------------------------
  Set Max Content Width (use in conjuction with ".entry-content img" css)
  ----------------------------------------------------------------------------------- */
if (!isset($content_width))
    $content_width = 680;
/* -------------------------------------------------------------------------------------
  Configure WP2.9+ Thumbnails & gets the current post type in the WordPress Admin
  ----------------------------------------------------------------------------------- */
if (function_exists('add_theme_support')) {
	$post_type_name = '';
    if (isset($_GET['post'])) {
        $post_type_name = get_post_type($_GET['post']);
    }
    if (isset($_REQUEST['post_type'])) {
        $post_type_name = sanitize_key($_REQUEST['post_type']);
    }
    if ($post_type_name == 'gallery') {
        add_theme_support('post-formats', array(
            'video', 'image', 'gallery', 'link'
        ));
    }
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    set_post_thumbnail_size(958, 9999);
	add_image_size('80x80','80','80',true);
	add_image_size('1280x525','1280','525',true);
	add_image_size('800x534','800','534',true);
	add_image_size('600x400','600','400',true);
	add_image_size('100x100','100','100',true);
	add_image_size('1280x258','1280','258',true);
	add_image_size('1000x400','1000','400',true);
	add_image_size('800x600','800','600',true);
	add_image_size('1000x800','800','600',true);
    add_theme_support('woocommerce');
}
/* -------------------------------------------------------------------------------------
  Custom Gravatar Support
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_custom_gravatar')) {
    function imic_custom_gravatar($avatar_defaults) {
        $imic_avatar = get_template_directory_uri() . '/images/img_avatar.png';
        $avatar_defaults[$imic_avatar] = 'Custom Gravatar (/images/img_avatar.png)';
        return $avatar_defaults;
    }
    add_filter('avatar_defaults', 'imic_custom_gravatar');
}
/* -------------------------------------------------------------------------------------
  Load Theme Options
  ----------------------------------------------------------------------------------- */
require_once(IMIC_FILEPATH . '/includes/reduxcore/framework.php');
require_once(IMIC_FILEPATH . '/includes/sample/sample-config.php');
include_once(IMIC_FILEPATH . '/imic-framework/imic-framework.php');
/* -------------------------------------------------------------------------------------
  For Paginate
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_pagination')) {
    function imic_pagination($pages = '', $range = 4, $pagination='',$status='future') {
        $showitems = ($range * 2) + 1;
        global $paged;
        if (empty($paged))
            $paged = 1;
        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }
		$first = ($status=='past')?'<li class="pull-right"><a href="'.get_pagenum_link($paged+1).'">'.__('Newer','framework').' &rarr; </a></li>':'<li class="pull-left"><a href="'.get_pagenum_link($paged-1).'">&larr; '.__('Older','framework').'</a></li>';
		$second = ($status!='past')?'<li class="pull-right"><a href="'.get_pagenum_link($paged+1).'">'.__('Newer','framework').' &rarr;</a></li>':'<li class="pull-left"><a href="'.get_pagenum_link($paged-1).'">&larr; '.__('Older','framework').'</a></li>';
        if (1 != $pages) {
			if($pagination=='Default') {
				echo '<ul class="pager">';
				if(($paged!='')&&($paged!=1)) {
		echo $second; }
		if($pages!=$paged) {
		echo $first; }
		echo '</ul>';
			}
			else {
            echo '<ul class="pagination">';
            echo '<li><a href="' . get_pagenum_link(1) . '" title="'.__('First','framework').'"><i class="fa fa-chevron-left"></i></a></li>';
            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 3 || $i <= $paged - $range - 3) || $pages <= $showitems )) {
                    echo ($paged == $i) ? "<li class=\"active\"><span>" . $i . "</span></li>" : "<li><a href='" . get_pagenum_link($i) . "' class=\"\">" . $i . "</a></li>";
                }
            }
           echo '<li><a href="' . get_pagenum_link($pages) . '" title="'.__('Last','framework').'"><i class="fa fa-chevron-right"></i></a></li>';
            echo '</ul>'; }
        }
    }
}
/* -------------------------------------------------------------------------------------
  For Remove Dimensions from thumbnail image
  ----------------------------------------------------------------------------------- */
add_filter('post_thumbnail_html', 'imic_remove_thumbnail_dimensions', 10);
add_filter('image_send_to_editor', 'imic_remove_thumbnail_dimensions', 10);
remove_action( ‘woocommerce_before_add_to_cart_form’, ‘woocommerce_template_single_product_add_to_cart’, 10, 2);
function imic_remove_thumbnail_dimensions($html) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}


/* -------------------------------------------------------------------------------------
  Excerpt More and  length
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_custom_read_more')) {
    function imic_custom_read_more() {
        return '';
    }
}
if (!function_exists('imic_excerpt')) {
    function imic_excerpt($limit = 50) {
        return '<p>' . wp_trim_words(get_the_excerpt(), $limit, imic_custom_read_more()) . '</p>';
    }
}
/* 	Comment Styling
  /*----------------------------------------------------------------------------------- */
if (!function_exists('imic_comment')) {
    function imic_comment($comment, $args, $depth) {
        $isByAuthor = false;
        if ($comment->comment_author_email == get_the_author_meta('email')) {
            $isByAuthor = true;
        }
        $GLOBALS['comment'] = $comment;
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
            <div class="post-comment-block">
                <div id="comment-<?php comment_ID(); ?>">
                    <div class="img-thumbnail"><?php echo get_avatar($comment, $size = '40'); ?></div>
        <?php
         echo preg_replace('/comment-reply-link/', 'comment-reply-link  pull-right', get_comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<i class="fa fa-reply"></i>'))), 1);
       echo '<h5>' . get_comment_author() .__(' says','framework').'</h5>';
        ?>            
                    <span class="meta-data">
            <?php
            echo get_comment_date();
            _e(' at ', 'framework');
            echo get_comment_time();
            ?>
                    </span>
            <?php if ($comment->comment_approved == '0') : ?>
                        <em class="moderation"><?php _e('Your comment is awaiting moderation.', 'framework') ?></em>
                        <br />
            <?php endif; ?>
            <div class="comment-text"><?php comment_text() ?></div>
                </div>
            </div>
            <?php
        }
    }
?>