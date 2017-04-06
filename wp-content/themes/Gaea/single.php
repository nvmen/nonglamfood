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
$pageSidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list', true);
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
                    <?php while(have_posts()):the_post(); ?>
                		<div class="entry single-post">
                            <h2 class="title"><?php the_title(); ?></h2>
                            <div class="meta-data">
                                <?php if(get_post_type()=='staff'){
                                $job_title = get_post_meta(get_the_ID(), 'imic_staff_position', true);
                              if(!empty($job_title)){
                                echo '<span>'.__(' Designation : ','framework').$job_title.'</span>';
                            }}else{ ?>
                                <span><i class="fa fa-calendar"></i><?php _e(' Posted on ','framework'); echo esc_html(get_the_date()); ?></span>
                                <span><i class="fa fa-archive"></i><?php _e(' Categories: ','framework'); ?><?php the_category(', '); ?></span>
                                <span><i class="fa fa-comments"></i> <?php comments_popup_link(''.__('No comments yet','framework'), '1', '%', 'comments-link',__('Comments are off for this post','framework')); ?></span>
                              <?php } ?>
                            </div>
                            <?php if ( '' != get_the_post_thumbnail() ) { the_post_thumbnail('1000x400',array('class'=>'img-thumbnail post-single-image')); }
								$categories = get_the_category(get_the_ID()); 
								$current_post = get_the_ID(); ?>
                            <article class="post-content"> 
                                <?php the_content();
								if ($imic_options['switch_sharing'] == 1 && $imic_options['share_post_types']['1'] == '1') {
									imic_share_buttons();
								}
								 ?>
                            </article>
                            <div class="post-tags">
                            	<?php if (has_tag()) {
									echo'<div class="post-meta">';
									echo'<i class="fa fa-tags"></i> ';
									the_tags('', '');
									echo'</div>';
								} ?>
                            </div>
                            <?php endwhile; 
                             if(get_post_type()=='post'){
                            ?>
                            <!-- Related Posts -->
                            <div class="related-posts">
                                <h4 class="title"><?php _e('You might also like','framework'); ?></h4>
                                <div class="row">
                                <?php query_posts(array('post_type'=>'post','posts_per_page'=>3,'category_name'=>$categories[0]->slug,'post__not_in' => array($current_post)));
											if(have_posts()):while(have_posts()):the_post(); ?>
                                    <div class="col-md-4 related-post format-standard">
                                        <a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="media-box"><?php if ( '' != get_the_post_thumbnail() ) { the_post_thumbnail('600x400',array('class'=>'img-thumbnail post-thumb')); } ?></a>
                                        <h3 class="post-title"><a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>"><?php the_title(); ?></a></h3>
                                        <span class="post-time meta-data"><?php _e('Posted on ','framework'); echo esc_html(get_the_date()); ?></span>
                                    </div>
                                    <?php endwhile; endif; wp_reset_query(); ?>
                                </div>
                            </div>
                            
                            <!-- Post Comments -->
                            <?php comments_template('', true); } ?> 
                    </div>
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