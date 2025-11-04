<?php

add_filter('manage_posts_columns', 'sumun_add_original_language_column');
add_filter('manage_pages_columns', 'sumun_add_original_language_column');
function sumun_add_original_language_column($columns) {
    $columns['original_language'] = __('Idioma original', 'sumun');
    return $columns;
}

add_action('manage_posts_custom_column', 'sumun_show_original_language_column', 10, 2);
add_action('manage_pages_custom_column', 'sumun_show_original_language_column', 10, 2);
function sumun_show_original_language_column($column_name, $post_id) {
    if ($column_name !== 'original_language') {
        return;
    }
	
	$post_type = get_post_type( $post_id );

    $args = array(
        'element_id' => $post_id,
        'element_type' => $post_type,
    );
    $details = apply_filters('wpml_element_language_details', NULL, $args);
    if ( isset( $details->source_language_code ) && !empty( $details->source_language_code ) ) {
        $code = $details->source_language_code;
        echo '<strong>' . esc_html( strtoupper( $code ) ) . '</strong>';
    }

}