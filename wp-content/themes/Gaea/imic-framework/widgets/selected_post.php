<?php
/*** Widget code for Selected Post ***/
class selected_post extends WP_Widget {
	// constructor
	function selected_post() {
		 $widget_ops = array('description' => __( 'Display latest and selected post of different post type.','imic-framework-admin') );
         parent::WP_Widget(false, $name = __('Selected Post','imic-framework-admin'), $widget_ops);
	}
	// widget form creation
	function form($instance) {
		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);
			 $type = esc_attr($instance['type']);
			 $number = esc_attr($instance['number']);
		} else {
			 $title = '';
			 $type = '';
			 $number = '';
		}
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'imic-framework-admin'); ?></label>
            <input class="spTitle" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Select Post Type', 'imic-framework-admin'); ?></label>
            <select class="spType" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
                <?php
                $post_types = get_post_types( array('_builtin' => false), 'names' ); 
                array_unshift($post_types, "post");
                if(($key = array_search('wpcf7_contact_form', $post_types)) !== false){
				unset($post_types[$key]);
				}
                if(!empty($post_types)){
                    foreach ( $post_types as $post_type ) {
                        $activePost = ($type == $post_type)? 'selected' : '';
                       echo '<option value="'. $post_type .'" '.$activePost.'>' . $post_type . '</p>';
                    }
                }else{
                    echo '<option value="no">'.__('No Post Type Found.','imic-framework-admin').'</option>';
                }
                ?>
            </select> 
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show', 'imic-framework-admin'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p> 
	<?php
	}
	// update widget
	function update($new_instance, $old_instance) {
		  $instance = $old_instance;
		  // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		  $instance['type'] = strip_tags($new_instance['type']);
		  $instance['number'] = strip_tags($new_instance['number']);
		  
		 return $instance;
	}
	// display widget
	function widget($args, $instance) {
	   extract( $args );
	   // these are the widget options
	   $post_title = apply_filters('widget_title', $instance['title']);
	   $type = apply_filters('widget_type', $instance['type']);
	   $number = apply_filters('widget_number', $instance['number']);
	   
	   $numberPost = (!empty($number))? $number : 3 ;	
	   	   
	   echo $args['before_widget'];
		
		if( !empty($instance['title']) ){
			echo $args['before_title'];
			echo apply_filters('widget_title',$instance['title'], $instance, $this->id_base);
			echo $args['after_title'];
		}
		$posts = query_posts(array('order'=>'DESC', 'post_type' => $type, 'posts_per_page' => $numberPost, 'post_status' => 'publish'));
		if(!empty($posts)){ 
			echo '<ul>';
			 foreach($posts as $post){ 
			 	if($type!='event'){
			 		$postDate =  strtotime($post->post_date);
				}else{
					$event_post = get_post_custom($post->ID);
					$postDate = strtotime($event_post['imic_event_start_dt'][0]);					
				}
				$postImage = get_the_post_thumbnail( $post->ID, 'full', array('class' => "img-thumbnail") );
				echo '<li class="clearfix">
						  <a href="'.esc_url(get_permalink($post->ID)).'" class="media-box post-image">';
						  	if ( !empty($postImage)) :
								echo $postImage;
						 	endif;
				echo '	  </a>
						<div class="widget-blog-content"><a href="'.esc_url(get_permalink($post->ID)).'">'.$post->post_title.'</a>
						<span class="meta-data"><i class="fa fa-calendar"></i>'.__(' on ','imic-framework-admin').esc_attr(date_i18n('jS F, Y',$postDate)).'</span>
						</div>
					</li>';					
					}		
			echo '</ul>';
		}else{
			echo __('No ','imic-framework-admin').$type.__(' Found','imic-framework-admin');		
		}
   
	   echo $args['after_widget'];
	}
}
// register widget
add_action('widgets_init', create_function('', 'return register_widget("selected_post");'));
?>