<?php
/*
Template Name: Home
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
if(have_posts()):while(have_posts()):the_post();
$post_id = get_post($id);
		$content = $post_id->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]>', $content);
if($content!='') {
echo '<div class="lead-block"><div class="container">';
the_content();
echo '</div></div>';
}
endwhile; endif;
$border = '';
$pageSidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list', true);
if(!empty($pageSidebar)&&is_active_sidebar($pageSidebar)) {
$border = " border-col";
$class = 8;  
}else{
$class = 12;  
}
$recent_category_slug = '';
$recent_post_status = get_post_meta(get_the_ID(),'imic_latest_post_status',true);
$partner_status = get_post_meta(get_the_ID(),'imic_partners_section_status',true);
$project_status = get_post_meta(get_the_ID(),'imic_featured_projects_status',true);
$recent_section_shortcode = get_post_meta(get_the_ID(),'imic_home_shortcode_editor',true);
if($recent_post_status==1) {
?>
  	<!-- Start Content -->
  	<div class="main" role="main">
    	<div id="content" class="content full">
        	<div class="container">
            	<div class="row">
                	<div class="col-md-<?php echo $class; echo $border; ?>">
                    <?php 	$recent_post_type = get_post_meta(get_the_ID(),'imic_selected_post_type',true);
							if($recent_post_type!='') {
							$recent_posts_title = get_post_meta(get_the_ID(),'imic_latest_post_title',true);
							$recent_posts_title = ($recent_posts_title=='')?'Latest Posts':$recent_posts_title;
							$recent_posts_count = get_post_meta(get_the_ID(),'imic_posts_to_show_on',true);
							$recent_posts_count = ($recent_posts_count=='')?3:$recent_posts_count;
							$recent_posts_blog_url = get_post_meta(get_the_ID(),'imic_visit_blog_url',true);
							if($recent_post_type=='post') {
							$recent_category = get_post_meta(get_the_ID(),'imic_post_category',true);
							} elseif($recent_post_type=='event') {
							$recent_category = get_post_meta(get_the_ID(),'imic_event_category',true);
							} elseif($recent_post_type=='gallery') {
							$recent_category = get_post_meta(get_the_ID(),'imic_gallery_category',true);
							} elseif($recent_post_type=='project') {
							$recent_category = get_post_meta(get_the_ID(),'imic_project_category',true);
							} elseif($recent_post_type=='product') {
							$recent_category = get_post_meta(get_the_ID(),'imic_product_category',true);
							} else { $recent_category = ''; }
							$post_options = get_post_meta(get_the_ID(),'imic_select_post_options',false);
							$post_content = get_post_meta(get_the_ID(),'imic_select_post_content',true);
							$thumb_hyperlink = get_post_meta(get_the_ID(),'imic_select_thumb_hyperlink',true);
							$title_hyperlink = get_post_meta(get_the_ID(),'imic_select_title_hyperlink',true);
							$visit_post_button_title = get_post_meta(get_the_ID(),'imic_visit_post_title',true);
							if($recent_post_type=='post') {
							if(!empty($recent_category)){
								$recent_category_slug = 'category_name';
							$recent_category= get_category($recent_category);
							$recent_category= $recent_category->slug; } }
							else {
							if(!empty($recent_category)){
							if($recent_post_type=='product') {
								$recent_category_slug = $recent_post_type.'_cat'; }
							else {
								$recent_category_slug = $recent_post_type.'-category'; }
							$recent_category= get_term_by('id',$recent_category,$recent_category_slug);
							if(!empty($recent_category)){
							$recent_category= $recent_category->slug; } }
							} ?>
                        <!-- Latest Posts -->
                        <div class="latest-posts">
                            <h3 class="title"><i class="fa fa-folder"></i> &nbsp;<?php echo $recent_posts_title; ?></h3>
                            
                            <?php if(($recent_post_type=='post')||($recent_post_type=='gallery')||($recent_post_type=='project')||($recent_post_type=='staff')||($recent_post_type=='product')) {
								echo '<ul class="posts-listing">';
                            		query_posts(array('post_type'=>$recent_post_type,$recent_category_slug=>$recent_category,'posts_per_page'=>$recent_posts_count));
									global $product;
									if(have_posts()):while(have_posts()):the_post(); ?>
                            	<li class="post-list-item format-standard">
                                	<div class="row">
                                    <?php $values = array('thumb','title'); $post_col = 12; $post_sm_col = 12; $thumb_col = 12; if(count(array_intersect($post_options, array('thumb','title'))) == count($values)){ if ( '' != get_the_post_thumbnail() ) { $post_col = 7; $post_sm_col = 6; $thumb_col = 5; } else { $post_col = 12; $post_sm_col = 12; $thumb_col = 12; } } if(in_array('thumb',$post_options)) { ?>
                                        <div class="col-md-<?php echo $thumb_col; ?> col-sm-<?php echo $post_sm_col; ?>" data-appear-animation="fadeInLeft" data-appear-animation-delay="1">
                                			<?php if($thumb_hyperlink=='single') { ?><a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="media-box"><?php the_post_thumbnail('600x400',array('class'=>'img-thumbnail post-thumb')); ?></a>
                                            <?php } elseif($thumb_hyperlink=='image') { $post_thumbnail_id = get_post_thumbnail_id( get_the_ID() ); $image = wp_get_attachment_image_src($post_thumbnail_id,'full'); ?><a href="<?php echo $image[0]; ?>" data-rel="prettyPhoto" class="media-box"><?php the_post_thumbnail('600x400',array('class'=>'img-thumbnail post-thumb')); ?></a>
                                            <?php } else { ?><?php the_post_thumbnail('600x400',array('class'=>'img-thumbnail post-thumb')); ?><?php } ?>
                                        </div><?php } ?>
                                    	<div class="col-md-<?php echo $post_col; ?> col-sm-<?php echo $post_sm_col; ?>">
                                			<?php if(in_array('title',$post_options)) { if($title_hyperlink=='single') { ?><h3 class="post-title"><a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>"><?php the_title(); ?></a></h3><?php } else { echo '<h3 class="post-title">'.get_the_title().'</h3>'; } } if($recent_post_type=='post'||$recent_post_type=='project'||$recent_post_type=='gallery') { ?>
                                    		<span class="post-time meta-data"><?php echo esc_attr(get_the_date(get_option('date_format'))); ?></span><?php } elseif($recent_post_type=='product') { echo '<span class="price">'.$product->get_price_html().' </span>';
                                            do_action( 'woocommerce_after_shop_loop_item' ); } else { echo '<span class="meta-data">'.get_post_meta(get_the_ID(),'imic_staff_position',true).'</span>'; }?>
                                    		<?php if(in_array('text',$post_options)) { if($post_content=='excerpt') { echo imic_excerpt(); } else { the_content(); } } if(in_array('more',$post_options)) { ?>
                                            <a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="btn btn-sm btn-default"><?php _e('Continue reading ','framework'); ?><i class="fa fa-long-arrow-right"></i></a><?php } ?>
                                       	</div>
                                   	</div>
                                </li>
                                <?php endwhile;
								else: ?>
                                <li class="post-list-item format-standard">
                                	<div class="row">
                                    	<div class="col-md-12 col-sm-6">
                                			<h3 class="post-title"><?php _e('No Posts Found','framework'); ?></h3>
                                       	</div>
                                   	</div>
                                </li>
                                <?php endif; wp_reset_query(); echo '</ul>'; }
								else{
									$events = imic_recur_events('future','',$recent_category); ?>
                                    <div id="ajax_events" class="events-listing">
									<ul class="upcoming-events listing-content">
                            		<?php if(!empty($events)) { $nos_event = 1; ksort($events); foreach($events as $key=>$value) { $date_converted=date('Y-m-d',$key );
								$style = '';
                				$custom_event_url= imic_query_arg($date_converted,$value); ?>
                            	<li>
                                    <a href="<?php echo esc_url($custom_event_url); ?>" class="event-details-btn"><i class="fa fa-angle-right"></i></a>
                                	<?php if ( '' != get_the_post_thumbnail($value) ) { echo get_the_post_thumbnail($value,'80x80',array('class'=>'img-thumbnail event-thumb')); } else { $style = "style=\"opacity:1;\""; } ?>
                                    <span class="event-date" <?php echo $style; ?>>
                                    	<span class="day"><?php echo esc_attr(date_i18n('d', $key)); ?></span>
                                    <span class="monthyear"><?php echo esc_attr(date_i18n('M, ', $key)); echo esc_attr(date_i18n('y', $key)); ?></span>
                                   	</span>
                                    <div class="event-excerpt">
                                        <div class="event-cats meta-data"><?php echo get_the_term_list($value, 'event-category', '', ', ', '' ); ?></div>
                                    <h5 class="event-title"><a href="<?php echo esc_url($custom_event_url); ?>"><?php echo get_the_title($value); ?></a></h5>
                                    <?php $address = get_post_meta($value,'imic_event_address2',true); if($address!='') { ?>
                                    <p class="event-location"><i class="fa fa-map-marker"></i> <?php echo $address; ?></p><?php } ?>
                                    </div>
                               	</li><?php if (++$nos_event > $recent_posts_count) {
								break; } } } else { ?>
                                <li class="item event-item">	
			  <div class="event-detail">
                        <h4><?php _e("Sorry, there are no events for this month.","framework"); ?></h4>
                      </div>
                    </li><?php } ?>
                            	</ul></div>
								<?php }
								 if($recent_posts_blog_url!='') { ?><a href="<?php echo $recent_posts_blog_url; ?>" class="btn toblog btn-default pull-right"><?php echo $visit_post_button_title; ?></a><?php } ?>
                        </div><?php } else { echo do_shortcode($recent_section_shortcode); } ?>
                  	</div>
                    <?php if(is_active_sidebar($pageSidebar)) { ?>
                    <!-- Sidebar -->
                    <div class="col-md-4 sidebar right-sidebar">
                    	<?php dynamic_sidebar($pageSidebar); ?>
                    </div>
                    <?php } if($recent_post_type!='') { echo do_shortcode($recent_section_shortcode); } ?>
              	</div>
          	</div>
        </div>
   	</div>
    <?php } if($partner_status==1) { 
	$total_partner_link = 0;
	$partner_desc = get_post_meta(get_the_ID(),'imic_partners_description',true);
	$partner_image = get_post_meta(get_the_ID(),'imic_partners_image',false);
	$partner_link = get_post_meta(get_the_ID(),'imic_partner_link',true);
	$partner_title = get_post_meta(get_the_ID(),'imic_partner_area_title',true);
	$partner_title = ($partner_title!='')?$partner_title:'<small>Mission</small>Partners';
	$total_partner_link = count($partner_link);
	$plogowidth = get_post_meta(get_the_ID(),'imic_partner_logo_width',true);
	?>
    <!-- Our Partners -->
    <div class="our-partners">
    	<div class="container">
        	<h2 class="title"><?php echo $partner_title; ?></h2>
        	<div class="row">
            	<?php $partner_class = 12; if($partner_desc!='') { $partner_class = 9; ?><div class="col-md-3">
                	<?php echo $partner_desc; ?>
                </div><?php } ?>
            	<div class="col-md-<?php echo $partner_class; ?>">
                	<ul class="partner-logos">
                   	<?php if($partner_image!='') { $count = 10; $link = 1; foreach($partner_image as $image) { 
						$image = wp_get_attachment_image_src( $image, '', '' );
						if($link<=$total_partner_link) {
						?>
                    	<li data-appear-animation="fadeInDown" data-appear-animation-delay="<?php echo $count; ?>" style="width:<?php echo $plogowidth; ?>;"><a href="<?php echo $partner_link[$link-1]; ?>"><img src="<?php echo esc_url($image[0]); ?>" alt=""></a></li>
                        <?php } else { ?>
                        <li data-appear-animation="fadeInDown" data-appear-animation-delay="<?php echo $count; ?>" style="width:<?php echo $plogowidth; ?>;"><img src="<?php echo esc_url($image[0]); ?>" alt=""></li>
                 		<?php } $count = $count+100; $link++; } } ?>
                    </ul>
                </div>
          	</div>
       	</div>
    </div>
    <?php } if($project_status==1) { 
	$projects_url = get_post_meta(get_the_ID(),'imic_all_projects_url',true);
	$project_column = get_post_meta(get_the_ID(),'imic_home_projects_columns_layout',true);
	$project_column = ($project_column=='')?'3':$project_column;
	$projects_title = get_post_meta(get_the_ID(),'imic_projects_area_title',true);
	$projects_title = ($projects_title!='')?$projects_title:'<small>Featured</small>Projects';
	?>
    <!-- Start Featured Projects -->
    <div class="featured-projects">
    	<div class="container">
            <?php if($projects_url!='') { ?><a href="<?php echo $projects_url; ?>" class="btn toblog btn-default pull-right"><?php _e('All Projects','framework'); ?></a><?php } ?>
        	<h2><?php echo $projects_title; ?></h2>
        	<div class="row">
            <?php query_posts(array('post_type'=>'project','posts_per_page'=>3));
					if(have_posts()):while(have_posts()):the_post(); ?>
            	<div class="col-md-<?php echo $project_column; ?> col-sm-<?php echo $project_column; ?> format-standard">
                	<a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="featured-project-block media-box">
                    	<?php if ( '' != get_the_post_thumbnail() ) { the_post_thumbnail('600x400',array('class'=>'featured-image')); } ?>
                        <span class="project-overlay">
                        	<span class="project-title"><?php the_title(); ?></span>
                        	<span class="project-cat"><?php echo imic_custom_taxonomies_terms_links(get_the_ID()); ?></span>
                        </span>
                    </a>
                </div>
                <?php endwhile; else: ?>
                <div class="col-md-12 col-sm-12 format-standard">
                        <span class="project-overlay">
                        	<span class="project-title"><?php _e('No Projects Found','framework'); ?></span>
                        </span>
                    </a>
                </div>
                <?php endif; wp_reset_query(); ?>
            </div>
        </div>
    </div>
    <!-- End Featured Projects -->
<?php } get_footer(); ?>