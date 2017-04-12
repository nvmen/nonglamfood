<?php
/**
 * Resolved conflict with other plugins
 *
 * @package   PT_Content_Views
 * @author    PT Guy <http://www.contentviewspro.com/>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2016 PT Guy
 */
# Autoptimize: Disable "Force JavaScript in <head>"
add_filter( 'autoptimize_filter_js_defer', 'cv_filter_js_defer', 10, 1 );
if ( !function_exists( 'cv_filter_js_defer' ) ) {
	function cv_filter_js_defer( $defer ) {
		$defer = "defer ";
		return $defer;
	}

}

add_filter( 'pt_cv_field_content_excerpt', 'cv_filter_fce', 9, 3 );
if ( !function_exists( 'cv_filter_fce' ) ) {
	function cv_filter_fce( $args, $fargs, $this_post ) {

		# Page Builder by SiteOrigin: Fix incorrect excerpt
		if ( function_exists( 'siteorigin_panels_filter_content' ) ) {
			if ( !isset( $this_post->cv_so_content ) ) {
				$this_post->cv_so_content = siteorigin_panels_filter_content( $args );
			}

			$args = $this_post->cv_so_content;
		}

		return $args;
	}

}

# FacetWP: Fix View does not exist", missing posts in output when access page with parameters 'fwp_*' of FacetWP plugin
add_filter( 'facetwp_is_main_query', 'cv_facetwp_is_main_query', 999, 2 );
if ( !function_exists( 'cv_facetwp_is_main_query' ) ) {
	function cv_facetwp_is_main_query( $is_main_query, $query ) {
		if ( $query->get( 'cv_get_view' ) || $query->get( 'by_contentviews' ) ) {
			$is_main_query = false;
		}

		return $is_main_query;
	}

}

# "View maybe not exist" error, caused by custom filter hook (which modifies `post_type` in WordPress query) of another plugin
add_action( 'pre_get_posts', 'cv_fix_no_view_found', 999 );
if ( !function_exists( 'cv_fix_no_view_found' ) ) {
	function cv_fix_no_view_found( $query ) {
		if ( $query->get( 'cv_get_view' ) ) {
			$query->set( 'post_type', PT_CV_POST_TYPE );
		}

		return $query;
	}

}

# Remove line break holder of Divi theme from excerpt
add_filter( 'pt_cv_before_generate_excerpt', 'cv_divitheme_before_generate_excerpt' );
if ( !function_exists( 'cv_divitheme_before_generate_excerpt' ) ) {
	function cv_divitheme_before_generate_excerpt( $args ) {
		if ( defined( 'ET_CORE_VERSION' ) ) {
			$args = str_replace( array( '<!-- [et_pb_line_break_holder] -->', '&lt;!-- [et_pb_line_break_holder] --&gt;' ), '', $args );
		}

		return $args;
	}

}

/**
 * Fix problem: shortcode is visible in content, when do Ajax pagination
 * @since 1.9.6
 */
add_action( 'pt_cv_before_content', 'cv_fix_shortcode_visible_in_pagination', 9 );
if ( !function_exists( 'cv_fix_shortcode_visible_in_pagination' ) ) {
	function cv_fix_shortcode_visible_in_pagination() {
		if ( (defined( 'PT_CV_DOING_PAGINATION' ) || defined( 'PT_CV_DOING_PREVIEW' )) && class_exists( 'WPBMap' ) && method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
			WPBMap::addAllMappedShortcodes();
		}
	}

}

add_action( 'pre_get_posts', 'cv_fix_pgp_sortby', 9 );
if ( !function_exists( 'cv_fix_pgp_sortby' ) ) {
	function cv_fix_pgp_sortby( $query ) {
		if ( $query->get( 'by_contentviews' ) ) {
			/**
			 * Fix issue: Wrong posts order, caused by plugin "Post Types Order"
			 * @since 1.9.6
			 */
			$query->set( 'ignore_custom_sort', true );
		}

		return $query;
	}

}