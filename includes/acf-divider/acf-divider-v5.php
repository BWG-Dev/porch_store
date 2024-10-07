<?php

class acf_field_divider extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct() {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

		$this->name = 'divider';

		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __( 'Divider', 'acf-divider' );

		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'layout';

		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'font_size' => 14,
		);

		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('divider', 'error');
		*/

		$this->l10n = array(
			'error' => __( 'Error! Please enter a higher value', 'acf-divider' ),
		);

		// do not delete!
		parent::__construct();

	}


	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/

		acf_render_field_setting(
			$field,
			array(
				'label' => __( 'Custom CSS', 'acf-divider' ),
				'type'  => 'textarea',
				'name'  => 'custom_css',
			)
		);

		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Instructions', 'acf-divider' ),
				'instructions' => __( 'Divide field groups', 'acf-divider' ),
				'type'         => 'message',
				'message'      => 'Use "Field Divider" to better organize your edit screen by grouping fields together.',
			)
		);

	}



	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {
		$customCSS = "style='" . $field['custom_css'] . "'";
		$label     = $field['label'];
		echo "<h2 class='acf-divider-heading' $customCSS><span>$label</span></h2>";
	}




	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/



	function input_admin_head() {
		wp_enqueue_style( 'acf-input-divider', plugin_dir_url( __FILE__ ) . 'css/input.css' );
		wp_enqueue_style( 'acf-input-divider' );
	}



	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	// function field_group_admin_head() {
	// 	wp_register_script( 'acf-input-divider',  get_template_directory_uri() . '/includes/acf-divider/js/input.js' );
	// 	wp_enqueue_script( 'acf-input-divider' );

	// 	wp_enqueue_style( 'acf-input-divider', get_template_directory_uri() . '/includes/acf-divider/css/input.css' );
	// 	wp_enqueue_style( 'acf-input-divider' );
	// }


}


// create field
new acf_field_divider();
