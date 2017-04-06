<?php
/*
Template Name: Gallery
*/
get_header();
wp_enqueue_script( 'imic_jquery_flexslider' );
wp_enqueue_script('imic_magnific_gallery');
wp_enqueue_script('imic_sg');
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
$gallery_filter = get_post_meta(get_the_ID(),'imic_gallery_secondary_bar_type_status',true);
$column_class = get_post_meta(get_the_ID(),'imic_projects_columns_layout',true);
$gallery_pagination = get_post_meta(get_the_ID(),'imic_gallery_page_pagination',true);
$gallery_numbers = get_post_meta(get_the_ID(),'imic_gallery_number_show',true);
$gallery_numbers = ($gallery_numbers=='')?-1:$gallery_numbers;
if($gallery_filter=='1') {
?>
<div class="secondary-bar">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-12">
                    <ul class="nav nav-pills sort-source" data-sort-id="gallery" data-option-key="filter">
                    <?php $gallery_cats = get_terms("gallery-category");?>
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
    <!-- Start Body Content -->
  	<div class="main" role="main">
    	<div id="content" class="content full">
    		<div class="container">
            <div class="col-md-<?php echo $class; ?>">
				<div class="page-content"><?php the_content(); ?></div>
                <ul class="grid-holder col-<?php echo $column_class; ?> gallery-grid sort-destination" data-sort-id="gallery">
                <?php $gallery_category = get_post_meta(get_the_ID(),'imic_advanced_gallery_taxonomy',true);
				if($gallery_category!=''){
				$gallery_categories= get_term_by('id',$gallery_category,'gallery-category');
				if(!empty($gallery_categories)){
				$gallery_category= $gallery_categories->slug; }}
				$paged = (get_query_var('paged'))?get_query_var('paged'):1;
				query_posts(array('post_type'=>'gallery','gallery-category'=>$gallery_category,'paged'=>$paged,'posts_per_page'=>$gallery_numbers));
				if(have_posts()):while(have_posts()):the_post();
				$thumb_id=get_post_thumbnail_id(get_the_ID());
				$post_format_temp =get_post_format();
				if (has_post_thumbnail() || ((count($image_data) > 0)&&($post_format_temp=='gallery'))):
				$post_format =!empty($post_format_temp)?$post_format_temp:'image';
				$term_slug = get_the_terms(get_the_ID(), 'gallery-category');
						echo '<li class=" grid-item format-'.$post_format.' ';
						if (!empty($term_slug)) {
						foreach ($term_slug as $term) {
						  echo $term->slug.' ';
						}
						} ?>">
                        <?php switch (get_post_format()) {
						case 'image': ?>
                        <div class="grid-item-inner">
                        <?php if ( '' != get_the_post_thumbnail() ) { 
								$image_id = get_post_thumbnail_id(get_the_ID()); 
								$image = wp_get_attachment_image_src($image_id,'full',''); ?>
                        	<a href="<?php echo esc_url($image[0]); ?>" data-rel="prettyPhoto" class="media-box">
                            	<?php the_post_thumbnail('800x600'); ?>
                                <span class="gallery-cat"><?php the_title(); ?></span>
                           	</a><?php } ?>
                    	</div>
                        <?php 
						break;
						case 'gallery': ?>
						<div class="grid-item-inner media-box">
                        <?php $image_data=  get_post_meta(get_the_ID(),'imic_gallery_images',false);
						echo imic_gallery_flexslider(get_the_ID());     
						if (count($image_data) > 0) {
						echo'<ul class="slides">';
						foreach ($image_data as $custom_gallery_images) {
						$large_src = wp_get_attachment_image_src($custom_gallery_images, '1000x800');
						echo'<li class="item"><a href="' . $large_src[0] . '" data-rel="prettyPhoto[' . get_the_title() . ']">';
						echo wp_get_attachment_image($custom_gallery_images, '800x600');
						echo '<span class="gallery-cat">'.get_the_title().'</span>';
						echo'</a></li>';
						}
						echo'</ul>'; } echo '</div>'; ?>
                        </div>
						<?php break;
						case 'link':
						$link_url = get_post_meta(get_the_ID(),'imic_gallery_link_url',true);
						if (!empty($link_url)) {
						echo '<a href="' . $link_url . '" target="_blank" class="media-box">';
						the_post_thumbnail('800x600');
						echo '<span class="gallery-cat">'.get_the_title().'</span>';
						echo'</a>';
						}
						break;
						case 'video':
						$video_url = get_post_meta(get_the_ID(),'imic_gallery_video_url',true);
						if (!empty($video_url)) {
						echo '<a href="' . $video_url . '" data-rel="prettyPhoto" class="media-box">';
						the_post_thumbnail('800x600');
						echo '<span class="gallery-cat">'.get_the_title().'</span>';
						echo'</a>';
						}
						break;
						default:
						if(!empty($thumb_id)){
						$large_src_i = wp_get_attachment_image_src($thumb_id, '1000x800');
						echo'<a href="' . $large_src_i[0] . '" data-rel="prettyPhoto" class="media-box">';
						the_post_thumbnail('800x600');
						echo '<span class="gallery-cat">'.get_the_title().'</span>';
						echo'</a>';
						}
						break;
						} ?>
                  	</li>
                    <?php endif; endwhile; else: ?>
                    <li class="grid-item format-image projects">
                        <div class="grid-item-inner">
                                <span class="gallery-cat"><?php _e('No Gallery Posts to display','framework'); ?></span>
                    	</div>
                  	</li>
                    <?php endif;  ?>
                </ul>
				<?php if ($imic_options['switch_sharing'] == 1 && $imic_options['share_post_types']['5'] == '1') {
                    imic_share_buttons();
                } ?>
                <?php if($gallery_pagination==1) { imic_pagination(); } wp_reset_query(); ?>
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
<?php get_footer(); ?>