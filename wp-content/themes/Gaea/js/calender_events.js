jQuery(document).ready(function() {
	var expiryDate = jQuery('.counter-preview').data('date');
				var target = new Date(expiryDate),
				finished = false,
				availiableExamples = {
					set15daysFromNow: 15 * 24 * 60 * 60 * 1000,
					set5minFromNow  : 5 * 60 * 1000,
					set1minFromNow  : 1 * 60 * 1000
				};
				function callbacks(event) {
					var $this = jQuery(this);
					switch(event.type) {
						case "seconds":
						case "minutes":
						case "hours":
						case "days":
						case "weeks":
						case "daysLeft":
							$this.find('div span#'+event.type).html(event.value);
							if(finished) {
								$this.fadeTo(0, 1);
								finished = false;
							}
							break;
						case "finished":
							$this.fadeTo('slow', .5);
							finished = true;
							break;
					}
				}
				jQuery('.counter-preview').countdown(target.valueOf(), callbacks);
    jQuery('.calendar').prepend('<img id="loading-image" src="' + calenderEvents.homeurl + '/images/loader.gif" alt="Loading..." />');
	var source = {
		googleCalendarId: 'usa__en@holiday.calendar.google.com'
	};
      jQuery('.calendar').fullCalendar({
        monthNames: calenderEvents.monthNames,
        monthNamesShort: calenderEvents.monthNamesShort,
        dayNames: calenderEvents.dayNames,
        dayNamesShort: calenderEvents.dayNamesShort,
        editable: true,
		eventLimit: 3,
			eventSources: [
				{
					url: calenderEvents.homeurl + '/includes/json-feed.php',
					type: 'POST',
					data: {
					   event_cat_id: jQuery('.event_calendar').attr('id'),
					  },
					
				},
				{
					googleCalendarApiKey: calenderEvents.googlekey,
					googleCalendarId:calenderEvents.googlecalid,
				}
				],
		eventClick: function(event, element) {
			if (event.url.indexOf('google') > -1) {
                               // opens events in a popup window
                               window.open(event.url, 'gcalevent', 'width=700,height=600');
                               return false; }
			else {
			var milliseconds = (new Date).getTime();
			var seconds = milliseconds/1000;
			var arr = event.id.split('|');
			jQuery('#events-preview-box').fadeOut('slow');
			jQuery.ajax({
            type: 'POST',
			async: false, 
            url: calenderEvents.ajaxurl,
            data: {
                action: 'imic_get_event_details',
                id: event.id,
            },
            success: function(data) {
                jQuery('#events-preview-box').html('');
				jQuery('#events-preview-box').fadeIn('slow');
                jQuery('#events-preview-box').html(data);
				jQuery('a[data-toggle=tooltip]').tooltip();
				if(arr[1]<seconds) { jQuery(".preview-event-bar").hide(); }
				var expiryDate = jQuery('.counter-preview').data('date');
				var target = new Date(expiryDate),
				finished = false;
				jQuery('.counter-preview').countdown(target.valueOf(), callbacks);
            },
			}); }
        },
		eventRender: function (event, element)
        {
			if(calenderEvents.preview==1) { 
            element.attr('href', 'javascript:void(0)'); }
        },
        timeFormat: calenderEvents.time_format,
        firstDay:calenderEvents.start_of_week,
        loading: function(bool) {
            if (bool)
                jQuery('#loading-image').show();
            else
                jQuery('#loading-image').hide();
        },
    });
jQuery("ul.nav-pills li").click(function(){
	var term = jQuery(this).attr("id");
	reloadCal(term);
});
function reloadCal(event_term) {
	var source = {
		googleCalendarApiKey: calenderEvents.googlekey,
		googleCalendarId: calenderEvents.googlecalid
	};
	if((event_term!="google")&&(event_term!="")) {
	jQuery('.calendar').fullCalendar('removeEventSource',source.googleCalendarId); }
	else {
		jQuery('.calendar').fullCalendar('removeEventSource',source.googleCalendarId);
		jQuery('.calendar').fullCalendar('addEventSource', source);	
	}
	//newSource = jQuery.post(calenderEvents.homeurl + '/includes/json-feed.php',  {json: JSON.stringify("ss")});
    //jQuery('.calendar').fullCalendar('removeEventSource', source);
	//if(event_term!='') {
	jQuery('.calendar').fullCalendar('removeEventSource', calenderEvents.homeurl + '/includes/json-feed.php'); //}
	jQuery('.calendar').fullCalendar('refetchEvents');
    jQuery('.calendar').fullCalendar('addEventSource', { url: calenderEvents.homeurl + '/includes/json-feed.php',
					type: 'POST',
					data: {
					   event_cat_id: event_term,
					  }})
    jQuery('.calendar').fullCalendar('refetchEvents');
}
});