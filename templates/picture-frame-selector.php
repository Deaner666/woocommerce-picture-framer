<div id="picture-frame-selector">
	<div id="picture-frame-selector-lead-in">
		<h3>Picture frames gallery</h3>
		<p><button class="modal-opener">Click here</button> to try your chosen map with different frames and mounts</p>
	</div>
	<div id="picture-frame-selector-modal">
		<h1>You did it!</h1>
		<p>Here we are - Picture framer!</p>

		<h2>Picture Frames</h2>
		<ul id="picture-frames-list">
		<?php
			$picture_frames = new WP_Query( 'post_type=picture_frames' );
			if( $picture_frames->have_posts() ) {
				while( $picture_frames->have_posts() ) {
					$picture_frames->the_post();
					$post_id = get_the_ID();
					$picture_frame_type = json_decode( get_post_meta($post_id, 'wpf_picture_frame_type', true) );
					
					if ( $picture_frame_type == 'frame' ) {
						?>
						<li>
							<?php
								if ( has_post_thumbnail() ) { the_post_thumbnail('thumbnail'); } 
								get_the_title();
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
								if ( has_post_thumbnail() ) { the_post_thumbnail('thumbnail'); } 
								get_the_title();
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
	</div>
</div>