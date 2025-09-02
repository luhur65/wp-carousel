<?php
/**
 * Mix content carousel video
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/mix-content-type/video.php
 *
 * @package WP_Carousel_Pro
 */

$video_type          = isset( $source['carousel_video_source_type'] ) ? $source['carousel_video_source_type'] : '';
$wpcp_video_id       = isset( $source['carousel_mix_video_source_id'] ) ? $source['carousel_mix_video_source_id'] : '';
$video_source_upload = isset( $source['carousel_video_source_upload'] ) ? $source['carousel_video_source_upload'] : '';
$video_source_thumb  = isset( $source['carousel_video_source_thumb'] ) ? $source['carousel_video_source_thumb'] : '';
$twitch_video_url    = '';
// Check if the video type is 'twitch'.
if ( 'twitch' === $video_type ) {
	// Sanitize and retrieve the server name (domain) for use in the Twitch URL.
	$wpcp_host = ! empty( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';

	// Retrieve Twitch ID type (e.g., 'channel' or 'video') or set to an empty string if not set.
	$twitch_id_type = isset( $source['twitch_id_type'] ) ? $source['twitch_id_type'] : '';

	// Retrieve Twitch video ID or set default value to 'video' if not set.
	$video_twitch_id = isset( $source['carousel_video_twitch_id'] ) ? $source['carousel_video_twitch_id'] : 'video';

	// Retrieve Twitch channel ID or set to an empty string if not set.
	$video_channel_id = isset( $source['carousel_video_channel_id'] ) ? $source['carousel_video_channel_id'] : '';

	// Construct the Twitch video URL based on the ID type.
	if ( 'channel' === $twitch_id_type && $video_channel_id ) {
		// If ID type is 'channel' and channel ID is available, construct the channel URL.
		$twitch_video_url = 'https://player.twitch.tv/?channel=' . $video_channel_id . '&parent=' . $wpcp_host;
	} else {
		// Otherwise, construct the video URL.
		$twitch_video_url = 'https://player.twitch.tv/?video=' . $video_twitch_id . '&parent=' . $wpcp_host;
	}
}

$sp_url = self::get_mix_video_thumb_url( $video_type, $wpcp_video_id, '', $video_source_thumb, $video_source_upload, $twitch_video_url );

wp_enqueue_script( 'wpcp-fancybox-popup' );
wp_enqueue_script( 'wpcp-fancybox-config' );
?>
	<div class="wpcp-single-item wcp-video-item">
		<div class="wpcp-slide-image">
		<?php
		if ( 'lightbox' === $video_play_mode ) {
			if ( 'wistia' === $sp_url['video_type'] || 'tiktok' === $sp_url['video_type'] || 'twitch' === $sp_url['video_type'] ) {
				?>
			<a class="wcp-video" data-fancybox="wpcp_view" data-type="iframe"  data-buttons='["<?php echo esc_attr( $l_box_zoom_button ); ?>","<?php echo esc_attr( $show_full_screen ); ?>","<?php echo esc_attr( $show_slideshow ); ?>","<?php echo esc_attr( $show_social_share ); ?>","<?php echo esc_attr( $show_download_button ); ?>","<?php echo esc_attr( $show_img_thumb ); ?>","<?php echo esc_attr( $l_box_close_button ); ?>"]'  href="<?php echo esc_url( $sp_url['video_url'] ); ?>"> 
				<?php
			} else {
				?>
			<a class="wcp-video" data-fancybox="wpcp_view"  data-buttons='["<?php echo esc_attr( $l_box_zoom_button ); ?>","<?php echo esc_attr( $show_full_screen ); ?>","<?php echo esc_attr( $show_slideshow ); ?>","<?php echo esc_attr( $show_social_share ); ?>","<?php echo esc_attr( $show_download_button ); ?>","<?php echo esc_attr( $show_img_thumb ); ?>","<?php echo esc_attr( $l_box_close_button ); ?>"]'  data-lightbox-gallery="group-<?php echo esc_attr( $post_id ); ?>" data-item-slug="<?php echo esc_attr( self::get_item_slug( $sp_url['video_url'] ) ); ?>" data-lightbox-gallery="group-<?php echo esc_attr( $post_id ); ?>" href="<?php echo esc_url( $sp_url['video_url'] ); ?>">
			<?php } ?>
				<img src="<?php echo esc_url( $sp_url['video_thumb_url'] ); ?>" alt="<?php echo esc_html( $sp_url['video_alt_text'] ); ?>"><i class="fa fa-play-circle-o" aria-hidden="true"></i>
			</a>
			<?php
		} else {
			?>
			<div class="wcp-video-iframe-wrapper">
				<div class="wcp-video-section wcp-video-inline-mode">
					<!-- Display Video Thumbnail -->
					<img src="<?php echo esc_url( $sp_url['video_thumb_url'] ); ?>" alt="<?php echo esc_html( $sp_url['video_alt_text'] ); ?>"><i class="fa fa-play-circle-o" aria-hidden="true"></i>
					<!-- Play Button Icon -->
					<button><i class="fa fa-play-circle-o" aria-hidden="true"></i></button>
					<?php if ( 'youtube' === $sp_url['video_type'] ) : ?>
						<!-- YouTube Video Embed -->
						<div class="wcp-video-iframe-wrapper wcp-inline-video-youtube" data-embed="<?php echo esc_attr( $sp_url['video_id'] ); ?>" data-fancybox data-src="<?php echo esc_url( $sp_url['video_url'] ); ?>" data-type="<?php echo esc_attr( $sp_url['video_type'] ); ?>" data-width="600" data-height="400">
						</div>

						<?php
					elseif ( 'vimeo' === $sp_url['video_type'] ) :
						$video_url = isset( $sp_url['video_url'] ) ? $sp_url['video_url'] : '';

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
						<div class="skip-lazy wcp-iframe wcp-lazy-load-video" data-embed="<?php echo esc_url( $sp_url['video_url'] ); ?>">
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
		?>
		</div>
		<div class="wpcp-single-content">
			<?php echo do_shortcode( wpautop( $wp_embed->autoembed( $mix_content_description ) ) ); ?>
		</div>
	</div>
