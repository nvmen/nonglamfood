/*
 *
 *	Admin jQuery Functions
 *	------------------------------------------------
 *	Imic Framework
 * 	Copyright Imic 2014 - http://imicreation.com
 *
 */
jQuery(window).load(function() {
    jQuery('#imic_number_of_post_cat').parent().parent().find('.rwmb-clone').each(function() {
        jQuery(this).find('.rwmb-button').hide();
    })
    jQuery('#imic_number_of_post_cat').parent().parent().find('.add-clone').hide();
jQuery('#wpseo_meta.postbox').show();
});
jQuery(function(jQuery) {
//SELECTED TEMPLATE BASED META BOX DISPLAY
    function show_hide_metabox() {
        if ((jQuery('body').hasClass('post-php')||(jQuery('body').hasClass('post-new-php')))&&jQuery('body').hasClass('post-type-page')) {
            var metaID = jQuery('#page_template').val().slice(0, -4);
            metaboxName = metaID.substring(metaID.indexOf('-') + 1);
            jQuery('#normal-sortables > div').each(function(i, k)
            {
                if (jQuery(this).attr('id').indexOf(metaboxName) != -1)
                {
                jQuery(this).show();
                }
                else
                {
                    jQuery(this).hide();
                }
                if(metaboxName=='gallery'){
                     jQuery('#secondry_bar_meta_box').hide();
                }
                else{
                 jQuery('#secondry_bar_meta_box').show();   
                }
                 jQuery('#template-sidebar1').show();
                 jQuery('#post_page_meta_box').show();
		})
        }
    }
    show_hide_metabox();
    // META FIELD DISPLAY ON TEMPLATE SELECT
    jQuery('#page_template').on('change', function() {
        show_hide_metabox();
    });
    // META FIELD DISPLAY ON TEMPLATE SELECT
     // Hide Revslider Metabox
    jQuery('#mymetabox_revslider_0').hide();
    // Map Display
    var $imic_contact_map_display = jQuery('#imic_contact_map_display');
    function map_display() {
        var $imic_contact_bottom_box = jQuery('#imic_contact_map_box_code').parent().parent();
        if ($imic_contact_map_display.val() == "no") {
            $imic_contact_bottom_box.css('display', 'none');
        }
        else {
            $imic_contact_bottom_box.css('display', 'block');
        }
    }
    map_display();
    $imic_contact_map_display.change(function() {
        map_display();
    });
    // slider display for home
    var $imic_Choose_slider_display = jQuery('#imic_Choose_slider_display');
    function slider_display() {
        var $imic_slider_image = jQuery('#imic_slider_image_description').parent().parent();
        var $imic_slider_pagination = jQuery('#imic_slider_pagination').parent().parent();
        var $imic_slider_auto_slide = jQuery('#imic_slider_auto_slide').parent().parent();
        var $imic_slider_direction_arrows = jQuery('#imic_slider_direction_arrows').parent().parent();
        var $imic_slider_effects = jQuery('#imic_slider_effects').parent().parent();
        var $imic_select_revolution_from_list = jQuery('#imic_select_revolution_from_list').parent().parent();
        
        if ($imic_Choose_slider_display.val() == 0) {
            $imic_slider_image.show();
            $imic_slider_pagination.show();
            $imic_slider_auto_slide.show();
            $imic_slider_direction_arrows.show();
            $imic_slider_effects.show();
            $imic_select_revolution_from_list.hide();
        }
        else {
             $imic_slider_image.hide();
            $imic_slider_pagination.hide();
            $imic_slider_auto_slide.hide();
            $imic_slider_direction_arrows.hide();
            $imic_slider_effects.hide();
            $imic_select_revolution_from_list.show();
        }
    }
    slider_display();
    $imic_Choose_slider_display.change(function() {
        slider_display();
    });
	// Post Section for home
    var $imic_Choose_post_type = jQuery('#imic_selected_post_type');
    function select_post_type_home() {
        var $imic_post_category = jQuery('#imic_post_category').parent().parent();
        var $imic_event_category = jQuery('#imic_event_category').parent().parent();
        var $imic_gallery_category = jQuery('#imic_gallery_category').parent().parent();
        var $imic_project_category = jQuery('#imic_project_category').parent().parent();
        var $imic_product_category = jQuery('#imic_product_category').parent().parent();
        var $imic_post_options = jQuery('label[for="imic_select_post_options"]').parent().parent();
		var $imic_post_content = jQuery('label[for="imic_select_post_content"]').parent().parent();
		var $imic_image_hyperlink = jQuery('label[for="imic_select_thumb_hyperlink"]').parent().parent();
		var $imic_title_hyperlink = jQuery('label[for="imic_select_title_hyperlink"]').parent().parent();
        if ($imic_Choose_post_type.val() == 'post') {
            $imic_post_category.show();
            $imic_event_category.hide();
            $imic_gallery_category.hide();
            $imic_project_category.hide();
            $imic_product_category.hide();
			$imic_post_options.show();
			$imic_post_content.show();
			$imic_image_hyperlink.show();
			$imic_title_hyperlink.show();
        }
        else if($imic_Choose_post_type.val() == 'event'){
             $imic_post_category.hide();
            $imic_event_category.show();
            $imic_gallery_category.hide();
            $imic_project_category.hide();
            $imic_product_category.hide();
			$imic_post_options.hide();
			$imic_post_content.hide();
			$imic_image_hyperlink.hide();
			$imic_title_hyperlink.hide();
        }
		else if($imic_Choose_post_type.val() == 'gallery'){
             $imic_post_category.hide();
            $imic_event_category.hide();
            $imic_gallery_category.show();
            $imic_project_category.hide();
            $imic_product_category.hide();
			$imic_post_options.show();
			$imic_post_content.show();
			$imic_image_hyperlink.show();
			$imic_title_hyperlink.show();
        }
		else if($imic_Choose_post_type.val() == 'project'){
             $imic_post_category.hide();
            $imic_event_category.hide();
            $imic_gallery_category.hide();
            $imic_project_category.show();
            $imic_product_category.hide();
			$imic_post_options.show();
			$imic_post_content.show();
			$imic_image_hyperlink.show();
			$imic_title_hyperlink.show();
        }
		else if($imic_Choose_post_type.val() == 'product'){
             $imic_post_category.hide();
            $imic_event_category.hide();
            $imic_gallery_category.hide();
            $imic_project_category.hide();
            $imic_product_category.show();
			$imic_post_options.show();
			$imic_post_content.show();
			$imic_image_hyperlink.show();
			$imic_title_hyperlink.show();
        }
		else {
			$imic_post_category.hide();
            $imic_event_category.hide();
            $imic_gallery_category.hide();
            $imic_project_category.hide();
            $imic_product_category.hide();
			$imic_post_options.show();
			$imic_post_content.show();
			$imic_image_hyperlink.show();
			$imic_title_hyperlink.show();
		}
    }
    select_post_type_home();
    $imic_Choose_post_type.change(function() {
        select_post_type_home();
    });
	//header options for page/post
	var $imic_pages_choose_slider_display = jQuery('#imic_pages_Choose_slider_display');
    function pages_slider_display() {
        var $imic_pages_slider_image = jQuery('#imic_pages_slider_image_description').parent().parent();
        var $imic_pages_slider_pagination = jQuery('#imic_pages_slider_pagination').parent().parent();
        var $imic_pages_slider_auto_slide = jQuery('#imic_pages_slider_auto_slide').parent().parent();
        var $imic_pages_slider_direction_arrows = jQuery('#imic_pages_slider_direction_arrows').parent().parent();
        var $imic_pages_slider_effects = jQuery('#imic_pages_slider_effects').parent().parent();
		var $imic_pages_nivo_effects = jQuery('#imic_pages_nivo_effects').parent().parent();
        var $imic_pages_select_revolution_from_list = jQuery('#imic_pages_select_revolution_from_list').parent().parent();
		var $imic_banner_image = jQuery('#imic_header_image_description').parent().parent();
		var $imic_pages_slider_height = jQuery('#imic_pages_slider_height').parent().parent();
            var $imic_pages_banner_color = jQuery('#imic_pages_banner_color').closest('.rwmb-field');
           if ($imic_pages_choose_slider_display.val() == 3) {
            $imic_pages_slider_image.show();
            $imic_pages_slider_pagination.show();
            $imic_pages_slider_auto_slide.show();
            $imic_pages_slider_direction_arrows.show();
            $imic_pages_slider_effects.show();
			 $imic_pages_slider_height.show();
            $imic_pages_select_revolution_from_list.hide();
			 $imic_banner_image.hide();
			 $imic_pages_banner_color.hide();
			 $imic_pages_nivo_effects.hide();
        }
		else if ($imic_pages_choose_slider_display.val() == 4) {
            $imic_pages_slider_image.show();
            $imic_pages_slider_pagination.show();
            $imic_pages_slider_auto_slide.show();
            $imic_pages_slider_direction_arrows.show();
			$imic_pages_nivo_effects.show();
            $imic_pages_slider_effects.hide();
			 $imic_pages_slider_height.show();
            $imic_pages_select_revolution_from_list.hide();
			 $imic_banner_image.hide();
			 $imic_pages_banner_color.hide();
        }
		else if($imic_pages_choose_slider_display.val() == 2) {
			  $imic_banner_image.show();
			  $imic_pages_slider_image.hide();
            $imic_pages_slider_pagination.hide();
            $imic_pages_slider_auto_slide.hide();
            $imic_pages_slider_direction_arrows.hide();
            $imic_pages_slider_effects.hide();
            $imic_pages_select_revolution_from_list.hide();
			$imic_pages_slider_height.show();
			$imic_pages_banner_color.hide();
			$imic_pages_nivo_effects.hide();
		}
        else if($imic_pages_choose_slider_display.val() == 5) {
             $imic_pages_slider_image.hide();
            $imic_pages_slider_pagination.hide();
            $imic_pages_slider_auto_slide.hide();
            $imic_pages_slider_direction_arrows.hide();
            $imic_pages_slider_effects.hide();
			$imic_banner_image.hide();
			$imic_pages_slider_height.hide();
			$imic_pages_banner_color.hide();
			$imic_pages_nivo_effects.hide();
            $imic_pages_select_revolution_from_list.show();
        }
		else if($imic_pages_choose_slider_display.val() == 1) {
			$imic_pages_banner_color.show();
			$imic_pages_slider_image.hide();
            $imic_pages_slider_pagination.hide();
            $imic_pages_slider_auto_slide.hide();
            $imic_pages_slider_direction_arrows.hide();
            $imic_pages_slider_effects.hide();
			$imic_banner_image.hide();
			$imic_pages_slider_height.show();
            $imic_pages_select_revolution_from_list.hide();
			$imic_pages_nivo_effects.hide();
		}
		else {
			$imic_pages_slider_image.hide();
            $imic_pages_slider_pagination.hide();
            $imic_pages_slider_auto_slide.hide();
            $imic_pages_slider_direction_arrows.hide();
            $imic_pages_slider_effects.hide();
			$imic_banner_image.hide();
			$imic_pages_slider_height.hide();
            $imic_pages_select_revolution_from_list.hide();
			$imic_pages_banner_color.hide();
			$imic_pages_nivo_effects.hide();
		}
    }
    pages_slider_display();
    $imic_pages_choose_slider_display.change(function() {
        pages_slider_display();
    });
    jQuery('.repeatable-add').click(function() {
        field = jQuery(this).closest('td').find('.custom_repeatable li:last').clone(true);
        fieldLocation = jQuery(this).closest('td').find('.custom_repeatable li:last');
        jQuery('input', field).val('').attr('name', function(index, name) {
            return name.replace(/(\d+)/, function(fullMatch, n) {
                return Number(n) + 1;
            });
        })
        field.insertAfter(fieldLocation, jQuery(this).closest('td'))
        return false;
    });
    jQuery('.repeatable-remove').click(function() {
        jQuery(this).parent().remove();
        return false;
    });
   //Megamenu
     var megamenu = jQuery('.megamenu');
    megamenu.each(function() {
        checkCheckbox(jQuery(this));
    });
    megamenu.click(function() {
        checkCheckbox(jQuery(this));
    })
    function checkCheckbox(mega_check) {
        if (mega_check.is(':checked')) {
            mega_check.parents('.custom_menu_data').find('.enabled_mega_data').show();
        }
        else {
            mega_check.parents('.custom_menu_data').find('.enabled_mega_data').hide();
        }
    }
    var menu_post_type = jQuery('.enabled_mega_data .menu-post-type');
    function show_hide_post() {
        menu_post_type.each(function() {
            if (jQuery(this).val() == '') {
                jQuery(this).parents('.enabled_mega_data').find('.menu-post-id-comma').parent().parent().show();
                jQuery(this).parents('.enabled_mega_data').find('.menu-post').parent().parent().hide();
            }
            else {
                jQuery(this).parents('.enabled_mega_data').find('.menu-post-id-comma').parent().parent().hide();
                jQuery(this).parents('.enabled_mega_data').find('.menu-post').parent().parent().show();
            }
        })
    }
    show_hide_post();
    menu_post_type.on('change', function() {
        show_hide_post();
    });
  // Event Recurrence Box
    var $imic_event_frequency_type = jQuery('#imic_event_frequency_type');
   function eventRecurrenceDisplay() {
        var $imic_event_day_month = jQuery('#imic_event_day_month').closest('.rwmb-field');
        var $imic_event_week_day = jQuery('#imic_event_week_day').closest('.rwmb-field');
        var $imic_event_frequency = jQuery('#imic_event_frequency').closest('.rwmb-field');
        var $imic_event_frequency_count = jQuery('#imic_event_frequency_count').closest('.rwmb-field');
     if ($imic_event_frequency_type.val() == 0) {
            $imic_event_day_month.hide();
            $imic_event_week_day.hide();
            $imic_event_frequency.hide();
            $imic_event_frequency_count.hide();
       }
       else if ($imic_event_frequency_type.val() == 1) {
            $imic_event_day_month.hide();
            $imic_event_week_day.hide();
            $imic_event_frequency.show();
            $imic_event_frequency_count.show();
       }
        else {
            $imic_event_day_month.show();
            $imic_event_week_day.show();
            $imic_event_frequency.hide();
            $imic_event_frequency_count.show();
        }
    }
    eventRecurrenceDisplay();
    $imic_event_frequency_type.change(function() {
        eventRecurrenceDisplay();
    });
	var imic_gallery_meta_box = jQuery('#gallery_meta_box');
    var imic_gallery_video_url = jQuery('#imic_gallery_video_url').parent().parent();
    var imic_gallery_link_url = jQuery('#imic_gallery_link_url').parent().parent();
    var imic_gallery_slider_images = jQuery('#imic_gallery_images_description').parent().parent();
    var imic_gallery_audio = jQuery('#imic_gallery_audio').parent().parent();
     var imic_gallery_audio_display = jQuery('#imic_gallery_audio_display').parent().parent();
    var imic_gallery_audio_uploaded = jQuery('#imic_gallery_uploaded_audio_description').parent().parent();
   var imic_gallery_slider_all =jQuery('#imic_gallery_slider_pagination,#imic_gallery_slider_speed,#imic_gallery_slider_auto_slide,#imic_gallery_slider_direction_arrows,#imic_gallery_slider_effects').parent().parent();
   function checkPostFormat(radio_val) {
        if (jQuery.trim(radio_val) == 'video') {
            imic_gallery_meta_box.show();
            imic_gallery_link_url.hide();
            imic_gallery_slider_images.hide();
            imic_gallery_audio_display.hide();
            imic_gallery_audio_uploaded.hide();
            imic_gallery_slider_all.hide();
            imic_gallery_audio.hide();
            imic_gallery_video_url.show();
            imic_gallery_meta_box.find('#imic_gallery_slider_image_description').closest('.rwmb-field').show();
        }
        else if (jQuery.trim(radio_val) == 'link') {
            imic_gallery_meta_box.show();
            imic_gallery_link_url.show();
            imic_gallery_slider_images.hide();
            imic_gallery_audio.hide();
            imic_gallery_audio_display.hide();
            imic_gallery_slider_all.hide();
            imic_gallery_audio_uploaded.hide();
            imic_gallery_video_url.hide();
            imic_gallery_meta_box.find('#imic_gallery_slider_image_description').closest('.rwmb-field').show();
        }
        else if (jQuery.trim(radio_val) == 'gallery') {
            imic_gallery_meta_box.show();
            imic_gallery_link_url.hide();
            imic_gallery_slider_images.show();
            imic_gallery_audio.hide();
            imic_gallery_audio_display.hide();
            imic_gallery_audio_uploaded.hide();
            imic_gallery_slider_all.show();
            imic_gallery_video_url.hide();
            imic_gallery_meta_box.find('#imic_gallery_slider_image_description').closest('.rwmb-field').hide();
        }
         else if (jQuery.trim(radio_val) == 'audio') {
            imic_gallery_meta_box.show();
            imic_gallery_link_url.hide();
            imic_gallery_slider_images.hide();
            imic_gallery_video_url.hide();
            imic_gallery_slider_all.hide();
            imic_gallery_audio.show();
            imic_gallery_audio_display.show();
            imic_gallery_audio_uploaded.show();
            imic_gallery_meta_box.find('#imic_gallery_slider_image_description').closest('.rwmb-field').show();
            audio_display();
        }
        else {
            imic_gallery_meta_box.hide();
            
        }
    }
    jQuery('.post-type-gallery .post-format,.post-type-post .post-format').click(function() {
        if (jQuery(this).is(':checked'))
        {
            var radio_val = jQuery(this).val();
            checkPostFormat(radio_val)
        }
    })
    jQuery('.post-type-gallery,.post-type-post').find('.post-format').each(function() {
        if (jQuery(this).is(':checked'))
        {
            var radio_val = jQuery(this).val();
            checkPostFormat(radio_val);
            
        }
    });
// secondary Bar Type Box
    var $imic_secondary_bar_type = jQuery('#imic_secondary_bar_type');
   function secondaryBarType() {
        var $imic_secondary_left_title = jQuery('#imic_secondary_left_title').closest('.rwmb-field');
        var $imic_secondary_left_url = jQuery('#imic_secondary_left_url').closest('.rwmb-field');
        var $imic_secondary_right_title = jQuery('#imic_secondary_right_title').closest('.rwmb-field');
        var $imic_secondary_right_url = jQuery('#imic_secondary_right_url').closest('.rwmb-field');
        var $imic_single_page_menu_description = jQuery('#imic_single_page_menu_description').closest('.rwmb-field');
        var $imic_secondry_shortcode = jQuery('#imic_secondary_shortcode').closest('.rwmb-field');
       if ($imic_secondary_bar_type.val() == 0) {
            $imic_secondary_left_title.show();
            $imic_secondary_left_url.show();
            $imic_secondary_right_title.show();
            $imic_secondary_right_url.show();
            $imic_single_page_menu_description.hide();
            $imic_secondry_shortcode.hide();
       }
       else if ($imic_secondary_bar_type.val() == 1) {
           $imic_secondary_left_title.show();
            $imic_secondary_left_url.show();
            $imic_secondary_right_title.hide();
            $imic_secondary_right_url.hide();
            $imic_single_page_menu_description.show();
            $imic_secondry_shortcode.hide();
       }
        else {
           $imic_secondary_left_title.hide();
            $imic_secondary_left_url.hide();
            $imic_secondary_right_title.hide();
            $imic_secondary_right_url.hide();
            $imic_single_page_menu_description.hide();
            $imic_secondry_shortcode.show();
        }
    }
    secondaryBarType();
    $imic_secondary_bar_type.change(function() {
        secondaryBarType();
    });
    // Project Layout Type Box
    var $imic_project_layout_type = jQuery('#imic_project_layout_type');
   function projectLayoutType() {
        var $imic_project_secondary_bar_type_status = jQuery('#imic_project_secondary_bar_type_status').closest('.rwmb-field');
        var $imic_temp_projects_columns_layout = jQuery('#imic_temp_projects_columns_layout').closest('.rwmb-field');
        if ($imic_project_layout_type.val() == 0) {
            $imic_project_secondary_bar_type_status.hide();
            $imic_temp_projects_columns_layout.hide();
       }
     else {
            $imic_project_secondary_bar_type_status.show();
            $imic_temp_projects_columns_layout.show();
        }
    }
    projectLayoutType();
    $imic_project_layout_type.change(function() {
        projectLayoutType();
    });
     // Event Layout Type Box
    var $imic_event_layout_type = jQuery('#imic_event_layout_type');
   function eventLayoutType() {
        var $imic_temp_event_columns_layout = jQuery('#imic_temp_event_columns_layout').closest('.rwmb-field');
		var $imic_temp_events_count = jQuery('#imic_events_count').parent().parent();
       if ($imic_event_layout_type.val() == 0) {
            $imic_temp_event_columns_layout.hide();
			$imic_temp_events_count.hide();
       }
	   else if($imic_event_layout_type.val()==2) {
		   $imic_temp_events_count.show();
		   $imic_temp_event_columns_layout.hide();
	   }
     else{
          $imic_temp_event_columns_layout.show();
		 $imic_temp_events_count.hide();
        }}
    eventLayoutType();
    $imic_event_layout_type.change(function() {
        eventLayoutType();
    });
  });
    