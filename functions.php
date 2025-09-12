<?php
/**
 * Enqueue script and styles for child theme
 */
function woodmart_child_enqueue_styles() {
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'woodmart-style' ), woodmart_get_theme_info( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 10010 );


add_filter( 'woodmart_enqueue_bootstrap_style', '__return_true' );



if ( ! function_exists( 'woodmart_product_loop_video_metabox' ) ) {
	function woodmart_product_loop_video_metabox() {
		$woodmart_prefix = 'woodmart_';

		$metabox = \XTS\Admin\Modules\Options\Metaboxes::add_metabox(
			array(
				'id'         => 'xts_archive_video_metabox',
				'title'      => esc_html__( 'Woodmart product loop video', 'woodmart' ),
				'post_types' => array( 'product' ),
				'priority'   => 'high',
				'position'   => 'after',
			)
		);

		$metabox->add_section(
			array(
				'id'       => 'archive_video_section',
				'name'     => esc_html__( 'Local video', 'woodmart' ),
				'icon'     => 'xts-i-video',
				'priority' => 10,
			)
		);

		$metabox->add_field(
			array(
				'id'          => $woodmart_prefix . 'archive_product_video',
				'type'        => 'upload',
				'name'        => esc_html__( 'Local video (MP4)', 'woodmart' ),
				'description' => esc_html__( 'Upload MP4 video file for product archive page', 'woodmart' ),
				'section'     => 'archive_video_section',
				'priority'    => 10,
				'mime_types'  => array( 'video/mp4' ),
			)
		);
	}

	add_action( 'init', 'woodmart_product_loop_video_metabox', 1 );
}

if ( ! function_exists( 'woodmart_add_video_to_product_loop' ) ) {
	function woodmart_add_video_to_product_loop( $image, $product, $size, $attr ) {
		$video_url = get_post_meta( $product->get_id(), 'woodmart_archive_product_video', true );

		if ( $video_url ) {
			$image = sprintf(
				'<div class="wd-product-video-wrapper">
						<video class="loop-product-video" autoplay muted loop playsinline>
						<source src="%s" type="video/mp4">
						</video>
					</div>',
				esc_url( isset( $video_url['url'] ) ? $video_url['url'] : '' )
			);
		}

		return $image;
	}

	add_filter( 'woodmart_get_product_thumbnail', 'woodmart_add_video_to_product_loop', 10, 5 );
}

if ( ! function_exists( 'woodmart_add_video_class_to_product_loop' ) ) {
	function woodmart_add_video_class_to_product_loop( $classes, $product ) {
		if ( 'product' === get_post_type( $product->get_id() ) ) {
			$video_url = get_post_meta( $product->get_id(), 'woodmart_archive_product_video', true );

			if ( $video_url ) {
				$classes[] = 'wd-product-has-video';
			}
		}

		return $classes;
	}

	add_filter( 'woocommerce_post_class', 'woodmart_add_video_class_to_product_loop', 10, 2 );
}
