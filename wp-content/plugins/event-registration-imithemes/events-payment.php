<?php 
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Events_Payments_List_Table extends WP_List_Table {
	
    function __construct(){
    	global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'Event Payment', 'framework' ),     //singular name of the listed records
            'plural'    => __( 'Events Payments', 'framework' ),   //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
    		)
		);
    }
	
	function extra_tablenav( $which ) {
	   if ( $which == "top" ){
		   $status = (isset($_GET['status']))?$_GET['status']:'select'; ?>
           <div class="alignleft actions">
            <form id="posts-filter" method="get" action="">
                <input class="post_status_page" type="hidden" value="events_payments" name="page">
                <input class="post_type_page" type="hidden" value="event" name="post_type">
                <select name="status">
                    <option value="select"><?php _e('Select Status','framework'); ?></option>
                    <option value="Completed" <?php echo ($status=='Completed')?'selected':''; ?>><?php _e('Completed','framework'); ?></option>
                    <option value="Incompleted" <?php echo ($status=='Incompleted')?'selected':''; ?>><?php _e('Incompleted','framework'); ?></option>
                    <option value="Pending" <?php echo ($status=='Pending')?'selected':''; ?>><?php _e('Pending','framework'); ?></option>
                </select>
                <input class="button" type="submit" value="Filter" name="">
            </form>
	        </div>
            <div class="alignleft actions">
            <form id="posts-filter" method="get" action="">
                <input class="post_status_page" type="hidden" value="causes_payments" name="page">
                <input class="post_type_page" type="hidden" value="causes" name="post_type">
                <select name="status">
                    <option value="select"><?php _e('Select Status','framework'); ?></option>
                    <option value="Completed" <?php echo ($status=='Completed')?'selected':''; ?>><?php _e('Completed','framework'); ?></option>
                    <option value="Incompleted" <?php echo ($status=='Incompleted')?'selected':''; ?>><?php _e('Incompleted','framework'); ?></option>
                    <option value="Pending" <?php echo ($status=='Pending')?'selected':''; ?>><?php _e('Pending','framework'); ?></option>
                </select>
                <input class="button" type="submit" value="Filter" name="">
            </form>
	        </div>
            <div id="overlay" class="overlay-bg">
                <div id="popup" class="overlay-content popup1">
                    <strong class="msg"></strong>
                    <p><u><?php _e('Update Payment Status for user','framework'); ?></u></p>
                    <p>
                        <select id="user-payment-status">
                            <option value="Completed"><?php _e('Completed','framework'); ?></option>
                            <option value="Pending"><?php _e('Pending','framework'); ?></option>
                            <option value="Incompleted"><?php _e('Incompleted','framework'); ?></option>
                        </select>
                    </p>
                    <p>
                        <button class="close-btn update-btn"><?php _e('Update','framework'); ?></button>
                        <button class="close-btn"><?php _e('Close','framework'); ?></button>
                    </p>
                </div>
            </div>
           <?php
	   }
	}
	
	function no_items() {
		_e( 'No events payments found.' );
	}

	function column_default( $item, $column_name ) {
		switch( $column_name ) { 
			case 'ID':
			case 'transaction_id':
			case 'status':
			case 'cause_name':
			case 'paid_by':
			case 'date':
			case 'amount':
			case 'pm_status':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}

	function get_columns(){
        $columns = array(
            'ID' => __( 'ID', 'framework' ),
			'transaction_id' => __( 'Transaction Id', 'framework' ),
            'status'    => __( 'Status', 'framework' ),
            'cause_name'      => __( 'Event Name', 'framework' ),
            'paid_by'      => __( 'Paid By', 'framework' ),
            'date'      => __( 'Date', 'framework' ),
            'amount'      => __( 'Amount in ', 'framework' ).' '.get_option('paypal_currency_options'),
			'pm_status'      => __( 'Payment Status', 'framework' ),
        );
         return $columns;
    }
	
	private function table_data() {
		global $wpdb;
		$post_name = 'event';
	    $payments_list = array();
	  	$status = (isset($_GET['status']))?$_GET['status']:'Completed';
		$table_name = $wpdb->prefix . "imic_payment_transaction";
		if(isset($_GET['status'])) {
			$sql_select="select * FROM $table_name WHERE `status` = '$status' AND `post_name` = '$post_name'"; 
		} else {
			$sql_select="select * FROM $table_name WHERE `post_name` = '$post_name'"; 
		}
		$data = $wpdb->get_results($sql_select,OBJECT)or print mysql_error();
		
		if(!empty($data)){
			$serial = 1;
			foreach($data as $data_t){
				$cause_id = strstr($data_t->cause_id, '-', true);
				$cause_title = get_the_title($cause_id);
				
				$payment_row = array( 
							'ID' => $serial,
							'transaction_id' => $data_t->transaction_id,
							'status'    => '<span id="status-'. $data_t->id .'">'. $data_t->status.'</span>',
							'cause_name'      => $cause_title,
							'paid_by'      => '<span id="'. $data_t->id .'" class="pay"><a>'.$data_t->user_name.'</a></span>',
							'date'      => date('d-m-Y', strtotime($data_t->date)),
							'amount'      => $data_t->amount,
							'pm_status'  => ($data_t->status=='Completed')?'Verified':'Not Verified'
							); 
				   
				array_push($payments_list, $payment_row);	
				$serial++; 
			}
		}
			
		return 	$payments_list;
	}
	
	function prepare_items() {
		$columns = $this->get_columns();
        $hidden = array();
 		$sortable = array();
        $data = $this->table_data();

		$user = get_current_user_id();
		$screen = get_current_screen();
		$screen_option = $screen->get_option('per_page', 'option');
		$per_page = get_user_meta($user, $screen_option, true);
		if ( empty ( $per_page) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}
        $perPage = $per_page;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
 
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
		
		$this->_actions = '';
 
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
 
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
	}

} ?>