<?php
// old functions from old theme to support old shortcodes
// **********************************************************************//
// ! Custom Static Blocks Post Type
// **********************************************************************//

add_action('init', 'et_register_static_blocks');

if(!function_exists('et_register_static_blocks')) {
    function et_register_static_blocks() {
            $labels = array(
                'name' => _x( 'Static Blocks', 'post type general name', ETHEME_DOMAIN ),
                'singular_name' => _x( 'Block', 'post type singular name', ETHEME_DOMAIN ),
                'add_new' => _x( 'Add New', 'static block', ETHEME_DOMAIN ),
                'add_new_item' => sprintf( __( 'Add New %s', ETHEME_DOMAIN ), __( 'Static Blocks', ETHEME_DOMAIN ) ),
                'edit_item' => sprintf( __( 'Edit %s', ETHEME_DOMAIN ), __( 'Static Blocks', ETHEME_DOMAIN ) ),
                'new_item' => sprintf( __( 'New %s', ETHEME_DOMAIN ), __( 'Static Blocks', ETHEME_DOMAIN ) ),
                'all_items' => sprintf( __( 'All %s', ETHEME_DOMAIN ), __( 'Static Blocks', ETHEME_DOMAIN ) ),
                'view_item' => sprintf( __( 'View %s', ETHEME_DOMAIN ), __( 'Static Blocks', ETHEME_DOMAIN ) ),
                'search_items' => sprintf( __( 'Search %s', ETHEME_DOMAIN ), __( 'Static Blocks', ETHEME_DOMAIN ) ),
                'not_found' =>  sprintf( __( 'No %s Found', ETHEME_DOMAIN ), __( 'Static Blocks', ETHEME_DOMAIN ) ),
                'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', ETHEME_DOMAIN ), __( 'Static Blocks', ETHEME_DOMAIN ) ),
                'parent_item_colon' => '',
                'menu_name' => __( 'Static Blocks', ETHEME_DOMAIN )

            );
            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'staticblocks' ),
                'capability_type' => 'post',
                'has_archive' => 'staticblocks',
                'hierarchical' => false,
                'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
                'menu_position' => 8
            );
            register_post_type( 'staticblocks', $args );
    }
}

if(!function_exists('et_get_static_blocks')) {
    function et_get_static_blocks () {
        $return_array = array();
        $args = array( 'post_type' => 'staticblocks', 'posts_per_page' => 50);
        //if ( class_exists( 'bbPress') ) remove_action( 'set_current_user', 'bbp_setup_current_user' );
        $myposts = get_posts( $args );
        $i=0;
        foreach ( $myposts as $post ) {
            $i++;
            $return_array[$i]['label'] = get_the_title($post->ID);
            $return_array[$i]['value'] = $post->ID;
        }
        wp_reset_postdata();
        //if ( class_exists( 'bbPress') ) add_action( 'set_current_user', 'bbp_setup_current_user', 10 );
        return $return_array;
    }
}


if(!function_exists('et_show_block')) {
    function et_show_block ($id = false) {
        echo et_get_block($id);
    }
}

add_filter('et_the_content', 'wpautop', 10);
add_filter('et_the_content', 'do_shortcode', 11);

// **********************************************************************//
// ! New etheme_get_image function
// **********************************************************************//
if( ! function_exists( 'new_etheme_get_image' ) ) :
    function new_etheme_get_image( $attach_id, $size ) {
        if ( function_exists( 'wpb_getImageBySize' ) ) {
            $image = wpb_getImageBySize( array(
                    'attach_id' => $attach_id,
                    'thumb_size' => $size,
                ) );
            $image = $image['thumbnail'];
        } else {
            $image = wp_get_attachment_image( $attach_id, $size );
        }

        return $image;
    }
endif;

if(!function_exists('et_get_block')) {
    function et_get_block($id = false) {
        if(!$id) return;
        $args = array( 'include' => $id,'post_type' => 'staticblocks', 'posts_per_page' => 50);
        $output = '';
        $myposts = get_posts( $args );
        foreach ( $myposts as $post ) {
            setup_postdata($post);
            //$output = wpautop(do_shortcode(get_the_content($post->ID)));
            $output = apply_filters('et_the_content', get_the_content());
            $shortcodes_custom_css = get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
            if ( ! empty( $shortcodes_custom_css ) ) {
                $output .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
                $output .= $shortcodes_custom_css;
                $output .= '</style>';
            }
        }
        wp_reset_postdata();
        return $output;
   }
}
