<?php
/**
 * Video thumb
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/video-type/image.php
 *
 * @package WP_Carousel_Pro
 */

?>
<div class="wpcp-slide-image">
<?php
if ( filter_var( $image_src, FILTER_VALIDATE_URL ) ) {
	if ( $thumbnail_slider ) {
		?>
		<img src="<?php echo esc_url( $image_src ); ?>"  width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
		<?php
	} elseif ( 'lightbox' === $video_play_mode && ( 'wistia' === $sp_url['video_type'] || 'tiktok' === $sp_url['video_type'] || 'twitch' === $sp_url['video_type'] ) ) {
		?>
		<a class="wcp-video" data-fancybox="wpcp_view" data-type="iframe" data-buttons='["zoom","slideShow","fullScreen","share","download","thumbs","close"]' href="<?php echo esc_url( $video_url ); ?>">
		<?php if ( 'false' !== $lazy_load_image && 'ticker' !== $carousel_mode ) { ?>
			<img loading="lazy" class="wcp-lazy swiper-lazy" data-src="<?php echo esc_url( $image_src ); ?>" src="<?php echo esc_url( $image_src ); ?>" data-item-slug="<?php echo esc_attr( self::get_item_slug( $video_url ) ); ?>" width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
		<?php } else { ?>
			<img src="<?php echo esc_url( $image_src ); ?>" data-item-slug="<?php echo esc_attr( self::get_item_slug( $video_url ) ); ?>" width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
		<?php } ?>
			<i class="fa fa-play-circle-o" aria-hidden="true"></i>
		</a>
			<?php
	} else {
		if ( ! $image_width_attr && $image_src ) {
			$image_attr        = @getimagesize( $image_src );
			$image_width_attr  = isset( $image_attr[0] ) ? $image_attr[0] : $image_width_attr;
			$image_height_attr = isset( $image_attr[1] ) ? $image_attr[1] : $image_height_attr;
		}
		if ( 'lightbox' === $video_play_mode ) {
			?>
			<a class="wcp-video" data-fancybox="wpcp_view" data-buttons='["zoom","slideShow","fullScreen","share","download","thumbs","close"]' href="<?php echo esc_url( $video_url ); ?>" data-item-slug="<?php echo esc_attr( self::get_item_slug( $video_url ) ); ?>">
			<?php if ( 'false' !== $lazy_load_image && 'ticker' !== $carousel_mode ) { ?>
				<img loading="lazy" class="wcp-lazy swiper-lazy" data-src="<?php echo esc_url( $image_src ); ?>" src="<?php echo esc_url( $image_src ); ?>" width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
			<?php } else { ?>
				<img src="<?php echo esc_url( $image_src ); ?>" width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
			<?php } if ( isset( $sp_url['video_url'] ) && ! empty( $sp_url['video_url'] ) ) { ?>
				<i class="fa fa-play-circle-o" aria-hidden="true"></i>
			<?php } ?>
			</a>
			<?php
		} else { // Inline mode.
			?>
			<div class="wcp-video-iframe-wrapper">
				<div class="wcp-video-section wcp-video-inline-mode">
					<!-- Display Video Thumbnail -->
					<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_html( $video_thumb_alt_text ); ?>" width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>">
					<!-- Play Button Icon -->
					<button><i class="fa fa-play-circle-o" aria-hidden="true"></i></button>
					<?php if ( 'youtube' === $sp_url['video_type'] ) : ?>
					<!-- YouTube Video Embed -->
					<div class="wcp-video-iframe-wrapper wcp-inline-video-youtube" data-embed="<?php echo esc_attr( $sp_url['video_id'] ); ?>" data-fancybox data-src="<?php echo esc_url( $video_url ); ?>" data-type="<?php echo esc_attr( $sp_url['video_type'] ); ?>" data-width="600" data-height="400">
						</div>

						<?php
					elseif ( 'vimeo' === $sp_url['video_type'] ) :
						// Extract the video ID.
						$query_position = strpos( $video_url, '?' );
						$base_url       = $query_position ? substr( $video_url, 0, $query_position ) : $video_url;
						$video_id       = substr( $base_url, strrpos( $base_url, '/' ) + 1 );

						// Retain query parameters if present (e.g., autoplay=1&muted=1).
						$query_string = $query_position ? substr( $video_url, $query_position ) : '';
						$query_string = str_replace( 'muted=1', 'muted=0', $query_string );
						// Update the URL to embed format.
						$sp_url['video_url'] = 'https://player.vimeo.com/video/' . $video_id . $query_string;
						?>
						<!-- Vimeo Video Embed -->
					<div class="skip-lazy wcp-iframe wcp-lazy-load-video" data-embed="<?php echo esc_attr( $sp_url['video_url'] ); ?>" allowfullscreen>
					</div>

						<?php
					elseif ( isset( $sp_url['video_url'] ) && ! empty( $sp_url['video_url'] ) ) :
						$video_url = isset( $sp_url['video_url'] ) ? $sp_url['video_url'] : '';

						// Check if the URL is a DailyMotion video URL.
						if ( strpos( $video_url, 'dailymotion.com/video/' ) !== false ) {
							// Extract the video ID.
							$video_id = substr( $video_url, strrpos( $video_url, '/' ) + 1 );
							// Update the URL to embed format.
							$sp_url['video_url'] = 'https://www.dailymotion.com/embed/video/' . $video_id;
						}
						?>
						<!-- Custom Video Embed -->
					<div class="skip-lazy wcp-iframe wcp-lazy-load-video" data-embed="<?php echo esc_attr( $sp_url['video_url'] ); ?>"></div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
	}
}
?>
</div>
