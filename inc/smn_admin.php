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
	echo $post_id . $post_type;

    $post = get_post($post_id);
    $args = array(
        'element_id' => $post_id,
        'element_type' => $post_type,
    );
    $details = apply_filters('wpml_element_language_details', $post, $args);
    unset($details->post_content);
        echo '<pre>';
		print_r($details);
		echo '</pre>';


    // Obtenemos el ID original (puede devolver WP_Error)
    $original_id = apply_filters('wpml_original_element_id', $post_id, 'post_' . $post_type, true);

    if (is_wp_error($original_id)) {
        echo '<em>' . __('Error:', 'sumun') . '</em>';
		echo '<pre>';
		print_r($original_id);
		echo '</pre>';
	} elseif(empty($original_id)) {
        echo '<em>' . __('No definido', 'sumun') . '.</em>';
        return;
    }

    // Obtenemos los detalles del idioma (puede devolver WP_Error)
    $language_info = apply_filters('wpml_post_language_details', NULL, $original_id);

    if (is_wp_error($language_info) || empty($language_info) || !isset($language_info['language_code'])) {
        echo '<em>' . __('No definido', 'sumun') . '..</em>';
        return;
    }

    $code = $language_info['language_code'];
    $languages = apply_filters('wpml_active_languages', NULL, array('skip_missing' => 0));

    // Seguridad: comprobar que la lista de idiomas es válida
    if (is_wp_error($languages) || empty($languages)) {
        echo esc_html(strtoupper($code));
        return;
    }

    $name = isset($languages[$code]['translated_name'])
        ? $languages[$code]['translated_name']
        : strtoupper($code);

    echo esc_html($name);
}

// Opcional: hacer la columna ordenable
add_filter('manage_edit-post_sortable_columns', function($columns) {
    $columns['original_language'] = 'original_language';
    return $columns;
});
add_filter('manage_edit-page_sortable_columns', function($columns) {
    $columns['original_language'] = 'original_language';
    return $columns;
});
