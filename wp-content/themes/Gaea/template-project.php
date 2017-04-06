<?php
/*
Template Name: Projects
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
$project_type = get_post_meta(get_the_ID(),'imic_project_layout_type',true);
$project_layout = get_post_meta(get_the_ID(),'imic_temp_projects_columns_layout',true);
$project_filter = get_post_meta(get_the_ID(),'imic_project_secondary_bar_type_status',true);
$project_category = get_post_meta(get_the_ID(),'imic_advanced_project_taxonomy',true);
							if($project_category!=''){
							$project_categories= get_term_by('id',$project_category,'project-category');
							if(!empty($project_categories)){
							$project_category= $project_categories->slug; }}
if($project_filter==0) {
?>
<div class="secondary-bar">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-12">
                    <ul class="nav nav-pills sort-source" data-sort-id="projects" data-option-key="filter">
                    <?php $gallery_cats = get_terms("project-category");?>
                                            <li data-option-value="*" class="active"><a href="#"><i class="fa fa-th"></i> <?php _e('All','framework'); ?></a></li>
                                     	<?php foreach($gallery_cats as $gallery_cat) { ?>
                                            <li data-option-value=".<?php echo $gallery_cat->slug; ?>"><a href="#"><?php echo $gallery_cat->name; ?></a></li>
                                    	<?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php } 
	?>
	<div class="main" role="main">
    	<div id="content" class="content full">
    		<div class="container">
            	<div class="row">
                	<div class="col-md-<?php echo $class; ?>">
						<div class="page-content"><?php the_content(); ?></div>	
                    <?php if($project_type==0) { ?>
                        <h2 class="title"><?php _e('Projects','framework'); ?></h2>
                        <ul class="posts-listing">
                        <?php 
							$paged = (get_query_var('paged'))?get_query_var('paged'):1;
							query_posts(array('post_type'=>'project','project-category'=>$project_category,'paged'=>$paged));
							if(have_posts()):while(have_posts()):the_post(); ?>
                            <li class="post-list-item format-standard">
                                <div class="row"><?php $post_col = 12; if ( '' != get_the_post_thumbnail() ) { $post_col = 7; ?>
                                    <div class="col-md-5">
                                        <a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="media-box"><?php the_post_thumbnail('600x400',array('class'=>'img-thumbnail post-thumb')); ?></a>
                                    </div><?php } ?>
                                    <div class="col-md-7">
                                        <h3 class="post-title"><a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>"><?php the_title(); ?></a></h3>
                                        <span class="post-time meta-data"><?php echo esc_attr(get_the_date(get_option('date_format'))); ?></span>
                                        <div class="post-excerpt"><?php echo imic_excerpt(); ?></div>
                                    </div>
                                </div>
                            </li>
                            <?php endwhile; else: ?>
                            <li class="post-list-item format-standard">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="post-title"><?php _e('No Projects to show','framework'); ?></h3>
                                        <div class="post-excerpt"><?php echo imic_excerpt(); ?></div>
                                    </div>
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <!-- Pagination -->
                        <?php imic_pagination();  wp_reset_query(); ?>
                    <?php } else { ?>
                    <ul class="grid-holder col-<?php echo $project_layout; ?> projects-grid sort-destination" data-sort-id="projects">
                    <?php $paged = (get_query_var('paged'))?get_query_var('paged'):1;
							query_posts(array('post_type'=>'project','paged'=>$paged,'project-category'=>$project_category));
						if(have_posts()):while(have_posts()):the_post();
						$term_slug = get_the_terms(get_the_ID(), 'project-category');
						echo '<li class=" grid-item format-standard ';
						if (!empty($term_slug)) {
						foreach ($term_slug as $term) {
						  echo $term->slug.' ';
						}
						} ?>">
                        <div class="grid-item-inner">
                        <?php if ( '' != get_the_post_thumbnail() ) { ?>
                        	<a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="media-box"> <?php the_post_thumbnail(); ?> <span class="project-cat"><?php echo imic_custom_taxonomies_terms_links(get_the_ID()); ?></span> </a><?php } ?>
                            <div class="grid-content">
                                <h3><a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>"><?php the_title(); ?></a></h3>
                                        <div class="post-excerpt"><?php echo imic_excerpt(); ?></div>
                            </div>
                    	</div>
                  	</li>
                    <?php endwhile; else: ?>
                    <li class="grid-item format-standard education">
                        <div class="grid-item-inner">
                            <div class="grid-content">
                                <h3><?php _e('No Projects to display','framework'); ?></h3>
                            </div>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul><?php imic_pagination(); wp_reset_query(); } ?></div>
                    <!-- Start Sidebar -->
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