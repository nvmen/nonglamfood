<?php 
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 * @author     WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.8
 */
get_header(); 
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
$pageSidebar = get_post_meta($id,'imic_select_sidebar_from_list', true);
if(!empty($pageSidebar)&&is_active_sidebar($pageSidebar)) {
$class = 9;  
}else{
$class = 12;  
}
//$pageOptions = imic_page_design($variable_post_id); //page design options ?>
<div class="main" role="main">
<div id="content" class="content full">
<div class="container">
    <div class="row">
        <?php if (have_posts()):?>
             <div class="col-md-<?php echo $class; ?> product-archive">  
                <!-- Products Listing -->
                            <?php
                            /**
                             * woocommerce_before_main_content hook
                             *
                             * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                             * @hooked woocommerce_breadcrumb - 20
                             */
                            do_action('woocommerce_before_main_content');?>
                            <?php do_action('woocommerce_archive_description'); ?>
                            <?php if (have_posts()) : ?>
                                <?php
                                /**
                                 * woocommerce_before_shop_loop hook
                                 *
                                 * @hooked woocommerce_result_count - 20
                                 * @hooked woocommerce_catalog_ordering - 30
                                 */
                                do_action('woocommerce_before_shop_loop');
                                ?>
                                <?php woocommerce_product_loop_start(); ?>
                                <?php woocommerce_product_subcategories(); ?>
                                <?php while (have_posts()) : the_post(); ?>
                                    <?php wc_get_template_part('content', 'product'); ?>
                                <?php endwhile; // end of the loop. ?>
                                <?php woocommerce_product_loop_end(); ?>
                                <?php
                                /**
                                 * woocommerce_after_shop_loop hook
                                 *
                                 * @hooked woocommerce_pagination - 10
                                 */
//				do_action( 'woocommerce_after_shop_loop' );
                                ?>
                            <?php elseif (!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) : ?>
                                <?php wc_get_template('loop/no-products-found.php'); ?>
                            <?php endif; ?>
                            <?php
                            /**
                             * woocommerce_after_main_content hook
                             *
                             * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                             */
                            do_action('woocommerce_after_main_content');
                            ?>
                <?php
                imic_pagination();
                ?>
            </div>
        <?php
       else:
           wc_get_template('loop/no-products-found.php'); 
        endif; ?>
        <!-- Start Sidebar -->
        <?php if(is_active_sidebar($pageSidebar)) { ?>
    	<div class="col-md-3">
    		<?php dynamic_sidebar($pageSidebar); ?>
    	</div>
    	<?php } ?>
        <!-- End Sidebar -->
    </div>
</div>
</div>
</div>
<?php get_footer(); ?>