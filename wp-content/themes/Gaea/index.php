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
$pageSidebar = get_post_meta($id,'imic_select_sidebar_from_list', true);
if(!empty($pageSidebar)&&is_active_sidebar($pageSidebar)) {
$class = 9;  
}else{
$class = 12;  
}
if(is_archive()||is_tag()) {
	echo '<div class="secondary-bar">
	<div class="container">
	<div class="row">
	<div class="col-md-12">';
	if(is_tag()) {
	echo '<span class="big">'. __( 'Tag Archives: ', 'framework' ), single_tag_title( '', false ) .'</span>'; }
	elseif(is_archive()) {
	echo '<span class="big">'. __( 'Archives: ', 'framework' ), single_cat_title( '', false ) .'</span>'; }
	echo '</div>
	</div>
	</div>
	</div>';
}
?>
    <!-- Start Body Content -->
  	<div class="main" role="main">
    	<div id="content" class="content full">
    		<div class="container">
            	<div class="row">
                	<div class="col-md-<?php echo $class; ?>">
                        <ul class="posts-listing">
                        <?php if(have_posts()):while(have_posts()):the_post(); ?>
                            <li id="post-<?php the_ID(); ?>" <?php post_class('post-list-item format-standard'); ?>>
                                <div class="row">
                                <?php $post_class = 12; if ( '' != get_the_post_thumbnail() ) { $post_class = 7; ?>
                                    <div class="col-md-5">
                                        <a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="media-box"><?php the_post_thumbnail('600x400',array('class'=>'img-thumbnail post-thumb')); ?></a>
                                    </div><?php } ?>
                                    <div class="col-md-<?php echo $post_class; ?>">
                                        <h3 class="post-title"><a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>"><?php the_title(); ?></a></h3>
                                    <span class="post-time meta-data"><?php _e('Đăng ngày ','framework'); echo esc_html(get_the_date()); ?></span>
                                        <p class="post-excerpt"><?php the_excerpt(); ?></p>
                                        <?php wp_link_pages( array(
														'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfourteen' ) . '</span>',
														'after'       => '</div>',
														'link_before' => '<span>',
														'link_after'  => '</span>',
													) ); ?>
                                        <a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="btn btn-sm btn-default"><?php _e('Continue reading ','framework'); ?><i class="fa fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </li>
                            <?php endwhile; else: ?>
                            <li class="post-list-item format-standard">
                                <div class="row">
                                    <div class="col-md-12">
                                    <h3 class="post-title"><?php _e('There is no posts to display.','framework'); ?></h3>
                                    </div>
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <!-- Pagination -->
                        <?php if (function_exists('imic_pagination')) { imic_pagination(); } else { 
								next_posts_link( 'Older Entries', $the_query->max_num_pages );
								previous_posts_link( 'Newer Entries' ); } ?>
                   	</div>
                    <!-- Start Sidebar -->
                    <?php if(is_active_sidebar($pageSidebar)) { ?>
                    <div class="col-md-3 sidebar right-sidebar">
                        <?php dynamic_sidebar($pageSidebar); ?>
               	</div>
                <?php } ?>
            </div>
	</div>
       	</div>
   	</div>
<?php get_footer(); ?>