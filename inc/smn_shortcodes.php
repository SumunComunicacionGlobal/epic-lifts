<?php 
// Función para obtener descargas remotas
function get_remote_sdm_downloads_epic($atts) {
    // Extrae el atributo product_category del shortcode
    $atts = shortcode_atts(array(
        'product_category' => '13', // ID por defecto
    ), $atts, 'remote_downloads');

    $product_category = $atts['product_category'];

    // URL de la API con el ID de la categoría
    $url = 'https://epicpowerconverters.com/wp-json/wp/v2/sdm_downloads?product_category=' . $product_category . '&per_page=100';

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return 'Error fetching data';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    if (!empty($data)) {
        $output = '<ul class="wp-block-list is-style-separator-list is-style-download-list">';
        foreach ($data as $item) {
            $output .= '<li class="downloads-list">';
            $output .= '<a href="' . $item->link . '">' . $item->title->rendered . '</a>';
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    return 'No products found';
}

// Shortcode para mostrar las descargas
function display_remote_sdm_downloads_epic($atts) {
    return get_remote_sdm_downloads_epic($atts);
}
add_shortcode('remote_downloads', 'display_remote_sdm_downloads_epic');