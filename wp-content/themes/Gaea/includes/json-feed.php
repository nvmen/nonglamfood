<?php
// - standalone json feed -
header('Content-Type:application/json');
// - grab wp load, wherever it's hiding -
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
include_once('../../../../wp-includes/wp-db.php');
// - grab date barrier -
//$today6am = strtotime('today 6:00') + ( get_option( 'gmt_offset' ) * 3600 );
$today = date('Y-m-d');
$offset = get_option('timezone_string');
		if($offset=='') { $offset = "Australia/Melbourne"; }
	date_default_timezone_set($offset);
// - query -
global $wpdb;
if (isset($_POST['event_cat_id'])&&!empty($_POST['event_cat_id'])){
  $event_cat_id = $_POST['event_cat_id'];
  $querystr = "SELECT * FROM $wpdb->posts INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) INNER JOIN $wpdb->postmeta AS mt1 ON ($wpdb->posts.ID = mt1.post_id) WHERE 1=1 AND ( $wpdb->term_relationships.term_taxonomy_id IN (SELECT `term_taxonomy_id` FROM $wpdb->term_taxonomy  WHERE `term_id`=$event_cat_id) ) AND $wpdb->posts.post_type = 'event' AND ($wpdb->posts.post_status = 'publish') AND ($wpdb->postmeta.meta_key = 'imic_event_start_dt' AND (mt1.meta_key = 'imic_event_frequency_end' AND CAST(mt1.meta_value AS CHAR) > $today) ) GROUP BY $wpdb->posts.ID ORDER BY $wpdb->postmeta.meta_value ASC LIMIT 0, 500
 ";
}
else{
    $querystr = "SELECT *
    FROM $wpdb->posts wposts, $wpdb->postmeta metastart, $wpdb->postmeta metaend
    WHERE (wposts.ID = metastart.post_id AND wposts.ID = metaend.post_id)
    AND (metaend.meta_key = 'imic_event_end_dt' AND metaend.meta_value > $today )
    AND metastart.meta_key = 'imic_event_end_dt'
    AND wposts.post_type = 'event'
    AND wposts.post_status = 'publish'
    ORDER BY metastart.meta_value ASC LIMIT 500
 ";
}
$events = $wpdb->get_results($querystr, OBJECT);
$jsonevents = array();
// - loop -
if ($events) {
	    global $post;
	    foreach ($events as $post) {
		    setup_postdata($post);
			$cat_id = '';
                    $frequency = get_post_meta(get_the_ID(),'imic_event_frequency_type',true);
		     $cat_id = wp_get_post_terms( get_the_ID(), 'event-category', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all') );
		if(!empty($cat_id)) { $cat_id = $cat_id[0]->term_id; }
		$cat_data = get_option("category_".$cat_id);
		$event_color = ($cat_data['catBG']!='')?$cat_data['catBG']:$imic_options['event_default_color'];
		$frequency_count = '';
			$frequency_count = get_post_meta(get_the_ID(),'imic_event_frequency_count',true);
			if($frequency>0) { $color = $imic_options['recurring_event_color']; $frequency_count = $frequency_count; } else { $frequency_count = 0; $color = $event_color; }
		    // - custom post type variables -
		    $start = get_post_meta(get_the_ID(),'imic_event_start_dt',true);
		    $end = get_post_meta(get_the_ID(),'imic_event_end_dt',true);
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
			$start = strtotime("+".$fr_repeat." month", $eventDate);
			}
			else {
			$new_date = $seconds * $fr_repeat;
            $start = $eventDate + $new_date;
			}
			}
			elseif($frequency_type==2) {
				$eventTime = date('G:i',$eventDate);
				$eventDate = strtotime( date('Y-m-1',$eventDate) );
				if($fr_repeat==0) { $fr_repeat = $fr_repeat+1; }
			$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
			$next_month = date('F',$eventDate);
			$next_event_year = date('Y',$eventDate);
			//echo $next_month;
			$eventDate = date('Y-m-d '.$eventTime, strtotime($frequency_month_day.' '.$frequency_week_day.' of '.$next_month.' '.$next_event_year));
			//echo $eventDate;
			$start = strtotime($eventDate);
			}
			$date_converted = date('Y-m-d',$start);
		    // - grab gmt for start -
		    $gmts = date('Y-m-d H:i:s', $start);
		    $gmts = get_gmt_from_date($gmts); // this function requires Y-m-d H:i:s
		    $gmts = strtotime($gmts);
		     
		    // - grab gmt for end -
		    $gmte = date('Y-m-d H:i:s', strtotime($end));
		    $gmte = get_gmt_from_date($gmte); // this function requires Y-m-d H:i:s
		    $gmte = strtotime($gmte);
		     
		    // - set to ISO 8601 date format -
		    $stime = date('Y-m-d H:i:s', $start);
		    $etime = date('Y-m-d H:i:s', strtotime($end));
		     
		    // - json items -
		    $jsonevents[]= array(
			'id' => $post->ID.'|'.$start,
		        'title' => $post->post_title,
		        'allDay' => false, // <- true by default with FullCalendar
		        'start' => $stime,
		        'end' => $etime,
		        'url' => imic_query_arg($date_converted,$post->ID),
				'backgroundColor' => $color,
			'borderColor' => $color
		        );
		$fr_repeat++; }
		    }
	    }
// - fire away -
$options = get_option('imic_options');
$events_feeds = $options['event_feeds'];
if($events_feeds==1) {
echo json_encode($jsonevents); }
?>