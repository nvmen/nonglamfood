<?php
/*
    Plugin Name: Hide Related Products in WooCommerce
    Version: 1.0
    Plugin URI: http://www.basitansari.com
    Description: Don't want to show Related Products in your WooCommerce store ? Hide them with this plugin.
    Author: Basit Ansari
    Author URI: http://www.basitansari.com/
    License: GPL v3
    
    Hide Related Products in WooCommerce Store
    Copyright (C) 2013, www.basitansari.com

*/


function wc_remove_related_products( $args ) {
    return array();
}
add_filter('woocommerce_related_products_args','wc_remove_related_products', 10);
