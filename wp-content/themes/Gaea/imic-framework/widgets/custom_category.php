<?php
/*** Widget code for Selected Post ***/
class custom_category extends WP_Widget {
	// constructor
	function custom_category() {
		 $widget_ops = array('description' => __( "Display latest and selected post categories of different post type.", 'imic-framework-admin') );
        parent::WP_Widget(false, $name = __( 'Custom Categories','imic-framework-admin'), $widget_ops);
	}
	// widget form creation
	function form($instance) {
	
		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);
			 $type = esc_attr($instance['type']);
		} else {
			 $title = '';
			 $type = '';
		}
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'imic-framework-admin'); ?></label>
            <input class="spTitle" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p><p>
            <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Select Post Type', 'imic-framework-admin'); ?></label>
            <select class="spType" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
                <?php
                $post_types = imic_get_all_types();
				if(($key = array_search('attachment', $post_types)) !== false){
					unset($post_types[$key]);
				}
                                if(($key = array_search('page', $post_types)) !== false){
					unset($post_types[$key]);
				}
                                if(($key = array_search('staff', $post_types)) !== false){
					unset($post_types[$key]);
				}
		    if(!empty($post_types)){
                    foreach ( $post_types as $post_type ) {
						$activePost = ($type == $post_type)? 'selected' : '';
                        echo '<option value="'. $post_type .'" '.$activePost.'>' . $post_type . '</p>';
                    }
                }else{
                     echo '<option value="no">'.__( 'No Post Type Found.','imic-framework-admin').'</option>';
                }
                ?>
            </select> 
        </p> 
	<?php
	}
         // update widget
	function update($new_instance, $old_instance) {
		  $instance = $old_instance;
		  // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		  $instance['type'] = strip_tags($new_instance['type']);
		  return $instance;
	}
         // display widget
	function widget($args, $instance) {
	   extract( $args );
	   // these are the widget options
	   $post_title = apply_filters('widget_title', $instance['title']);
	   $type = apply_filters('widget_type', $instance['type']);
	   
	   $numberPost = (!empty($number))? $number : 3 ;	
	   echo $args['before_widget'];
		if( !empty($instance['title']) ){
			echo $args['before_title'];
			echo apply_filters('widget_title',$instance['title'], $instance, $this->id_base);
			echo $args['after_title'];
		}
                 if($type == 'event') {
                 $pages_e = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'template-event-category.php'
                ));
                
                 $imic_event_category_page_url=!empty($pages_e[0]->ID)?get_permalink($pages_e[0]->ID):'';
                }
		if($type=='product'){
                    $post_terms = get_terms('product_cat');
                }else{
                  $post_terms = get_terms($type.'-category');  
                }
		echo '<ul>';
		foreach ($post_terms as $term) {
                    $term_name = $term->name;
                   $term_link = get_term_link($term,$type.'-category'); 
               $count = $term->count;
                        if($type == 'event') {
							$count = 0;
			global $post;
			$argss = array( 'post_type' => 'event', 'event-category'=>$term->slug,'posts_per_page'=>-1);
			$events_count = get_posts( $argss );
			foreach($events_count as $post) {
				setup_postdata( $post );
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
			if($frequency==30) {
			$eventDate = strtotime("+".$fr_repeat." month", $eventDate);
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
			$eventDate = strtotime($eventDate);
			}
			else {
			$new_date = $seconds * $fr_repeat;
            $eventDate = $eventDate + $new_date;
			}
            if ($eventDate >= date('U')) {
					$count = $count+1;
				} $fr_repeat++; }
			}
			wp_reset_postdata();
			}
                        if((!empty($term_link))&&($count>0)){
			echo '<li><a href="' . esc_url($term_link) .'">' . $term_name . '</a> (' . $count . ')</li>';
                }}
		echo '</ul>';
                echo $args['after_widget'];
	}
}
// register widget
add_action('widgets_init', create_function('', 'return register_widget("custom_category");'));
?>