<?php
if(!function_exists('imicRegisterEventMenuPage')){
	function imicRegisterEventMenuPage(){
		global $eventsPayments; 
		$eventsPayments = add_submenu_page( 'edit.php?post_type=event',__('Events Payments','framework'), __('Events Payments','framework'),'manage_options', 'events_payments', 'events_payment',6 );
		add_action("load-$eventsPayments", "events_payments_screen_options");
	}
	add_action( 'admin_menu', 'imicRegisterEventMenuPage');
}
if(!function_exists('events_payment')){
	function events_payment(){
		wp_enqueue_style('event-style',IMI_EVENTS_PLUGIN.'css/event-style.css');
		wp_enqueue_script('event-jquery',IMI_EVENTS_PLUGIN.'js/event-jquery.js');
		wp_localize_script('event-jquery', 'ajax', array('url' => admin_url('admin-ajax.php')));
		
		
		$eventsPaymentList = new Events_Payments_List_Table();
  		echo '</pre><div class="wrap"><h2>Events Payments</h2>'; 
  		$eventsPaymentList->prepare_items(); 
  		$eventsPaymentList->display(); 
  		echo '</div>';		
	}
}
function imic_events_frontend_script() {
	wp_enqueue_script('event-front-jquery',IMI_EVENTS_PLUGIN.'js/event.js','','',true);
	wp_localize_script('event-front-jquery', 'ajax', array('url' => admin_url('admin-ajax.php')));
}
add_action( 'wp_enqueue_scripts', 'imic_events_frontend_script' );
if(!function_exists('events_payments_screen_options')){
	function events_payments_screen_options(){
		global $eventsPayments;
		$screen = get_current_screen();
		// get out of here if we are not on our settings page
		if(!is_object($screen) || $screen->id != $eventsPayments)
			return;
		$args = array(
			'label' => __('Payments per page', 'framework'),
			'default' => 10,
			'option' => 'payments_per_page'
		);
		add_screen_option( 'per_page', $args );
	}
}
if(!function_exists('events_payments_set_screen_option')){
	function events_payments_set_screen_option($status, $option, $value) {
		if ( 'payments_per_page' == $option ) return $value;
	}
}
add_filter('set-screen-option', 'events_payments_set_screen_option', 10, 3);
if(!function_exists('imic_create_transaction_table')){
	function imic_create_transaction_table() {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		global $wpdb;
		$db_table_name = $wpdb->prefix . 'imic_payment_transaction';
		if( $wpdb->get_var( "SHOW TABLES LIKE '$db_table_name'" ) != $db_table_name ) {
			if ( ! empty( $wpdb->charset ) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty( $wpdb->collate ) )
				$charset_collate .= " COLLATE $wpdb->collate";
				$sql = "CREATE TABLE " . $db_table_name . " (
				id int(11) NOT NULL AUTO_INCREMENT,
				post_name varchar(20) NOT NULL,
				transaction_id varchar(60) NOT NULL,
				cause_id varchar(20) NOT NULL,
				amount int(11) NOT NULL,
				status varchar(60) NOT NULL,
				user_name varchar(20) NOT NULL,
				user_lname varchar(20) NOT NULL,
				user_email varchar(20) NOT NULL,
				user_phone int(11) NOT NULL,
				user_address varchar(20) NOT NULL,
				user_notes varchar(255) NOT NULL,
				date datetime NOT NULL,
				PRIMARY KEY (id)
				) $charset_collate;";
				dbDelta( $sql );
		}
	}
	add_action('wp_head','imic_create_transaction_table');
}

function imic_event_grids() {
	$date = date('Y-m-d H:i:s');
	$itemnumber = $_POST['itemnumber'];
	$name = $_POST['name'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$amount = $_POST['amount'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$notes = $_POST['notes'];
	$postname = $_POST['posttype'];
	global $wpdb;
	$table_name = $wpdb->prefix . "imic_payment_transaction";
	$sql = "INSERT INTO $table_name (post_name,transaction_id,cause_id,amount,status,user_name,user_lname,user_email,user_phone,user_address,user_notes,date) VALUES ('$postname','','$itemnumber', '$amount','pending','$name','$lastname','$email', '$phone','$address','$notes','$date')";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	//Email properties
		$mail_to = get_option('admin_email');
		$mail_subject = $name ." filled the form to pay event.";
		$mail_msg = $name ."' paying for Event with email address: '". $email ."'";
		wp_mail($mail_to,$mail_subject,$mail_msg);
	die();
}
add_action('wp_ajax_nopriv_imic_event_grids', 'imic_event_grids');
add_action('wp_ajax_imic_event_grids', 'imic_event_grids');

function imic_cause_function() {
	$output = '';
	$id = $_POST['id'];
	global $wpdb;
	$table_name = $wpdb->prefix . "imic_payment_transaction";
	$sql_select="select * from ".$table_name ." WHERE id IN ($id)";
	$data =$wpdb->get_results($sql_select,OBJECT)or print mysql_error();
	$output .= '<p><u>This is the information of user.</u></p>';
	$output .= '<p>User Transaction ID: '.$data[0]->transaction_id.'</p>';
	$output .= '<p>User Name: '.$data[0]->user_name.' '.$data[0]->user_lname.'</p>';
	$output .= '<p>User Email: '.$data[0]->user_email.'</p>';
	$output .= '<p>User Phone: '.$data[0]->user_phone.'</p>';
	$output .= '<p>User Address: '.$data[0]->user_address.'</p>';
	$output .= '<p>User Notes: '.$data[0]->user_notes.'</p>';
	echo $output;
	die();
}
add_action('wp_ajax_nopriv_imic_cause_function', 'imic_cause_function');
add_action('wp_ajax_imic_cause_function', 'imic_cause_function');

function imic_cause_status_function() {
	$output = '';
	$id = $_POST['id'];
	$status = $_POST['status'];
	global $wpdb;
	$table_name = $wpdb->prefix . "imic_payment_transaction";
	$sql = "UPDATE $table_name SET status='$status' WHERE id='$id'";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	echo $status;
	die();
}
add_action('wp_ajax_nopriv_imic_cause_status_function', 'imic_cause_status_function');
add_action('wp_ajax_imic_cause_status_function', 'imic_cause_status_function');

function imic_validate_payment($tx) {
	// Init cURL
	$request = curl_init();
	$paypal_payment = ($paypal_payment=="live")?"https://www.paypal.com/cgi-bin/webscr":"https://www.sandbox.paypal.com/cgi-bin/webscr";
	// Set request options
	curl_setopt_array($request, array
	(
	  CURLOPT_URL => $paypal_payment,
	  CURLOPT_POST => TRUE,
	  CURLOPT_POSTFIELDS => http_build_query(array
		(
		  'cmd' => '_notify-synch',
		  'tx' => $tx,
		  'at' => get_option('paypal_token_id'),
		)),
	  CURLOPT_RETURNTRANSFER => TRUE,
	  CURLOPT_HEADER => FALSE,
	  // CURLOPT_SSL_VERIFYPEER => TRUE,
	  // CURLOPT_CAINFO => 'cacert.pem',
	));

	// Execute request and get response and status code
	$response = curl_exec($request);
	$status   = curl_getinfo($request, CURLINFO_HTTP_CODE);
	
	// Close connection
	curl_close($request);
	// Remove SUCCESS part (7 characters long)
	$response = substr($response, 7);
	
	// URL decode
	$response = urldecode($response);

	// Turn into associative array
	preg_match_all('/^([^=\s]++)=(.*+)/m', $response, $m, PREG_PATTERN_ORDER);
	$response = array_combine($m[1], $m[2]);
	
	// Fix character encoding if different from UTF-8 (in my case)
	if(isset($response['charset']) AND strtoupper($response['charset']) !== 'UTF-8')
	{
	  foreach($response as $key => &$value)
	  {
		$value = mb_convert_encoding($value, 'UTF-8', $response['charset']);
	  }
	  $response['charset_original'] = $response['charset'];
	  $response['charset'] = 'UTF-8';
	}

	// Sort on keys for readability (handy when debugging)
	ksort($response);
	return $response;
} 

if(!function_exists('imic_get_currency_symbol')){
function imic_get_currency_symbol( $currency = '' ) {
	if ( ! $currency ) {
		$currency = 'USD';
	}
	switch ( $currency ) {
		case 'AED' :
			$currency_symbol = 'Ø¯.Ø¥';
			break;
		case 'BDT':
			$currency_symbol = '&#2547;&nbsp;';
			break;
		case 'BRL' :
			$currency_symbol = '&#82;&#36;';
			break;
		case 'BGN' :
			$currency_symbol = '&#1083;&#1074;.';
			break;
		case 'AUD' :
		case 'CAD' :
		case 'CLP' :
		case 'MXN' :
		case 'NZD' :
		case 'HKD' :
		case 'SGD' :
		case 'USD' :
			$currency_symbol = '&#36;';
			break;
		case 'EUR' :
			$currency_symbol = '&euro;';
			break;
		case 'CNY' :
		case 'RMB' :
		case 'JPY' :
			$currency_symbol = '&yen;';
			break;
		case 'RUB' :
			$currency_symbol = '&#1088;&#1091;&#1073;.';
			break;
		case 'KRW' : $currency_symbol = '&#8361;'; break;
		case 'TRY' : $currency_symbol = '&#84;&#76;'; break;
		case 'NOK' : $currency_symbol = '&#107;&#114;'; break;
		case 'ZAR' : $currency_symbol = '&#82;'; break;
		case 'CZK' : $currency_symbol = '&#75;&#269;'; break;
		case 'MYR' : $currency_symbol = '&#82;&#77;'; break;
		case 'DKK' : $currency_symbol = 'kr.'; break;
		case 'HUF' : $currency_symbol = '&#70;&#116;'; break;
		case 'IDR' : $currency_symbol = 'Rp'; break;
		case 'INR' : $currency_symbol = '&#x20B9;'; break;
		case 'ISK' : $currency_symbol = 'Kr.'; break;
		case 'ILS' : $currency_symbol = '&#8362;'; break;
		case 'PHP' : $currency_symbol = '&#8369;'; break;
		case 'PLN' : $currency_symbol = '&#122;&#322;'; break;
		case 'SEK' : $currency_symbol = '&#107;&#114;'; break;
		case 'CHF' : $currency_symbol = '&#67;&#72;&#70;'; break;
		case 'TWD' : $currency_symbol = '&#78;&#84;&#36;'; break;
		case 'THB' : $currency_symbol = '&#3647;'; break;
		case 'GBP' : $currency_symbol = '&pound;'; break;
		case 'RON' : $currency_symbol = 'lei'; break;
		case 'VND' : $currency_symbol = '&#8363;'; break;
		case 'NGN' : $currency_symbol = '&#8358;'; break;
		case 'HRK' : $currency_symbol = 'Kn'; break;
		default    : $currency_symbol = ''; break;
	}
	return $currency_symbol;
}
}
?>