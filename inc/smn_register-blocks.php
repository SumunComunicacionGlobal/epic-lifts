<?php
/**
 * Register ACF Blocks
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @return void
 */


function smn_register_blocks() {
    register_block_type( get_stylesheet_directory() . '/custom-blocks/marquee' );
   
    register_block_type( get_stylesheet_directory() . '/custom-blocks/carousel', [
        'render_callback' => ['Carousel_Slider_Block', 'render_carousel']
    ]);
    register_block_type( get_stylesheet_directory() . '/custom-blocks/slide' );
}

add_action( 'init', 'smn_register_blocks' );

/**
 * Register custom blocks script
 */
function smn_register_block_script() {
    wp_register_script( 'marquee-js', get_template_directory_uri() . '/custom-blocks/marquee/marquee.js', [ 'jquery', 'acf' ] );
    
}
 add_action( 'init', 'smn_register_block_script' );

 add_filter( 'render_block', 'smn_render_block', 10, 2 );
function smn_render_block( $block_content, $block ) {
	
	if ( $block['blockName'] === 'core/post-excerpt' ) {
		
		global $post;
		$excerpt = $post->post_excerpt;

		if ( 'caso-de-exito' == $post->post_type ) {
			
			if ( !$post->post_excerpt ) return '';
			
			$array = explode( PHP_EOL, $post->post_excerpt );
			$r = '<ul class="wp-block-list is-style-separator-list excerpt-list">';
			foreach ( $array as $i ) {
				$r .= '<li>' . $i . '</li>';
			}
			$r .= '</ul>';
		
			$excerpt = $r;
			
			$block_content = '<div class="wp-block-post-excerpt__excerpt">';
				$block_content .= $excerpt;
			$block_content .= '</div>';
		}

	}

	return $block_content;
	
}
