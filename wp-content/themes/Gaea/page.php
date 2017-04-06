<?php get_header();
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
$post_id = get_post($id);
		$content = $post_id->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]>', $content);
if($content!='') {
?>
    <!-- End Page Header -->
<div class="main" role="main">
	<div id="content" class="content full">
    <?php 
$pageSidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list', true);
if(!empty($pageSidebar)&&is_active_sidebar($pageSidebar)) {
$class = 9;  
}else{
$class = 12;  
}
echo '<div class ="container">';
echo '<div class="row">';
echo '<div class ="col-md-'.$class.'">';
    if(have_posts()):while(have_posts()):the_post();
	the_content();
	endwhile; endif; ?>
<?php if ($imic_options['switch_sharing'] == 1 && $imic_options['share_post_types']['2'] == '1') { ?>
	<?php imic_share_buttons(); ?>
<?php }
echo '</div>';
if(is_active_sidebar($pageSidebar)) {
echo'<div class="col-md-3">';
dynamic_sidebar($pageSidebar);
echo'</div>';
}
echo'</div></div>'; ?>
        </div>
</div>
<?php } get_footer(); ?>