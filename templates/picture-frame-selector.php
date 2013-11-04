<div id="picture-frame-selector">
	<div id="picture-frame-selector-lead-in">
		<h3>Picture frames gallery</h3>
		<p><button class="modal-opener">Click here</button> to try your chosen map with different frames and mounts</p>
	</div>
	<div id="picture-frame-selector-modal">
		<h1>You did it!</h1>
		<p>Here we are - Picture framer!</p>

		<ul class="picture-frames-list">
		<?php
			// Picture Frames & Mounts Query
			$args = array(
				'post_type' => 'picture_frames',
				'meta_query' => array(
					array(
						'key' => 'wpf_picture_frame_type',
						'value' => 'frame',
						'type' => 'CHAR',
						'compare' => '='
					)
				)
			);
			$picture_frames = new WP_Query( $args );
			// $picture_frames = new WP_Query( 'post_type=picture_frames' );

			// The Picture Frames Loop
			while ( $picture_frames->have_posts() ) {
				$picture_frames->the_post();
				if ( get_post_meta( get_post_ID(), 'wpf_picture_frame_type', true ) == 'frame' ) {
					echo '<li>' . get_the_title() . '</li>';
				}
			}
			wp_reset_postdata();
		?>
		</ul>
		<ul class="mounts-list">
		<?php
			// The Mounts Query
			$args2 = array(
				'post_type' => 'picture_frames',
				'meta_query' => array(
					array(
						'key' => 'wpf_picture_frame_type',
						'value' => 'mount',
						'type' => 'CHAR',
						'compare' => '='
					)
				)
			);
			$mounts = new WP_Query( $args2 );

			// The Mounts Loop
			while( $mounts->have_posts() ) {
				$mounts->the_post();
				if ( get_post_meta( get_post_ID(), 'wpf_picture_frame_type', true ) == 'mount' ) {
					echo '<li>' . get_the_title() . '</li>';
				}
			}
			wp_reset_postdata();
		?>
		</ul>
	</div>
</div>