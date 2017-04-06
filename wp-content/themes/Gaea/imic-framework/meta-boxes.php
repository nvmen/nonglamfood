<?php
/* * ** Meta Box Functions **** */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    $post_types = array(
		'' => __('Select','framework'),
		'post' => __('Post', 'framework'),
		'event' => __('Event','framework'),
		'staff' => __('Staff','framework'),
		'gallery' => __('Gallery','framework'),
		'project' => __('Project','framework'),
		'product' => __('Product','framework'),
            );
	$product_cat = 'product_cat';
}
else {
	$post_types = array(
		'' => __('Select','framework'),
		'post' => __('Post', 'framework'),
		'event' => __('Event','framework'),
		'staff' => __('Staff','framework'),
		'gallery' => __('Gallery','framework'),
		'project' => __('Project','framework'),
            );
	$product_cat = 'category';
}
$prefix = 'imic_';
global $meta_boxes;
load_theme_textdomain('framework', IMIC_FILEPATH . '/language');
$meta_boxes = array();
/* Gallery Meta Box
  ================================================== */
$meta_boxes[] = array(
    'id' => 'gallery_meta_box',
    'title' => __('Gallery Meta Box', 'framework'),
    'pages' => array('gallery'),
    'fields' => array(
        // Gallery Video Url
        array(
            'name' => __('Video Url', 'framework'),
            'id' => $prefix . 'gallery_video_url',
            'desc' => __("Enter the Video URL.", 'framework'),
            'type' => 'url',
        ),
        // Gallery Link Url
        array(
            'name' => __('Link Url', 'framework'),
            'id' => $prefix . 'gallery_link_url',
            'desc' => __("Enter the Link URL.", 'framework'),
            'type' => 'url',
        ),
        // Gallery Images
        array(
            'name' => __('Gallery Image', 'framework'),
            'id' => $prefix . 'gallery_images',
            'desc' => __("Enter Gallery Image.", 'framework'),
            'type' => 'image_advanced',
        ),
		array(
            'name' => __('Slider Speed', 'framework'),
            'id' => $prefix . 'gallery_slider_speed',
            'desc' => __("Default Slider Speed is 5000.", 'framework'),
            'type' => 'text',
        ),
       array(
            'name' => __('Slider Pagination', 'framework'),
            'id' => $prefix . 'gallery_slider_pagination',
            'desc' => __("Enable to show pagination for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Enable', 'framework'),
                'no' => __('Disable', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Auto Slide', 'framework'),
            'id' => $prefix . 'gallery_slider_auto_slide',
            'desc' => __("Select Yes to slide automatically.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'framework'),
                'no' => __('No', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Direction Arrows', 'framework'),
            'id' => $prefix . 'gallery_slider_direction_arrows',
            'desc' => __("Select Yes to show slider direction arrows.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'framework'),
                'no' => __('No', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Effects', 'framework'),
            'id' => $prefix . 'gallery_slider_effects',
            'desc' => __("Select effects for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'fade' => __('Fade', 'framework'),
                'slide' => __('Slide', 'framework'),
            ),
        ),
        //Audio Display
        array(
            'name' => __('Audio Display', 'framework'),
            'id' => $prefix . 'gallery_audio_display',
            'desc' => __("Select Audio Display.", 'framework'),
            'type' => 'select',
            'options' => array(
                '1' => __('By Ifame', 'framework'),
                '2' => __('By Upload', 'framework'),
            ),
        ),
        array(
            'name' => __('SoundCloud Track', 'framework'),
            'id' => $prefix . 'gallery_audio',
            'desc' => __("Enter SoundCloud iframe code to show on post.", 'framework'),
            'type' => 'textarea',
            'std' => '',
        ),
        // Upload Audio
        array(
            'name' => __('Audio', 'framework'),
            'id' => $prefix . 'gallery_uploaded_audio',
            'desc' => __("Upload Audio.", 'framework'),
            'type' => 'file_advanced',
            'max_file_uploads' => 1
        ),
    )
);
  /* Staff Meta Box
  ================================================== */
$meta_boxes[] = array(
    'id' => 'staff_meta_box',
    'title' => __('Staff Member Meta', 'framework'),
    'pages' => array('staff'),
    'fields' => array(
		array(
            'name' => __('Position', 'framework'),
            'id' => $prefix . 'staff_position',
            'desc' => __("Enter the staff job title.", 'framework'),
            'type' => 'text',
            'std' => '',
        ),
    )
);
  /* Project Meta Box
  ================================================== */
$meta_boxes[] = array(
    'id' => 'project_meta_box',
    'title' => __('Project Details Meta', 'framework'),
    'pages' => array('project'),
    'fields' => array(
		array(
			'name' => __( 'Team Members', 'meta-box' ),
			'id' => $prefix.'project_team',
			'type' => 'post',
			// Post type
			'post_type' => 'staff',
			// Field type, either 'select' or 'select_advanced' (default)
			'field_type' => 'select_advanced',
			'multiple' => true,
			// Query arguments (optional). No settings means get all published posts
			'query_args' => array(
			'post_status' => 'publish',
			'posts_per_page' => - 1,
			)
			),
			array(
            'name' => __('Project Centre', 'framework'),
            'id' => $prefix . 'project_address',
            'desc' => __("Enter the Property Centre.", 'framework'),
            'clone' => false,
            'type' => 'text',
            'std' => '',
        ),
		array(
			'id' => $prefix.'project_location',
			'name' => __( 'Location', 'framework' ),
			'type' => 'map',
			'std' => '-6.233406,-35.049906,15', // 'latitude,longitude[,zoom]' (zoom is optional)
			'style' => 'width: 500px; height: 500px',
			'address_field' => 'imic_project_address', // Name of text field where address is entered. Can be list of text fields, separated by commas (for ex. city, state)
			),
    )
);
/* * ** Template Blog *** */
$meta_boxes[] = array(
    'id' => 'template-blog1',
    'title' => __('Post Categories', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
		array(
        'name'    => __( 'Post Category', 'framework' ),
        'id'      => $prefix . 'advanced_post_taxonomy',
        'desc' => __("Choose Post Category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
				'std' => '',
            ),
    )
);
/* * ** Template Project *** */
$meta_boxes[] = array(
    'id' => 'template-project8',
    'title' => __('Project Categories', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
		array(
        'name'    => __( 'Project Category', 'framework' ),
        'id'      => $prefix . 'advanced_project_taxonomy',
        'desc' => __("Choose Post Category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'project-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
				'std' => '',
            ),
    )
);
/* Event Meta Box
  ================================================== */
/*** Event Details Meta box ***/   
$meta_boxes[] = array(
    'id' => 'event_meta_box',
    'title' => __('Event Details Meta Box', 'framework'),
    'pages' => array('event'),
    'fields' => array( 
        // Event Start Date 
		array(
            'name' => __('Featured Event', 'framework'),
            'id' => $prefix . 'featured_event',
            'desc' => __("Select Featured Event.", 'framework'),
            'type' => 'select',
            'options' => array(
				'no' => __('No','framework'),
				'yes' => __('Yes','framework'),
        ),
		), 
        array(
            'name' => __('Event Start Date', 'framework'),
            'id' => $prefix . 'event_start_dt',
            'desc' => __("Insert date of Event start.", 'framework'),
            'type' => 'datetime',
			'js_options' => array(
	              'dateFormat'      => 'yy-mm-dd',
				  	'timeFormat' => 'hh:mm',
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
					'stepMinute' => 5,
					'showSecond' => false,
					'stepSecond' => 10,
				),
        ),
        //Event End Date
        array(
            'name' => __(' Event End Date', 'framework'),
            'id' => $prefix . 'event_end_dt',
            'desc' => __("Insert date of Event end.", 'framework'),
            'type' => 'datetime',
			'js_options' => array(
	              'dateFormat'      => 'yy-mm-dd',
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
					'stepMinute' => 5,
					'showSecond' => false,
					'stepSecond' => 10,
				),
        ),
    )
);
/*** Event Address Meta box ***/   
$meta_boxes[] = array(
    'id' => 'event_address_box',
    'title' => __('Event Address Box', 'framework'),
    'pages' => array('event'),
    'fields' => array( 
	 //Address
		 array(
			'name'  => __('Address1', 'framework'),
			'id'    => $prefix."event_address1",
			'desc'  =>  __("Enter event's address1.", 'framework'),
			'type' => 'text',
		),
		array(
			'name'  => __('Address2', 'framework'),
			'id'    => $prefix."event_address2",
			'desc'  =>  __("This field should have real address to get GMap.", 'framework'),
			'type' => 'text',
		),   
		array(
			'id'    => $prefix."event_map_location",
			'name' => __( 'Location', 'meta-box' ),
			'type' => 'map',
			'std' => '-6.233406,-35.049906,15', // 'latitude,longitude[,zoom]' (zoom is optional)
			'style' => 'width: 500px; height: 500px',
			'address_field' => 'imic_event_address2', // Name of text field where address is entered. Can be list of text fields, separated by commas (for ex. city, state)
			), 
		array(
            'name' => __('Event Registration', 'framework'),
            'id' => $prefix . 'event_registration_status',
            'desc' => __("Select Enabled to active Event Registration.", 'framework'),
            'type' => 'select',
            'options' => array(
		'0' => __('Disable', 'framework'),
				'1' => __('Enable','framework'),
            ),
			'std' => 0,
        ),
		array(
			'name'  => __('Event Registration Fee', 'framework'),
			'id'    => $prefix."event_registration_fee",
			'desc'  =>  __('Enter event\'s registration fee, Leave blank for free Registration(This field will work only when Event Registration Plugin activated.)', 'framework'),
			'type'  => 'text',
		),     
		array(
			'name' => __( 'Guest Registration', 'meta-box' ),
			'id' => $prefix.'event_guest_checkout',
			'type' => 'radio',
			// Array of 'value' => 'Label' pairs for radio options.
			// Note: the 'value' is stored in meta field, not the 'Label'
			'options' => array(
			'1' => __( 'Yes', 'meta-box' ),
			'0' => __( 'No', 'meta-box' ),
			),
			), 
		)
);
/*** Event Image Type ***/   
$meta_boxes[] = array(
    'id' => 'event_image_type_box',
    'title' => __('Event Featured Image Type', 'framework'),
    'pages' => array('event'),
    'fields' => array( 
	 //Address
		 array(
            'name' => __('Event Image Type', 'framework'),
            'id' => $prefix . 'event_image_type',
            'desc' => __("Select Image Type.", 'framework'),
            'type' => 'select',
            'options' => array(
				'featured' => __('Featured Image','framework'),
				'slider' => __('Image Slider','framework'),
        ),
		),
			array(
            'name' => __('Slider Image', 'framework'),
            'id' => $prefix . 'event_slider_images',
            'desc' => __("Upload Slider images for slider.", 'framework'),
            'type' => 'image_advanced',
            'max_file_uploads' => 10
        ),
		)
);
/*** Event Sponsors ***/   
$meta_boxes[] = array(
    'id' => 'event_sponsors_box',
    'title' => __('Event Sponsors', 'framework'),
    'pages' => array('event'),
    'fields' => array( 
	 //Address
		 array(
            'name' => __('Event Image Type', 'framework'),
            'id' => $prefix . 'event_sponsors_status',
            'desc' => __("Select Image Type.", 'framework'),
            'type' => 'select',
            'options' => array(
				'0' => __('Disable','framework'),
				'1' => __('Enable','framework'),
        ),
		),
			array(
            'name' => __('Event Sponsors', 'framework'),
            'id' => $prefix . 'event_sponsors_images',
            'desc' => __("Upload Sponsors images for Event.", 'framework'),
            'type' => 'image_advanced',
            'max_file_uploads' => 10
        ),
		)
);
/*** Event Recurrence Meta box ***/   
$meta_boxes[] = array(
    'id' => 'event_recurring_box',
    'title' => __('Event Recurrence Box', 'framework'),
    'pages' => array('event'),
    'fields' => array( 		 
        //Frequency of Event
		array(
            'name' => __('Event Frequency Type', 'framework'),
            'id' => $prefix . 'event_frequency_type',
            'desc' => __("Select Frequency Type.", 'framework'),
            'type' => 'select',
            'options' => array(
				'0' => __('Not Required','framework'),
				'1' => __('Fixed Date','framework'),
                '2' => __('Week Day', 'framework'),
        ),
		),
		array(
            'name' => __('Day of Month', 'framework'),
            'id' => $prefix . 'event_day_month',
            'desc' => __("Select Day of Month.", 'framework'),
            'type' => 'select',
            'options' => array(
				'first' => __('First','framework'),
                'second' => __('Second', 'framework'),
				'third' => __('Third', 'framework'),
				'fourth' => __('Fourth', 'framework'),
				'last' => __('Last', 'framework'),
        ),
		),
		array(
            'name' => __('Event Week Day', 'framework'),
            'id' => $prefix . 'event_week_day',
            'desc' => __("Select Week Day.", 'framework'),
            'type' => 'select',
            'options' => array(
				'sunday' => __('Sunday','framework'),
                'monday' => __('Monday', 'framework'),
				'tuesday' => __('Tuesday', 'framework'),
				'wednesday' => __('Wednesday', 'framework'),
				'thursday' => __('Thursday', 'framework'),
				'friday' => __('Friday', 'framework'),
				'saturday' => __('Saturday', 'framework'),
        ),
		),
        array(
            'name' => __('Event Frequency', 'framework'),
            'id' => $prefix . 'event_frequency',
            'desc' => __("Select Frequency.", 'framework'),
            'type' => 'select',
            'options' => array(
				'35' => __('Select', 'framework'),
                '1' => __('Every Day', 'framework'),
				'2' => __('Every Second Day', 'framework'),
				'3' => __('Every Third Day', 'framework'),
				'4' => __('Every Fourth Day', 'framework'),
				'5' => __('Every Fifth Day', 'framework'),
				'6' => __('Every Sixth Day', 'framework'),
                '7' => __('Every Week', 'framework'),
				'30' => __('Every Month', 'framework'),
            ),
        ),
		//Frequency Count
		array(
            'name' => __('Number of times to repeat event', 'framework'),
            'id' => $prefix . 'event_frequency_count',
            'desc' => __("Enter the number of how many time this event should repeat.", 'framework'),
            'type' => 'text',
        ),    
		array(
            'name' => __('Do not change', 'framework'),
            'id' => $prefix . 'event_frequency_end',
            'desc' => __("If any changes done in this file, may your theme will not work like running now.", 'framework'),
            'type' => 'hidden',
        ),    
    )
);
/* Post Page Meta Box
  ================================================== */
$meta_boxes[] = array(
    'id' => 'post_page_meta_box',
    'title' => __('Page/Post Header Options', 'framework'),
   'pages' => array('post','page','project','event','product'),
    'fields' => array(
		array(
            'name' => __('Choose Header Type', 'framework'),
            'id' => $prefix . 'pages_Choose_slider_display',
            'desc' => __("Select Slider Display.", 'framework'),
            'type' => 'select',
            'options' => array(
					'1' => __('Banner', 'framework'),
				  '2' => __('Banner Image', 'framework'),
                '3' => __('Flex Slider', 'framework'),
					'4' => __('Nivo Slider','framework'),
                '5' => __('Revolution Slider', 'framework'),
            ),
			'std' => 2
        ),
		array(
				'name' => __( 'Banner Color', 'meta-box' ),
				'id' => $prefix.'pages_banner_color',
				'type' => 'color',
				),
		array(
            'name' => __('Banner Image', 'framework'),
            'id' => $prefix . 'header_image',
            'desc' => __("Upload banner image for header for this Page/Post.", 'framework'),
            'type' => 'image_advanced',
            'max_file_uploads' => 1
        ),
        array(
                   'name' => __('Select Revolution Slider from list','framework'),
                    'id' => $prefix . 'pages_select_revolution_from_list',
                    'desc' => __("Select Revolution Slider from list", 'framework'),
                    'type' => 'select',
                    'options' => imic_RevSliderShortCode(),
                ),
        //Slider Image
		array(
            'name' => __('Banner/Slider Height', 'framework'),
            'id' => $prefix . 'pages_slider_height',
            'desc' => __("Enter Height for Banner/Slider Ex-265.", 'framework'),
            'type' => 'text',
        ),
        array(
            'name' => __('Slider Image', 'framework'),
            'id' => $prefix . 'pages_slider_image',
            'desc' => __("Enter Slider Image.", 'framework'),
            'type' => 'image_advanced',
        ),
		array(
            'name' => __('Slider Pagination', 'framework'),
            'id' => $prefix . 'pages_slider_pagination',
            'desc' => __("Enable to show pagination for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Enable', 'framework'),
                'no' => __('Disable', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Auto Slide', 'framework'),
            'id' => $prefix . 'pages_slider_auto_slide',
            'desc' => __("Select Yes to slide automatically.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'framework'),
                'no' => __('No', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Direction Arrows', 'framework'),
            'id' => $prefix . 'pages_slider_direction_arrows',
            'desc' => __("Select Yes to show slider direction arrows.", 'framework'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'framework'),
                'no' => __('No', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Effects', 'framework'),
            'id' => $prefix . 'pages_slider_effects',
            'desc' => __("Select effects for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'fade' => __('Fade', 'framework'),
                'slide' => __('Slide', 'framework'),
            ),
        ),
		array(
            'name' => __('Slider Effects', 'framework'),
            'id' => $prefix . 'pages_nivo_effects',
            'desc' => __("Select effects for slider.", 'framework'),
            'type' => 'select',
            'options' => array(
                'sliceDown' => __('sliceDown', 'framework'),
                'sliceDownLeft' => __('sliceDownLeft', 'framework'),
				'sliceUp' => __('sliceUp', 'framework'),
                'sliceUpLeft' => __('sliceUpLeft', 'framework'),
				'sliceUpDown' => __('sliceUpDown', 'framework'),
                'sliceUpDownLeft' => __('sliceUpDownLeft', 'framework'),
				'fold' => __('fold', 'framework'),
                'fade' => __('fade', 'framework'),
				'random' => __('random', 'framework'),
                'slideInRight' => __('slideInRight', 'framework'),
				'slideInLeft' => __('slideInLeft', 'framework'),
                'boxRandom' => __('boxRandom', 'framework'),
				'boxRain' => __('boxRain', 'framework'),
				'boxRainReverse' => __('boxRainReverse', 'framework'),
				'boxRainGrow' => __('boxRainGrow', 'framework'),
				'boxRainGrowReverse' => __('boxRainGrowReverse', 'framework'),
            ),
        ),
        )
);
/* Secondry Bar Meta Box
  ================================================== */
$meta_boxes[] = array(
    'id' => 'secondry_bar_meta_box',
    'title' => __('Secondry Bar Options', 'framework'),
    'pages' => array('post', 'page', 'sermons', 'event', 'product','project'),
    'fields' => array(
          array(
            'name' => __('Enabled/Disable Secondary Bar', 'framework'),
            'id' => $prefix . 'secondary_bar_type_status',
            'desc' => __("Select Enabled to active Secondary Bar.", 'framework'),
            'type' => 'select',
            'options' => array(
		'0' => __('Disable', 'framework'),
		'1' => __('Enable','framework'),
            ),
	'std' => 0,
        ),
        array(
            'name' => __('Choose Secondary Bar Type', 'framework'),
            'id' => $prefix . 'secondary_bar_type',
            'desc' => __("Select Secondary Bar Type.", 'framework'),
            'type' => 'select',
            'options' => array(
                '0' => __('Title + Button', 'framework'),
                '1' => __('Single Page Menu', 'framework'),
                '2' => __('Shortcode', 'framework'),
            ),
        ),
         array(
			'name'  => __('Left Title', 'framework'),
			'id'    => $prefix."secondary_left_title",
			'desc'  =>  __("Enter Left Title.", 'framework'),
			'type' => 'text',
		),
         array(
			'name'  => __('Left Url', 'framework'),
			'id'    => $prefix."secondary_left_url",
			'desc'  =>  __("Enter Left Url.", 'framework'),
			'type' => 'text',
		),
         array(
			'name'  => __('Right Title', 'framework'),
			'id'    => $prefix."secondary_right_title",
			'desc'  =>  __("Enter Left Title.", 'framework'),
			'type' => 'text',
		),
         array(
			'name'  => __('Right Url', 'framework'),
			'id'    => $prefix."secondary_right_url",
			'desc'  =>  __("Enter Right Url.", 'framework'),
			'type' => 'text',
		),
        array(
                    'name'  => __('Single Page Menu', 'framework'),
                    'id'    => $prefix."single_page_menu",
                    'desc'  =>  __('Enter Single Page Menu title and id.', 'framework'),
                    'type'  => 'text_list',
                    'clone' => true,
                    'options' => array(
                            '0' => __('Title', 'framework'),
                            '1' => __('Id', 'framework'))
                      ),
          array(
			'name'  => __('Secondry Shortcode', 'framework'),
			'id'    => $prefix."secondary_shortcode",
			'desc'  =>  __("Enter Shortcode.", 'framework'),
			'type' =>  'wysiwyg',
                        'options' => array(
                        'textarea_rows' => 4,
                        'teeny'         => true,
                        'media_buttons' => false,
				),
		),
    )
);
/* * **Home Page Meta Box1 *** */
$meta_boxes[] = array(
    'id' => 'template-home1',
    'title' => __('Latest Post Metabox', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
          array(
            'name' => __('Enabled/Disable Latest Post', 'framework'),
            'id' => $prefix . 'latest_post_status',
            'desc' => __("Select Enabled to active Latest Post.", 'framework'),
            'type' => 'select',
            'options' => array(
		'0' => __('Disable', 'framework'),
		'1' => __('Enable','framework'),
            ),
	'std' => 0,
        ),
		array(
            'name' => __('Select Post Type', 'framework'),
            'id' => $prefix . 'selected_post_type',
            'desc' => __("Select Post Type.", 'framework'),
            'type' => 'select',
            'options' => $post_types,
		'std' => 'post',
        ),
		array(
        'name'    => __( 'Post Category', 'framework' ),
        'id'      => $prefix . 'post_category',
        'desc' => __("Choose Post Category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
            ),
		array(
        'name'    => __( 'Event Category', 'framework' ),
        'id'      => $prefix . 'event_category',
        'desc' => __("Choose Event Category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'event-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
            ),
		array(
        'name'    => __( 'Gallery Category', 'framework' ),
        'id'      => $prefix . 'gallery_category',
        'desc' => __("Choose Gallery Category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'gallery-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
            ),
		array(
        'name'    => __( 'Project Category', 'framework' ),
        'id'      => $prefix . 'project_category',
        'desc' => __("Choose Project Category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'project-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
            ),
		array(
        'name'    => __( 'Product Category', 'framework' ),
        'id'      => $prefix . 'product_category',
        'desc' => __("Choose Product Category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => $product_cat,
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
            ),
		array(
			'name' => __( 'Select Post Options', 'meta-box' ),
			'id' => $prefix.'select_post_options',
			'type' => 'checkbox_list',
			// Options of checkboxes, in format 'value' => 'Label'
			'options' => array(
			'thumb' => __( 'Thumbnail', 'framework' ),
			'title' => __( 'Title', 'framework' ),
			'text' => __( 'Text', 'framework' ),
			'more' => __('Read More','framework'),
			),
			),
		array(
			'name' => __( 'Select Post Content Type', 'meta-box' ),
			'id' => $prefix.'select_post_content',
			'type' => 'radio',
			// Array of 'value' => 'Label' pairs for radio options.
			// Note: the 'value' is stored in meta field, not the 'Label'
			'options' => array(
			'excerpt' => __( 'Excerpt', 'framework' ),
			'content' => __( 'Content', 'framework' ),
			),
			),
		array(
			'name' => __( 'Select Image Hyperlink', 'meta-box' ),
			'id' => $prefix.'select_thumb_hyperlink',
			'type' => 'radio',
			// Array of 'value' => 'Label' pairs for radio options.
			// Note: the 'value' is stored in meta field, not the 'Label'
			'options' => array(
			'0' => __( 'No Link', 'framework' ),
			'image' => __( 'Big Image', 'framework' ),
			'single' => __( 'Link to Post', 'framework' ),
			),
			),
		array(
			'name' => __( 'Select Title Hyperlink', 'meta-box' ),
			'id' => $prefix.'select_title_hyperlink',
			'type' => 'radio',
			// Array of 'value' => 'Label' pairs for radio options.
			// Note: the 'value' is stored in meta field, not the 'Label'
			'options' => array(
			'0' => __( 'No Link', 'framework' ),
			'single' => __( 'Link to Post', 'framework' ),
			),
			),
        array(
            'name' => __('Number of Latest Posts to show on page', 'framework'),
            'id' => $prefix . 'posts_to_show_on',
                'desc' => __("Enter the number of Latest Posts to show on page. example - 3 .", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
         array(
            'name' => __('Latest Post Title', 'framework'),
            'id' => $prefix . 'latest_post_title',
            'desc' => __("Enter the Latest Post Title.", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
		array(
            'name' => __('Visit Post Button Title', 'framework'),
            'id' => $prefix . 'visit_post_title',
            'desc' => __("Enter Visit Post Title.", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
         array(
            'name' => __('Visit Post Button Url', 'framework'),
            'id' => $prefix . 'visit_blog_url',
            'desc' => __("Enter Visit Blog Url.", 'framework'),
            'type' => 'text',
            'std' => ''
        ),
		array(
			'name' => __( 'Shortcode Editor', 'meta-box' ),
			'id' => $prefix.'home_shortcode_editor',
			'type' => 'wysiwyg',
			// Set the 'raw' parameter to TRUE to prevent data being passed through wpautop() on save
			'raw' => false,
			'std' => __( 'WYSIWYG default value', 'meta-box' ),
			// Editor settings, see wp_editor() function: look4wp.com/wp_editor
			'options' => array(
			'textarea_rows' => 4,
			'tinymce' => true,
			'media_buttons' => true,
			),
			),
        ));
/* * **Home Page Meta Box2 *** */
$meta_boxes[] = array(
    'id' => 'template-home2',
    'title' => __('Partners Metabox', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
          array(
            'name' => __('Enabled/Disable Partners Section', 'framework'),
            'id' => $prefix . 'partners_section_status',
            'desc' => __("Select Enabled to active Partners Section.", 'framework'),
            'type' => 'select',
            'options' => array(
		'0' => __('Disable', 'framework'),
		'1' => __('Enable','framework'),
            ),
	'std' => 0,
        ),
		array(
            'name' => __('Partner Area Title', 'framework'),
            'id' => $prefix . 'partner_area_title',
            'desc' => __("Enter partner area title.", 'framework'),
            'clone' => false,
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => __('Partners Description', 'framework'),
            'id' => $prefix . 'partners_description',
             'desc' => __("Enter Partners Description.", 'framework'),
            'type' =>  'wysiwyg',
            'options' => array(
            'textarea_rows' => 4,
            'teeny'         => true,
            'media_buttons' => false,
    ),
           
        ),
        array(
            'name' => __('Upload Partners Image', 'framework'),
            'id' => $prefix.'partners_image',
            'desc' => __("Upload Partners Image", 'framework'),
            'type' => 'image_advanced',
        ),
		array(
            'name' => __('Partner Logo Width', 'framework'),
            'id' => $prefix . 'partner_logo_width',
            'desc' => __("Enter width in px. This will be for all logo images, leave blank to show full size images.", 'framework'),
            'clone' => false,
            'type' => 'text',
            'std' => '100px',
        ),
		array(
            'name' => __('Partner URL', 'framework'),
            'id' => $prefix . 'partner_link',
            'desc' => __("Enter links for logos. First one is for the first image and so on..", 'framework'),
            'clone' => true,
            'type' => 'text',
            'std' => '',
        ),
       ));
/* * **Home Page Meta Box3 *** */
$meta_boxes[] = array(
    'id' => 'template-home3',
    'title' => __('Featured Projects Metabox', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
          array(
            'name' => __('Enabled/Disable Featured Projects', 'framework'),
            'id' => $prefix . 'featured_projects_status',
            'desc' => __("Select Enabled to active Featured Projects Section.", 'framework'),
            'type' => 'select',
            'options' => array(
		'0' => __('Disable', 'framework'),
		'1' => __('Enable','framework'),
            ),
	'std' => 0,
        ),
		array(
            'name' => __('Projects area Title', 'framework'),
            'id' => $prefix . 'projects_area_title',
            'desc' => __("Enter projects area title.", 'framework'),
            'clone' => false,
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => __('All Projects Url', 'framework'),
            'id' => $prefix . 'all_projects_url',
                'desc' => __("Enter All Projects Url.", 'framework'),
            'type' => 'text',
           
        ),
        array(
            'name' => __('Columns Layout', 'framework'),
           'id' => $prefix . 'home_projects_columns_layout',
           'desc' => __("Select Columns Layout .", 'framework'),
           'type' => 'select',
           'options' => array(
				 '2' => __('6', 'framework'),
				 '4' => __('3', 'framework'),
				 '3' => __('4','framework'),
				 '6' => __('2','framework'),
			),
			'std' => 4,
	),
       ));
/* * **Gallery Page Meta Box1 *** */
$meta_boxes[] = array(
    'id' => 'template-gallery1',
    'title' => __('Gallery  Metabox', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
           array(
            'name' => __('Enabled/Disable Sorting  Bar', 'framework'),
            'id' => $prefix . 'gallery_secondary_bar_type_status',
            'desc' => __("Select Enabled to active Sorting  Bar.", 'framework'),
            'type' => 'select',
            'options' => array(
		'1' => __('Enable', 'framework'),
		'0' => __('Disable','framework'),
            ),
	'std' => 0,
        ),
		array(
            'name' => __('Enabled/Disable Pagination', 'framework'),
            'id' => $prefix . 'gallery_page_pagination',
            'desc' => __("Select Enabled to active Pagination.", 'framework'),
            'type' => 'select',
            'options' => array(
		'1' => __('Enable', 'framework'),
		'0' => __('Disable','framework'),
            ),
	'std' => 0,
        ),
		array(
        'name'    => __( 'Gallery Category', 'framework' ),
        'id'      => $prefix . 'advanced_gallery_taxonomy',
        'desc' => __("Choose Gallery Category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'gallery-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
				'std' => '',
            ),
		array(
			'name'  => __('Gallery to show on page', 'framework'),
			'id'    => $prefix."gallery_number_show",
			'desc'  =>  __("Enter number of galleries to show on page, blank will show all Gallery items.", 'framework'),
			'type' => 'text',
		),  
       array(
            'name' => __('Columns Layout', 'framework'),
            'id' => $prefix . 'projects_columns_layout',
            'desc' => __("Select Columns Layout .", 'framework'),
            'type' => 'select',
            'options' => array(
				'3' => __('3', 'framework'),
				'4' => __('4','framework'),
				'2' => __('2','framework'),
					),
			'std' => 4,
	),
       ));
/* * **Project Page Meta Box1*** */
$meta_boxes[] = array(
    'id' => 'template-project1',
    'title' => __('Project Metabox', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
           array(
            'name' => __('Project Layout Type', 'framework'),
            'id' => $prefix . 'project_layout_type',
            'desc' => __("Select Project Layout Type.", 'framework'),
            'type' => 'select',
            'options' => array(
		'0' => __('List', 'framework'),
		'1' => __('Grid','framework'),
            ),
	'std' => 0,
        ),
         array(
            'name' => __('Enabled/Disable Sorting  Bar', 'framework'),
            'id' => $prefix . 'project_secondary_bar_type_status',
            'desc' => __("Select Enabled to active Sorting  Bar.", 'framework'),
            'type' => 'select',
            'options' => array(
		'0' => __('Enable', 'framework'),
		'1' => __('Disable','framework'),
            ),
	'std' => 0,
        ),
       array(
            'name' => __('Columns Layout', 'framework'),
            'id' => $prefix . 'temp_projects_columns_layout',
            'desc' => __("Select Columns Layout .", 'framework'),
            'type' => 'select',
            'options' => array(
				'3' => __('3', 'framework'),
				'4' => __('4','framework'),
				'2' => __('2','framework'),
					),
			'std' => 4,
	),
       ));
/* * **Contact Page Meta Box1*** */
$meta_boxes[] = array(
    'id' => 'template-contact1',
    'title' => __('Contact Metabox', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
     array(
			'name'  => __('Address', 'framework'),
			'id'    => $prefix."contact_address",
			'desc'  =>  __("This field should have real address to get GMap.", 'framework'),
			'type' => 'text',
		),   
		array(
			'id'    => $prefix."contact_map_location",
			'name' => __( 'Location', 'meta-box' ),
			'type' => 'map',
			'std' => '-6.233406,-35.049906,15', // 'latitude,longitude[,zoom]' (zoom is optional)
			'style' => 'width: 500px; height: 500px',
			'address_field' => 'imic_contact_address', // Name of text field where address is entered. Can be list of text fields, separated by commas (for ex. city, state)
			), 
       ));
/* * **Event Page Meta Box1*** */
$meta_boxes[] = array(
    'id' => 'template-event1',
    'title' => __('Event Metabox', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
		array(
            'name' => __('Featured Event Bar', 'framework'),
            'id' => $prefix . 'event_featured_bar',
            'desc' => __("Enable/Disable Featured Event Bar.", 'framework'),
            'type' => 'select',
            'options' => array(
		'0' => __('Disable', 'framework'),
		'1' => __('Enable','framework'),
            ),
	'std' => 0,
        ),   
      array(
            'name' => __('Event Layout Type', 'framework'),
            'id' => $prefix . 'event_layout_type',
            'desc' => __("Select Event Layout Type.", 'framework'),
            'type' => 'select',
            'options' => array(
		'0' => __('Month List', 'framework'),
		'1' => __('Grid','framework'),
		'2' => __('Future&Past','framework'),
            ),
	'std' => 0,
        ),  
		array(
			'name'  => __('Event Count', 'framework'),
			'id'    => $prefix."events_count",
			'desc'  =>  __("Number of Events to show for Future&Past.", 'framework'),
			'type' => 'text',
		),   
		array(
        'name'    => __( 'Event Category', 'framework' ),
        'id'      => $prefix . 'advanced_event_list_taxonomy',
        'desc' => __("Choose Event Category", 'framework'),
        'type'    => 'taxonomy_advanced',
        'options' => array(
                // Taxonomy name
                'taxonomy' => 'event-category',
                'type' => 'select',
                // Additional arguments for get_terms() function. Optional
                'args' => array('orderby' => 'count', 'hide_empty' => true)
                ),
			'std' => '',
            ), 
         array(
            'name' => __('Columns Layout', 'framework'),
            'id' => $prefix . 'temp_event_columns_layout',
            'desc' => __("Select Columns Layout .", 'framework'),
            'type' => 'select',
            'options' => array(
				'3' => __('3', 'framework'),
				'4' => __('4','framework'),
				'2' => __('2','framework'),
					),
			'std' => 4,
	),
       ));
/* * ******************* META BOX REGISTERING ********************** */
/**
 * Register meta boxes
 *
 * @return void
 */
function imic_register_meta_boxes() {
    global $meta_boxes;
    // Make sure there's no errors when the plugin is deactivated or during upgrade
    if (class_exists('RW_Meta_Box')) {
        foreach ($meta_boxes as $meta_box) {
            new RW_Meta_Box($meta_box);
        }
    }
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking Page template, categories, etc.
add_action('admin_init', 'imic_register_meta_boxes');
/* * ******************* META BOX CHECK ********************** */
/**
 * Check if meta boxes is included
 *
 * @return bool
 */
function rw_maybe_include($template_file) {
    // Include in back-end only
    if (!defined('WP_ADMIN') || !WP_ADMIN)
        return false;
    // Always include for ajax
    if (defined('DOING_AJAX') && DOING_AJAX)
        return true;
    // Check for post IDs
    $checked_post_IDs = array();
    if (isset($_GET['post']))
        $post_id = $_GET['post'];
    elseif (isset($_POST['post_ID']))
        $post_id = $_POST['post_ID'];
    else
        $post_id = false;
    $post_id = (int) $post_id;
    if (in_array($post_id, $checked_post_IDs))
        return true;
    // Check for Page template
    $checked_templates = array($template_file);
    $template = get_post_meta($post_id, '_wp_page_template', true);
    if (in_array($template, $checked_templates))
        return true;
// If no condition matched
    return false;
}
?>