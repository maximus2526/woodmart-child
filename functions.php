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
				$video = sprintf(
					'<div class="wd-product-video-wrapper">
							<video autoplay muted loop playsinline>
							<source src="%s" type="video/mp4">
							</video>
						</div>',
					esc_url( $first_video['upload_video_url'] )
				);
			}
			$image = $image . $video;
		}

		return $image;
	}

	add_filter( 'woodmart_get_product_thumbnail', 'woodmart_add_video_to_product_loop', 10, 5 );
}

if ( ! function_exists( 'woodmart_hover_image' ) ) {
	function woodmart_hover_image() {
		global $product;

		$video_url = get_post_meta( $product->get_id(), 'woodmart_wc_video_gallery', true );

		if ( $video_url && is_array( $video_url ) ) {
			$first_video = reset( $video_url );
			if ( is_array( $first_video ) && ! empty( $first_video['upload_video_url'] ) ) {
				return;
			}
		}

		$attachment_ids = $product->get_gallery_image_ids();

		$hover_image = '';

		foreach ( $attachment_ids as $attachment_id ) {
			if ( ! empty( $attachment_id ) ) {
				$hover_image = woodmart_get_product_thumbnail( 'woocommerce_thumbnail', $attachment_id );

				$attachment_url = wp_get_attachment_url( $attachment_id );
				if ( $attachment_url && 'mp4' === strtolower( pathinfo( $attachment_url, PATHINFO_EXTENSION ) ) ) {
					return;
				}
			}
		}

		if ( $hover_image != '' && woodmart_get_opt( 'hover_image' ) ) :
			?>
			<div class="hover-img">
				<?php echo woodmart_get_product_thumbnail( 'woocommerce_thumbnail', $attachment_ids[0] ); ?>
			</div>
			<?php
		endif;
	}
}
