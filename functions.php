<?php
/**
 * Enqueue script and styles for child theme
 */
function woodmart_child_enqueue_styles() {
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'woodmart-style' ), woodmart_get_theme_info( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 10010 );

if ( ! function_exists( 'woodmart_add_video_to_product_loop' ) ) {
	function woodmart_add_video_to_product_loop( $image, $product, $size, $attr ) {
		$video_url = get_post_meta( $product->get_id(), 'woodmart_wc_video_gallery', true );

		if ( $video_url && is_array( $video_url ) ) {
			$first_video = reset( $video_url );

			if ( is_array( $first_video ) && ! empty( $first_video['upload_video_url'] ) ) {
				$image = sprintf(
					'<div class="wd-product-video-wrapper">
							<video class="loop-product-video" autoplay muted loop playsinline>
							<source src="%s" type="video/mp4">
							</video>
						</div>',
					esc_url( $first_video['upload_video_url'] )
				);
			}
		}

		return $image;
	}

	add_filter( 'woodmart_get_product_thumbnail', 'woodmart_add_video_to_product_loop', 10, 5 );
}

if ( ! function_exists( 'woodmart_add_video_class_to_product_loop' ) ) {
	function woodmart_add_video_class_to_product_loop( $classes, $product ) {
		$product_id = $product->get_id();
		if ( 'product' === get_post_type( $product_id ) ) {
			$video_url = get_post_meta( $product_id, 'woodmart_wc_video_gallery', true );

			if ( $video_url ) {
				$classes[] = 'wd-product-has-video';
			}
		}

		return $classes;
	}

	add_filter( 'woocommerce_post_class', 'woodmart_add_video_class_to_product_loop', 10, 2 );
}
