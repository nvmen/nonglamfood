<?php
/**
 * The Template for displaying all single products.
 * @author 		WooThemes
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
?>
<div class="main" role="main">
<div id="content" class="content full">
<div class="container">
    <div class="row">
        <div class="col-md-<?php echo $class; ?>"> 
           <?php
                while ( have_posts() ) : the_post(); 
			 		wc_get_template_part( 'content', 'single-product' );
		
                    /** Sermon Tags * */
                    $tag= get_the_term_list(get_the_ID(), 'product_tag', '', ', ', '');
                    if(!empty($tag)){
                    echo '<div class="post-meta">';
                    echo '<i class="fa fa-tags"></i>';
                    echo $tag;
                    echo '</div>';
                    }
                endwhile;
                ?>
           </div>
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