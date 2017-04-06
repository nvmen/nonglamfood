<?php
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
get_header();
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
    				<h2><?php _e('404 Error!','framework'); ?></h2>
                </div>
           	</div>
        </div>
    </div>
    <!-- End Page Header -->
    <div class="double-border"></div>
    <!-- Start Body Content -->
  	<div class="main" role="main">
    	<div id="content" class="content full">
    		<div class="container">
            	<div class="text-align-center error-404">
            		<h1 class="huge"><?php _e('404','framework'); ?></h1>
              		<hr class="sm">
              		<p><strong><?php _e('Sorry - Page Not Found!','framework'); ?></strong></p>
					<p><?php _e('The page you are looking for was moved, removed, renamed<br>or might never existed. You stumbled upon a broken link :','framework'); ?></p>
             	</div>
            </div>
       	</div>
   	</div>
<?php get_footer(); ?>