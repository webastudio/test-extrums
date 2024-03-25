<?php

if ( ! defined( '_S_VERSION' ) ) {
    // Replace the version number of the theme on each release.
    define( '_S_VERSION', '1.0.0' );
}

function test_extrums_scripts() {
    wp_enqueue_style( 'test-extrums-style', get_stylesheet_uri(), array(), _S_VERSION );
    wp_enqueue_style( 'test-extrums-bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css', array(), _S_VERSION );
    wp_enqueue_script( 'test-extrums-navigation-js', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', array(), _S_VERSION, true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'test_extrums_scripts' );

function test_extrums_function($atts = [])
{
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
    $wporg_atts = shortcode_atts(
        array(
            'id' => 0,
        ), $atts
    );
    ob_start();
    echo '<style>.test-extrums-shortcode {background: #f5f5f5}</style>';
    if(!is_null($wporg_atts['id'])) {
        $post_id = $wporg_atts['id'];
        $test_extrums_field = get_post_meta( $post_id, 'test_extrums_field', true );
        echo '<div class="test-extrums-shortcode p-3 mb-3">';
        echo '<strong class="text-center">I am is shortcode</strong>';
        echo '<h2>'. get_the_title($post_id) .'</h2>';
        if( has_excerpt( $post_id ) ) {
            echo '<p>'. get_the_excerpt( $post_id ) .'</p>';
        }

        if($test_extrums_field) {
            echo $test_extrums_field;
        }
        echo '</div>';
    }
    return ob_get_clean();
}
add_shortcode( 'show_post', 'test_extrums_function' );

add_action( 'add_meta_boxes', 'test_extrums_metabox' );

function test_extrums_metabox() {

    add_meta_box(
        'test-extrums-metabox',
        'Simple Post Box',
        'test_extrums_metabox_callback',
        'post',
        'normal',
        'default'
    );

}

function test_extrums_metabox_callback($post) {
    $test_extrums_field = get_post_meta( $post->ID, 'test_extrums_field', true );
    echo '
    <style>
    .form-table textarea {
        width: 100%;
        min-height: 100px;
    }
    </style>
    <table class="form-table">
		<tbody>
			<tr>
				<th><label for="test_extrums_field">Simple Field</label></th>
				<td>
				    <textarea name="test_extrums_field" id="test_extrums_field">' . esc_attr( $test_extrums_field ) . '</textarea>
				</td>			
			</tr>			
		</tbody>
	</table>';
}

add_action( 'save_post', 'test_extrums_save_meta', 10, 2 );

function test_extrums_save_meta( $post_id, $post ) {

    $post_type = get_post_type_object( $post->post_type );

    if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
        return $post_id;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
    }

    if( 'post' !== $post->post_type ) {
        return $post_id;
    }

    if( isset( $_POST[ 'test_extrums_field' ] ) ) {
        update_post_meta( $post_id, 'test_extrums_field', sanitize_text_field( $_POST[ 'test_extrums_field' ] ) );
    } else {
        delete_post_meta( $post_id, 'test_extrums_field' );
    }

    return $post_id;

}

function update_last_viewed(){
    if(is_admin() || !is_single()) return;

    $current_post_id = get_the_ID();

    if(is_user_logged_in()){

        $recenty_viewed = get_user_meta(get_current_user_id(), 'last_viewed', true);
        if( '' == $recenty_viewed ){
            $recenty_viewed = 0;
        }

        update_user_meta(get_current_user_id(), 'last_viewed', $current_post_id);

    }
}
add_action('wp_footer', 'update_last_viewed');
