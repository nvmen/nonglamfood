<?php
/*
Plugin Name: Location Picker
Plugin URI: http://pluginscart.com
Description: Add location picker using Google Map. (Capture longitute, latitude and address using just one click on contact form 7), Location Picker comes in your customers help to ease their pain caused by filling in address and exact location.
Author: PluginsCart
Author URI: http://pluginscart.com
Version: 1.0
Text Domain: cf7_maps
Domain Path: languages
*/
define( 'WPCF7_MAP_VERSION', '1.0' );

define( 'WPCF7_MAP_REQUIRED_WP_VERSION', '4.2' );

define( 'WPCF7_MAP_PLUGIN', __FILE__ );

define( 'WPCF7_MAP_PLUGIN_BASENAME', plugin_basename( WPCF7_MAP_PLUGIN ) );

define( 'WPCF7_MAP_PLUGIN_NAME', trim( dirname( WPCF7_MAP_PLUGIN_BASENAME ), '/' ) );

define( 'WPCF7_MAP_PLUGIN_DIR', untrailingslashit( dirname( WPCF7_MAP_PLUGIN ) ) );

define( 'WPCF7_MAP_PLUGIN_MODULES_DIR', WPCF7_MAP_PLUGIN_DIR . '/modules' );


add_action('init', 'contact_form_7_map_fields_textdomain');

function contact_form_7_map_fields_textdomain() {
	// Load the default language files
	load_plugin_textdomain( 'cf7_maps', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}



add_action('plugins_loaded', 'contact_form_7_map_fields', 11);

function contact_form_7_map_fields() {
	global $pagenow;
	if( class_exists('WPCF7_Shortcode') ) {
		
		
		wpcf7_add_shortcode( array( 'latlong', 'latlong*' ), 'wpcf7_latlong_shortcode_handler', true );
				
		wpcf7_add_shortcode( array( 'location', 'location*' ), 'wpcf7_location_shortcode_handler', true );
		
		wpcf7_add_shortcode( array( 'mapcanvas'), 'wpcf7_mapcanvas_shortcode_handler', true );
		
		wpcf7_add_shortcode( array( 'postcode'), 'wpcf7_postcode_shortcode_handler', true );
		
	} else {
		if($pagenow != 'plugins.php') { return; }
		add_action('admin_notices', 'cfmapfieldserror');
		add_action('admin_enqueue_scripts', 'contact_form_7_map_fields_scripts');

		function cfmapfieldserror() {
			$out = '<div class="error" id="messages"><p>';
			if(file_exists(WP_PLUGIN_DIR.'/contact-form-7/wp-contact-form-7.php')) {
				$out .= 'The Contact Form 7 is installed, but <strong>you must activate Contact Form 7</strong> below for the Location picker to work.';
			} else {
				$out .= 'The Contact Form 7 plugin must be installed for the Location Picker to work. <a href="'.admin_url('plugin-install.php?tab=plugin-information&plugin=contact-form-7&from=plugins&TB_iframe=true&width=600&height=550').'" class="thickbox" title="Contact Form 7">Install Now.</a>';
			}
			$out .= '</p></div>';
			echo $out;
		}
	}
}

function contact_form_7_map_fields_scripts() {
	wp_enqueue_script('thickbox');
}


/**
 * Proper way to enqueue scripts and styles
 */
function theme_name_scripts() {
//	wp_enqueue_style( 'style-name', get_stylesheet_uri() );
 wp_enqueue_script( 'jquery' );
  wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBuU_0_uLMnFM-2oWod_fzC0atPZj7dHlU&sensor=false');
  
wp_enqueue_script(
		'mapjs',
		plugin_dir_url( __FILE__ ) . 'map.js',
		array( 'jquery','google-maps' )
	);
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );

/**
** A base module for [map] and [location]
**/

/* Shortcode handler */

add_filter('wpcf7_form_elements', 'wpcf7_form_elements_strip_paragraphs_and_brs_maps');

/**
 * Strip paragraph tags being wrapped around the field
 * @param $form
 *
 * @return mixed
 */
function wpcf7_form_elements_strip_paragraphs_and_brs_maps($form) {
	return preg_replace_callback('/<p>(<input\stype="hidden"(?:.*?))<\/p>/ism', 'wpcf7_form_elements_strip_paragraphs_and_brs_maps_callback', $form);
}

function wpcf7_form_elements_strip_paragraphs_and_brs_maps_callback($matches = array()) {
	return "\n".'<!-- CF7 Maps -->'."\n".'<div>'.str_replace('<br>', '', str_replace('<br />', '', stripslashes_deep($matches[1]))).'</div>'."\n".'<!-- End CF7 Maps -->'."\n";
}


/* Tag generator */

if ( is_admin() ) {
	add_action( 'admin_init', 'wpcf7_add_tag_generator_map', 30 );
}

function wpcf7_add_tag_generator_map() {

	if( class_exists('WPCF7_TagGenerator') ) {

		$tag_generator = WPCF7_TagGenerator::get_instance();
		$tag_generator->add( 'location', __( 'location', 'cf7_maps' ), 'wpcf7_tg_pane_location' );

		$tag_generator->add( 'latlong', __( 'latlong', 'cf7_maps' ), 'wpcf7_tg_pane_latlong' );
		
		$tag_generator->add( 'mapcanvas', __( 'Map Canvas', 'cf7_maps' ), 'wpcf7_tg_pane_mapcanvas' );
		
		$tag_generator->add( 'postcode', __( 'postcode', 'cf7_maps' ), 'wpcf7_tg_pane_postcode' );
	}
}

/**
** A base module for [location], [location*]
**/

/* Shortcode handler */

function wpcf7_location_shortcode_handler( $tag ) {

	$tag = new WPCF7_Shortcode( $tag );

	if ( empty( $tag->name ) ) {
		return '';
	}

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type, 'wpcf7-text' );

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}

	$class .= ' wpcf7-text';

	if ( 'map' === $tag->type ) {
		$class .= ' wpcf7-validates-as-required';
	}

	$value = (string) reset( $tag->values );

	$placeholder = '';
	if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
		$placeholder = $value;
		$value       = '';
	}

	$default_value = $tag->get_default_option( $value );

//	$value = contact_form_7_map_fields_fill_user_data( $value, $tag );

	

	// Arrays get imploded.
	$value = is_array( $value ) ? implode( apply_filters( 'wpcf7_map_field_implode_glue', ', ' ), $value ) : $value;

	// Make sure we're using a string. Objects get JSON-encoded.
	if ( ! is_string( $value ) ) {
		$value = json_encode( $value );
	}

	$value = apply_filters( 'wpcf7_map_field_value', apply_filters( 'wpcf7_map_field_value_' . $tag->get_id_option(), $value ) );

	$value = wpcf7_get_hangover( $tag->name, $value );

	$atts = array(
		'type'        => 'textarea',
		'class'       => $tag->get_class_option( $class ),
	//	'id'          => $tag->get_id_option(),
		'id'          => "cf7_location_picker_address",
		'name'        => $tag->name,
		'tabindex'    => $tag->get_option( 'tabindex', 'int', true ),
		'placeholder' => $placeholder,
		
	);

	$atts = wpcf7_format_atts( $atts );

	$html = sprintf( '<textarea %1$s /></textarea>%2$s', $atts, $validation_error );

	return $html;
}



function wpcf7_tg_pane_location( $contact_form, $args = '' ) {

	$args = wp_parse_args( $args, array() );

	$description = __( "Generate a form tag for a map and location field. For more details, see %s.", 'contact-form-7' );
	$desc_link = wpcf7_link( __( 'https://wordpress.org/plugins/contact-form-7-map/', 'contact-form-7' ), __( 'the plugin page on WordPress.org', 'contact-form-7' ), array('target' => '_blank' ) );
?>
<div class="control-box">
	<fieldset>
		<legend><?php printf( esc_html( $description ), $desc_link ); ?></legend>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'ID attribute', 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'cf7_modules' ) ); ?>)</label></th>
					<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
				</tr>
			
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?></label></th>
				
					<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /><br />
					<label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html( __( 'Use this text as the placeholder of the field', 'contact-form-7' ) ); ?></label></td>
				</tr>
				
			</tbody>
		</table>
	</fieldset>
</div>
	<div class="insert-box">
		<input type="text" name="location" class="tag code" readonly="readonly" onfocus="this.select()" />

		<div class="submitbox">
			<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
		</div>

		<br class="clear" />

		<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
	</div>
<?php
}


/**
** A base module for [latlong], [latlong*]
**/

/* Shortcode handler */

function wpcf7_latlong_shortcode_handler( $tag ) {

	$tag = new WPCF7_Shortcode( $tag );

	if ( empty( $tag->name ) ) {
		return '';
	}

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type, 'wpcf7-text' );

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}

	$class .= ' wpcf7-text';

	if ( 'map' === $tag->type ) {
		$class .= ' wpcf7-validates-as-required';
	}

	$value = (string) reset( $tag->values );

	$placeholder = '';
	if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
		$placeholder = $value;
		$value       = '';
	}

	$default_value = $tag->get_default_option( $value );

	//$value = contact_form_7_map_fields_fill_user_data( $value, $tag );

	// Post data hasn't filled yet. No arrays.
	if( $default_value === $value ) {
	//	$value = contact_form_7_map_fields_fill_post_data( $value );
	}

	// Arrays get imploded.
	$value = is_array( $value ) ? implode( apply_filters( 'wpcf7_map_field_implode_glue', ', ' ), $value ) : $value;

	// Make sure we're using a string. Objects get JSON-encoded.
	if ( ! is_string( $value ) ) {
		$value = json_encode( $value );
	}

	$value = apply_filters( 'wpcf7_map_field_value', apply_filters( 'wpcf7_map_field_value_' . $tag->get_id_option(), $value ) );

	$value = wpcf7_get_hangover( $tag->name, $value );

	$atts = array(
		'type'        => 'text',
		'class'       => $tag->get_class_option( $class ),
	//	'id'          => $tag->get_id_option(),
	'id'          => "cf7_location_picker_output",
		'name'        => $tag->name,
		'tabindex'    => $tag->get_option( 'tabindex', 'int', true ),
		'placeholder' => $placeholder,
		'value'       => $value,
	);

	$atts = wpcf7_format_atts( $atts );

	$html = sprintf( '<input %1$s />%2$s', $atts, $validation_error );

	return $html;
}



function wpcf7_tg_pane_latlong( $contact_form, $args = '' ) {

	$args = wp_parse_args( $args, array() );

	$description = __( "Generate a form tag for a map and location field. For more details, see %s.", 'contact-form-7' );
	$desc_link = wpcf7_link( __( 'https://wordpress.org/plugins/contact-form-7-map/', 'contact-form-7' ), __( 'the plugin page on WordPress.org', 'contact-form-7' ), array('target' => '_blank' ) );
?>
<div class="control-box">
	<fieldset>
		<legend><?php printf( esc_html( $description ), $desc_link ); ?></legend>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'ID attribute', 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'cf7_modules' ) ); ?>)</label></th>
					<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
				</tr>
			
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?></label></th>
				
					<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /><br />
					<label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html( __( 'Use this text as the placeholder of the field', 'contact-form-7' ) ); ?></label></td>
				</tr>
				
				
			</tbody>
		</table>
	</fieldset>
</div>
	<div class="insert-box">
		<input type="text" name="latlong" class="tag code" readonly="readonly" onfocus="this.select()" />

		<div class="submitbox">
			<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
		</div>

		<br class="clear" />

		<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
	</div>
<?php
}




/**
** A base module for [mapcanvas]
**/

/* Shortcode handler */

function wpcf7_mapcanvas_shortcode_handler( $tag ) {

		$html = sprintf( ' <div id="map_canvas" style="width: 400px; height: 400px;"></div>
    	<p class="small" style="text-align: center">Click to set the marker or drag to pan.</p>
    </div>');

	return $html;
}


function wpcf7_tg_pane_mapcanvas( $contact_form, $args = '' ) {

	$args = wp_parse_args( $args, array() );

	$description = __( "Generate a form tag for a map and location field. For more details, see %s.", 'contact-form-7' );
	$desc_link = wpcf7_link( __( 'https://wordpress.org/plugins/contact-form-7-map/', 'contact-form-7' ), __( 'the plugin page on WordPress.org', 'contact-form-7' ), array('target' => '_blank' ) );
?>
<div class="control-box">
	<fieldset>
		<legend><?php printf( esc_html( $description ), $desc_link ); ?></legend>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'ID attribute', 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'cf7_modules' ) ); ?>)</label></th>
					<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
				</tr>
			
								
			</tbody>
		</table>
	</fieldset>
</div>
	<div class="insert-box">
		<input type="text" name="mapcanvas" class="tag code" readonly="readonly" onfocus="this.select()" />

		<div class="submitbox">
			<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
		</div>

		<br class="clear" />

		<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
	</div>
<?php
}




/**
** A base module for [postcode], [postcode*]
**/

/* Shortcode handler */

function wpcf7_postcode_shortcode_handler( $tag ) {

	$tag = new WPCF7_Shortcode( $tag );

	if ( empty( $tag->name ) ) {
		return '';
	}

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type, 'wpcf7-text' );

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}

	$class .= ' wpcf7-text';

	if ( 'map' === $tag->type ) {
		$class .= ' wpcf7-validates-as-required';
	}

	$value = (string) reset( $tag->values );

	$placeholder = '';
	if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
		$placeholder = $value;
		$value       = '';
	}

	$default_value = $tag->get_default_option( $value );

	//$value = contact_form_7_map_fields_fill_user_data( $value, $tag );

	// Post data hasn't filled yet. No arrays.
	if( $default_value === $value ) {
	//	$value = contact_form_7_map_fields_fill_post_data( $value );
	}

	// Arrays get imploded.
	$value = is_array( $value ) ? implode( apply_filters( 'wpcf7_map_field_implode_glue', ', ' ), $value ) : $value;

	// Make sure we're using a string. Objects get JSON-encoded.
	if ( ! is_string( $value ) ) {
		$value = json_encode( $value );
	}

	$value = apply_filters( 'wpcf7_map_field_value', apply_filters( 'wpcf7_map_field_value_' . $tag->get_id_option(), $value ) );

	$value = wpcf7_get_hangover( $tag->name, $value );

	$atts = array(
		'type'        => 'text',
		'class'       => $tag->get_class_option( $class ),
	//	'id'          => $tag->get_id_option(),
	'id'          => "cf7_location_picker_postcode",
		'name'        => $tag->name,
		'tabindex'    => $tag->get_option( 'tabindex', 'int', true ),
		'placeholder' => $placeholder,
		'value'       => $value,
	);

	$atts = wpcf7_format_atts( $atts );

	$html = sprintf( '<input %1$s />%2$s <input type="button" onclick="search();" value="Show" class="formandu-button">', $atts, $validation_error );

	return $html;
}



function wpcf7_tg_pane_postcode( $contact_form, $args = '' ) {

	$args = wp_parse_args( $args, array() );

	$description = __( "Generate a form tag for a map and location field. For more details, see %s.", 'contact-form-7' );
	$desc_link = wpcf7_link( __( 'https://wordpress.org/plugins/contact-form-7-map/', 'contact-form-7' ), __( 'the plugin page on WordPress.org', 'contact-form-7' ), array('target' => '_blank' ) );
?>
<div class="control-box">
	<fieldset>
		<legend><?php printf( esc_html( $description ), $desc_link ); ?></legend>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'ID attribute', 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'cf7_modules' ) ); ?>)</label></th>
					<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
				</tr>
			
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?></label></th>
				
					<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /><br />
					<label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html( __( 'Use this text as the placeholder of the field', 'contact-form-7' ) ); ?></label></td>
				</tr>
				
			</tbody>
		</table>
	</fieldset>
</div>
	<div class="insert-box">
		<input type="text" name="postcode" class="tag code" readonly="readonly" onfocus="this.select()" />

		<div class="submitbox">
			<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
		</div>

		<br class="clear" />

		<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
	</div>
<?php
}
