<?php
/*** Widget code for Twitter Feeds ***/
class insta_gallery extends WP_Widget {
	// constructor
	function insta_gallery() {
		 $widget_ops = array('description' => __( "Show Instagram Gallery.", 'imic-framework-admin') );
         parent::WP_Widget(false, $name = __('Instagram Gallery','imic-framework-admin'), $widget_ops);
	}
	// widget form creation
	function form($instance) {
		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);
			 $username = esc_attr($instance['username']);
			 $count = esc_attr($instance['count']);
			 $accessToken = esc_attr($instance['accessToken']);
		} else {
			 $title = '';
			 $username = '';
			 $count = '';
			 $accessToken = '';
		}
	?>
        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'imic-framework-admin'); ?></label>
        	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('User ID', 'imic-framework-admin'); ?></label>
        	<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of Images', 'imic-framework-admin'); ?></label>
        	<input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('accessToken'); ?>"><?php _e('Access Token', 'imic-framework-admin'); ?></label>
        	<input class="widefat" id="<?php echo $this->get_field_id('accessToken'); ?>" name="<?php echo $this->get_field_name('accessToken'); ?>" type="text" value="<?php echo $accessToken; ?>" />
        </p>
	<?php
	}
	// update widget
	function update($new_instance, $old_instance) {
		  $instance = $old_instance;
		  // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		  $instance['username'] = strip_tags($new_instance['username']);
		  $instance['count'] = strip_tags($new_instance['count']);
		  $instance['accessToken'] = strip_tags($new_instance['accessToken']);
		 return $instance;
	}
	// display widget
	function widget($args, $instance) {
	   extract( $args );
	   // these are the widget options
	   $title = apply_filters('widget_title', $instance['title']);
	   $username = apply_filters('widget_username', $instance['username']);
	   $count = apply_filters('widget_count', $instance['count']);
	   $accessToken = apply_filters('widget_accessToken', $instance['accessToken']);
	   echo $args['before_widget'];
	   	if( !empty($instance['title']) ){
			echo $args['before_title'];
			echo apply_filters('widget_title',$instance['title'], $instance, $this->id_base);
			echo $args['after_title'];
		}
		$username;
		$count;
		$accessToken;	
		wp_localize_script('imic_jquery_init','insta',array('ids'=>$username,'counts'=>$count,'token'=>$accessToken));
		echo '<ul id="insta-widget" class="clearfix"></ul>';
	   echo $args['after_widget'];
	}
}
// register widget
add_action('widgets_init', create_function('', 'return register_widget("insta_gallery");'));
?>