<?php get_header();
$event_image = $imic_options['header_image']['url'];
?>
<div class="page-header" style="background-image:url(<?php echo $event_image; ?>)">
    	<div class="container">
        	<div class="row">
            
            	<div class="col-md-6 col-sm-6 hidden-xs">
                <?php if(function_exists('bcn_display'))
    { ?>
          			<ol class="breadcrumb">
            			<?php bcn_display(); ?>
          			</ol><?php } ?>
            	</div>
            	<div class="col-md-6 col-sm-6 col-xs-12">
    				<h2><?php _e('Search','framework'); ?></h2>
                </div>
           	</div>
        </div>
    </div>
<div class="double-border"></div>
    <!-- Secondary Header -->
    <div class="secondary-bar">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-12">
                	<span class="big"><?php printf( __( 'Search Results for: %s', 'framework' ), get_search_query() ); ?></span>
              	</div>
            </div>
        </div>
    </div>
    <?php if(is_active_sidebar('page-sidebar')) { $class = 9; } else { $class = 12; } ?>
    <!-- Start Body Content -->
  	<div class="main" role="main">
    	<div id="content" class="content full">
    		<div class="container">
            	<div class="row">
                	<div class="col-md-<?php echo $class; ?>">
                        <ul class="posts-listing">
                        <?php if(have_posts()):while(have_posts()):the_post(); ?>
                            <li class="post-list-item format-standard">
                                <div class="row">
                                    <div class="col-md-5">
                                    <?php if ( '' != get_the_post_thumbnail() ) { ?>
                                        <a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="media-box"><?php the_post_thumbnail('600x400',array('class'=>'img-thumbnail post-thumb')); ?></a><?php } ?>
                                    </div>
                                    <div class="col-md-7">
                                        <h3 class="post-title"><a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>"><?php the_title(); ?></a></h3>
                                    <span class="post-time meta-data"><?php _e('Đăng ngày ','framework'); echo esc_html(get_the_date()); ?></span>
                                        <p class="post-excerpt"><?php the_excerpt(); ?></p>
                                        <a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="btn btn-sm btn-default"><?php _e('Continue reading ','framework'); ?><i class="fa fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </li>
                            <?php endwhile; else: ?>
                            <li class="post-list-item format-standard">
                                <div class="row">
                                    <div class="col-md-12">
                                    <h3 class="post-title"><?php _e('Nothing Found, Please try again.','framework'); ?></h3>
                                    </div>
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <!-- Pagination -->
                        <?php imic_pagination(); ?>
                   	</div>
                    <!-- Start Sidebar -->
                    <?php if(is_active_sidebar('page-sidebar')) { ?>
                    <div class="col-md-3 sidebar right-sidebar">
                        <?php dynamic_sidebar('page-sidebar'); ?>
               	</div>
                <?php } ?>
            </div>
       	</div>
   	</div>
<?php get_footer(); ?>