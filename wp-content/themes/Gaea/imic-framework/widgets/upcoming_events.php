<?php
/*** Widget code for Upcoming Events ***/
class upcoming_events extends WP_Widget {
	// constructor
	function upcoming_events() {
		 $widget_ops = array('description' => __( "Display Upcoming Events.", 'imic-framework-admin') );
         parent::WP_Widget(false, $name = __('Upcoming Events','imic-framework-admin'), $widget_ops);
	}
	// widget form creation
	function form($instance) {
	    // Check values
                if( $instance) {
			 $title = esc_attr($instance['title']);
			 $number = esc_attr($instance['number']);
			 $category = esc_attr($instance['category']);
			 $status = esc_attr($instance['status']);
			 $url = esc_attr($instance['url']);
		} else {
			 $title = '';
			 $number = '';
           $category='';
		   $status = '';
		   $url = '';
		}
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'imic-framework-admin'); ?></label>
            <input class="spTitle_<?php echo $title; ?>" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        
        <p>
	            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of events to show', 'imic-framework-admin'); ?></label>
	            <input class="spNumber_<?php echo $number; ?>" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
       
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select Category', 'imic-framework-admin'); ?></label>
            <select class="spType_event_cat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
            <option value=""><?php _e('All','imic-framework-admin'); ?></option>
                <?php
                $post_terms = get_terms('event-category');
                if(!empty($post_terms)){
                      foreach ($post_terms as $term) {
                         
                        $term_name = $term->name;
                        $term_id = $term->slug;
                        $activePost = ($term_id == $category)? 'selected' : '';
                        echo '<option value="'. $term_id .'" '.$activePost.'>' . $term_name . '</p>';
                    }
                }
                ?>
            </select> 
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('status'); ?>"><?php _e('Select Event Type', 'imic-framework-admin'); ?></label>
            <select class="spType_event_cat" id="<?php echo $this->get_field_id('status'); ?>" name="<?php echo $this->get_field_name('status'); ?>">
            <option value="future" <?php echo ($status=='future')?'selected':''; ?>><?php _e('Future','imic-framework-admin'); ?></option>
            <option value="past" <?php echo ($status=='past')?'selected':''; ?>><?php _e('Past','imic-framework-admin'); ?></option>
            </select> 
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('All Events URL', 'imic-framework-admin'); ?></label>
            <input class="spTitle_<?php echo $url; ?>" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
        </p>
	<?php
	}
	// update widget
	function update($new_instance, $old_instance) {
		  $instance = $old_instance;
                // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		  $instance['number'] = strip_tags($new_instance['number']);
		  $instance['category'] = strip_tags($new_instance['category']);
		  $instance['status'] = strip_tags($new_instance['status']);
		  $instance['url'] = strip_tags($new_instance['url']);
		  return $instance;
	}
	// display widget
	function widget($args, $instance) {
           $cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'upcoming_events', 'widget' );
		}
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}
		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}
		ob_start();
	   extract( $args );
           
	   // these are the widget options
	   $post_title = apply_filters('widget_title', $instance['title']);
	   $post_title = ($post_title=='')?__('Upcoming Events','imic-framework-admin'):$post_title;
	   $number = apply_filters('widget_number', $instance['number']);
       $category = apply_filters('widget-category', empty($instance['category']) ?'': $instance['category'], $instance, $this->id_base);
	   $numberEvent = (!empty($number))? $number : 4 ;
	   $EventHeading = $post_title;
	   $status = apply_filters('widget_status', $instance['status']);
	   $url = apply_filters('widget_url', $instance['url']);
	   echo $args['before_widget'];
		if( !empty($instance['title']) ){
			echo $args['before_title'];
			echo '<i class="fa fa-calendar"></i> '.apply_filters('widget_title',$EventHeading, $instance, $this->id_base);
			echo $args['after_title'];
		}
		wp_reset_postdata();
		$events = imic_recur_events($status,'nos',$category);
		if($status=='future') { ksort($events); } else { krsort($events); }
		if(!empty($events)) { $total = 1;
			echo '<ul class="upcoming-events">'; 
			foreach($events as $key=>$value) {
				$style = '';
				$date_converted=date('Y-m-d',$key );
                $custom_event_url= imic_query_arg($date_converted,$value);
				echo '
                            	<li data-appear-animation="fadeInRight" data-appear-animation-delay="1">';
                                	if ( '' != get_the_post_thumbnail($value) ) { echo get_the_post_thumbnail($value,'80x80',array('class'=>'img-thumbnail event-thumb')); } else { $style = "style=\"opacity:1;\""; } 
                                    echo '<span class="event-date" '.$style.'>
                                    	<span class="day">'.esc_attr(date_i18n('d', $key)).'</span>
                                        <span class="monthyear">'.esc_attr(date_i18n('M, ', $key)).esc_attr(date_i18n('y', $key)).'</span>
                                   	</span>
                                    <div class="event-excerpt">
                                        <div class="event-cats meta-data">'.get_the_term_list($value, 'event-category', '', ', ', '' ).'</div>
                                    	<h5 class="event-title"><a href="'.esc_url($custom_event_url).'">'.get_the_title($value).'</a></h5>';
										$address = get_post_meta($value,'imic_event_address2',true); if($address!='') {
                                    	echo '<p class="event-location"><i class="fa fa-map-marker"></i> '.$address.'</p>'; }
                                    echo '</div>
                               	</li>'; if (++$total > $numberEvent) { break; }
			} echo '</ul>';
			wp_reset_postdata();
			if($url!='') {
			echo '<div class="upcoming-events-footer">
					<hr class="sm">
					<a href="'.$url.'">'.__('See all events','framework').'</a>
					</div>'; }
		}else{
			_e('No Upcoming Events Found','imic-framework-admin');		
		}
	   if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'upcoming_events', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	   echo $args['after_widget'];
	}
}
// register widget
add_action('widgets_init', create_function('', 'return register_widget("upcoming_events");'));
?>