<!-- Start Footer -->
    <?php
$menu_locations = get_nav_menu_locations();
global $imic_options;
if ( is_active_sidebar( 'footer-sidebar' ) ) : ?>
<footer class="site-footer site-top-footer">
    <div class="container">
        <div class="row">
        	<?php dynamic_sidebar('footer-sidebar'); ?>
        </div>
    </div>
</footer>
<?php endif; ?>
    <footer class="site-footer site-bottom-footer">
    	<div class="container">
        	<div class="row">
                	<?php
            if (!empty($imic_options['footer_copyright_text'])) {
                echo '<div class="col-md-4 col-sm-4">'; ?>
                <p><?php echo $imic_options['footer_copyright_text']; ?></p>
                <?php echo '</div>';
            }
			 if (!empty($menu_locations['footer-menu'])) {
            	echo '<div class="col-md-8 col-sm-8">';
                    	wp_nav_menu(array('theme_location' => 'footer-menu', 'container' => '','items_wrap' => '<ul id="%1$s" class="footer-nav">%3$s</ul>'));
                    echo '</div>'; } ?>
                </div>
            </div>
    </footer>
    <!-- End Footer -->
<?php if ($imic_options['enable_backtotop'] == 1) { 
echo'<a id="back-to-top"><i class="fa fa-angle-double-up"></i></a> ';
 } ?>
</div>
<!-- End Boxed Body -->
<?php wp_footer(); ?>
</body>
<?php
$event_id = get_the_ID();
$post_type = get_post_type($event_id);
if($post_type=='event') {
$event_guest_register = get_post_meta(get_the_ID(),'imic_event_guest_checkout',true);
$event_registration_fee = get_post_meta(get_the_ID(),'imic_event_registration_fee',true);
$address1 = get_post_meta($id,'imic_event_address1',true);
$address2 = get_post_meta($id,'imic_event_address2',true);
$date = get_query_var('event_date');
if(empty($date)){
   $date= get_post_meta(get_the_ID(),'imic_event_start_dt',true);
}
 $event_time=get_post_meta(get_the_ID(),'imic_event_start_dt',true);
 $event_time = strtotime($event_time);
$date = strtotime($date);
if(is_user_logged_in()||$event_guest_register==1) {
	global $current_user;
      get_currentuserinfo();
	  $this_email = $current_user->user_email;
	  $this_fname = $current_user->user_firstname;
	  $this_lname = $current_user->user_lastname;
	  $this_username = $current_user->display_name;
	  $this_actualname = ($this_fname=='')?$this_username:$this_fname; ?> 
<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php _e('Your ticket for the ','framework'); echo get_the_title(); ?></h4>
            </div>
            <div class="modal-body">
                <!-- Event Register Tickets -->
                <div class="ticket-booking-wrapper">
                    <div class="ticket-booking">
                        <div class="event-ticket ticket-form">
                            <div class="event-ticket-left">
                            	<div class="ticket-id"><?php $event_reg = isset($_REQUEST['item_number'])?$_REQUEST['item_number']:''; if(!empty($event_reg)) { $event_reg = explode('-',$event_reg); echo $event_reg[1]; } ?></div>
                                <div class="ticket-handle"></div>
                                <div class="ticket-cuts ticket-cuts-top"></div>
                                <div class="ticket-cuts ticket-cuts-bottom"></div>
                            </div>
                            <div class="event-ticket-right">
                                <div class="event-ticket-right-inner">
                                    <div class="row">
                                        <div class="col-md-9 col-sm-9">
                                            <span class="registerant-info">
                                                <?php echo $this_actualname.' '.$this_lname; ?><br><?php echo $this_email; ?>
                                            </span>
                                             <span class="meta-data"><?php _e('Event','framework'); ?></span>
                                             <h4 id="dy-event-title"><?php echo get_the_title(); ?></h4>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <span class="ticket-cost"><?php if($event_registration_fee!=0||$event_registration_fee!='') { echo imic_get_currency_symbol(get_option('paypal_currency_options')).$event_registration_fee; } else { _e('Free','framework'); } ?></span>
                                        </div>
                                    </div>
                                    <div class="event-ticket-info">
                                        <div class="row">
                                            <div class="col">
                                                <p class="ticket-col" id="dy-event-date"><?php echo esc_attr(date_i18n(get_option('date_format'),$date)); ?></p>
                                            </div>
                                            <div class="col">
                                                <p class="ticket-col event-location" id="dy-event-location"><?php echo $address1; ?></p>
                                            </div>
                                            <div class="col">
                                                <p id="dy-event-time"><?php _e('Starts ','framework'); echo esc_attr(date_i18n(get_option('time_format'),$event_time)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="event-area"><?php echo $address2; ?></span>
                                    <div class="row">
                                        <div class="col-md-12">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default inverted" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onClick="window.print()">Print</button>
            </div>
        </div>
    </div>
</div>
<?php } } ?>
</html>