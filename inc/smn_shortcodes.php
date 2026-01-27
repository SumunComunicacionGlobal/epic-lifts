<?php 
// Función para obtener descargas remotas
function get_remote_sdm_downloads_epic($atts) {
    // Extrae el atributo product_category del shortcode
    $atts = shortcode_atts(array(
        'product_category' => '13', // ID por defecto
        'domain' => 'epicpowerconverters.com'
    ), $atts, 'remote_downloads');

    $product_category = $atts['product_category'];
    $domain = $atts['domain'];

    // Asegura que el dominio tenga https:// si no contiene http o https
    if (strpos($domain, 'http://') !== 0 && strpos($domain, 'https://') !== 0) {
        $domain = 'https://' . $domain;
    }

    // URL de la API con el ID de la categoría
    $url = $domain . '/wp-json/wp/v2/sdm_downloads?product_category=' . $product_category . '&per_page=100';

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

add_shortcode( 'rank_math_breadcrumb', 'smn_wpseo_breadcrumb' );
function smn_wpseo_breadcrumb() {
    return wpautop( do_shortcode( '[wpseo_breadcrumb]' ) );
}

add_shortcode('categorized_downloads', function() {
    $terms = get_terms(array(
        'taxonomy' => 'dlm_download_category',
    ));

    if (is_wp_error($terms) || empty($terms)) {
        return 'No categories found.';
    }

    $output = '';
    foreach ($terms as $term) {
        $output .= '<h2>' . sprintf( __( 'Descargas %s', 'epic'), '<strong>' . esc_html($term->name) . '</strong>' ) . '</h2>';
        $output .= do_shortcode('[downloads category="' . esc_attr($term->slug) . '"]');
    }

    return $output;
});

add_shortcode( 'page_grid', 'smn_page_grid_shortcode' );
function smn_page_grid_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'posts_per_page' => -1,
        'post_type' => 'page',
        'post_parent' => 0,
    ), $atts, 'page_grid' );

    // If no post_parent is specified, use the current page ID
    if ( $atts['post_parent'] == 0 ) {
        $atts['post_parent'] = get_the_ID();
    }

    $args = array(
        'post_type'      => $atts['post_type'],
        'posts_per_page' => intval( $atts['posts_per_page'] ),
        'post_parent'    => intval( $atts['post_parent'] ),
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    );

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        return '';
    }


    $output = '<div class="wp-block-query smn-page-grid">';
    while ( $query->have_posts() ) {
        $query->the_post();
        global $post;
        $output .= '<div class="wp-block-post smn-page-grid-item">';
            if ( has_post_thumbnail() ) {
                $output .= '<div class="smn-page-grid-thumbnail"><a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), 'medium' ) . '</a></div>';
            }
            $output .= '<h2 class="smn-page-grid-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
            $excerpt = $post->post_excerpt;
            if ( $excerpt ) {
                $output .= '<div class="smn-page-grid-excerpt">' . wpautop( esc_html( $excerpt ) ) . '</div>';
            }
            $output .= '<div class="wp-block-group is-layout-flex">';
                $output .= '<a class="wp-block-read-more has-small-font-size" href="' . esc_url( apply_filters( 'wpml_permalink', get_permalink(), apply_filters( 'wpml_current_language', NULL ) ) ) . '">' . esc_html__( 'Continue reading', 'epic' ) . '</a> ';
                $output .= '<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">';
                    $output .= '<div class="wp-block-button is-style-outline">';
                        $output .= '<a class="wp-block-button__link" href="' . esc_url( apply_filters( 'wpml_permalink', get_permalink(19), apply_filters( 'wpml_current_language', NULL ) ) ) . '">' . esc_html__( 'Request info', 'epic' ) . '</a>';
                    $output .= '</div>';
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</div>';
    }
    $output .= '</div>';
    wp_reset_postdata();
    return $output;
}