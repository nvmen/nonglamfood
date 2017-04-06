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
?>
    <!-- End Page Header -->
<div class="main" role="main">
	<div id="content" class="content full">
    <?php 
$project_address = get_post_meta(get_the_ID(),'imic_project_address',true);
$project_members = get_post_meta(get_the_ID(),'imic_project_team',false);
$pageSidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list', true);
if(($project_address!='')||(!empty($project_members))||(is_active_sidebar($pageSidebar))) { 
$class = 9; } else { $class = 12; }
echo '<div class ="container">';
echo '<div class="row">';
echo '<div class ="col-md-'.$class.'"><div class="entry">';
echo '<h2 class="title">'.get_the_title().'</h2>';
	if(have_posts()):while(have_posts()):the_post(); 
	the_content();
	if ($imic_options['switch_sharing'] == 1 && $imic_options['share_post_types']['4'] == '1') {
		imic_share_buttons();
	}
	endwhile; endif;
echo '</div></div>';
if(is_active_sidebar($pageSidebar)) {
echo'<div class="col-md-3">';
dynamic_sidebar($pageSidebar);
echo'</div>';
}
else { 
$project_title = get_the_title();
$project_centre = get_post_meta(get_the_ID(),'imic_project_location',true);
$project_centre = explode(',',$project_centre);
?>
	<!-- Start Sidebar -->
                    <div class="col-md-3 right-sidebar sidebar">
                    <?php if($project_address!='') { 
						wp_enqueue_script('imic_google_map');
						wp_enqueue_script('imic_event_gmap');
						echo '<div class="event_container"><div id="property'.get_the_ID().'" style="display:none;"><span class ="latitude">'.$project_centre[0].'</span><span class ="longitude">'.$project_centre[1].'</span></div></div>';
					?>
                    	<div class="project-centre widget sidebar-widget">
                        	<h3 class="title"><?php _e('Project Centre','framework'); ?></h3>
                        	<div class="clearfix map-single-page" id="onemap"></div>
                            <span class="label label-default"><?php echo $project_address; ?></span>
                       	</div><?php } ?>
                        <?php if(!empty($project_members)) { ?>
                    	<div class="widget sidebar-widget widget_team_project">
                            <h3 class="title widgettitle"><?php _e('People on this project','framework'); ?></h3>
                            <ul><?php foreach($project_members as $pid) {
									$staff_position = get_post_meta($pid,'imic_staff_position',true); ?>
                            	<li>
                                	<?php echo get_the_post_thumbnail($pid,'80x80',array('class'=>'img-thumbnail')); ?>
                                    <div class="people-info">
                                    	<h5 class="people-name"><a data-toggle="modal" data-target="#team-modal-<?php echo $pid+2648; ?>" href="#" class=""><?php echo get_the_title($pid); ?></a></h5>
                                    	<?php if($staff_position!='') { ?><p class="people-position"><?php echo $staff_position; ?></p><?php } ?>
                                   	</div>
                               	</li>
								<div class="modal fade" id="team-modal-<?php echo $pid+2648; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel"><?php echo $project_title; ?></h4>
                          </div>
                            <div class="modal-body">
                                <div class="staff-item">
                                <div class="row">
                                    <div class="col-md-5 col-sm-6">
                                    	<?php echo get_the_post_thumbnail($pid,'600x400',array('class'=>'img-thumbnail')); ?>
                                    </div>
                                    <div class="col-md-7 col-sm-6">
                                    	<h3><?php echo get_the_title($pid); ?></h3>
                                    	<span class="meta-data"><?php echo $staff_position; ?></span>
                                        <?php $post_id = get_post($pid);
											$content = $post_id->post_content;
											$content = apply_filters('the_content', $content);
											$content = str_replace(']]>', ']]>', $content);
											echo $content; ?>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                      </div>
                    </div>
								<?php } ?>
                            </ul>
                        </div>
                        <?php } ?>
                    </div>
<?php }
echo'</div></div>';?>
        </div>
</div>
<?php get_footer(); ?>