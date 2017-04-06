<!DOCTYPE html>
<!--// OPEN HTML //-->
<html <?php language_attributes(); ?> class="no-js">
    <head>
        <?php
        $options = get_option('imic_options');
        /** Theme layout design * */
        $bodyClass = ($options['site_layout'] == 'boxed') ? ' boxed' : '';
        $style='';
       if($options['site_layout'] == 'boxed'){
            if (!empty($options['upload-repeatable-bg-image']['id'])) {
            $style = ' style="background-image:url(' . $options['upload-repeatable-bg-image']['url'] . '); background-repeat:repeat; background-size:auto;"';
        } else if (!empty($options['full-screen-bg-image']['id'])) {
            $style = ' style="background-image:url(' . $options['full-screen-bg-image']['url'] . '); background-repeat: no-repeat; background-size:cover;"';
        }
           else if(!empty($options['repeatable-bg-image'])) {
            $style = ' style="background-image:url(' . get_template_directory_uri() . '/images/patterns/' . $options['repeatable-bg-image'] . '); background-repeat:repeat; background-size:auto;"';
        }
        }
        ?>
        <!--// SITE TITLE //-->
        <title>
            <?php wp_title('|', true, 'right'); ?>
<?php bloginfo('name'); ?>
        </title>
        <!--// SITE META //-->
        <meta charset="<?php bloginfo('charset'); ?>" />
        <!-- Mobile Specific Metas
        ================================================== -->
<?php if ($options['switch-responsive'] == 1) { ?>
            <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
            <meta name="format-detection" content="telephone=no"><?php } ?>
        <!--// PINGBACK & FAVICON //-->
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <?php if (isset($options['custom_favicon']) && $options['custom_favicon'] != "") { ?><link rel="shortcut icon" href="<?php echo $options['custom_favicon']['url']; ?>" /><?php
        }
        $offset = get_option('timezone_string');
		if($offset=='') { $offset = "Australia/Melbourne"; }
		date_default_timezone_set($offset);
        ?>
        <!-- CSS
        ================================================== -->
        <!--[if lte IE 9]><link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie.css" media="screen" /><![endif]-->
        <?php //  WORDPRESS HEAD HOOK 
         wp_head(); ?>
    </head>
    <!--// CLOSE HEAD //-->
    <?php $HeaderLayout = 'header-v'.$options['header_layout']; ?>
    <body <?php body_class($bodyClass); echo $style;  ?>>
        <!--[if lt IE 7]>
	<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
<![endif]-->
<div class="body <?php echo $HeaderLayout; ?>"> 
	<!-- Start Site Header -->
    <?php $menu_locations = get_nav_menu_locations(); ?>
	<header class="site-header">
    	<?php if ($options['enable-top-bar'] == 1): ?>
    	<div class="top-header hidden-xs">
        	<div class="container">
            <div class="row">
            <div class="col-md-6">
            <?php if($options['enable_countdown']==1) { 
             $next_event_time="";
					$events = imic_recur_events('future','',''); ksort($events); foreach($events as $key=>$value) { $next_event_time = $key; break; } ?>
                    <?php if(!empty($next_event_time)){ ?>
                    	<div class="upcoming-event-bar">
                        	<h4><i class="fa fa-calendar"></i><?php _e(' Next Event','framework'); ?></h4>
                            <div id="counter" class="counter" data-date="<?php echo $next_event_time; ?>">
                         		<div class="timer-col"> <span id="days"></span><span class="timer-type"><?php _e('d','framework'); ?></span></div>
                        		<div class="timer-col"> <span id="hours"></span><span class="timer-type"><?php _e('h','framework'); ?></span></div>
                      			<div class="timer-col"> <span id="minutes"></span><span class="timer-type"><?php _e('m','framework'); ?></span></div>
                         		<div class="timer-col"> <span id="seconds"></span><span class="timer-type"><?php _e('s','framework'); ?></span></div>
                            </div>
                        </div>
                    <?php  } }
					else {
						echo '<div class="top-custom-text">';
						echo $options['header_left_text'];	
						echo '</div>';
					}
					 	echo '</div>'; 
						echo '<div class="col-md-6">';
						if (!empty($menu_locations['top-menu'])) {
                    	wp_nav_menu(array('theme_location' => 'top-menu', 'container' => '','items_wrap' => '<ul id="%1$s" class="top-menu">%3$s</ul>')); }
						echo '<ul class="social-links social-links-lighter">';
						$socialSites = $options['header_social_links'];
						foreach ($socialSites as $key => $value) {
						if (filter_var($value, FILTER_VALIDATE_URL)) {
                      echo '<li><a href="' . $value . '" target="_blank"><i class="fa ' . $key . '"></i></a></li>';
								}
							}
                        echo '</ul></div>';  ?>
                </div>
           	</div>
       	</div>
        <?php endif; ?>
        <?php if ($options['header_layout'] == 1): ?>
    	<div class="lower-header">
        	<div class="container for-navi">
                    	<h1 class="logo">
                        	<?php
                                    global $imic_options;
                                    if (!empty($imic_options['logo_upload']['url'])) {
                                        echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="default-logo"><img src="' . $imic_options['logo_upload']['url'] . '" alt="Logo"></a>';
                                    } else {
                                        echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="default-logo"><img src="' . get_template_directory_uri() . '/images/logo.png" alt="Logo"></a>';
                                    }
                                    ?>
                                    <?php
                                    global $imic_options;
                                    if (!empty($imic_options['retina_logo_upload']['url'])) {
                                        echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="retina-logo"><img src="' . $imic_options['retina_logo_upload']['url'] . '" alt="Logo"></a>';
                                    } else {
                                        echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="retina-logo"><img src="' . get_template_directory_uri() . '/images/logo@2x.png" alt="Logo"></a>';
                                    }
                           	?>
                        </h1>
                <?php if ($imic_options['enable-search'] == 1) {
                    imic_search_button_header();
                } ?>
                <?php if ($imic_options['enable-cart'] == 1) {
                   echo imic_cart_button_header();
                } ?>
                    <?php if (!empty($menu_locations['primary-menu'])) { ?>
                    <!-- Main Navigation -->
                    	<nav class="main-navigation">
                    		<?php wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'sf-menu', 'container' => '', 'walker' => new imic_mega_menu_walker)); ?>
                    	</nav>
                        <a href="#" class="visible-sm visible-xs" id="menu-toggle"><i class="fa fa-bars"></i></a>
                    <?php } ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($options['header_layout'] == 2): ?>
    	<div class="lower-header">
        	<div class="container for-navi">
                <h1 class="logo">
                    <?php
                            global $imic_options;
                            if (!empty($imic_options['logo_upload']['url'])) {
                                echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="default-logo"><img src="' . $imic_options['logo_upload']['url'] . '" alt="Logo"></a>';
                            } else {
                                echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="default-logo"><img src="' . get_template_directory_uri() . '/images/logo.png" alt="Logo"></a>';
                            }
                            ?>
                            <?php
                            global $imic_options;
                            if (!empty($imic_options['retina_logo_upload']['url'])) {
                                echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="retina-logo"><img src="' . $imic_options['retina_logo_upload']['url'] . '" alt="Logo"></a>';
                            } else {
                                echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="retina-logo"><img src="' . get_template_directory_uri() . '/images/logo@2x.png" alt="Logo"></a>';
                            }
                    ?>
                </h1>
                <?php if ($imic_options['enable-search'] == 1) {
                    imic_search_button_header();
                } ?>
                <?php if ($imic_options['enable-cart'] == 1) {
                   echo imic_cart_button_header();
                } ?>
              	<?php if (!empty($menu_locations['primary-menu'])) { ?>
                	<!-- Main Navigation -->
                    <nav class="main-navigation">
                        <?php wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'sf-menu', 'container' => '', 'walker' => new imic_mega_menu_walker)); ?>
                    </nav>
                    <a href="#" class="visible-sm visible-xs" id="menu-toggle"><i class="fa fa-bars"></i></a>
                <?php } ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($options['header_layout'] == 3): ?>
    	<div class="lower-header">
        	<div class="container for-navi">
                <h1 class="logo">
                    <?php
                            global $imic_options;
                            if (!empty($imic_options['logo_upload']['url'])) {
                                echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="default-logo"><img src="' . $imic_options['logo_upload']['url'] . '" alt="Logo"></a>';
                            } else {
                                echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="default-logo"><img src="' . get_template_directory_uri() . '/images/logo.png" alt="Logo"></a>';
                            }
                            ?>
                            <?php
                            global $imic_options;
                            if (!empty($imic_options['retina_logo_upload']['url'])) {
                                echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="retina-logo"><img src="' . $imic_options['retina_logo_upload']['url'] . '" alt="Logo"></a>';
                            } else {
                                echo '<a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '" class="retina-logo"><img src="' . get_template_directory_uri() . '/images/logo@2x.png" alt="Logo"></a>';
                            }
                    ?>
                </h1>
                <ul class="social-links social-links-lighter pull-right hidden-xs hidden-sm">
                    <?php $socialSites = $options['header_social_links'];
						foreach ($socialSites as $key => $value) {
							if (filter_var($value, FILTER_VALIDATE_URL)) {
								echo '<li><a href="' . $value . '" target="_blank"><i class="fa ' . $key . '"></i></a></li>';
							}
						} ?>
                </ul>
            	<!-- Mobile Menu Trigger Icon -->
                <a href="#" class="visible-sm visible-xs" id="menu-toggle"><i class="fa fa-bars"></i></a>
            </div>
        </div>
        <!-- Full Width Menu -->
        <div class="full-width-menu accent-bg">
        	<div class="container">
                <?php if ($imic_options['enable-search'] == 1) {
                    imic_search_button_header();
                } ?>
                <?php if ($imic_options['enable-cart'] == 1) {
                   echo imic_cart_button_header();
                } ?>
              	<?php if (!empty($menu_locations['primary-menu'])) { ?>
                	<!-- Main Navigation -->
                    <nav class="main-navigation">
                        <?php wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'sf-menu', 'container' => '', 'walker' => new imic_mega_menu_walker)); ?>
                    </nav>
                <?php } ?>
           	</div>
    	</div>
        <?php endif; ?>
	</header>
	<!-- End Site Header -->