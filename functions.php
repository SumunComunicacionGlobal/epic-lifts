<?php
/**
 * epic functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package epic
 */

// Assign existing 'dlm_download_category' taxonomy to 'caso-de-exito' custom post type
function assign_dlm_download_category_to_caso_de_exito() {
	register_taxonomy_for_object_type('dlm_download_category', 'caso-de-exito');
}
add_action('init', 'assign_dlm_download_category_to_caso_de_exito');

// Show 'dlm_download_category' column in 'caso-de-exito' admin list
add_filter('manage_caso-de-exito_posts_columns', function($columns) {
	$columns['dlm_download_category'] = __('Category', 'epic');
	return $columns;
});

add_action('manage_caso-de-exito_posts_custom_column', function($column, $post_id) {
	if ($column === 'dlm_download_category') {
		$terms = get_the_terms($post_id, 'dlm_download_category');
		if (!empty($terms) && !is_wp_error($terms)) {
			$term_names = wp_list_pluck($terms, 'name');
			echo esc_html(implode(', ', $term_names));
		} else {
			echo '&mdash;';
		}
	}
}, 10, 2);

if ( ! function_exists( 'smn_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 *
	 * @return void
	 */
	function smn_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

		// Add support for excerpts in pages.
		add_post_type_support( 'page', 'excerpt' );
	}

endif;

add_action( 'after_setup_theme', 'smn_support' );

add_filter( 'wpml_tm_job_field_is_translatable', '__return_true' );

if ( ! function_exists( 'smn_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @return void
	 */
	function smn_styles() {
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get( 'Version' );

		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style(
			'smn-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'smn-style' );
	}

endif;

add_action( 'wp_enqueue_scripts', 'smn_styles' );

// Enqueue scripts
require get_template_directory() . '/inc/smn_enqueue-scripts.php';

// Hooks to add content above the navigation block
require get_template_directory() . '/inc/smn_nav.php';

// Register blocks
require get_template_directory() . '/inc/smn_register-blocks.php';

// Shortcodes
require get_template_directory() . '/inc/smn_shortcodes.php';

// Admin customizations
require get_template_directory() . '/inc/smn_admin.php';


/* Quitar <p> y <br/> de Contact Form 7 */
add_filter('wpcf7_autop_or_not', '__return_false');


/**
 * Obtiene las clases de términos de taxonomía.
 *
 * @param string|array $class Una clase o un array de clases adicionales.
 * @param int $term_id El ID del término.
 * @param string $taxonomy El nombre de la taxonomía.
 * @return array Array de clases.
 */
function get_term_class($class, $term_id = 0, $taxonomy = '') {
    $classes = is_array($class) ? $class : explode(' ', $class);
    $term_id = (int) $term_id;

    if (!$term_id) {
        $term = get_queried_object();
        if ($term instanceof WP_Term && !is_wp_error($term)) {
            $term_id = $term->term_id;
            $taxonomy = $term->taxonomy;
        }
    }

    $term = get_term($term_id, $taxonomy);

    if ($term instanceof WP_Term && !is_wp_error($term)) {
        $classes[] = $term->taxonomy . '-' . $term->slug;
    }

    return $classes;
}
