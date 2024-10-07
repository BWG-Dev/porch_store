<?php


// 2. Include field type for ACF5
// $version = 5 and can be ignored until ACF6 exists
function porcho_include_field_types_divider( $version = false ) {

	// include_once 'acf-divider-v5.php';
	include_once get_template_directory() . '/includes/acf-divider/acf-divider-v5.php';
	if( is_admin() ) {
	wp_register_script( 'acf-input-divider',  get_template_directory_uri() . '/includes/acf-divider/js/input.js' );
	wp_enqueue_script( 'acf-input-divider' );

	wp_enqueue_style( 'acf-input-divider', get_template_directory_uri() . '/includes/acf-divider/css/input.css' );
	wp_enqueue_style( 'acf-input-divider' );
	}
}

add_action( 'acf/include_field_types', 'porcho_include_field_types_divider' );
add_action( 'init', 'porcho_include_field_types_divider' );

