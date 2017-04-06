<?php
/*
Template Name: Events
*/
get_header();
wp_enqueue_script('imic_jquery_flexslider');
if(is_home()) { $id = get_option('page_for_posts'); }
else { $id = get_the_ID(); }
$page_header = get_post_meta($id,'imic_pages_Choose_slider_display',true);
if($page_header==3) {
	get_template_part( 'pages', 'flex' );
}
elseif($page_header==4) {
	get_template_part( 'pages', 'nivo' );
}
elseif($page_header==5) {
	get_template_part( 'pages', 'revolution' );
}
else {
	get_template_part( 'pages', 'banner' );
} 
$pageSidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list', true);
if(!empty($pageSidebar)&&is_active_sidebar($pageSidebar)) {
$class = 9;  
}else{
$class = 12;  
}
$event_layout = get_post_meta(get_the_ID(),'imic_event_layout_type',true);
$event_column = get_post_meta(get_the_ID(),'imic_temp_event_columns_layout',true);
$event_featured_bar = get_post_meta(get_the_ID(),'imic_event_featured_bar',true);
$featured_events = imic_recur_events("future","yes",'');
$next_events = imic_recur_events('past',"",'');
if($event_featured_bar==1) {
?>
<div class="secondary-bar">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-5">
                	<h4><?php _e('Featured Events','framework'); ?></h4>
                    <div class="flexslider" data-arrows="yes" data-style="slide" id="featured-events">
                        <ul class="upcoming-events slides">
                           <?php ksort($featured_events); foreach($featured_events as $key=>$value) { $date_converted=date('Y-m-d',$key );
						   	$style = '';
                			$custom_event_url= imic_query_arg($date_converted,$value); ?>
                            <li><?php if ( '' != get_the_post_thumbnail($value) ) { echo get_the_post_thumbnail($value,'80x80',array('class'=>'img-thumbnail event-thumb')); } else { $style = "style=\"opacity:1;\""; } ?>
                                <span class="event-date" <?php echo $style; ?>>
                                    <span class="day"><?php echo esc_attr(date_i18n('d', $key)); ?></span>
                                    <span class="monthyear"><?php echo esc_attr(date_i18n('M, ', $key)); echo esc_attr(date_i18n('y', $key)); ?></span>
                                </span>
                                <div class="event-excerpt">
                                    <div class="event-cats meta-data"><?php echo get_the_term_list($value, 'event-category', '', ', ', '' ); ?></div>
                                    <h5 class="event-title"><a href="<?php echo esc_url($custom_event_url); ?>"><?php echo get_the_title($value); ?></a></h5>
                                    <?php $address = get_post_meta($value,'imic_event_address2',true); if($address!='') { ?>
                                    <p class="event-location"><i class="fa fa-map-marker"></i> <?php echo $address; ?></p><?php } ?>
                                </div>
                            </li><?php } ?>
                        </ul>
                    </div>
                </div>
            	<div class="col-md-6 col-md-offset-1">
                	<h4><?php _e('Recently passed events','framework'); ?></h4>
                    <ul class="passed-events angles">
                    <?php krsort($next_events); $total = 1; foreach($next_events as $key=>$value) { $date_converted=date('Y-m-d',$key );
                $custom_event_url= imic_query_arg($date_converted,$value); ?>
                    	<li><a href="<?php echo esc_url($custom_event_url); ?>"><?php echo get_the_title($value); ?></a></li>
                    <?php if (++$total > 5) { break; } } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php } 
if($event_layout=='0') {
wp_enqueue_script('event_ajax', IMIC_THEME_PATH . '/js/event_ajax.js', '', '', true);
wp_localize_script('event_ajax', 'urlajax', array('ajaxurl' => admin_url('admin-ajax.php')));
$event_category = get_post_meta(get_the_ID(),'imic_advanced_event_list_taxonomy',true);
if($event_category!=''){
$event_categories= get_term_by('id',$event_category,'event-category');
if(!empty($event_categories)){
$event_category= $event_categories->slug; }}
$currentEventTime = date('Y-m');
		$prev_month = date('Y-m', strtotime('-1 month', strtotime($currentEventTime)));
		$next_months = date('Y-m', strtotime('+1 month', strtotime($currentEventTime)));
		$today = date('Y-m-d');
		$stop_date = date('Y-m-t');
		$current_end_date = date('Y-m-d H:i:s', strtotime($stop_date . ' + 1 day'));
		$sinc = 1;				  
				  $currentTime = date('Y-m-d G:i');
				  query_posts(array(
								'post_type' => 'event',
								'event-category' => $event_category,
								'meta_key' => 'imic_event_start_dt',
								'meta_query' => array(
										'relation' => 'AND',
										array(
											'key' => 'imic_event_frequency_end',
											'value' => $today,
											'compare' => '>='
										),
										array(
											'key' => 'imic_event_start_dt',
											'value' => $current_end_date,
											'compare' => '<'
										)
								),
								'orderby' => 'meta_value',
								'order' => 'ASC',
								'posts_per_page' => -1
							)
				  ); $count = 0;
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
			
					if(($eventDate > strtotime($currentTime)) && ($eventDate >= strtotime($today))&& ($eventDate <= strtotime($current_end_date))){
					$events[$eventDate+$sinc] = get_the_ID();
					$sinc++; $count++; }
					$fr_repeat++; } 
					endwhile; 
				  } wp_reset_query();
?>
<div class="main" role="main">
    	<div id="content" class="content full">
    		<div class="container">
        		<div class="row">
            		<div class="col-md-<?php echo $class; ?>">
						<div class="page-content"><?php the_content(); ?></div>
						<div class="events-listing" id="ajax_events">
                            <div class="listing-header">
                                <h2 class="title "><?php _e('Monthly Events','framework'); ?> <label class="label label-primary"><?php echo date_i18n('F', strtotime($currentEventTime)); ?></label></h2>
                                
                                <nav class="nav-np">
                                    <a href="javascript:" class="upcomingEvents" rel="" id="<?php echo $prev_month; ?>"><i class="fa fa-angle-left"></i></a>
                    					<a href="javascript:" class="upcomingEvents" rel="" id="<?php echo $next_months; ?>"><i class="fa fa-angle-right"></i></a>
                                </nav>
                            </div>
                            <ul class="upcoming-events listing-content">
                            <?php 
								if(!empty($events)) { ksort($events); foreach($events as $key=>$value) { $date_converted=date('Y-m-d',$key );
                				$custom_event_url= imic_query_arg($date_converted,$value);
								$style = ''; ?>
                            	<li>
                                    <a href="<?php echo esc_url($custom_event_url); ?>" class="event-details-btn"><i class="fa fa-angle-right"></i></a>
                                	<?php if ( '' != get_the_post_thumbnail($value) ) { echo get_the_post_thumbnail($value,'80x80',array('class'=>'img-thumbnail event-thumb')); } else  { $style = "style=\"opacity:1;\""; } ?>
                                    <span class="event-date" <?php echo $style; ?>>
                                    	<span class="day"><?php echo esc_attr(date_i18n('d', $key)); ?></span>
                                    <span class="monthyear"><?php echo esc_attr(date_i18n('M, ', $key)); echo esc_attr(date_i18n('y', $key)); ?></span>
                                   	</span>
                                    <div class="event-excerpt">
                                        <div class="event-cats meta-data"><?php echo get_the_term_list($value, 'event-category', '', ', ', '' ); ?></div>
                                    <h5 class="event-title"><a href="<?php echo esc_url($custom_event_url); ?>"><?php echo get_the_title($value); ?></a></h5>
                                    <?php $address = get_post_meta($value,'imic_event_address2',true); if($address!='') { ?>
                                    <p class="event-location"><i class="fa fa-map-marker"></i> <?php echo $address; ?></p><?php } ?>
                                    </div>
                               	</li><?php } } else { ?>
                                <li class="item event-item">	
			  <div class="event-detail">
                        <h4><?php _e("Sorry, there are no events for this month.","framework"); ?></h4>
                      </div>
                    </li><?php } ?>
                            	</ul>
</div>
</div>
<?php if(is_active_sidebar($pageSidebar)) { ?>
                    <!-- Sidebar -->
                    <div class="col-md-3 sidebar right-sidebar">
                    	<?php dynamic_sidebar($pageSidebar); ?>
                    </div>
                    <?php } ?>
				</div>
			</div>
	</div>
</div>
<?php } 
elseif($event_layout=='2') {
wp_enqueue_script('event_ajax', IMIC_THEME_PATH . '/js/event_ajax.js', '', '', true);
wp_localize_script('event_ajax', 'urlajax', array('ajaxurl' => admin_url('admin-ajax.php')));
$event_category = get_post_meta(get_the_ID(),'imic_advanced_event_list_taxonomy',true);
$event_count = get_post_meta(get_the_ID(),'imic_events_count',true);
$event_count = ($event_count!='')?$event_count:10;
if($event_category!=''){
$event_categories= get_term_by('id',$event_category,'event-category');
if(!empty($event_categories)){
$event_category= $event_categories->slug; }}
$events = imic_recur_events('future','','');
?>
<div class="main" role="main">
    	<div id="content" class="content full">
    		<div class="container">
        		<div class="row">
            		<div class="col-md-<?php echo $class; ?>">
						<div class="page-content"><?php the_content(); ?></div>
						<div class="events-listing" id="ajax_events">
                            <div class="listing-header">
                            	<a href="javascript:" class="pastevents btn btn-default pull-right btn-sm" rel="<?php echo $event_category; ?>" id="past-<?php echo $event_count; ?>"><?php _e('Past','framework'); ?></a>
                                <h2 class="title "><?php _e('Future Events','framework'); ?></h2>
                            </div>
                            <ul class="upcoming-events listing-content">
                            <?php 
								if(!empty($events)) { 
								$total = 1;
								ksort($events); foreach($events as $key=>$value) { 
								$date_converted=date('Y-m-d',$key );
                				$custom_event_url= imic_query_arg($date_converted,$value);
								$style = ''; ?>
                            	<li>
                                    <a href="<?php echo esc_url($custom_event_url); ?>" class="event-details-btn"><i class="fa fa-angle-right"></i></a>
                                	<?php if ( '' != get_the_post_thumbnail($value) ) { echo get_the_post_thumbnail($value,'80x80',array('class'=>'img-thumbnail event-thumb')); } else  { $style = "style=\"opacity:1;\""; } ?>
                                    <span class="event-date" <?php echo $style; ?>>
                                    	<span class="day"><?php echo esc_attr(date_i18n('d', $key)); ?></span>
                                    <span class="monthyear"><?php echo esc_attr(date_i18n('M, ', $key)); echo esc_attr(date_i18n('y', $key)); ?></span>
                                   	</span>
                                    <div class="event-excerpt">
                                        <div class="event-cats meta-data"><?php echo get_the_term_list($value, 'event-category', '', ', ', '' ); ?></div>
                                    <h5 class="event-title"><a href="<?php echo esc_url($custom_event_url); ?>"><?php echo get_the_title($value); ?></a></h5>
                                    <?php $address = get_post_meta($value,'imic_event_address2',true); if($address!='') { ?>
                                    <p class="event-location"><i class="fa fa-map-marker"></i> <?php echo $address; ?></p><?php } ?>
                                    </div>
                               	</li><?php if($total++>=$event_count) { break; } } } else { ?>
                                <li class="item event-item">	
			  <div class="event-detail">
                        <h4><?php _e("Sorry, there are no events for this month.","framework"); ?></h4>
                      </div>
                    </li><?php } ?>
                            	</ul>
</div>
</div>
<?php if(is_active_sidebar($pageSidebar)) { ?>
                    <!-- Sidebar -->
                    <div class="col-md-3 sidebar right-sidebar">
                    	<?php dynamic_sidebar($pageSidebar); ?>
                    </div>
                    <?php } ?>
				</div>
			</div>
	</div>
</div>
<?php } else { ?>
<div class="main" role="main">
    	<div id="content" class="content full">
    		<div class="container">
        		<div class="row">
            		<div class="col-md-<?php echo $class; ?>">
<ul class="grid-holder col-<?php echo $event_column; ?> events-grid">
<?php $events = imic_recur_events('future','',''); ksort($events);
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$count = 1;
				$saiji = 1;
				$perPage = get_option('posts_per_page');
				$paginate = 1;
				if($paged>1) {
				$paginate = ($paged-1)*$perPage; $paginate = $paginate+1; }
				$TotalEvents = count($events);
				if($TotalEvents%$perPage==0) {
					$TotalPages = $TotalEvents/$perPage;
				}
				else {
					$TotalPages = $TotalEvents/$perPage;
					$TotalPages = $TotalPages+1;
				}
 foreach($events as $key=>$value): $date_converted=date('Y-m-d',$key );
                $custom_event_url= imic_query_arg($date_converted,$value);
				
				if($count==$paginate&&$saiji<=$perPage) { $paginate++; $saiji++;
				 ?>
                    <li class="grid-item format-standard">
                        <div class="grid-item-inner"> <?php if ( '' != get_the_post_thumbnail($value) ) { echo '<a href="'.esc_url($custom_event_url).'" class="media-box">'.get_the_post_thumbnail($value,'full').'</a>'; }
						$address1 = get_post_meta($value,'imic_event_address1',true);
						$address2 = get_post_meta($value,'imic_event_address2',true); ?>
                        <ul class="info-cols clearfix">
                            <li><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo date_i18n(get_option('date_format'), $key); ?>"><i class="fa fa-calendar"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo date_i18n(get_option('time_format'), $key); ?>"><i class="fa fa-clock-o"></i></a></li><?php if($address2!='') { ?>
                            <li><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $address2; ?>"><i class="fa fa-map-marker"></i></a></li><?php } if($address1!='') { ?>
                            <li><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $address1; ?>"><i class="fa fa-flag"></i></a></li><?php } ?>
                        </ul>
                        <div class="grid-content">
                            <h3><a href="<?php echo esc_url($custom_event_url); ?>"><?php echo get_the_title($value); ?></a></h3>
                            <?php $page_data = get_page( $value );
									$excerpt = strip_tags($page_data->post_excerpt);
									echo $excerpt; ?>
                        </div>
                    </div>
                  </li><?php } $count++; endforeach; ?>
                  </ul><?php imic_pagination($TotalPages,$perPage,'Default','past'); ?>
                  </div>
		<?php if(is_active_sidebar($pageSidebar)) { ?>
                    <!-- Sidebar -->
                    <div class="col-md-3 sidebar right-sidebar">
                    	<?php dynamic_sidebar($pageSidebar); ?>
                    </div>
                    <?php } ?>
				</div>
			</div>
	</div>
</div>
<?php } ?>
<?php get_footer(); ?>