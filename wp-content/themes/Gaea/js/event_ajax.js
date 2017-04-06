jQuery(document).ready(function() {
    jQuery("#ajax_events").on("click", "a.upcomingEvents", function(event) {
        var dateEvent = jQuery(this).attr('id');
		var termEvent = jQuery(this).attr('rel');
        jQuery('.listing-cont').fadeOut('slow');
        jQuery.ajax({
            type: 'POST',
            url: urlajax.ajaxurl,
            data: {
                action: 'imic_event_grid',
                date: dateEvent,
				term: termEvent,
            },
            success: function(data) {
                jQuery('.listing-cont').fadeIn('slow');
                jQuery('#ajax_events').html('');
                jQuery('#ajax_events').append(data);
                //jQuery('#ajax_events').fadeOut();
            },
            error: function(errorThrown) {
            }
        });
    });
	jQuery("#ajax_events").on("click", "a.pastevents", function(event) {
        var status = jQuery(this).attr('id');
		var termEvent = jQuery(this).attr('rel');
        jQuery('.listing-cont').fadeOut('slow');
        jQuery.ajax({
            type: 'POST',
            url: urlajax.ajaxurl,
            data: {
                action: 'imic_event_status_view',
                status: status,
				term: termEvent,
            },
            success: function(data) {
                jQuery('.listing-cont').fadeIn('slow');
                jQuery('#ajax_events').html('');
                jQuery('#ajax_events').append(data);
                //jQuery('#ajax_events').fadeOut();
            },
            error: function(errorThrown) {
            }
        });
    });
 });