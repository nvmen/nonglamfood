<?php
       load_theme_textdomain('imic-framework-admin', IMIC_FILEPATH. '/language/');
	// Create TinyMCE's editor button & plugin for IMIC Framework Shortcodes
	add_action('init', 'imic_sc_button'); 
	
	function imic_sc_button() {  
	   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )  
	   {  
	     add_filter('mce_external_plugins', 'imic_add_tinymce_plugin');  
	     add_filter('mce_buttons', 'imic_register_shortcode_button');  
	   }  
	} 
	
	function imic_register_shortcode_button($button) {  
	    array_push($button, 'separator', 'imicframework_shortcodes' );  
	    return $button;  
	}
	
	function imic_add_tinymce_plugin($plugins) {  
	    $plugins['imicframework_shortcodes'] = get_template_directory_uri() . '/imic-framework/imic-shortcodes/imic-tinymce.editor.plugin.js';  
	    return $plugins;  
	} 
	
	
	
	/* ==================================================
	
	SHORTCODES OUTPUT
	
	================================================== */
	
	/* STAFF SHORTCODE
	================================================== */
	
	function imic_staff($atts, $content = null) {
		extract(shortcode_atts(array(
			"title" => "",
			"number"			=> 3,
			"orderby" => '',
			"order" => 'ASC'
		), $atts));
		
		$output = '';
		if($title!='') { 
		$output .= '<h3 class="title"><i class="fa fa-folder"></i> '.$title.'</h3>'; }
		query_posts(array('post_type'=>'staff','posts_per_page'=>$number,'orderby'=>$orderby,'order'=>$order));
		if(have_posts()):while(have_posts()):the_post();
				$output .= '<div class="staff-item">
                                <div class="row">';
									$post_col = 12; $post_sm_col = 12; if ( '' != get_the_post_thumbnail() ) { $post_col = 7; $post_sm_col = 6;
                                    $output .= '<div class="col-md-5 col-sm-6">'.
                                    	get_the_post_thumbnail(get_the_ID(),'600x400',array('class'=>'img-thumbnail')).'
                                    </div>'; }
                                    $output .= '<div class="col-md-'.$post_col.' col-sm-'.$post_sm_col.'">
                                    	<h3><a data-toggle="modal" data-target="#team-modal-'.(get_the_ID()+2648).'" href="#" class="">'.get_the_title().'</a></h3>
                                    	<span class="meta-data">'.get_post_meta(get_the_ID(),'imic_staff_position',true).'</span>
                                        '.imic_excerpt().'
                                    </div>
                                </div>
                            </div>';
				$output .= '<div class="modal fade" id="team-modal-'.(get_the_ID()+2648).'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">'.__('Team Members','framework').'</h4>
                          </div>
                            <div class="modal-body">
                                <div class="staff-item">
                                <div class="row">
                                    <div class="col-md-5 col-sm-6">
                                    	'.get_the_post_thumbnail(get_the_ID(),'600x400',array('class'=>'img-thumbnail')).'
                                    </div>
                                    <div class="col-md-7 col-sm-6">
                                    	<h3>'.get_the_title().'</h3>
                                    	<span class="meta-data">'.get_post_meta(get_the_ID(),'imic_staff_position',true).'</span>';
                                        $post_id = get_post(get_the_ID());
											$content = $post_id->post_content;
											$content = apply_filters('the_content', $content);
											$content = str_replace(']]>', ']]>', $content);
											$output .= $content;
                                    $output .= '</div>
                                </div>
                            </div>
                            </div>
                        </div>
                      </div>
                    </div>';
		endwhile; wp_reset_query();
		else: 
			$output .= '<div class="staff-item">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                    	<h3>'.__('No Staff Member to show','framework').'</h3>
                                    </div>
                                </div>
                            </div>';
		endif;
		return $output;
	}
	add_shortcode('staff', 'imic_staff');
	
	/* GALLERY SHORTCODE
	==================================================*/
	function imic_gallery($atts, $content = null) {
		extract(shortcode_atts(array(
			"title" => "",
			"category"			=> '',
			"count"	=>	4,
			"column"	=>	4
		), $atts));
		wp_enqueue_script( 'imic_jquery_flexslider' );
		wp_enqueue_script('imic_magnific_gallery');
		wp_enqueue_script('imic_sg');
		$output = '';
		$output .= '<div class="row">
            <div class="col-md-12">';
			if($title!='') { 
		$output .= '<h3 class="title"><i class="fa fa-folder"></i> '.$title.'</h3>'; }
        $output .= '<ul class="grid-holder col-'.$column.' gallery-grid sort-destination" data-sort-id="gallery">';
				query_posts(array('post_type'=>'gallery','gallery-category'=>$category,'posts_per_page'=>$count));
				if(have_posts()):while(have_posts()):the_post();
				$thumb_id=get_post_thumbnail_id(get_the_ID());
				$post_format_temp =get_post_format();
				if (has_post_thumbnail() || ((count($image_data) > 0)&&($post_format_temp=='gallery'))):
				$post_format =!empty($post_format_temp)?$post_format_temp:'image';
				$term_slug = get_the_terms(get_the_ID(), 'gallery-category');
						$output .= '<li class=" grid-item format-'.$post_format.' ';
						if (!empty($term_slug)) {
						foreach ($term_slug as $term) {
						  $output .= $term->slug.' ';
						}
						} $output .= '">';
                        switch (get_post_format()) {
						case 'image':
                        $output .= '<div class="grid-item-inner">';
                        if ( '' != get_the_post_thumbnail() ) { 
								$image_id = get_post_thumbnail_id(get_the_ID()); 
								$image = wp_get_attachment_image_src($image_id,'full','');
                        	$output .= '<a href="'.esc_url($image[0]).'" data-rel="prettyPhoto" class="media-box">';
                            	$output .= get_the_post_thumbnail(get_the_ID(),'800x600');
                                $output .= '<span class="gallery-cat">'.get_the_title().'</span>
                           	</a>'; }
                    	$output .= '</div>';
						break;
						case 'gallery':
						$output .= '<div class="grid-item-inner media-box">';
                        $image_data=  get_post_meta(get_the_ID(),'imic_gallery_images',false);
						$output .= imic_gallery_flexslider(get_the_ID());     
						if (count($image_data) > 0) {
						$output .= '<ul class="slides">';
						foreach ($image_data as $custom_gallery_images) {
						$large_src = wp_get_attachment_image_src($custom_gallery_images, '1000x800');
						$output .= '<li class="item"><a href="' . $large_src[0] . '" data-rel="prettyPhoto[' . get_the_title() . ']">';
						$output .= wp_get_attachment_image($custom_gallery_images, '800x600');
						$output .= '<span class="gallery-cat">'.get_the_title().'</span>';
						$output .= '</a></li>';
						}
						$output .= '</ul>'; } $output .= '</div></div>';
						break;
						case 'link':
						$link_url = get_post_meta(get_the_ID(),'imic_gallery_link_url',true);
						if (!empty($link_url)) {
						$output .= '<a href="' . $link_url . '" target="_blank" class="media-box">';
						$output .= get_the_post_thumbnail(get_the_ID(),'800x600');
						$output .=  '<span class="gallery-cat">'.get_the_title().'</span>';
						$output .= '</a>';
						}
						break;
						case 'video':
						$video_url = get_post_meta(get_the_ID(),'imic_gallery_video_url',true);
						if (!empty($video_url)) {
						$output .= '<a href="' . $video_url . '" data-rel="prettyPhoto" class="media-box">';
						$output .= get_the_post_thumbnail(get_the_ID(),'800x600');
						$output .= '<span class="gallery-cat">'.get_the_title().'</span>';
						$output .= '</a>';
						}
						break;
						default:
						if(!empty($thumb_id)){
						$large_src_i = wp_get_attachment_image_src($thumb_id, '1000x800');
						$output .= '<a href="' . $large_src_i[0] . '" data-rel="prettyPhoto" class="media-box">';
						$output .= get_the_post_thumbnail(get_the_ID(),'800x600');
						$output .= '<span class="gallery-cat">'.get_the_title().'</span>';
						$output .= '</a>';
						}
						break;
						}
                  	$output .= '</li>';
                    endif; endwhile; else:
                    $output .'<li class="grid-item format-image projects">
                        <div class="grid-item-inner">
                                <span class="gallery-cat">'.__('No Gallery Posts to display','framework').'</span>
                    	</div>
                  	</li>';
                    endif;
                $output .= '</ul>'; 
				wp_reset_query();
                $output .= '</div></div>';
		return $output;
	}
	add_shortcode('galleries', 'imic_gallery');
	
	
	/* GOOGLE MAP SHORTCODE
	================================================== */
	
	function imic_gmap($atts, $content = null) {
		extract(shortcode_atts(array(
			"address"			=> '',
		), $atts));
		
		$output = '';
		wp_enqueue_script('imic_google_map');
		wp_enqueue_script('imic_gmap');
		wp_localize_script('imic_gmap','gmap',array('address'=>$address));
		$output .= '<div id="googleMap"></div><div class="spacer-20"></div>';
		return $output;
	}
	add_shortcode('gmap', 'imic_gmap');
	
	/* TESTIMONIALS SHORTCODE
	================================================== */
	
	function imic_testimonial($atts, $content = null) {
		extract(shortcode_atts(array(
			"number"			=> "",
		), $atts));
		
		$output = '';
		$output .= '<ul class="testimonials">';
		query_posts(array('post_type'=>'testimonials','posts_per_page'=>$number));
		if(have_posts()):while(have_posts()):the_post();
		$company = get_post_meta(get_the_ID(),'imic_client_company',true);
		$Client_Url = get_post_meta(get_the_ID(),'imic_client_co_url',true);
		$domain_url=$url_html = '';
		if(filter_var($Client_Url, FILTER_VALIDATE_URL)){ 
		$domain_url = parse_url($Client_Url);
		$domain_url = $domain_url['host'];
		$url_html = '<br><a href="'.$Client_Url.'">'.$domain_url.'</a>';
		 }
				$output .= '<li>
                              '.imic_excerpt(50).get_the_post_thumbnail(get_the_ID(),'full',array('class'=>'testimonial-sender')).'
                              <cite>'.get_the_title().' - <strong>'.$company.'</strong>'.$url_html.'
                           </cite>
                        </li>';
					endwhile; endif; wp_reset_query();
		$output .= '</ul>';
		return $output;
	}
	add_shortcode('testimonial', 'imic_testimonial');
	/* 
	/* PRICING TABLE SHORTCODE
	================================================== */
	function imic_pricing_table($atts, $content = null) {
		extract(shortcode_atts(array(
		'column' => '',
		),$atts));
		$output = '';
		$column = ($column==4)?'four':'three';
		$output = '<div class="pricing-table '.$column.'-cols margin-40">'. do_shortcode($content).'</div>';
		return $output;
	}
	add_shortcode('pricingtable', 'imic_pricing_table');
	
	function imic_pricing_table_heading( $atts, $content = null ) {
		extract(shortcode_atts(array(
		'active' => '',
		),$atts));
		$output = '';
		$active_class = '';
		if($active!='') { $active_class = ' highlight accent-color'; }
		$output = '<div class="pricing-column '.$active_class.'"><h3>'. do_shortcode($content);		
		return $output;
	}
	add_shortcode('headingss', 'imic_pricing_table_heading');
	function imic_pricing_table_reason( $atts, $content = null ) {
		$output = '<span class="highlight-reason">'. do_shortcode($content).'</span>';		
		return $output;
	}
	add_shortcode('reason', 'imic_pricing_table_reason');
	function imic_pricing_table_price( $atts, $content = null ) {
		extract(shortcode_atts(array(
		'currency' => '',
		),$atts));
		$output = '</h3><div class="pricing-column-content"><h4> <span class="dollar-sign">'.$currency.' </span> '. do_shortcode($content);		
		return $output;
	}
	add_shortcode('price', 'imic_pricing_table_price');
	
	function imic_pricing_table_interval( $atts, $content = null ) {
		$output = '</h4><span class="interval">';
		$output .= do_shortcode($content) .'</span><ul class="features" style="height: 157px;">';
		
		return $output;
	}
	add_shortcode('interval', 'imic_pricing_table_interval');
	function imic_pricing_table_row( $atts, $content = null ) {
		$output = '<li>';
		$output .= do_shortcode($content) .'</li>';
		
		return $output;
	}
	add_shortcode('row', 'imic_pricing_table_row');
	function imic_pricing_table_url( $atts, $content = null ) {
		$output = '</ul><a class="btn btn-primary" href="'.do_shortcode($content) .'">'.__('Sign up now!','framework').'</a></div></div>';
		
		return $output;
	}
	add_shortcode('url', 'imic_pricing_table_url');
	
	/* BUTTON SHORTCODE
	================================================== */
	
	function imic_button($atts, $content = null) {
		extract(shortcode_atts(array(
			"colour"		=> "",
			"type"			=> "",
			"link" 			=> "#",
			"target"		=> '_self',
			"size"		=> '',
			"extraclass"   => ''
		), $atts));
		
		$button_output = "";
		$button_class = 'btn '. $colour .' '. $extraclass .' '. $size;
		$buttonType = ($type == 'disabled')? 'disabled="disabled"' : '';
						
		$button_output .= '<a class="'.$button_class.'" href="'.$link.'" target="'.$target.'" '.$buttonType.'>' . do_shortcode($content) . '</a>';		
		return $button_output;
	}
	add_shortcode('imic_button', 'imic_button');
	
	
	/* ICON SHORTCODE
	================================================== */
		
	function imic_icon($atts, $content = null) {
		extract(shortcode_atts(array(
			"image"			=> ""
		), $atts));
		
		return '<i class="fa ' .$image. '"></i>';
	}
	add_shortcode('icon', 'imic_icon');
	
	/* ICON BOX SHORTCODE
	================================================== */
		
	function imic_icon_box($atts, $content = null) {
		extract(shortcode_atts(array(
			"icon_image"	=> "",
			"title"			=> "",
			"description"	=> "",
			"type" => "",
                    "icon_box" => "",
			"url" => "",
			"target" => ""
		), $atts));
                if($icon_box=='with_out_description'){
					if($url=='') {
                    $output ='<span class="icon-block"><span class="icon '.$type.'"><i class="fa '.$icon_image.'"></i></span></span><strong>'.$title.'</strong>'; }
					else {
					$url = ($target=='mailto')?'<a href="mailto:'.$url.'">':'<a href="'.$url.'" target="'.$target.'">';
					$output = $url.'<span class="icon-block"><span class="icon '.$type.'"><i class="fa '.$icon_image.'"></i></span></span><strong>'.$title.'</strong></a>';	
					}
                }
                else{
					if($url=='') {
		$output='<div class="icon-block"><span class="icon '.$type.'"><i class="fa '.$icon_image.'"></i></span>
			<h3>'.$title.'</h3>
			<p>'.$description.'</p>
			</div>'; }
			else {
				$url = ($target=='mailto')?'<a href="mailto:'.$url.'">':'<a href="'.$url.'" target="'.$target.'">';
				$output = $url.'<div class="icon-block"><span class="icon '.$type.'"><i class="fa '.$icon_image.'"></i></span>
			<h3>'.$title.'</h3>
			<p>'.$description.'</p>
			</div></a>';
			}
                }
                return $output;
	}
	add_shortcode('icon_box', 'imic_icon_box');
	/* COLUMN SHORTCODES
	================================================== */
	function imic_one_full( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"extra" => '',
			"anim" => '',
		), $atts));
		$animation = (!empty($anim)) ? 'data-appear-animation="'.$anim.'"' : '';
	    return '<div class="col-md-12 ' . $extra . '" '. $animation .'>' . do_shortcode($content) . '</div>';
	}
	add_shortcode('one_full', 'imic_one_full');
	
	function imic_one_half( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"extra" => '',
			"anim" => '',
		), $atts));
		$animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
	    return '<div class="col-md-6 ' . $extra . '" '. $animation .'>' . do_shortcode($content) . '</div>';
	}
	add_shortcode('one_half', 'imic_one_half');
	
	function imic_one_third( $atts, $content = null ) {
	   extract(shortcode_atts(array(
			"extra" => '',
			"anim" => ''
		), $atts));
		$animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
	    return '<div class="col-md-4 ' . $extra . '" '. $animation .'>' . do_shortcode($content) . '</div>';
	}
	add_shortcode('one_third', 'imic_one_third');
	function imic_one_fourth( $atts, $content = null ) {
	   extract(shortcode_atts(array(
			"extra" => '',
			"anim" => ''
		), $atts));
		$animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
	    return '<div class="col-md-3 ' . $extra . '" '. $animation .'>' . do_shortcode($content) . '</div>';
	}
	add_shortcode('one_fourth', 'imic_one_fourth');
	function imic_one_sixth( $atts, $content = null ) {
	   extract(shortcode_atts(array(
			"extra" => '',
			"anim" => ''
		), $atts));
		$animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
	    return '<div class="col-md-2 ' . $extra . '" '. $animation .'>' . do_shortcode($content) . '</div>';
	}
	add_shortcode('one_sixth', 'imic_one_sixth');
	
	function imic_two_third( $atts, $content = null ) {
	   extract(shortcode_atts(array(
			"extra" => '',
			"anim" => ''
		), $atts));
		$animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
	    return '<div class="col-md-8 ' . $extra . '" '. $animation .'>' . do_shortcode($content) . '</div>';
	}
	add_shortcode('two_third', 'imic_two_third');
	/* TABLE SHORTCODES
	================================================= */
	function imic_table_wrap( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"type" => ''
		), $atts));
		$output = '<table class="table '.$type.'">';
		$output .= do_shortcode($content) .'</table>';
		
		return $output;
		
	}
	add_shortcode('htable', 'imic_table_wrap');
	function imic_table_headtag( $atts, $content = null ) {
		$output = '<thead>'. do_shortcode($content) .'</thead>';		
		return $output;
	}
	add_shortcode('thead', 'imic_table_headtag');
	function imic_table_body( $atts, $content = null ) {
		$output = '<tbody>'. do_shortcode($content) .'</tbody>';		
		return $output;
	}
	add_shortcode('tbody', 'imic_table_body');
	
	function imic_table_row( $atts, $content = null ) {
		$output = '<tr>';
		$output .= do_shortcode($content) .'</tr>';
		
		return $output;
	}
	add_shortcode('trow', 'imic_table_row');
	
	function imic_table_column( $atts, $content = null ) {
	
		$output = '<td>';
		$output .= do_shortcode($content) .'</td>';
		
		return $output;
	}
	add_shortcode('tcol', 'imic_table_column');
	function imic_table_head( $atts, $content = null ) {
		$output = '<th>';
		$output .= do_shortcode($content) .'</th>';
		
		return $output;
	}
	add_shortcode('thcol', 'imic_table_head');
	
	/* TYPOGRAPHY SHORTCODES
	================================================= */
	// Anchor tag
	function imic_anchor( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"href"			=> '',
			"extra"			=> ''
		), $atts));
	   return '<a href="'.$href.'" class="'.$extra.'" >' . do_shortcode($content) . ' </a>';
	}
	add_shortcode('anchor', 'imic_anchor');
	// Div tag
	function imic_div( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"extra"			=> ''
		), $atts));
	   return '<div class="'.$extra.'">' . do_shortcode($content) . ' </div>';
	}
	add_shortcode('div', 'imic_div');
	// Hr tag
	function imic_hr( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"extra"			=> ''
		), $atts));
	   return '<hr class="'.$extra.'">';
	}
	add_shortcode('hr', 'imic_hr');
	// Section tag
	function imic_section( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"extra"			=> ''
		), $atts));
	   return '<section class="'.$extra.'">' . do_shortcode($content) . ' </section>';
	}
	add_shortcode('section', 'imic_section');
	// Alert tag
	function imic_alert( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"type"			=> '',
			"close"			=> ''
		), $atts));
		$closeButton = ($close == 'true') ? '<a class="close" data-dismiss="alert" href="#">&times;</a>' : '';
	   return '<div class="alert '. $type .' fade in">  ' .$closeButton . do_shortcode($content) . ' </div>';
	}
	add_shortcode('alert', 'imic_alert');
	
	// Heading Tag
	function imic_heading_tag($atts, $content = null) {
		extract(shortcode_atts(array(
		   "size" => '',
		   "extra" => '',
		   "icon" => '',
		   "type" => ''
		), $atts));
		if($icon!='') {
		$output = '<'.$size.' class="'.$extra.'"><i class="fa '.$icon.'"></i> '.do_shortcode($content).'</'.$size.'>';
		}
		else {
		$output = '<'.$size.' class="'.$extra.'">' . do_shortcode($content) .'</'.$size.'>';
		}
		return $output;
	}
	add_shortcode("heading", "imic_heading_tag");
	
	// Divider Tag
	function imic_divider_tag($atts, $content = null) {
		extract(shortcode_atts(array(
		   "extra" => '',
		), $atts));
		
		return '<hr class="'. $extra .'">';
	}
	add_shortcode("divider", "imic_divider_tag");
	
	// Paragraph type 
	function imic_paragraph($atts, $content = null) {
		extract(shortcode_atts(array(
		   "extra" => '',
		), $atts));
		
		return '<p class="' . $extra . '">'. do_shortcode($content) .'</p>';
	}
	add_shortcode("paragraph", "imic_paragraph");
	
	// Span type 
	function imic_span($atts, $content = null) {
		extract(shortcode_atts(array(
		   "extra" => '',
		), $atts));
		
		return '<span class="' . $extra . '">'. do_shortcode($content) .'</span>';
	}
	add_shortcode("span", "imic_span");	
	
	// Container type 
	function imic_container($atts, $content = null) {
		extract(shortcode_atts(array(
		   "extra" => '',
		), $atts));
		
		return '<div class="' . $extra . '">'. do_shortcode($content) .'</div>';
	}
	add_shortcode("container", "imic_container");
	
	// Dropcap type 
	function imic_dropcap($atts, $content = null) {
		extract(shortcode_atts(array(
		   "type" => '',
		), $atts));
		
		return '<p class="drop-caps ' . $type . '">'. do_shortcode($content) .'</p>';
	}
	add_shortcode("dropcap", "imic_dropcap");
		
	// Blockquote type
	function imic_blockquote($atts, $content = null) {
		extract(shortcode_atts(array(
		   "name" => '',
		), $atts));
		if(!empty($name)){ $authorName= '<cite>- '.$name.'</cite>'; }else{ $authorName= ''; } 
		return '<blockquote><p>'. do_shortcode($content) .'</p>' . $authorName . '</blockquote>';
	}
	add_shortcode("blockquote", "imic_blockquote");
	
	// Code type
	function imic_code($atts, $content = null) {
		extract(shortcode_atts(array(
		   "type" => '',
		), $atts));
		
		if($type=='inline'){ 
			return '<code>'. do_shortcode($content) .'</code>'; 
		}else{ 
			return '<pre>'. do_shortcode($content) .'</pre>'; 
		} 
		
	}
	add_shortcode("code", "imic_code");
		
	// Label Tag
	function imic_label_tag($atts, $content = null) {
		extract(shortcode_atts(array(
		   "type" => '',
		), $atts));
		$output = '<span class="label '.$type.'">' . do_shortcode($content) .'</span>';
		
		return $output;
	}
	add_shortcode("label", "imic_label_tag");	
	
	
	/* LISTS SHORTCODES
	================================================= */
	function imic_list( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"type" => '',
			"extra" => '',
			"icon" => ''
		), $atts));
				
		if($type == 'ordered'){
			$output = '<ol>' . do_shortcode($content) .'</ol>';
		}else if($type == 'desc'){
			$output = '<dl>' . do_shortcode($content) .'</dl>';
		} else{
			$output = '<ul class="'.$type .' '. $extra .'">' . do_shortcode($content) .'</ul>';		
		}
		
		return $output;		
	}
	add_shortcode('list', 'imic_list');
	
	function imic_list_item( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"icon" => '',
			"type" => ''
		), $atts));
		
		if(($type == 'icon')||($type == 'inline')){
			$output = '<li><i class="fa '.$icon.'"></i> ' . do_shortcode($content) .'</li>';
		}else{
			$output = '<li>' . do_shortcode($content) .'</li>';
		}
		return $output;		
	}
	add_shortcode('list_item', 'imic_list_item');
	
	function imic_list_item_dt( $atts, $content = null ) {		
		$output = '<dt>' . do_shortcode($content) .'</dt>';
		
		return $output;		
	}
	add_shortcode('list_item_dt', 'imic_list_item_dt');
	
	function imic_list_item_dd( $atts, $content = null ) {		
		$output = '<dd>' . do_shortcode($content) .'</dd>';
		
		return $output;		
	}
	add_shortcode('list_item_dd', 'imic_list_item_dd');
	function imic_page_first( $atts, $content = null ) {
		return '<li><a href="#"><i class="fa fa-chevron-left"></i></a></li>';		
	}
	add_shortcode('page_first', 'imic_page_first');
	
	function imic_page_last( $atts, $content = null ) {
		return '<li><a href="#"><i class="fa fa-chevron-right"></i></a></li>';		
	}
	add_shortcode('page_last', 'imic_page_last');	
	
	function imic_page( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"class" => ''
		), $atts));
		
		return '<li class="'.$class.'"><a href="#">'. do_shortcode($content) .' </a></li>';		
	}
	add_shortcode('page', 'imic_page');	
	
	/* TABS SHORTCODES
	================================================= */
	function imic_tabs( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"type" => '',
			"full" => 1
		), $atts));
		if($type==2) {
			if($full==1) {
			return '<div class="tabs">'. do_shortcode($content) .'</div>'; }
			else {
			return '<div class="tabs">'. do_shortcode($content) .'</div>'; }
		}else {
		return '<div class="tabs">'. do_shortcode($content) .'</div>'; }
	}
	add_shortcode('tabs', 'imic_tabs');
	
	function imic_tabh( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"type" => ''
		), $atts));
		if($type==2) {
			return '<div class="nav-tabs-bar"><ul class="nav nav-tabs">'. do_shortcode($content) .'</ul></div>';
		}
		else {
		return '<ul class="nav nav-tabs">'. do_shortcode($content) .'</ul>';	}	
	}
	add_shortcode('tabh', 'imic_tabh');
	
	function imic_tab( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"id" => '',
			"class" => ''
		), $atts));
		
		return '<li class="'.$class.'"> <a data-toggle="tab" href="#'.$id.'"> '. do_shortcode($content) .' </a> </li>';		
	}
	add_shortcode('tab', 'imic_tab');	
	
	function imic_tabc( $atts, $content = null ) {		
		return '<div class="tab-content">'. do_shortcode($content) .'</div>';	
	}
	add_shortcode('tabc', 'imic_tabc');
	
	function imic_tabrow( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"id" => '',
			"class" => ''
		), $atts));
				
		$output = '<div id="'.$id.'" class="tab-pane '.$class.'">' . do_shortcode($content) .'</div>';
		
		return $output;		
	}
	add_shortcode('tabrow', 'imic_tabrow');
	/* ACCORDION SHORTCODES
	================================================= */
	function imic_accordions( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"id" => ''
		), $atts));
		
		return '<div class="accordion" id="accordion' .$id. '">'. do_shortcode($content) .'</div>';
	}
	add_shortcode('accordions', 'imic_accordions');
	
	function imic_accgroup( $atts, $content = null ) {
		return '<div class="accordion-group panel">'. do_shortcode($content) .'</div>';		
	}
	add_shortcode('accgroup', 'imic_accgroup');
	
	function imic_acchead( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"id" => '',
			"class" => '',
			"tab_id" =>''
		), $atts));
		
		$output = '<div class="accordion-heading accordionize"> <a class="accordion-toggle '. $class .'" data-toggle="collapse" data-parent="#accordion' .$id. '" href="#' .$tab_id. '"> '. do_shortcode($content) .' <i class="fa fa-angle-down"></i> </a> </div>';
		
		return $output;
	}
	add_shortcode('acchead', 'imic_acchead');	
	
	function imic_accbody( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"tab_id" => '',
			"in" => ''
		), $atts));
		
		$output = '<div id="' . $tab_id . '" class="accordion-body ' . $in . ' collapse">
					  <div class="accordion-inner"> '. do_shortcode($content) .' </div>
					</div>';
		
		return $output;		
	}
	add_shortcode('accbody', 'imic_accbody');
	/* TOGGLE SHORTCODES
	================================================= */
	function imic_toggles( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"id" => ''
		), $atts));
		
		return '<div class="accordion" id="toggle' .$id. '">'. do_shortcode($content) .'</div>';
	}
	add_shortcode('toggles', 'imic_toggles');
	
	function imic_togglegroup( $atts, $content = null ) {
		return '<div class="accordion-group panel">'. do_shortcode($content) .'</div>';		
	}
	add_shortcode('togglegroup', 'imic_togglegroup');
	
	function imic_togglehead( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"id" => '',
			"tab_id" =>''
		), $atts));
		
		$output = '<div class="accordion-heading togglize"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#" href="#' .$tab_id. '"> '. do_shortcode($content) .' <i class="fa fa-plus-circle"></i> <i class="fa fa-minus-circle"></i> </a> </div>';
	
		return $output;
	}
	add_shortcode('togglehead', 'imic_togglehead');	
	
	function imic_togglebody( $atts, $content = null ) {
		extract(shortcode_atts(array(
			"tab_id" => ''
		), $atts));
		
		$output = '<div id="' . $tab_id . '" class="accordion-body collapse">
              <div class="accordion-inner"> '. do_shortcode($content) .'  </div>
            </div>';
		
		return $output;		
	}
	add_shortcode('togglebody', 'imic_togglebody');
	/* PROGRESS BAR SHORTCODE
	================================================= */
	function imic_progress_bar($atts) {
		extract(shortcode_atts(array(
			"percentage" => '',
			"name" => '',
			"type" => '',
			"value" => '',
			"colour" => ''
		), $atts));
		
		if ($type == 'progress-striped') { $typeClass = $type; } else { $typeClass = ""; }
		if ($colour == 'progress-bar-warning' ) { $warningText = '(warning)'; } else { $warningText = ""; }
		
		$service_bar_output = '';
		$progress_text = '';
		if($name!='') {
				$service_bar_output = '<div class="progress-label"> <span>' . $name . '</span> </div>';
		}
		$service_bar_output .= '<div class="progress '. $typeClass .'">';
		
		if($type == 'progress-striped'){
        	$service_bar_output .= '<div class="progress-bar ' . $colour . '" style="width: ' . $value . '%">';
			$service_bar_output .= '<span class="sr-only">' . $value . '% Complete (success)</span>';
			$service_bar_output .= '</div>';        
		}else if($type == 'colored'){
			if(!empty($warningText)){ $spanClass=''; $progress_text = $value.'% Complete '.$warningText; }else{ $spanClass='sr-only'; $progress_text = ''; }
          	$service_bar_output .= '<div class="progress-bar ' . $colour . '" style="width: ' . $value . '%"> <span class="'.$spanClass.'">' . $progress_text.'</span> </div>';
		}else{
			$service_bar_output .= '<div class="progress-bar progress-bar-primary" data-appear-progress-animation="'.$value.'%"> <span class="progress-bar-tooltip">' . $value . '%</span> </div>';
		}
        $service_bar_output .= '</div>';
		
		return $service_bar_output;
	}
	
	add_shortcode('progress_bar', 'imic_progress_bar');
	
	
	/* TOOLTIP SHORTCODE
	================================================= */
	function imic_tooltip($atts, $content = null) {
		extract(shortcode_atts(array(
			"title" => '',
			"link" => '#',
			"direction" => 'top'
		), $atts));
				
		$tooltip_output = '<a href="'.$link.'" rel="tooltip" data-toggle="tooltip" data-original-title="'.$title.'" data-placement="'.$direction.'">'.do_shortcode($content).'</a>';
		return $tooltip_output;
	}
	
	add_shortcode('imic_tooltip', 'imic_tooltip');
	/* WORDPRESS LINK SHORTCODE
	================================================= */
	function imic_wordpress_link() {
		return '<a href="http://wordpress.org/" target="_blank">WordPress</a>';
	}
	add_shortcode('wp-link', 'imic_wordpress_link');
	
	/* COUNT SHORTCODE
	================================================= */
	function imic_count($atts) {
		extract(shortcode_atts(array(
			"speed" => '2000',
			"to" => '',
			"icon" => '',
			"subject" => '',
			"textstyle" => ''
		), $atts));
		
		$count_output = '';
		if ($speed == "") {$speed = '2000'; }
		$count_output .= '<div class="col-lg-3 col-md-3 col-sm-3 cust-counter">';
		$count_output .= '<div class="fact-ico"> <i class="fa ' . $icon . ' fa-4x"></i> </div>';
		$count_output .= '<div class="clearfix"></div>';
		$count_output .= '<div class="timer" data-perc="'.$speed.'"> <span class="count">' .$to. '</span></div>';
		$count_output .= '<div class="clearfix"></div>';
		if ($textstyle == "h3") {
			$count_output .= '<h3>'.$subject.'</h3></div>';		
		} else if ($textstyle == "h6") {
			$count_output .= '<h6>'.$subject.'</h6></div>';		
		} else {
			$count_output .= '<span class="fact">'.$subject.'</span></div>';
		}
		
		return $count_output;
	}
	
	add_shortcode('imic_count', 'imic_count');
	
	/* MODAL BOX SHORTCODE
	================================================== */
	function imic_modal_box($atts, $content = null) {
		extract(shortcode_atts(array(
			"id"			=> "",
			"title" 	=> "",
			"text"	=> "",
			"button" => ""
		), $atts));
		
		$modalBox = '<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#'.$id.'">'.$button.'</button>
            <div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="'.$id.'Label" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="'.$id.'Label">'.$title.'</h4>
                  </div>
                  <div class="modal-body"> '. $text .' </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default inverted" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>';
				
		return $modalBox;
		
	}
	add_shortcode('modal_box', 'imic_modal_box');
	/* FULLSCREEN VIDEO SHORTCODE
  ================================================= */
	function imic_fullscreen_video($atts, $content = null) {
		extract(shortcode_atts(array(
			"videourl" => '',
						), $atts));
		$fw_video_output = "";
		if (!empty($videourl)) {
			$fw_video_output .=imic_video_embed($videourl, 100, 100);
		}
		return $fw_video_output;
	}
	add_shortcode('fullscreenvideo', 'imic_fullscreen_video');


	/* FORM SHORTCODE
	================================================== */
	function imic_form_code($atts, $content = null) {
   extract(shortcode_atts(array(
        "form_email" => '',
        "form_title" => '',
                    ), $atts));
     if(!empty($form_email)){
        $admin_email = $form_email; 
      }else{
      $admin_email = get_option('admin_email');
       }
       $contact_title='';
       if(!empty($form_title)){
        $contact_title = '<h2>'.$form_title.'</h2>'; 
       }
$subject_email = __('Contact Form','framework'); 
$formCode = $contact_title.'<form action="'.get_template_directory_uri().'/mail/contact.php" type="post" class="contact-form clearfix" id="contactform">
<div class="row">
<div class="col-md-6">
<div class="form-group">
<input type="text" id="name" name="name"  class="form-control input-lg" placeholder="'.__('Họ tên','framework').' *">
</div>
<div class="form-group">
<input type="email" id="email" name="email"  class="form-control input-lg" placeholder="'.__('Email','framework').' *">
</div>
<div class="form-group">
<input type="text" id="phone" name="phone" class="form-control input-lg" placeholder="'.__('Số điện thoại','framework').'">
</div>
</div>
<div class="col-md-6">
<div class="form-group">
    <textarea cols="6" rows="7" id="comments" name="comments" class="form-control input-lg" placeholder="'.__('Tin nhắn','framework').'"></textarea>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<input type ="hidden" name ="image_path" id="image_path" value ="'.get_template_directory_uri().'">
<input type="hidden" id="phone" name="phone" class="form-control input-lg" placeholder="">
<input id="admin_email" name="admin_email" type="hidden" value ="'.$admin_email.'">
<input id="subject" name="subject" type="hidden" value ="'.$subject_email.'">
<input id="submit" name="submit" type="submit" class="btn btn-primary btn-lg pull-right" value="'.__('Gửi','framework').'">
</div>
</div>
</form><div id="message"></div>';
    return $formCode;
}
	add_shortcode('imic_form', 'imic_form_code');
        /* Event Calendar SHORTCODE
  ================================================= */
function event_calendar($atts) {
    extract(shortcode_atts(array(
        "category_id" => '',
		"filter" => '',
		"preview" => '',
                    ), $atts));
		wp_enqueue_style('imic_fullcalendar_css');
		wp_enqueue_style('imic_fullcalendar_print');
		wp_enqueue_script('imic_jquery_countdown');
		wp_localize_script('imic_jquery_countdown', 'upcoming_data', array('c_time' =>time()));
	global $imic_options;
       	$event_preview = $preview;
		$term_output = '';
		if($filter==1) { 
		$e_terms = get_terms('event-category');
		$term_output .= '<ul class="nav nav-pills calendar-nav-pills"><li class="e1Div active" id=""><a href="javascript:void(0)">'.__('All','framework').'</a></li>';
		if($imic_options['google_feed_id']!='') { 
		$term_output .= '<li class="e1Div" id="google"><a href="javascript:void(0)">'.__('Google','framework').'</a></li>'; }
		foreach($e_terms as $term) {
		$term_output .= '<li class="e1Div" id="'.$term->term_id.'"><a href="javascript:void(0)">'.$term->name.'</a></li>'; }
		$term_output .= '</ul>'; }
		//$google_feeds = $imic_options['google_feed'];
		$google_api_key = $imic_options['google_feed_key'];
		$google_calendar_id = $imic_options['google_feed_id'];
        $monthNamesValue = $imic_options['calendar_month_name'];
        $monthNames = (empty($monthNamesValue)) ? array() : explode(',', trim($monthNamesValue));
        $monthNamesShortValue = $imic_options['calendar_month_name_short'];
        $monthNamesShort = (empty($monthNamesShortValue)) ? array() : explode(',', trim($monthNamesShortValue));
        $dayNamesValue = $imic_options['calendar_day_name'];
        $dayNames = (empty($dayNamesValue)) ? array() : explode(',', trim($dayNamesValue));
        $dayNamesShortValue = $imic_options['calendar_day_name_short'];
        $dayNamesShort = (empty($dayNamesShortValue)) ? array() : explode(',', trim($dayNamesShortValue));
	wp_enqueue_script('imic_fullcalendar_ui');
		wp_enqueue_script('imic_fullcalendar');
		wp_enqueue_script('imic_gcal');
		wp_enqueue_script('imic_calender_events');
		$format=ImicConvertDate(get_option('time_format'));
        wp_localize_script('imic_calender_events', 'calenderEvents', array('homeurl' => get_template_directory_uri(), 'monthNames' => $monthNames, 'monthNamesShort' => $monthNamesShort, 'dayNames' => $dayNames, 'dayNamesShort' => $dayNamesShort,'time_format'=>$format,'start_of_week'=>get_option('start_of_week'),'googlekey'=>$google_api_key,'googlecalid'=>$google_calendar_id,'ajaxurl' => admin_url('admin-ajax.php'),'preview'=>$event_preview));
		if($event_preview==1) {
			$output = '';
			$events = imic_recur_events('future','',''); ksort($events); foreach($events as $key=>$value) { $id = $value; break; }
			$date_converted=date('Y-m-d',$key );
    $custom_event_url= imic_query_arg($date_converted,$id);
	$output .= '<ul class=" events-grid events-ajax-caller">';
	$output .= '<li>';
	$output .= '<div class="grid-item-inner">';
	$output .= '<div class="preview-event-bar">
                            <div id="counter" class="counter-preview top-header" data-date="'.$key.'">
                         		<div class="timer-col"> <span id="days"></span> <span class="timer-type">'.__('d','framework').'</span> </div>
                        		<div class="timer-col"> <span id="hours"></span> <span class="timer-type">'.__('h','framework').'</span> </div>
                      			<div class="timer-col"> <span id="minutes"></span> <span class="timer-type">'.__('m','framework').'</span> </div>
                         		<div class="timer-col"> <span id="seconds"></span> <span class="timer-type">'.__('s','framework').'</span> </div>
                            </div>
                        </div>';
	if ( '' != get_the_post_thumbnail($id) ) { 
	$output .= '<a href="'.esc_url($custom_event_url).'" class="media-box">'.get_the_post_thumbnail($id,'full').'</a>'; }
	$address1 = get_post_meta($id,'imic_event_address1',true);
	$address2 = get_post_meta($id,'imic_event_address2',true);
   	$output .= '<ul class="info-cols clearfix">';
  	$output .= '<li><a href="#" data-toggle="tooltip" data-placement="top" title="'.date_i18n(get_option('date_format'), $key).'"><i class="fa fa-calendar"></i></a></li><li><a href="#" data-toggle="tooltip" data-placement="top" title="'.date_i18n(get_option('time_format'), $key).'"><i class="fa fa-clock-o"></i></a></li>';
	if($address2!='') {
  	$output .= '<li><a href="#" data-toggle="tooltip" data-placement="top" title="'.$address2.'"><i class="fa fa-map-marker"></i></a></li>';
	} if($address1!='') {
	$output .= '<li><a href="#" data-toggle="tooltip" data-placement="top" title="'.$address1.'"><i class="fa fa-flag"></i></a></li>'; }
    $output .= '</ul><div class="grid-content"><h3><a href="'.esc_url($custom_event_url).'">'.get_the_title($id).'</a></h3>';
    $page_data = get_page( $id );
	$excerpt = strip_tags($page_data->post_excerpt);
	$output .= $excerpt;
	$output .= '<br/><a class="btn btn-sm btn-default" href="'.$custom_event_url.'">'.__('More','framework').'</a>';
    $output .= '</div></div></li></ul>';
			return '<div class="row"><div class="col-md-9">'.$term_output.'<div id="calendar"><div id ="'.$category_id.'" class ="event_calendar calendar"></div></div></div><div class="col-md-3"><h2 class="title ">'.__('Event Preview','framework').'</h2><div id="events-preview-box">'.$output.'</div></div></div>';
		}else {
			return $term_output.'<div id="calendar"><div id ="'.$category_id.'" class ="event_calendar calendar"></div></div>'; }
}
add_shortcode('event_calendar', 'event_calendar');
?>