<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/*
 *
 * 	imic Framework Theme Functions
 * 	------------------------------------------------
 * 	imic Framework v2.0
 * 	Copyright imic  2014 - http://www.imicreation.com/
 * 	imic_theme_activation()
 * 	imic_maintenance_mode()
 * 	imic_custom_login_logo()
 * 	imic_add_nofollow_cat()
 * 	imic_admin_bar_menu()
 * 	imic_admin_css()
 * 	imic_analytics()
 * 	imic_custom_styles()
 * 	imic_custom_script()
 * 	imic_content_filter()
 *  imic_video_embed()
 *	imic_register_sidebars()
 *	imic_event_grid()
 *	imic_event_time_columns()
 *	imic_afterSavePost()
 *	imic_get_cat_list()
 *	imic_widget_titles()
 *	imic_month_translate()
 *	imic_RevSliderShortCode()
 *	imic_query_arg()
 *	imicAddQueryVarsFilter()
 *	ImicConvertDate()
 *	imic_cat_count_flag()
 *	imic_recur_events()
 *	imic_custom_taxonomies_terms_links()
 *	imic_get_all_sidebars()
 *	imic_register_meta_box()
 *	imic_wp_get_attachment()
 *  imic_gallery_flexslider()
 *  imic_search_button_header
 *  imic_cart_button_header
 *	imic_share_buttons
 */
/* THEME ACTIVATION
  ================================================== */
if (!function_exists('imic_theme_activation')) {
    function imic_theme_activation() {
        global $pagenow;
        if (is_admin() && 'themes.php' == $pagenow && isset($_GET['activated'])) {
            #provide hook so themes can execute theme specific functions on activation
            do_action('imic_theme_activation');
          }
    }
    add_action('admin_init', 'imic_theme_activation');
}
/* MAINTENANCE MODE
  ================================================== */
if (!function_exists('imic_maintenance_mode')) {
    function imic_maintenance_mode() {
        $options = get_option('imic_options');
        $custom_logo = $custom_logo_output = $maintenance_mode = "";
        if (isset($options['custom_admin_login_logo'])) {
            $custom_logo = $options['custom_admin_login_logo'];
        }
        $custom_logo_output = '<img src="' . $custom_logo['url'] . '" alt="maintenance" style="height: 62px!important;margin: 0 auto; display: block;" />';
        if (isset($options['enable_maintenance'])) {
            $maintenance_mode = $options['enable_maintenance'];
        } else {
            $maintenance_mode = false;
        }
        if ($maintenance_mode) {
            if (!current_user_can('edit_themes') || !is_user_logged_in()) {
                wp_die($custom_logo_output . '<p style="text-align:center">' . __('We are currently in maintenance mode, please check back shortly.', 'framework') . '</p>', __('Maintenance Mode', 'framework'));
            }
        }
    }
    add_action('get_header', 'imic_maintenance_mode');
}
/* CUSTOM LOGIN LOGO
  ================================================== */
if (!function_exists('imic_custom_login_logo')) {
    function imic_custom_login_logo() {
        $options = get_option('imic_options');
        $custom_logo = "";
        if (isset($options['custom_admin_login_logo'])) {
            $custom_logo = $options['custom_admin_login_logo'];
        }
        echo '<style type="text/css">
			    .login h1 a { background-image:url(' . $custom_logo['url'] . ') !important; background-size: auto !important; width: auto !important; height: 95px !important; }
			</style>';
    }
    add_action('login_head', 'imic_custom_login_logo');
}
/* CATEGORY REL FIX
  ================================================== */
if (!function_exists('imic_add_nofollow_cat')) {
    function imic_add_nofollow_cat($text) {
        $text = str_replace('rel="category tag"', "", $text);
        return $text;
    }
    add_filter('the_category', 'imic_add_nofollow_cat');
}
/* CUSTOM ADMIN MENU ITEMS
  ================================================== */
if (!function_exists('imic_admin_bar_menu')) {
    function imic_admin_bar_menu() {
        global $wp_admin_bar;
        if (current_user_can('manage_options')) {
            $theme_customizer = array(
                'id' => '2',
                'title' => __('Color Customizer', 'framework'),
                'href' => admin_url('/customize.php'),
                'meta' => array('target' => 'blank')
            );
            $wp_admin_bar->add_menu($theme_customizer);
        }
    }
    add_action('admin_bar_menu', 'imic_admin_bar_menu', 99);
}
/* ADMIN CUSTOM POST TYPE ICONS
  ================================================== */
if (!function_exists('imic_admin_css')) {
    function imic_admin_css() {
        $mywp_version = get_bloginfo('version');
        if ($mywp_version < 3.8) {
            ?>
            <style type="text/css" media="screen">
                #menu-posts-event .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/portfolio.png) no-repeat 6px 7px!important;
                    background-size: 17px 15px;
                }
                #menu-posts-events:hover .wp-menu-image, #menu-posts-events.wp-has-current-submenu .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/portfolio_rollover.png) no-repeat 6px 7px!important;
                }
                #menu-posts-staff .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/team.png) no-repeat 6px 11px!important;
                    background-size: 18px 9px;
                }
                #menu-posts-staff:hover .wp-menu-image, #menu-posts-staff.wp-has-current-submenu .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/team_rollover.png) no-repeat 6px 11px!important;
                }
                #menu-posts-sermons .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/jobs.png) no-repeat 6px 11px!important;
                    background-size: 18px 9px;
                }
                #menu-posts-sermons:hover .wp-menu-image, #menu-posts-sermons.wp-has-current-submenu .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/jobs_rollover.png) no-repeat 6px 11px!important;
                }
                #menu-posts-gallery .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/showcase.png) no-repeat 6px 11px!important;
                    background-size: 18px 9px;
                }
                #menu-posts-gallery:hover .wp-menu-image, #menu-posts-gallery.wp-has-current-submenu .wp-menu-image {
                    background: url(<?php echo get_template_directory_uri(); ?>/images/wp/showcase_rollover.png) no-repeat 6px 11px!important;
                }
                #menu-posts-slide .wp-menu-image img {
                    width: 16px;
                }
                #toplevel_page_imic_theme_options .wp-menu-image img {
                    width: 11px;
                    margin-top: -2px;
                    margin-left: 3px;
                }
                .toplevel_page_imic_theme_options #adminmenu li#toplevel_page_imic_theme_options.wp-has-current-submenu a.wp-has-current-submenu, .toplevel_page_imic_theme_options #adminmenu #toplevel_page_imic_theme_options .wp-menu-arrow div, .toplevel_page_imic_theme_options #adminmenu #toplevel_page_imic_theme_options .wp-menu-arrow {
                    background: #222;
                    border-color: #222;
                }
                #wpbody-content {
                    min-height: 815px;
                }
                .wp-list-table th#thumbnail, .wp-list-table td.thumbnail {
                    width: 80px;
                }
                .wp-list-table td.thumbnail img {
                    max-width: 100%;
                    height: auto;
                }
            </style>
            <?php
        } else {
            ?>
            <style type="text/css" media="screen">
                .icon16.icon-post:before, #adminmenu #menu-posts-event div.wp-menu-image:before
                {
                    content: "\f337";
                }
                .icon16.icon-post:before, #adminmenu #menu-posts-sermons div.wp-menu-image:before
                {
                    content: "\f110";
                }
                .icon16.icon-post:before, #adminmenu #menu-posts-staff div.wp-menu-image:before
                {
                    content: "\f307";
                }
                .icon16.icon-post:before, #adminmenu #menu-posts-gallery div.wp-menu-image:before
                {
                    content: "\f161";
                }
                #menu-posts-slide .wp-menu-image img {
                    width: 16px;
                }
                #toplevel_page_imic_theme_options .wp-menu-image img {
                    width: 11px;
                    margin-top: -2px;
                    margin-left: 3px;
                }
                .toplevel_page_imic_theme_options #adminmenu li#toplevel_page_imic_theme_options.wp-has-current-submenu a.wp-has-current-submenu, .toplevel_page_imic_theme_options #adminmenu #toplevel_page_imic_theme_options .wp-menu-arrow div, .toplevel_page_imic_theme_options #adminmenu #toplevel_page_imic_theme_options .wp-menu-arrow {
                    background: #222;
                    border-color: #222;
                }
                #wpbody-content {
                    min-height: 815px;
                }
                .wp-list-table th#thumbnail, .wp-list-table td.thumbnail {
                    width: 80px;
                }
                .wp-list-table td.thumbnail img {
                    max-width: 100%;
                    height: auto;
                }
            </style>	
            <?php
        }
    }
    add_action('admin_head', 'imic_admin_css');
}
/* ----------------------------------------------------------------------------------- */
/* Show analytics code in footer */
/* ----------------------------------------------------------------------------------- */
if (!function_exists('imic_analytics')) {
    function imic_analytics() {
        $options = get_option('imic_options');
        if ($options['tracking-code'] != "") {
            echo '<script>';
            echo $options['tracking-code'];
            echo '</script>';
        }
    }
    add_action('wp_head', 'imic_analytics');
}
/* CUSTOM CSS OUTPUT
  ================================================== */
if (!function_exists('imic_custom_styles')) {
    function imic_custom_styles() {
        $options = get_option('imic_options');
       
        // OPEN STYLE TAG
        echo '<style type="text/css">' . "\n";
        // Custom CSS
        $custom_css = $options['custom_css'];
        if ($options['theme_color_type'][0] == 1) {
            $primaryColor = $options['primary_theme_color'];
			$secondaryColor = $options['secondary_theme_color'];
            echo '.text-primary, .btn-primary .badge, .btn-link,a.list-group-item.active > .badge,.nav-pills > .active > a > .badge, p.drop-caps:first-child:first-letter, .accent-color, .main-navigation > ul > li > a:hover, .posts-listing .post-time, h3.title .title-border i, .upcoming-events .event-cats a:hover, .nav-np .next:hover, .nav-np .prev:hover, .basic-link, .pagination > li > a:hover,.pagination > li > span:hover,.pagination > li > a:focus,.pagination > li > span:focus, .staff-item .meta-data, .woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price, .woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price, .event-ticket h4, .event-ticket .ticket-ico{
	color:'.esc_attr($primaryColor).';
}
a:hover{
	color:'.esc_attr($primaryColor).';
}
.basic-link:hover{
	opacity:.9
}
p.demo_store, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce span.onsale, .woocommerce-page span.onsale, .wpcf7-form .wpcf7-submit, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_layered_nav ul li.chosen a, .woocommerce-page .widget_layered_nav ul li.chosen a{ background: ' . esc_attr($primaryColor) . '; }
p.drop-caps.secondary:first-child:first-letter, .accent-bg, .fa.accent-color, .btn-primary,
.btn-primary.disabled,
.btn-primary[disabled],
fieldset[disabled] .btn-primary,
.btn-primary.disabled:hover,
.btn-primary[disabled]:hover,
fieldset[disabled] .btn-primary:hover,
.btn-primary.disabled:focus,
.btn-primary[disabled]:focus,
fieldset[disabled] .btn-primary:focus,
.btn-primary.disabled:active,
.btn-primary[disabled]:active,
fieldset[disabled] .btn-primary:active,
.btn-primary.disabled.active,
.btn-primary[disabled].active,
fieldset[disabled] .btn-primary.active,
.dropdown-menu > .active > a,
.dropdown-menu > .active > a:hover,
.dropdown-menu > .active > a:focus,
.nav-pills > li.active > a,
.nav-pills > li.active > a:hover,
.nav-pills > li.active > a:focus,
.pagination > .active > a,
.pagination > .active > span,
.pagination > .active > a:hover,
.pagination > .active > span:hover,
.pagination > .active > a:focus,
.pagination > .active > span:focus,
.label-primary,
.progress-bar-primary,
a.list-group-item.active,
a.list-group-item.active:hover,
a.list-group-item.active:focus, .accordion-heading .accordion-toggle.active, .accordion-heading:hover .accordion-toggle.active, .accordion-heading:hover .accordion-toggle.inactive,
.panel-primary > .panel-heading, .carousel-indicators .active, .flex-control-nav a:hover, .flex-control-nav a.flex-active, .media-box .media-box-wrapper, .top-menu li a, .upcoming-events .event-date, .media-box .zoom, .media-box .expand, .project-overlay .project-cat, .flexslider .flex-prev:hover, .flexslider .flex-next:hover, .events-listing .upcoming-events li:hover .event-details-btn:hover, .single-event-info .icon-s, .event-register-block:hover, .fc-events, .projects-grid .project-cat, .tagcloud a:hover, .main-navigation > ul > li ul{
  background-color: '.esc_attr($primaryColor).';
}
.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active,
.btn-primary.active,
.open .dropdown-toggle.btn-primary, .top-menu li a:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active, .wpcf7-form .wpcf7-submit{
  background: '.esc_attr($primaryColor).';
  opacity:.9
}
.nav .open > a,
.nav .open > a:hover,
.nav .open > a:focus,
.pagination > .active > a,
.pagination > .active > span,
.pagination > .active > a:hover,
.pagination > .active > span:hover,
.pagination > .active > a:focus,
.pagination > .active > span:focus,
a.thumbnail:hover,
a.thumbnail:focus,
a.thumbnail.active,
a.list-group-item.active,
a.list-group-item.active:hover,
a.list-group-item.active:focus,
.panel-primary,
.panel-primary > .panel-heading, .event-ticket-left .ticket-handle{
	border-color:'.esc_attr($primaryColor).';
}
.panel-primary > .panel-heading + .panel-collapse .panel-body, .main-navigation > ul > li ul, #featured-events ul.slides{
	border-top-color:'.esc_attr($primaryColor).';
}
.panel-primary > .panel-footer + .panel-collapse .panel-body, .nav-tabs li a:ui-tabs-active, .nav-tabs li.ui-tabs-active a, .nav-tabs > li.ui-tabs-active > a:hover, .nav-tabs > li.ui-tabs-active > a:focus, .title .title-border, .hero-slider, .page-header{
	border-bottom-color:'.esc_attr($primaryColor).';
}
blockquote{
	border-left-color:'.esc_attr($primaryColor).';
}
.main-navigation > ul > li ul:before, .main-navigation > ul > li.megamenu > ul:before{
	border-bottom-color:'.esc_attr($primaryColor).';
}
.main-navigation > ul > li ul li ul:before{
	border-right-color:'.esc_attr($primaryColor).';
}
.share-buttons.share-buttons-tc > li > a{
  background:'.esc_attr($primaryColor).';
}
/* SECONDARY COLOR */
.secondary-color, .top-header .social-links a:hover, h3.block-title{
	color:'.esc_attr($secondaryColor).';
}
.secondary-color-bg, .top-menu li.secondary a, .featured-projects, hr.sm, .flexslider .flex-prev, .flexslider .flex-next, .events-listing .upcoming-events li:hover .event-details-btn, .single-event-info .time, .event-single-venue > span:first-child, .tagcloud a, .staff-volunteers, .accordion-heading:hover .accordion-toggle, .widget_twitter_feeds li span.date, .woocommerce .woocommerce-info:before, .woocommerce-page .woocommerce-info:before, .woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-message:before, .ticket-cost{
	background-color:'.esc_attr($secondaryColor).';
}
.page-header{
	background-color:'.esc_attr($secondaryColor).';
}
.top-menu li.secondary a:hover, .secondary-color-bg:hover{
	background-color:'.esc_attr($secondaryColor).';
	opacity:.9;
}
.event-register-block{
	border-color:'.esc_attr($secondaryColor).';
}';
        }
        if ($custom_css) {
            echo "\n" . '/*========== User Custom CSS Styles ==========*/' . "\n";
            echo $custom_css;
        }
        // CLOSE STYLE TAG
        echo "</style>" . "\n";
    }
    add_action('wp_head', 'imic_custom_styles');
}
/* CUSTOM JS OUTPUT
  ================================================== */
if (!function_exists('imic_custom_script')) {
    function imic_custom_script() {
        $options = get_option('imic_options');
        $custom_js = $options['custom_js'];
        if ($custom_js) {
            echo'<script type ="text/javascript">';
            echo $custom_js;
            echo '</script>';
        }
    }
    add_action('wp_footer', 'imic_custom_script');
}
/* SHORTCODE FIX
  ================================================== */
if (!function_exists('imic_content_filter')) {
    function imic_content_filter($content) {
        // array of custom shortcodes requiring the fix 
        $block = join("|", array("imic_button", "icon", "iconbox", "imic_image", "anchor", "paragraph", "divider", "heading", "alert", "blockquote", "dropcap", "code", "label", "container", "spacer", "span", "one_full", "one_half", "one_third", "one_fourth", "one_sixth","two_third", "progress_bar", "imic_count", "imic_tooltip", "imic_video", "htable", "thead", "tbody", "trow", "thcol", "tcol", "pricing_table", "pt_column", "pt_package", "pt_button", "pt_details", "pt_price", "list", "list_item", "list_item_dt", "list_item_dd", "accordions", "accgroup", "acchead", "accbody", "toggles", "togglegroup", "togglehead", "togglebody", "tabs", "tabh", "tab", "tabc", "tabrow", "section", "page_first", "page_last", "page", "modal_box", "imic_form", "fullcalendar", "staff","fullscreenvideo"));
        // opening tag
        $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);
        // closing tag
        $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep);
        return $rep;
    }
    add_filter("the_content", "imic_content_filter");
}
/* VIDEO EMBED FUNCTIONS
  ================================================== */
if (!function_exists('imic_video_embed')) {
    function imic_video_embed($url, $width = 200, $height = 150) {
        if (strpos($url, 'youtube') || strpos($url, 'youtu.be')) {
            return imic_video_youtube($url, $width, $height);
        } else {
            return imic_video_vimeo($url, $width, $height);
        }
    }
}
/* Video Youtube
  ================================================== */
if (!function_exists('imic_video_youtube')) {
    function imic_video_youtube($url, $width = 200, $height = 150) {
        if (stristr($url,'youtu.be/'))
        { preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $video_id); return '<div class="fw-video"><iframe itemprop="video" src="http://www.youtube.com/embed/' . $video_id[4] . '?wmode=transparent&autoplay=0" width="' . $width . '" height="' . $height . '" ></iframe></div>'; }
    else 
        { preg_match('/(https:|http:|):(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch\?v=|(.*?)&v=|v\/|e\/|.+\/|watch.*v=|)([a-z_A-Z0-9]{11})/i', $url, $video_id); return '<div class="fw-video"><iframe itemprop="video" src="http://www.youtube.com/embed/' . $video_id[6] . '?wmode=transparent&autoplay=0" width="' . $width . '" height="' . $height . '" ></iframe></div>';
		}
    }
}
/* Video Vimeo
  ================================================== */
if (!function_exists('imic_video_vimeo')) {
   function imic_video_vimeo($url, $width = 200, $height = 150) {
        preg_match('/https?:\/\/vimeo.com\/(\d+)$/', $url, $video_id);
        return '<div class="fw-video"><iframe src="//player.vimeo.com/video/' . $video_id[1] . '?title=0&amp;byline=0&amp;autoplay=0&amp;portrait=0" width="' . $width . '" height="' . $height . '" allowfullscreen></iframe></div>';
    }
}
/* REGISTER SIDEBARS
  ================================================== */
if (!function_exists('imic_register_sidebars')) {
    function imic_register_sidebars() {
        if (function_exists('register_sidebar')) {
			$options = get_option('imic_options');
			$footer_class = $options["footer_layout"];
            register_sidebar(array(
                'name' => __('Home Page Sidebar', 'framework'),
                'id' => 'main-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widgettitle">',
                'after_title' => '</h3>'
            ));
            register_sidebar(array(
                'name' => __('Contact Sidebar', 'framework'),
                'id' => 'inner-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
                'after_title' => '</h3></div>'
            ));
            register_sidebar(array(
                'name' => __('Page Sidebar', 'framework'),
                'id' => 'page-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
                'after_title' => '</h3></div>'
            ));
			register_sidebar(array(
                'name' => __('Events Sidebar', 'framework'),
                'id' => 'events-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
                'after_title' => '</h3></div>'
            ));
			register_sidebar(array(
                'name' => __('Post Sidebar', 'framework'),
                'id' => 'post-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
                'after_title' => '</h3></div>'
            ));
			register_sidebar(array(
                'name' => __('Product Sidebar', 'framework'),
                'id' => 'product-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
                'after_title' => '</h3></div>'
            ));
            register_sidebar(array(
                'name' => __('Footer Widgets', 'framework'),
                'id' => 'footer-sidebar',
                'description' => '',
                'class' => '',
                'before_widget' => '<div class="col-md-'.$footer_class.' col-sm-'.$footer_class.' widget footer-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h4 class="footer-widget-title">',
                'after_title' => '</h4>'
            ));
        }
    }
    add_action('init', 'imic_register_sidebars', 35);
}
/* EVENT GRID FUNCTION
  ================================================== */
function imic_event_grid() {
	$offset = get_option('timezone_string');
		if($offset=='') { $offset = "Australia/Melbourne"; }
	date_default_timezone_set($offset);
    
    $currentEventTime = $_POST['date'];
	$EventTerm = $_POST['term'];
    $prev_month = date('Y-m', strtotime('-1 month', strtotime($currentEventTime)));
    $next_month = date('Y-m', strtotime('+1 month', strtotime($currentEventTime)));
	$previous_month_end = strtotime(date('Y-m-d 00:01', strtotime($currentEventTime)));
	$next_month_start = strtotime(date('Y-m-d 00:01', strtotime('+1 month', strtotime($currentEventTime))));
	echo '<div class="listing-header">
                                <h2 class="title ">Monthly Events <label class="label label-primary">'.date_i18n('F', strtotime($currentEventTime)).'</label></h2>';
    //echo '<h5>' . date_i18n('F', strtotime($currentEventTime)) . '</h5>
				echo '<nav class="nav-np">
					<a href="javascript:" rel="'.$EventTerm.'" class="upcomingEvents" id="' . $prev_month . '"><i class="fa fa-angle-left"></i></a>
					<a href="javascript:" rel="'.$EventTerm.'" class="upcomingEvents" id="' . $next_month . '"><i class="fa fa-angle-right"></i></a>
				</nav>
		  </div>
	  <ul class="upcoming-events listing-content">';
	  $currentTime = date('Y-m-d G:i');
	  $todays = date('Y-m-d');
    $today = date('Y-m');
	$curr_month = date('Y-m-t', strtotime('-1 month', strtotime($currentEventTime)));
    //$currentTime = date(get_option('time_format'));
    query_posts(array(
								'post_type' => 'event',
								'event-category' => $EventTerm,
								'meta_key' => 'imic_event_start_dt',
								'meta_query' => array(
										'relation' => 'AND',
										array(
											'key' => 'imic_event_frequency_end',
											'value' => $curr_month,
											'compare' => '>='
										),
										array(
											'key' => 'imic_event_start_dt',
											'value' => date('Y-m-t', strtotime($currentEventTime)),
											'compare' => '<='
										)
								),
								'orderby' => 'meta_value',
								'order' => 'ASC',
								'posts_per_page' => -1
							)
				  );
    $count = 0;
				  $events = array();
				  $sinc = 1;
				  $count = 0;
				  if(have_posts()){ 
					while (have_posts()):the_post();
					$eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
        $frequency = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
        $frequency_count = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);
		$frequency_active = get_post_meta(get_the_ID(),'imic_event_frequency_type',true);
		$frequency_type = get_post_meta(get_the_ID(),'imic_event_frequency_type',true);
		$frequency_month_day = get_post_meta(get_the_ID(),'imic_event_day_month',true);
		$frequency_week_day = get_post_meta(get_the_ID(),'imic_event_week_day',true);
        if ($frequency_active > 0) {
            $frequency_count = $frequency_count;
        } else { $frequency_count = 0; }
        $seconds = $frequency * 86400;
        $fr_repeat = 0;
        while ($fr_repeat <= $frequency_count) {
            $eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
            $eventDate = strtotime($eventDate);
			if($frequency_type==1||$frequency_type==0) {
			if($frequency==30) {
			$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
			}
			else {
			$new_date = $seconds * $fr_repeat;
            $eventDate = $eventDate + $new_date;
			}
			}
			else {
				$eventTime = date('G:i',$eventDate);
				$eventDate = strtotime( date('Y-m-1',$eventDate) );
				if($fr_repeat==0) { $fr_repeat = $fr_repeat+1; }
			$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
			$next_month = date('F',$eventDate);
			$next_event_year = date('Y',$eventDate);
			//echo $next_month;
			$eventDate = date('Y-m-d '.$eventTime, strtotime($frequency_month_day.' '.$frequency_week_day.' of '.$next_month.' '.$next_event_year));
			//echo $eventDate;
			$eventDate = strtotime($eventDate);
			}
					if(($eventDate > $previous_month_end) && ($eventDate < $next_month_start)){
					$sp[$eventDate+$sinc] = get_the_ID();
						$sinc++;
                    $count++;
                } $fr_repeat++; }
        endwhile; 
    }if ($count == 0) {
        echo '<li class="item event-item">	
			  <div class="event-detail">
				<h4>' . __('Sorry, there are no events for this month.', 'framework') . '</h4>
			  </div>
			</li>';
    } else { ksort($sp);
	foreach($sp as $key =>$value) {
                         $date_converted=date('Y-m-d',$key );
                $custom_event_url= imic_query_arg($date_converted,$value);       
					echo '<li>
                                    <a href="'.esc_url($custom_event_url).'" class="event-details-btn"><i class="fa fa-angle-right"></i></a>';
                                	if ( '' != get_the_post_thumbnail() ) { echo get_the_post_thumbnail($value,'80x80',array('class'=>'img-thumbnail event-thumb')); }
                                    echo '<span class="event-date">
                                    	<span class="day">'.esc_attr(date_i18n('d', $key)).'</span>
                                        <span class="monthyear">'.esc_attr(date_i18n('M, ', $key)).esc_attr(date_i18n('y', $key)).'</span>
                                   	</span>
                                    <div class="event-excerpt">
                                        <div class="event-cats meta-data">'.get_the_term_list($value, 'event-category', '', ', ', '' ).'</div>
                                    	<h5 class="event-title"><a href="'.esc_url($custom_event_url).'">'.get_the_title($value).'</a></h5>';
                                    	$address = get_post_meta($value,'imic_event_address2',true); if($address!='') { ?>
                                    <p class="event-location"><i class="fa fa-map-marker"></i> <?php echo $address; ?></p><?php }
                                    echo '</div>
                               	</li>';
	} }
    echo '</ul>';
    die();
}
add_action('wp_ajax_nopriv_imic_event_grid', 'imic_event_grid');
add_action('wp_ajax_imic_event_grid', 'imic_event_grid');
// get taxonomies terms links
if (!function_exists('imic_custom_taxonomies_terms_links')) {
    function imic_custom_taxonomies_terms_links($id) {
    global $post;
	$out = '';
    // get post by post id
    $post = get_post($id);
    // get post type by post
    $post_type = $post->post_type;
    // get post type taxonomies
    $taxonomies = get_object_taxonomies($post_type);
    foreach ($taxonomies as $taxonomy) {   
        // get the terms related to post
        $terms = get_the_terms( $post->ID, $taxonomy );
        if ( !empty( $terms ) ) {
                foreach ( $terms as $term ){
                $out = $term->name;
        }
        }
    }
    return $out;
}
}
//Manage Custom Columns for Event Post Type 
if (!function_exists('imic_event_time_columns')) {
    add_filter('manage_edit-event_columns', 'imic_event_time_columns');
    function imic_event_time_columns($columns) {
        $columns['Event'] = __('Event', 'framework');
		//$columns['Recurring'] = __('Recurring', 'framework');
        return $columns;
    }
}
if (!function_exists('imic_event_time_column_content')) {
    add_action('manage_event_posts_custom_column', 'imic_event_time_column_content', 10, 2);
    function imic_event_time_column_content($column_name, $post_id) {
        switch ($column_name) {
            case 'Event':
                $sdate = get_post_meta($post_id, 'imic_event_start_dt', true);
                $stime = get_post_meta($post_id, 'imic_event_start_tm', true);
				$edate = get_post_meta($post_id, 'imic_event_end_dt', true);
                $etime = get_post_meta($post_id, 'imic_event_end_tm', true);
                echo '<abbr title="'.$sdate .' '.$stime.'">'.$sdate.'</abbr><br title="'.$edate.' '.$etime.'">'.$edate;
                break;
			case 'Recurring':
                $frequency = get_post_meta($post_id, 'imic_event_frequency', true);
				$frequency_count = get_post_meta($post_id, 'imic_event_frequency_count', true);
				if($frequency==1){ $sent = "Every Day"; }
				elseif($frequency==2){ $sent = "Every 2nd Day"; }
				elseif($frequency==3){ $sent = "Every 3rd Day"; }
				elseif($frequency==4){ $sent = "Every 4th Day"; }
				elseif($frequency==5){ $sent = "Every 5th Day"; }
				elseif($frequency==6){ $sent = "Every 6th Day"; }
				elseif($frequency==30){ $sent = "Every Month"; }
				else{ $sent = "Every week"; }
				if($frequency>0) {
                echo '<abbr title="'.$sent .' '.$sent.'">'.$sent.'</abbr><br>'.$frequency_count.' time';
				}
                break;
        }
    }
}
if (!function_exists('imic_sortable_event_column')) {
    add_filter('manage_edit-event_sortable_columns', 'imic_sortable_event_column');
    function imic_sortable_event_column($columns) {
        $columns['Event'] = 'event';
        return $columns;
    }
}
//Event Recurring Date/Time
function imic_afterSavePost()
{
	if(isset($_GET['post']))
	 { 
	 $postId = $_GET['post'];
	$post_type = get_post_type($postId);
	if($post_type=='event')
	{
		
		$sdate = get_post_meta($postId,'imic_event_start_dt',true);
		$end_event_date = get_post_meta($postId,'imic_event_end_dt',true);
		if($end_event_date=='') { update_post_meta($postId,'imic_event_end_dt',$sdate); }
		$frequency = get_post_meta($postId,'imic_event_frequency',true);
		$frequency_count = get_post_meta($postId,'imic_event_frequency_count',true);
		$value = strtotime($sdate);
		if($frequency==30) {
		$svalue = strtotime("+".$frequency_count." month", $value);
		$suvalue = date('Y-m-d',$svalue);
		}
		else {
		$svalue = $frequency*$frequency_count*86400;
		$suvalue = $svalue+$value;
		$suvalue = date('Y-m-d',$suvalue);
		}
		update_post_meta($postId,'imic_event_frequency_end',$suvalue);
	} }
}
imic_afterSavePost();
//Add New Custom Menu Option
if ( !class_exists('IMIC_Custom_Nav')) {
class IMIC_Custom_Nav {
public function add_nav_menu_meta_boxes() {
   
add_meta_box(
'mega_nav_link',
__('Mega Menu','framework'),
array( $this, 'nav_menu_link'),
'nav-menus',
'side',
'low'
);
}
public function nav_menu_link() {
    
     global $_nav_menu_placeholder, $nav_menu_selected_id;
	$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;
    
        ?>
<div id="posttype-wl-login" class="posttypediv">
<div id="tabs-panel-wishlist-login" class="tabs-panel tabs-panel-active">
<ul id ="wishlist-login-checklist" class="categorychecklist form-no-clear">
<li>
<label class="menu-item-title">
<input type="checkbox" class="menu-item-object-id" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="<?php echo $_nav_menu_placeholder; ?>"> <?php _e('Create Column','framework'); ?>
</label>
    <input type="hidden" class="menu-item-db-id" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-db-id]" value="0">
    <input type="hidden" class="menu-item-object" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object]" value="page">
<input type="hidden" class="menu-item-parent-id" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-parent-id]" value="0">
   <input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="">
<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php _e('Column','framework'); ?>">
<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="custom_mega_menu">
</li>
</ul>
</div>
<p class="button-controls">
<span class="add-to-menu">
<input type="submit" class="button-secondary submit-add-to-menu right" value="<?php _e('Add to Menu','framework'); ?>" name="add-post-type-menu-item" id="submit-posttype-wl-login">
<span class="spinner"></span>
</span>
</p>
</div>
<?php }
}
}
$custom_nav = new IMIC_Custom_Nav;
add_action('admin_init', array($custom_nav, 'add_nav_menu_meta_boxes'));
//Get All Post Types
if(!function_exists('imic_get_all_types')){
add_action( 'wp_loaded', 'imic_get_all_types');
function imic_get_all_types(){
   $args = array(
   'public'   => true,
   );
$output = 'names'; // names or objects, note names is the default
return $post_types = get_post_types($args, $output); 
}
}
/* -------------------------------------------------------------------------------------
  Get Cat List.
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_get_cat_list')) {
    function imic_get_cat_list() {
        $amp_categories_obj = get_categories('exclude=1');
         
        $amp_categories = array();
        if(count($amp_categories_obj)>0){
        foreach ($amp_categories_obj as $amp_cat) {
            $amp_categories[$amp_cat->cat_ID] = $amp_cat->name;
        }}
        return $amp_categories;
    }
}
/* -------------------------------------------------------------------------------------
  Filter the Widget Title.
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_widget_titles')) {
    add_filter('dynamic_sidebar_params', 'imic_widget_titles', 20);
    function imic_widget_titles(array $params) {
        // $params will ordinarily be an array of 2 elements, we're only interested in the first element
        $widget = & $params[0];
        $id = $params[0]['id'];
        if ($id == 'footer-sidebar') {
            $widget['before_title'] = '<h4 class="widgettitle">';
            $widget['after_title'] = '</h4>';
        } else {
            $widget['before_title'] = '<div class="sidebar-widget-title"><h3 class="title">';
            $widget['after_title'] = '</h3></div>';
        }
        return $params;
    }
}
/* -------------------------------------------------------------------------------------
  Filter the Widget Text.
  ----------------------------------------------------------------------------------- */
add_filter('widget_text', 'do_shortcode');
/* -------------------------------------------------------------------------------------
Month Translate in Default.
  ----------------------------------------------------------------------------------- */
    if(!function_exists('imic_month_translate')){
function imic_month_translate( $str ) {
  
	$options = get_option('imic_options');
       $months = $options["calendar_month_name"];
    $months = explode(',',$months);
  if(count($months)<=1){
  $months = array("January","February","March","April","May","June","July","August","September","October","November","December");
}
$sb = array();
foreach($months as $month) { $sb[] = $month; } 
    $engMonth = array("January","February","March","April","May","June","July","August","September","October","November","December");
    $trMonth = $sb;
    $converted = str_replace($engMonth, $trMonth, $str);
    return $converted;
    }
    /* -------------------------------------------------------------------------------------
  Filter the  Month name of Post.
  ----------------------------------------------------------------------------------- */
add_filter( 'get_the_time', 'imic_month_translate' );
add_filter( 'the_date', 'imic_month_translate' );
add_filter( 'get_the_date', 'imic_month_translate' );
add_filter( 'comments_number', 'imic_month_translate' );
add_filter( 'get_comment_date', 'imic_month_translate' );
add_filter( 'get_comment_time', 'imic_month_translate' );
add_filter( 'date_i18n', 'imic_month_translate' );
}
/* -------------------------------------------------------------------------------------
  Short Month Translate in Default.
  ----------------------------------------------------------------------------------- */
if(!function_exists('imic_short_month_translate')){
function imic_short_month_translate( $str ) {
    
       $options = get_option('imic_options');
       $months = $options["calendar_month_name_short"];
    $months = explode(',',$months);
  if(count($months)<=1){
  $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
}
$sb = array();
foreach($months as $month) { $sb[] = $month; } 
    $engMonth = array("/\bJan\b/","/\bFeb\b/","/\bMar\b/","/\bApr\b/","/\bMay\b/","/\bJun\b/","/\bJul\b/","/\bAug\b/","/\bSep\b/","/\bOct\b/","/\bNov\b/","/\bDec\b/");
    $trMonth = $sb;
    $converted = preg_replace($engMonth, $trMonth, $str);
    return $converted;
}
/* -------------------------------------------------------------------------------------
  Filter the  Sort Month name of Post.
  ----------------------------------------------------------------------------------- */
add_filter( 'get_the_time', 'imic_short_month_translate' );
add_filter( 'the_date', 'imic_short_month_translate' );
add_filter( 'get_the_date', 'imic_short_month_translate' );
add_filter( 'comments_number', 'imic_short_month_translate' );
add_filter( 'get_comment_date', 'imic_short_month_translate' );
add_filter( 'get_comment_time', 'imic_short_month_translate' );
add_filter( 'date_i18n', 'imic_short_month_translate' );
}
 if(!function_exists('imic_day_translate')){
function imic_day_translate( $str ) {
	$options = get_option('imic_options');
       $days = $options["calendar_day_name"];
    $days = explode(',',$days);
  if(count($days)<=1){
  $days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
}
$sb = array();
foreach($days as $month) { $sb[] = $month; } 
    $engDay = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
    $trDay = $sb;
    $converted = str_replace($engDay, $trDay, $str);
    return $converted;
    }
    /* -------------------------------------------------------------------------------------
  Filter the  Day name of Post.
  ----------------------------------------------------------------------------------- */
add_filter('date_i18n', 'imic_day_translate');
}
/* -------------------------------------------------------------------------------------
  RevSlider ShortCode
  ----------------------------------------------------------------------------------- */
if(!function_exists('imic_RevSliderShortCode')){
function imic_RevSliderShortCode(){
     $slidernames = array();
    if(class_exists('RevSlider')){
     $sld = new RevSlider();
                $sliders = $sld->getArrSliders();
        if(!empty($sliders)){
           
        foreach($sliders as $slider){
          $title=$slider->getParam('title','false');
           $shortcode=$slider->getParam('shortcode','false');
            $slidernames[$shortcode]=$title;
        }}
           
}
return $slidernames;
        }}
/** -------------------------------------------------------------------------------------
 * Add Query Arg
 * @param  ID,param1,param2 of current Post.
 * @return  Url with Query arg which is passed default is event_date.
  ----------------------------------------------------------------------------------- */
if(!function_exists('imic_query_arg')){
 function imic_query_arg($date_converted,$id){
        $custom_event_url=add_query_arg('event_date',$date_converted,get_permalink($id));
    return $custom_event_url;
  }
}
/** -------------------------------------------------------------------------------------
 * Query Var Filter
 * @description event_date parameter is added to query_vars filter
----------------------------------------------------------------------------------- */
if(!function_exists('imicAddQueryVarsFilter')){
function imicAddQueryVarsFilter( $vars ){
  $vars[] = "event_date";
  $vars[] = "event_cat";
  $vars[] = "pg";
  $vars[] = "login";
  $vars[] = "guest";
  $vars[] = "print";
  return $vars;
}
add_filter('query_vars','imicAddQueryVarsFilter');
}
/** -------------------------------------------------------------------------------------
 * Convert the Format String from php to fullcalender
 * @see http://arshaw.com/fullcalendar/docs/utilities/formatDate/
 * @param $format
----------------------------------------------------------------------------------- */
if(!function_exists('ImicConvertDate')){
	 function ImicConvertDate($format) {
	 	$format_rules = array('a'=>'t',
			 'A'=>'T',
			 'B'=>'',
			 'c'=>'u',
			 'd'=>'dd',
			 'D'=>'ddd',
			 'F'=>'MMMM',
			 'g'=>'h',
			 'G'=>'H',
			 'h'=>'hh',
			 'H'=>'HH',
			 'i'=>'mm',
			 'I'=>'',
			 'j'=>'d',
			 'l'=>'dddd',
			 'L'=>'',
			 'm'=>'MM',
			 'M'=>'MMM',
			 'n'=>'M',
			 'O'=>'',
			 'r'=>'',
			 's'=>'ss',
			 'S'=>'S',
			 't'=>'',
			 'T'=>'',
			 'U'=>'',
			 'w'=>'',
			 'W'=>'',
			 'y'=>'yy',
			 'Y'=>'yyyy',
			 'z'=>'',
			 'Z'=>'');
	 	  $ret = '';
	 	for ($i=0; $i<strlen($format); $i++) {
	 		if (isset($format_rules[$format[$i]])) {
	 			$ret .= $format_rules[$format[$i]];
	 		} else {
	 			$ret .= $format[$i];
	 		}
	 	}
	 	return $ret;
}}
/** -------------------------------------------------------------------------------------
 * Return 0 if category have any post
 ----------------------------------------------------------------------------------- */
if(!function_exists('imic_cat_count_flag')){
function imic_cat_count_flag(){
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
             $flag=1;
              if(!empty($term)){
                 $flag= $output=($term->count==0)?0:1;
              }
             global $cat;
             if(!empty($cat)){
                  $cat_data= get_category($cat);
                $flag=($cat_data->count==0)?0:1;
             }
             return $flag;
}}
//Event Global Function
function imic_recur_events($status,$featured="nos",$term='',$month='') {
	$offset = get_option('timezone_string');
		if($offset=='') { $offset = "Australia/Melbourne"; }
	date_default_timezone_set($offset);
$featured = ($featured=="yes")?"no":"nos";
$today = date('Y-m-d');
if($month!="") {
	$stop_date = $month;
	$curr_month = date('Y-m-t', strtotime('-1 month', strtotime($stop_date)));
	$current_end_date = date('Y-m-d H:i:s', strtotime($stop_date . ' + 1 day'));
	$previous_month_end = strtotime(date('Y-m-d 00:01', strtotime($stop_date)));
	$next_month_start = strtotime(date('Y-m-d 00:01', strtotime('+1 month', strtotime($stop_date))));
	query_posts(array('post_type' => 'event','event-category' => $term,'meta_key' => 'imic_event_start_dt','meta_query' => array('relation' => 'AND',array('key' => 'imic_event_frequency_end','value' => $curr_month,'compare' => '>='),array('key' => 'imic_event_start_dt','value' => date('Y-m-t', strtotime($stop_date)),'compare' => '<=')),'orderby' => 'meta_value','order' => 'ASC','posts_per_page' => -1));
}
else {
if($status=='future') {
query_posts(array('post_type' => 'event', 'event-category'=>$term, 'meta_key' => 'imic_event_start_dt', 'meta_query' => array(array('key' => 'imic_event_frequency_end', 'value' => $today, 'compare' => '>='),array('key' => 'imic_featured_event', 'value' => $featured, 'compare' => '!=')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1)); }
else {
	query_posts(array('post_type' => 'event', 'event-category'=>$term, 'meta_key' => 'imic_event_start_dt', 'meta_query' => array(array('key' => 'imic_featured_event', 'value' => $featured, 'compare' => '!=')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1));
} }
					$event_add = array();
$sinc = 1;
if (have_posts()):
    while (have_posts()):the_post();
        $eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
        $frequency = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
        $frequency_count = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);
		$frequency_active = get_post_meta(get_the_ID(),'imic_event_frequency_type',true);
		$frequency_type = get_post_meta(get_the_ID(),'imic_event_frequency_type',true);
		$frequency_month_day = get_post_meta(get_the_ID(),'imic_event_day_month',true);
		$frequency_week_day = get_post_meta(get_the_ID(),'imic_event_week_day',true);
        if ($frequency_active > 0) {
            $frequency_count = $frequency_count;
        } else { $frequency_count = 0; }
        $seconds = $frequency * 86400;
        $fr_repeat = 0;
        while ($fr_repeat <= $frequency_count) {
            $eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
            $eventDate = strtotime($eventDate);
			if($frequency_type==1||$frequency_type==0) {
			if($frequency==30) {
			$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
			}
			else {
			$new_date = $seconds * $fr_repeat;
            $eventDate = $eventDate + $new_date;
			}
			}
			else {
				$eventTime = date('G:i',$eventDate);
				$eventDate = strtotime( date('Y-m-1',$eventDate) );
				if($fr_repeat==0) { $fr_repeat = $fr_repeat+1; }
			$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
			$next_month = date('F',$eventDate);
			$next_event_year = date('Y',$eventDate);
			//echo $next_month;
			$eventDate = date('Y-m-d '.$eventTime, strtotime($frequency_month_day.' '.$frequency_week_day.' of '.$next_month.' '.$next_event_year));
			//echo $eventDate;
			$eventDate = strtotime($eventDate);
			}
			if($month!='') {
				if(($eventDate > $previous_month_end) && ($eventDate < $next_month_start) && ($eventDate>=date('U'))){
					$event_add[$eventDate + $sinc] = get_the_ID();
               		$sinc++;
				}
			}else {
			if($status=="future") {
            if ($eventDate >= date('U')) {
                $event_add[$eventDate + $sinc] = get_the_ID();
                $sinc++;
            } }
			else {
			if ($eventDate <= date('U')) {
                $event_add[$eventDate + $sinc] = get_the_ID();
                $sinc++;
            } 	
			} } $fr_repeat++;
        } 
    endwhile;
    
endif; wp_reset_query(); return $event_add; }
//Event Status View Ajax Call
function imic_event_status_view() {
	$status = $_POST['status'];
	$status_number = explode("-",$status);
	$status = $status_number[0];
	$count = $status_number[1];
	$term = $_POST['term'];
	$featured = "";
	$offset = get_option('timezone_string');
		if($offset=='') { $offset = "Australia/Melbourne"; }
	date_default_timezone_set($offset);
$featured = ($featured=="yes")?"no":"nos";
$today = date('Y-m-d');
if($status=='future') {
query_posts(array('post_type' => 'event', 'event-category'=>$term, 'meta_key' => 'imic_event_start_dt', 'meta_query' => array(array('key' => 'imic_event_frequency_end', 'value' => $today, 'compare' => '>='),array('key' => 'imic_featured_event', 'value' => $featured, 'compare' => '!=')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1)); }
else {
	query_posts(array('post_type' => 'event', 'event-category'=>$term, 'meta_key' => 'imic_event_start_dt', 'meta_query' => array(array('key' => 'imic_featured_event', 'value' => $featured, 'compare' => '!=')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1));
} 
					$event_add = array();
$sinc = 1;
if (have_posts()):
    while (have_posts()):the_post();
        $eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
        $frequency = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
        $frequency_count = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);
		$frequency_active = get_post_meta(get_the_ID(),'imic_event_frequency_type',true);
		$frequency_type = get_post_meta(get_the_ID(),'imic_event_frequency_type',true);
		$frequency_month_day = get_post_meta(get_the_ID(),'imic_event_day_month',true);
		$frequency_week_day = get_post_meta(get_the_ID(),'imic_event_week_day',true);
        if ($frequency_active > 0) {
            $frequency_count = $frequency_count;
        } else { $frequency_count = 0; }
        $seconds = $frequency * 86400;
        $fr_repeat = 0;
        while ($fr_repeat <= $frequency_count) {
            $eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
            $eventDate = strtotime($eventDate);
			if($frequency_type==1||$frequency_type==0) {
			if($frequency==30) {
			$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
			}
			else {
			$new_date = $seconds * $fr_repeat;
            $eventDate = $eventDate + $new_date;
			}
			}
			else {
				$eventTime = date('G:i',$eventDate);
				$eventDate = strtotime( date('Y-m-1',$eventDate) );
				if($fr_repeat==0) { $fr_repeat = $fr_repeat+1; }
			$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
			$next_month = date('F',$eventDate);
			$next_event_year = date('Y',$eventDate);
			//echo $next_month;
			$eventDate = date('Y-m-d '.$eventTime, strtotime($frequency_month_day.' '.$frequency_week_day.' of '.$next_month.' '.$next_event_year));
			//echo $eventDate;
			$eventDate = strtotime($eventDate);
			}
			if($status=="future") {
            if ($eventDate >= date('U')) {
                $event_add[$eventDate + $sinc] = get_the_ID();
                $sinc++;
            } }
			else {
			if ($eventDate <= date('U')) {
                $event_add[$eventDate + $sinc] = get_the_ID();
                $sinc++;
            } 	
			}  $fr_repeat++;
        } 
    endwhile;
    
endif; wp_reset_query(); 
$title = ($status=="future")?__("Future Events","framework"):__("Past Events","framework");
	echo '<div class="listing-header">';
	if($status=="future") { 
					echo '<a href="javascript:" rel="'.$term.'" class="pastevents btn btn-default pull-right btn-sm" id="past-'.$count.'">'.__('Past','framework').'</a>'; } else {
					echo '<a href="javascript:" rel="'.$term.'" class="pastevents btn btn-default pull-right btn-sm" id="future-'.$count.'">'.__('Future','framework').'</a>'; }
                  	echo '<h2 class="title "><div class="title-border">'.$title.'</div></h2>';
		  echo '</div>
	  <ul class="upcoming-events listing-content">';
	if(!empty($event_add)) { 
		if($status=="future") { 
		ksort($event_add); } 
		else { krsort($event_add); } 
		$total = 1;
			foreach($event_add as $key=>$value) { $date_converted=date('Y-m-d',$key );
                				$custom_event_url= imic_query_arg($date_converted,$value);
								$style = '';
                            	echo '<li>
                                    <a href="'.esc_url($custom_event_url).'" class="event-details-btn"><i class="fa fa-angle-right"></i></a>';
                                	if ( '' != get_the_post_thumbnail($value) ) { 
									echo get_the_post_thumbnail($value,'80x80',array('class'=>'img-thumbnail event-thumb')); }
                                    echo '<span class="event-date">
                                    	<span class="day">'.esc_attr(date_i18n('d', $key)).'</span>
                                        <span class="monthyear">'.esc_attr(date_i18n('M, ', $key)).esc_attr(date_i18n('y', $key)).'</span>
                                   	</span>
                                    <div class="event-excerpt">
                                        <div class="event-cats meta-data">'.get_the_term_list($value, 'event-category', '', ', ', '' ).'</div>
                                    	<h5 class="event-title"><a href="'.esc_url($custom_event_url).'">'.get_the_title($value).'</a></h5>';
                                    	$address = get_post_meta($value,'imic_event_address2',true); if($address!='') { ?>
                                    <p class="event-location"><i class="fa fa-map-marker"></i> <?php echo $address; ?></p><?php }
                                    echo '</div>
                               	</li>'; if($total++>=$count) { break; } } } else { 
                                echo '<li class="item event-item">	
			  <div class="event-detail">
                        <h4>'.__("Sorry, there are no events for this month.","framework").'</h4>
                      </div>
                    </li>'; }
					echo '</ul>';
					die();
}
add_action('wp_ajax_nopriv_imic_event_status_view', 'imic_event_status_view');
add_action('wp_ajax_imic_event_status_view', 'imic_event_status_view');
//Get all Sidebars
if (!function_exists('imic_get_all_sidebars')) {
    function imic_get_all_sidebars() {
        $all_sidebars = array();
        global $wp_registered_sidebars;
        $all_sidebars = array('' => '');
        foreach ($wp_registered_sidebars as $sidebar) {
            $all_sidebars[$sidebar['id']] = $sidebar['name'];
        }
        return $all_sidebars;
    }
}
//Meta Box for Sidebar on all Posts/Page
if (!function_exists('imic_register_meta_box')) {
    add_action('admin_init', 'imic_register_meta_box');
    function imic_register_meta_box() {
        // Check if plugin is activated or included in theme
        if (!class_exists('RW_Meta_Box'))
            return;
        $prefix = 'imic_';
        $meta_box = array(
            'id' => 'template-sidebar1',
            'title' => __("Select Sidebar", 'framework'),
            'pages' => array('post', 'page', 'event', 'staff', 'product', 'project'),
            'context' => 'normal',
            'fields' => array(
                array(
                    'name' => 'Select Sidebar from list',
                    'id' => $prefix . 'select_sidebar_from_list',
                    'desc' => __("Select Sidebar from list, if using page builder then please add sidebar from element only.", 'framework'),
                    'type' => 'select',
                    'options' => imic_get_all_sidebars(),
                ),
            )
        );
        new RW_Meta_Box($meta_box);
    }
}
//Get Attachment details
if (!function_exists('imic_wp_get_attachment')) {
function imic_wp_get_attachment( $attachment_id ) {
	$attachment = get_post( $attachment_id );
	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink( $attachment->ID ),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	);
} }
/*AJAX LOGIN FORM FUNCTION
  ================================================================*/
if(!function_exists('imic_ajax_login_init')) {
function imic_ajax_login_init(){
    wp_register_script('ajax-login-script', get_template_directory_uri() . '/js/ajax-login-script.js', array('jquery') ); 
    wp_enqueue_script('ajax-login-script');
    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'loadingmessage' => __('Sending user info, please wait...','framework')
    ));
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'imic_ajax_login' );
}
if (!is_user_logged_in()) {
    add_action('init', 'imic_ajax_login_init');
} }
if(!function_exists('imic_ajax_login')) {
function imic_ajax_login(){
    check_ajax_referer( 'ajax-login-nonce', 'security' );
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
	if($_POST['rememberme']=='true') {
    $info['remember'] = true; }
	else{
	$info['remember'] = false; }
    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.','framework')));
    } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...','framework')));
    }
    die();
} }
/* Agent Register Funtion
  ================================================== */
function imic_agent_register() {
	if(!$_POST) exit;
	// Email address verification, do not edit.
	function isEmail($email) {
		return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
	}
	
	if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");
	
	$username     = $_POST['username'];
	$email    = $_POST['email'];
	$pwd1  = $_POST['pwd1'];
	$pwd2 = $_POST['pwd2'];
	
	if(trim($username) == '') {
		echo '<div class="alert alert-error">You must enter your username.</div>';
		exit();
	} else if(trim($email) == '') {
		echo '<div class="alert alert-error">You must enter email address.</div>';
		exit();
	} else if(!isEmail($email)) {
		echo '<div class="alert alert-error">You must enter a valid email address.</div>';
		exit();
	}else if(trim($pwd1) == '') {
		echo '<div class="alert alert-error">You must enter password.</div>';
		exit();
	}else if(trim($pwd2) == '') {
		echo '<div class="alert alert-error">You must enter repeat password.</div>';
		exit();
	}else if(trim($pwd1) != trim($pwd2)) {
		echo '<div class="alert alert-error">You must enter a same password.</div>';
		exit();
	}
	
	
	$err = '';
	$success = '';
	
	global $wpdb, $PasswordHash, $current_user, $user_ID;
	
	if (isset($_POST['task']) && $_POST['task'] == 'register') {
		$username = esc_sql(trim($_POST['username']));
		$pwd1 = esc_sql(trim($_POST['pwd1']));
		$pwd2 = esc_sql(trim($_POST['pwd2']));
		$email = esc_sql(trim($_POST['email']));
		
	   //Email properties
		$mail_to = get_option('admin_email');
		$mail_subject = $username ." registered successfully.";
		$mail_msg = "New user '". $username ."' registered for Event with email address '". $email ."'";
		
		if ($email == "" || $pwd1 == "" || $pwd2 == "" || $username == "") {
			$err = 'Please don\'t leave the required fields.';
		} else if ($pwd1 <> $pwd2) {
			$err = 'Password do not match.';
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$err = 'Invalid email address.';
		} else if (email_exists($email)) {
			$err = 'Email already exist.';
		} else {
	
			$user_id = wp_insert_user(
							array(
								'user_pass' => apply_filters('pre_user_user_pass', $pwd1), 
								'user_login' => apply_filters('pre_user_user_login', $username), 
								'user_email' => apply_filters('pre_user_user_email', $email), 
								'role' => 'subscriber')
							);
							
			if (is_wp_error($user_id)) {
				$err = 'Error on user creation.';
			} else {
				do_action('user_register', $user_id);
				$success = 'You\'re successfully register';
                                $info_register = array();
                                $info_register['user_login'] = $username;
                                $info_register['user_password'] = $pwd1;
                                wp_signon( $info_register, false );
                               }
		}
	}
	
	if (!empty($err)) :
		echo '<div class="alert alert-error">' . $err . '</div>';
	endif;
	
	if (!empty($success)) :
		wp_mail($mail_to,$mail_subject,$mail_msg);
		echo '<div class="alert alert-success">' . $success . '</div>';
	endif;
    die();
}
add_action('wp_ajax_nopriv_imic_agent_register', 'imic_agent_register');
add_action('wp_ajax_imic_agent_register', 'imic_agent_register');
/**
 * Disable admin bar on the frontend of your website
 * for subscribers.
 */
if(!function_exists('imic_disable_admin_bar')){
function imic_disable_admin_bar() { 
	if( ! current_user_can('edit_posts') )
        add_filter('show_admin_bar', '__return_false');	
}
add_action( 'after_setup_theme', 'imic_disable_admin_bar' );
}
 /**
 * Redirect back to homepage and not allow access to 
 * WP admin for Subscribers.
 */
if(!function_exists('imic_redirect_admin')){
function imic_redirect_admin(){
	if ( ! current_user_can( 'edit_posts' ) &&! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ){
		wp_redirect( site_url() );
exit;
}
}
add_action( 'admin_init', 'imic_redirect_admin' );   
}
/** -------------------------------------------------------------------------------------
 * Gallery Flexslider
 * @param ID of current Post.
 * @return Div with flexslider parameter.
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_gallery_flexslider')) {
    function imic_gallery_flexslider($id) {
		$speed = (get_post_meta(get_the_ID(), 'imic_gallery_slider_speed', true)!='')?get_post_meta(get_the_ID(), 'imic_gallery_slider_speed', true):5000;
        $pagination = get_post_meta(get_the_ID(), 'imic_gallery_slider_pagination', true);
        $auto_slide = get_post_meta(get_the_ID(), 'imic_gallery_slider_auto_slide', true);
        $direction = get_post_meta(get_the_ID(), 'imic_gallery_slider_direction_arrows', true);
        $effect = get_post_meta(get_the_ID(), 'imic_gallery_slider_effects', true);
        $pagination = !empty($pagination) ? $pagination : 'yes';
        $auto_slide = !empty($auto_slide) ? $auto_slide : 'yes';
        $direction = !empty($direction) ? $direction : 'yes';
        $effect = !empty($effect) ? $effect : 'slide';
        return '<div class="flexslider" data-autoplay="' . $auto_slide . '" data-pagination="' . $pagination . '" data-arrows="' . $direction . '" data-style="' . $effect . '" data-pause="yes" data-speed='.$speed.'>';
    }
}
//Event Preview function for Calendar
function imic_get_event_details() {
	$offset = get_option('timezone_string');
		if($offset=='') { $offset = "Australia/Melbourne"; }
	date_default_timezone_set($offset);
	$id = $_POST['id'];
	$data = explode("|",$id);
	$id = $data[0];
	$key = $data[1];
	$output = '';
	$custom_event_url = '';
	//$key = '';
	$date_converted=date('Y-m-d',$key );
    $custom_event_url= imic_query_arg($date_converted,$id);
	$output .= '<ul class=" events-grid events-ajax-caller">';
	$output .= '<li>';
	$output .= '<div class="grid-item-inner">';
	$output .= '<div class="preview-event-bar">
                            <div id="counter1" class="counter-preview top-header" data-date="'.$key.'">
                         		<div class="timer-col"> <span id="days"></span> <span class="timer-type">'.__('d','framework').'</span> </div>
                        		<div class="timer-col"> <span id="hours"></span> <span class="timer-type">'.__('h','framework').'</span> </div>
                      			<div class="timer-col"> <span id="minutes"></span> <span class="timer-type">'.__('m','framework').'</span> </div>
                         		<div class="timer-col"> <span id="seconds"></span> <span class="timer-type">'.__('s','framework').'</span> </div>
                            </div>
                        </div>';
	if ( '' != get_the_post_thumbnail($id) ) { 
	$output .= '<a href="'.esc_url($custom_event_url).'" class="media-box">'.get_the_post_thumbnail($id,'full').'</a>'; }
	$address1 = get_post_meta($id,'imic_event_address1',true);
	$address2 = get_post_meta($id,'imic_event_address2',true);
   	$output .= '<ul class="info-cols clearfix">';
  	$output .= '<li><a href="#" data-toggle="tooltip" data-placement="top" title="'.date_i18n(get_option('date_format'), $key).'"><i class="fa fa-calendar"></i></a></li><li><a href="#" data-toggle="tooltip" data-placement="top" title="'.date_i18n(get_option('time_format'), $key).'"><i class="fa fa-clock-o"></i></a></li>';
	if($address2!='') {
  	$output .= '<li><a href="#" data-toggle="tooltip" data-placement="top" title="'.$address2.'"><i class="fa fa-map-marker"></i></a></li>';
	} if($address1!='') {
	$output .= '<li><a href="#" data-toggle="tooltip" data-placement="top" title="'.$address1.'"><i class="fa fa-flag"></i></a></li>'; }
    $output .= '</ul><div class="grid-content"><h3><a href="'.esc_url($custom_event_url).'">'.get_the_title($id).'</a></h3>';
    $page_data = get_page( $id );
	$excerpt = strip_tags($page_data->post_excerpt);
	$output .= $excerpt;
	$output .= '<br/><a class="btn btn-sm btn-default" href="'.$custom_event_url.'">'.__('More','framework').'</a>';
    $output .= '</div></div></li></ul>';
	echo $output;
	die();
}
add_action('wp_ajax_nopriv_imic_get_event_details', 'imic_get_event_details');
add_action('wp_ajax_imic_get_event_details', 'imic_get_event_details');

 /**
 * IMIC SEARCH BUTTON
 */
if(!function_exists('imic_search_button_header')){
function imic_search_button_header(){
global $imic_options;
			
            echo '<div class="search-module hidden-xs">
                	<a href="#" class="search-module-trigger"><i class="fa fa-search"></i></a>
                    <div class="search-module-opened">
                    	 <form method="get" id="searchform" action="' .home_url('/').'/">
                        	<div class="input-group input-group-sm">
                        		<input type="text" name="s" id="s" class="form-control">
                            	<span class="input-group-btn"><button name ="submit" type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button></span>
                       		</div>
                        </form>
                    </div>
                </div>';
	}
}
 /**
 * IMIC CART BUTTON
 */

if(!function_exists('imic_cart_button_header')){
function imic_cart_button_header(){
global $imic_options, $woocommerce;
			if(class_exists('Woocommerce')):
				if(!$woocommerce->cart->cart_contents_count): ?>
					<div class="cart-module hidden-xs">
						<a href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>" class="cart-module-trigger"><i class="fa fa-shopping-cart"></i></a>
					</div>
				<?php else: ?>
				<div class="cart-module hidden-xs">
						<a href="#" class="cart-module-trigger" id="cart-module-trigger"><i class="fa fa-shopping-cart"></i><span class="cart-tquant"><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'framework'), $woocommerce->cart->cart_contents_count);?></span></a>
						<div class="cart-module-opened">
							<ul class="cart-module-items">
                            	<?php
									$count = 1;
								 foreach($woocommerce->cart->cart_contents as $cart_item): ?>
                                    <li>
                                        <a href="<?php echo get_permalink($cart_item['product_id']); ?>">
                                        <?php $thumbnail_id = ($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id']; ?>				
                                        <?php echo get_the_post_thumbnail($thumbnail_id, 'theme-small-thumb'); ?>
                                        <span class="cart-module-item-name"><?php echo $cart_item['data']->post->post_title; ?></span>
                                        <span class="cart-module-item-quantity"><?php echo $cart_item['quantity']; ?> x <?php echo $woocommerce->cart->get_product_subtotal($cart_item['data'], 1); ?></span>
                                        </a>
                                    </li>
                                <?php if ($count++ > 2){break;}  endforeach; ?>
							</ul>
							<div class="cart-module-footer">
								<a href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>" class="basic-link"><i class="fa fa-long-arrow-left"></i> <?php _e('View full cart', 'framework'); ?></a>
								<a href="<?php echo get_permalink(get_option('woocommerce_checkout_page_id')); ?>" class="basic-link"><?php _e('Checkout', 'framework'); ?> <i class="fa fa-long-arrow-right"></i></a>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php endif;
	}
}
 /**
 * IMIC SHARE BUTTONS
 */
if(!function_exists('imic_share_buttons')){
function imic_share_buttons(){
$posttitle = get_the_title();
$postpermalink = get_permalink();
$postexcerpt = get_the_excerpt();
global $imic_options;
			
            echo '<div class="share-bar">';
			if($imic_options['sharing_style'] == '0'){
				if($imic_options['sharing_color'] == '0'){
            		echo '<ul class="share-buttons">';
				}elseif($imic_options['sharing_color'] == '1'){
            		echo '<ul class="share-buttons share-buttons-tc">';
				}elseif($imic_options['sharing_color'] == '2'){
            		echo '<ul class="share-buttons share-buttons-gs">';
				}
			} elseif($imic_options['sharing_style'] == '1'){
				if($imic_options['sharing_color'] == '0'){
            		echo '<ul class="share-buttons share-buttons-squared">';
				}elseif($imic_options['sharing_color'] == '1'){
            		echo '<ul class="share-buttons share-buttons-tc share-buttons-squared">';
				}elseif($imic_options['sharing_color'] == '2'){
            		echo '<ul class="share-buttons share-buttons-gs share-buttons-squared">';
				}
			};
                	echo '<li class="share-title"><i class="fa fa-share-alt fa-2x"></i></li>';
					if($imic_options['share_icon']['1'] == '1'){
                   		echo '<li class="facebook-share"><a href="https://www.facebook.com/sharer/sharer.php?u=' . $postpermalink . '&t=' . $posttitle . '" target="_blank" title="Share on Facebook"><i class="fa fa-facebook"></i></a></li>';
					}
					if($imic_options['share_icon']['2'] == '1'){
                     	echo '<li class="twitter-share"><a href="https://twitter.com/intent/tweet?source=' . $postpermalink . '&text=' . $posttitle . ':' . $postpermalink . '" target="_blank" title="Tweet"><i class="fa fa-twitter"></i></a></li>';
					}
					if($imic_options['share_icon']['3'] == '1'){
                    echo '<li class="google-share"><a href="https://plus.google.com/share?url=' . $postpermalink . '" target="_blank" title="Share on Google+"><i class="fa fa-google-plus"></i></a></li>';
					}
					if($imic_options['share_icon']['4'] == '1'){
                    	echo '<li class="tumblr-share"><a href="http://www.tumblr.com/share?v=3&u=' . $postpermalink . '&t=' . $posttitle . '&s=" target="_blank" title="Post to Tumblr"><i class="fa fa-tumblr"></i></a></li>';
					}
					if($imic_options['share_icon']['5'] == '1'){
                    	echo '<li class="pinterest-share"><a href="http://pinterest.com/pin/create/button/?url=' . $postpermalink . '&description=' . $postexcerpt . '" target="_blank" title="Pin it"><i class="fa fa-pinterest"></i></a></li>';
					}
					if($imic_options['share_icon']['6'] == '1'){
                    	echo '<li class="reddit-share"><a href="http://www.reddit.com/submit?url=' . $postpermalink . '&title=' . $posttitle . '" target="_blank" title="Submit to Reddit"><i class="fa fa-reddit"></i></a></li>';
					}
					if($imic_options['share_icon']['7'] == '1'){
                    	echo '<li class="linkedin-share"><a href="http://www.linkedin.com/shareArticle?mini=true&url=' . $postpermalink . '&title=' . $posttitle . '&summary=' . $postexcerpt . '&source=' . $postpermalink . '" target="_blank" title="Share on LinkedIn"><i class="fa fa-linkedin"></i></a></li>';
					}
					if($imic_options['share_icon']['8'] == '1'){
                    	echo '<li class="email-share"><a href="mailto:?subject=' . $posttitle . '&body=' . $postexcerpt . ':' . $postpermalink . '" target="_blank" title="Email"><i class="fa fa-envelope"></i></a></li>';
					}
                echo '</ul>
            </div>';
	}
}
?>