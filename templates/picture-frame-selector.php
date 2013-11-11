<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $post, $woocommerce, $product;
?>

<section id="picture-frame-selector">
	
	<div id="picture-frame-selector-lead-in">
		<h3>Picture frames gallery</h3>
		<p><button class="modal-opener">Click here</button> to try your chosen map with different frames and mounts</p>
	</div>

	<article id="picture-frame-selector-modal">
		<header>
			<h1>Choose your frame and mount</h1>
		</header>

		<div id="picture-frame-selector-image">
			<!-- Product main thumbnail -->
			<?php
				if ( has_post_thumbnail() ) {

					$image_title 		= esc_attr( get_the_title( get_post_thumbnail_id() ) );
					$image_link  		= wp_get_attachment_url( get_post_thumbnail_id() );
					$image       		= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
						'title' => $image_title
						) );

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s"  rel="prettyPhoto">%s</a>', $image_link, $image_title, $image ), $post->ID );

				} else {

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" class="woocommerce-main-image zoom"/>', woocommerce_placeholder_img_src() ), $post->ID );

				}
			?>
			
			<!-- Frame overlays -->
			<?php
				$picture_frames = new WP_Query( 'post_type=picture_frames' );
				if( $picture_frames->have_posts() ) {
					while( $picture_frames->have_posts() ) {
						$picture_frames->the_post();
						$post_id = get_the_ID();
						$picture_frame_type = json_decode( get_post_meta($post_id, 'wpf_picture_frame_type', true) );
						
						if ( $picture_frame_type == 'frame' ) {
							?>
							<div class="overlay" id="frame_<?php echo $post_id; ?>">
								<?php
									if ( has_post_thumbnail() ) {
										echo the_post_thumbnail('medium');
									}
								?>
							</div>
							<?php
						}
					}
				}
				else {
					echo 'Uh oh, no mounts!';
				}
			?>
			
			<!-- Mount overlays -->


		</div><!-- /picture-frame-selector-image -->

		<div id="picture-frame-selector-lists">
			<h2>Picture Frames</h2>
			<ul id="picture-frames-list">
			<?php
				if( $picture_frames->have_posts() ) {
					while( $picture_frames->have_posts() ) {
						$picture_frames->the_post();
						$post_id = get_the_ID();
						$picture_frame_type = json_decode( get_post_meta($post_id, 'wpf_picture_frame_type', true) );
						
						if ( $picture_frame_type == 'frame' ) {
							?>
							<li>
								<?php
									if ( has_post_thumbnail() ) {
										echo '<a href="javascript:;" class="thumbnail-click" id="frame_click_' . $post_id . '">';
											the_post_thumbnail('thumbnail');
										echo '</a>';
									} 
									echo '<p class="frame-caption">' . get_the_title() . '</p>';
								?>
							</li>
							<?php
						}
					}
				}
				else {
					echo 'Uh oh, no picture frames!';
				}
			?>
			</ul>

			<h2>Mounts</h2>
			<ul id="mounts-list">
			<?php
				if( $picture_frames->have_posts() ) {
					while( $picture_frames->have_posts() ) {
						$picture_frames->the_post();
						$post_id = get_the_ID();
						$picture_frame_type = json_decode( get_post_meta($post_id, 'wpf_picture_frame_type', true) );
						
						if ( $picture_frame_type == 'mount' ) {
							?>
							<li>
								<?php
									if ( has_post_thumbnail() ) {
										echo '<a href="javascript:;">';
											the_post_thumbnail('thumbnail');
										echo '</a>';
									}
									echo '<p class="frame-caption">' . get_the_title() . '</p>';
								?>
							</li>
							<?php
						}
					}
				}
				else {
					echo 'Uh oh, no mounts!';
				}
			?>
			</ul>
		</div> <!-- /picture-frame-selector-lists -->
	</article>

</section>