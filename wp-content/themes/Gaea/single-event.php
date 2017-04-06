<?php get_header();
((get_query_var('login'))||(get_query_var('guest')))?wp_enqueue_script('imic_event_pay'):'';
wp_enqueue_script('agent-register', IMIC_THEME_PATH . '/js/agent-register.js', '', '', true);
wp_localize_script('agent-register', 'agent_register', array('ajaxurl' => admin_url('admin-ajax.php')));
$options = get_option('imic_options');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$transaction_id=isset($_REQUEST['tx'])?$_REQUEST['tx']:'';
if($transaction_id!='') {
	wp_enqueue_script('imic_print_ticket');
	if(is_numeric($transaction_id)) {
	global $wpdb;
	$table_name = $wpdb->prefix . "imic_payment_transaction";
	$payment_array=imic_validate_payment($transaction_id);
	$st = $payment_array['payment_status'];
	$user_id=isset($_REQUEST['item_number'])?$_REQUEST['item_number']:'';
	$cause_id=strstr($user_id, '-', true);
	$cause_name=get_the_title($cause_id);
	if(!empty($transaction_id)&&!empty($st)){
		$sql_select="select transaction_id from $table_name WHERE `transaction_id` = '$transaction_id'";
		$data =$wpdb->get_results($sql_select,ARRAY_A)or print mysql_error();
		if(empty($data)){
			$amt=isset($_REQUEST['amt'])?$_REQUEST['amt']:'';
			$sql = "UPDATE $table_name SET transaction_id='$transaction_id',status='$st' WHERE cause_id='$user_id'";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}else{}
	}
} }
$registration_status = get_post_meta(get_the_ID(),'imic_event_registration_status',true);
$event_registration_fee = get_post_meta(get_the_ID(),'imic_event_registration_fee',true);
wp_enqueue_script('imic_google_map');
wp_enqueue_script('imic_event_gmap');
if(is_home()) { $id = get_option('page_for_posts'); }
else { $id = get_the_ID(); }
$page_header = get_post_meta($id,'imic_pages_Choose_slider_display',true);
if($page_header==3) {
	get_template_part( 'pages', 'flex' );
}
elseif($page_header==4) {
	get_template_part( 'pages', 'nivo' );
}
elseif($page_header==5) {
	get_template_part( 'pages', 'revolution' );
}
else {
	get_template_part( 'pages', 'banner' );
}
$event_time = get_post_meta($id,'imic_event_start_dt',true);
$address1 = get_post_meta($id,'imic_event_address1',true);
$address2 = get_post_meta($id,'imic_event_address2',true);
$date = get_query_var('event_date');
if(empty($date)){
   $date= get_post_meta(get_the_ID(),'imic_event_start_dt',true);
}
 $event_time=get_post_meta(get_the_ID(),'imic_event_start_dt',true);
 $event_time = strtotime($event_time);
$date = strtotime($date);
$event_address = get_post_meta(get_the_ID(),'imic_event_map_location',true);
if($event_address!='') {
$event_address = explode(',', $event_address);
echo '<div class="event_container"><div id="property'.get_the_ID().'" style="display:none;"><span class ="latitude">'.$event_address[0].'</span><span class ="longitude">'.$event_address[1].'</span></div></div>'; }
?>
<div class="secondary-bar">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-3 col-sm-3 col-xs-6">
                	<div class="single-event-info">
                    	<span class="day"><?php echo esc_attr(date_i18n('l',$date)); ?></span>
                        <span class="date"><?php echo esc_attr(date_i18n(get_option('date_format'),$date)); ?></span>
                        <span class="time"><?php echo esc_attr(date_i18n(get_option('time_format'),$event_time)); ?></span>
                    </div>
                </div>
            	<div class="col-md-5 col-sm-5 col-xs-6">
                	<span class="event-single-venue">
                		<span><i class="fa fa-map-marker"></i></span>
                    	<span><?php echo $address1; ?></span>
                    	<span><?php echo $address2; ?></span>
                        <a data-toggle="modal" data-target="#map-modal" href="#" class="basic-link map-modal-opener"><?php _e('Get Directions','framework'); ?> <i class="fa fa-angle-right"></i></a>
                    <div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel"><?php _e('Get Directions','framework'); ?></h4>
                          </div>
                            <div class="modal-body">
                                <div class="clearfix map-single-page" id="onemap"></div>
                            </div>
                        </div>
                      </div>
                    </div>
                   	</span>
                </div>
                <?php if((is_plugin_active('event-registration-imithemes/event-reg.php'))&&($registration_status==1)) { 
					if($date>date('U')) { ?>
            	<div class="col-md-4 col-sm-4 col-xs-12">
                	<a href="#" id="donate-popup" class="event-register-block donate-paypal" data-toggle="modal" data-target="#PaymentModal"><?php if($event_registration_fee!=0||$event_registration_fee!='') { _e('Register for Event - ','framework'); echo imic_get_currency_symbol(get_option('paypal_currency_options')).$event_registration_fee; } else { _e('Register for Event - Free','framework'); } ?></a>
                </div><?php } else { echo '<div class="col-md-4 col-sm-4 col-xs-12"><div class="single-event-info"><span class="time">'.__('Passed Event','framework').'<span></div></div>'; } } ?>
            </div></div></div>
<?php
$pageSidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list', true);
if(!empty($pageSidebar)&&is_active_sidebar($pageSidebar)) {
$class = 9;  
}else{
$class = 12;  
}
echo '<div class="main" role="main">
<div id="content" class="content full">';
echo '<div class ="container">';
echo '<div class="row">';
echo '<div class ="col-md-'.$class.'">'; 
$recurring = get_post_meta(get_the_ID(),'imic_event_frequency_type',true); 
$recurring_icon = ($recurring!=0)?'<i class="fa fa-refresh"></i> '.__('Recurring','framework'):'';
$image_type = get_post_meta(get_the_ID(),'imic_event_image_type',true);
$slider_images = get_post_meta(get_the_ID(),'imic_event_slider_images',false);
$event_sponsors_status = get_post_meta(get_the_ID(),'imic_event_sponsors_status',true);
?>
<h2 class="title"><?php echo get_the_title(); ?> <label class="label label-primary"><?php echo $recurring_icon; ?></label></h2>
                    	<div class="entry">
                        <?php if($image_type=='slider') { if(!empty($slider_images)) { 
							wp_enqueue_script( 'imic_jquery_flexslider' );
							echo '<div class="flexslider" data-pagination="yes" data-style="slide">
                       			<ul class="slides">';
								foreach($slider_images as $image): 
								$image = wp_get_attachment_image_src($image,'1000x400',''); ?>
                                    <li>
                                        <img src="<?php echo esc_url($image[0]); ?>" alt="">
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                           	</div><?php } } else { if(''!=get_the_post_thumbnail()) { the_post_thumbnail('100x400'); } } ?>
                            <div class="spacer-30"></div>
<?php if(have_posts()):while(have_posts()):the_post();
	the_content();
	if ($imic_options['switch_sharing'] == 1 && $imic_options['share_post_types']['3'] == '1') {
		imic_share_buttons();
	}
	endwhile; endif; if($event_sponsors_status==1) { 
	$sponsors_images = get_post_meta(get_the_ID(),'imic_event_sponsors_images',false);
	?>
    <h3><?php _e('Event Sponsors','framework'); ?></h3>
                            <ul class="partner-logos">
                            <?php if(!empty($sponsors_images)) { foreach($sponsors_images as $image):
									$image = wp_get_attachment_image_src($image,'100x100',''); ?>
                                <li><img src="<?php echo esc_url($image[0]); ?>" alt=""></li>
                           	<?php endforeach; } ?>
                            </ul>
<?php }                          
echo '</div></div>';
if(is_active_sidebar($pageSidebar)) {
echo'<div class="col-md-3">';
dynamic_sidebar($pageSidebar);
echo'</div>';
}
$date_converted=date('Y-m-d',$date );
$custom_event_url= imic_query_arg($date_converted,get_the_ID()); 
$event_guest_checkout = get_post_meta(get_the_ID(),'imic_event_guest_checkout',true); ?>
<div class="modal fade" id="PaymentModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="PaymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            	<h4 class="modal-title" id="myModalLabel"><?php _e('Register for Event: ','framework'); ?><span class="accent-color payment-to-cause"><?php the_title(); ?></span></h4>
                            </div>
                            <div class="modal-body">
                            	<?php if(is_user_logged_in()) { echo do_shortcode('[imic_events amount="'.$event_registration_fee.'" event_id="'.get_the_ID().'" description="'.get_the_title().'" return="'.$custom_event_url.'"]'); } elseif((get_query_var('guest')==1)&&($event_guest_checkout==1)) { echo do_shortcode('[imic_events amount="'.$event_registration_fee.'" event_id="'.get_the_ID().'" description="'.get_the_title().'" return="'.$custom_event_url.'"]'); } else {  ?>
                                <div class="tabs">
                                  <ul class="nav nav-tabs">
                                    <li class="active"> <a data-toggle="tab" href="#login-user-form"> <?php _e('Login','framework'); ?> </a> </li>
                                    <li> <a data-toggle="tab" href="#register-user-form"> <?php _e('Register','framework'); ?> </a> </li>
                                  </ul>
                                  <div class="tab-content">
                                    <div id="login-user-form" class="tab-pane active">
                                      <form id="login" action="login" method="post">
										<?php 
                                        $redirect_login= get_post_meta(get_the_ID(),'imic_login_redirect_options',true);
                                        $redirect_login=!empty($redirect_login)?$redirect_login:  home_url();
                                        ?>
                                        <input type ="hidden" class ="redirect_login" name ="redirect_login" value ="<?php echo add_query_arg('login','1',$custom_event_url); ?>"/>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input class="form-control input1" id="loginname" type="text" name="loginname">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                        <input class="form-control input1" id="password" type="password" name="password">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                        <input type="checkbox" checked="checked" value="true" name="rememberme" id="rememberme" class="checkbox"> <?php _e('Remember Me!','framework'); ?>
                                        </div>
                                        <br>
                                        <input class="submit_button btn btn-primary button2" type="submit" value="<?php _e('Login Now','framework'); ?>" name="submit">
                                        <?php if($event_guest_checkout==1) { ?>
                                        <a class="submit_button btn btn-primary button2" type="button" href="<?php echo add_query_arg('guest','1',$custom_event_url); ?>"><?php _e('Proceed as Guest','framework'); ?></a>
                                        <?php } wp_nonce_field( 'ajax-login-nonce', 'security' ); ?><p class="status"></p>
                                        </form>
                                    </div>
                                    <div id="register-user-form" class="tab-pane">
                                      <form method="post" id="registerform" name="registerform" class="register-form">
                                        <input type ="hidden" class ="redirect_register" name ="redirect_register" value ="<?php echo add_query_arg('login','1',$custom_event_url); ?>"/>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input type="text" name="username" id="username" class="form-control" placeholder="<?php _e('Username','framework'); ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="<?php _e('Email','framework'); ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                        <input type="password" name="pwd1" id="pwd1" class="form-control" placeholder="<?php _e('Password','framework'); ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-refresh"></i></span>
                                        <input type="password" name="pwd2" id="pwd2" class="form-control" placeholder="<?php _e('Repeat Password','framework') ?>">
                                        </div>
                                        <br>
                                        <input type="hidden" name="image_path" id="image_path" value="<?php echo get_template_directory_uri(); ?>">                             
                                        <input type="hidden" name="task" id="task" value="register" />
                                        <button type="submit" id="submit" class="btn btn-primary"><?php _e('Register Now','framework'); ?></button>
                                        </form><div class="clearfix"></div>
                                        <div id="message"></div>
                                    </div>
                                  </div>
                                </div><?php } ?>
                            </div>
                            <div class="modal-footer">
                            	<p class="small short"><?php echo (get_option('registration_form_info')!='')?get_option('registration_form_info'):'If you would prefer to call in your registration, please call 1800.785.876'; ?></p>
                            </div>
                        </div>
                        </div>
                    </div>
</div>
        </div></div></div>
<?php get_footer(); ?>