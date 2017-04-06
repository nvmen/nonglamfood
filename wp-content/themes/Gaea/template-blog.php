<?php
/*
Template Name: Blog Masonry
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
$pageSidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list', true);
if(!empty($pageSidebar)&&is_active_sidebar($pageSidebar)) {
$class = 9;  
}else{
$class = 12;  
}
$project_filter = get_post_meta(get_the_ID(),'',true);
$project_layout = get_post_meta(get_the_ID(),'',true);
$gallery_filter_columns_layout = get_post_meta(get_the_ID(),'',true);
if ($gallery_filter_columns_layout == 3) {
$column_class = 'col-3';
} elseif ($gallery_filter_columns_layout == 4) {
$column_class = 'col-4';
} else {
$column_class = 'col-2';
}
?>
	<div class="main" role="main">
    	<div id="content" class="content full">
    		<div class="container">
            	<div class="row">
                	<div class="col-md-<?php echo $class; ?>">
						<div class="page-content"><?php the_content(); ?></div>
                        <ul class="grid-holder col-3 posts-grid">
                        <?php $post_category = get_post_meta($id,'imic_advanced_post_taxonomy',true);
							if(!empty($post_category)){
							$post_categories= get_category($post_category);
							$post_category= $post_categories->slug; }
							$paged = (get_query_var('paged'))?get_query_var('paged'):1;
							query_posts(array('post_type'=>'post','category_name' => $post_category,'paged'=>$paged));
							if(have_posts()):while(have_posts()):the_post(); ?>
                            <li class="grid-item format-standard">
                                <div class="grid-item-inner">
                                <?php if ( '' != get_the_post_thumbnail() ) { ?>
                                    <a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="media-box"> <?php the_post_thumbnail('800x600'); ?></a><?php } ?>
                                    <div class="grid-content">
                                        <h3 class="post-title"><a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>"><?php the_title(); ?></a></h3>
                                        <div class="meta-data"><?php _e('Posted by ','framework'); ?><a href="<?php $post_author_id = get_post_field( 'post_author', get_the_ID() ); echo esc_url(get_author_posts_url($post_author_id)); ?>"><?php echo get_the_author_meta( 'display_name', $post_author_id ); ?></a><?php _e(' on ','framework'); echo esc_attr(date_i18n(get_option('date_format'))); ?></div>
                                        <?php echo imic_excerpt(); ?>
                                    </div>
                                </div>
                            </li>
                            <?php endwhile; else: ?>
                            <li class="grid-item format-standard">
                                <div class="grid-item-inner">
                                    <div class="grid-content">
                                        <h3 class="post-title"><?php _e('No Posts to display','framework'); ?></h3>
                                    </div>
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <?php imic_pagination(); wp_reset_query(); ?>
                   	</div>
                    <?php if(is_active_sidebar($pageSidebar)) { ?>
                    <!-- Sidebar -->
                    <div class="col-md-3 sidebar right-sidebar">
                    	<?php dynamic_sidebar($pageSidebar); ?>
                    </div>
                    <?php } ?>
               	</div>
            </div>
       	</div>
   	</div>
<?php get_footer(); ?>