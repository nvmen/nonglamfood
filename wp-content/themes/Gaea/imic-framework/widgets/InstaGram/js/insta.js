jQuery(function($){
	"use strict";
var GAEA = window.GAEA || {};
/*-----------------------------------------------------------------------------------*/
/*	INSTAGRAM FEED WIDGET
/*-----------------------------------------------------------------------------------*/
	GAEA.InstaWidget = function() {
         var insta_ids = parseInt(insta.ids);
		var widgetFeed = new Instafeed({
			target: 'insta-widget',
			get: 'user',
			limit: insta.counts,
			userId: insta_ids,
			accessToken: insta.token,
			resolution: 'thumbnail',
			template: '<li><a href="{{link}}" target="_blank"><img src="{{image}}" alt="" /></a></li>'
		});
		
		$('#insta-widget').each(function() {
			widgetFeed.run();
		});
	}
        /* ==================================================
   Init Functions
================================================== */
$(document).ready(function(){
	GAEA.InstaWidget();
});
});