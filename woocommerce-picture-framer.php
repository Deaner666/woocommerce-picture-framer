<?php

/**
 * Plugin Name: Woocommerce Picture Framer
 * Plugin URI: https://github.com/Deaner666/woocommerce-picture-framer
 * Description: Plugin for automatically adding picture frame and mount designs to Woocommerce product images and thumbnails.
 * Version: 0.0.1
 * Author: Dave Dean
 * Author URI: http://www.moortor-design.co.uk
 * License: GPL2
 */

/*  Copyright 2013  Dave Dean  (email : dave@moortor-design.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Is Woocommerce installed?
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	//////////////////////////////////////////////////
	// 
	// Plugin activation
	// 
	//////////////////////////////////////////////////

	function wc_picture_framer_activation() {
		// Activation code here
	}
	
	register_activation_hook(__FILE__, 'wc_picture_framer_activation');

	//////////////////////////////////////////////////
	// 
	// Picture Frames Custom Post Type
	// 
	//////////////////////////////////////////////////

	add_action('init', 'wpf_register_picture_frame');

	function wpf_register_picture_frame() {
		$labels = array(
			'name'               => 'Picture Frames',
			'singular_name'      => 'Picture Frame',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Picture Frame',
			'edit_item'          => 'Edit Picture Frame',
			'new_item'           => 'New Picture Frame',
			'all_items'          => 'All Picture Frames',
			'view_item'          => 'View Picture Frame',
			'search_items'       => 'Search Picture Frames',
			'not_found'          => 'No picture frames found',
			'not_found_in_trash' => 'No picture frames found in Trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Picture Frames'
		);

	    $args = array(
	       'labels' => $labels,
	       'hierarchical' => false,
	       'description' => 'Picture Frames and mounts for product images and thumbnails',
	       'supports' => array('title', 'editor', 'thumbnail'),
	       'public' => true,
	       'show_ui' => true,
	       'show_in_menu' => true,
	       'show_in_nav_menus' => true,
	       'publicly_queryable' => true,
	       'exclude_from_search' => false,
	       'menu_position' => 63,
	       'has_archive' => true,
	       'query_var' => true,
	       'can_export' => true,
	       'rewrite' => true,
	       'capability_type' => 'post'
	    );

	    register_post_type('picture_frames', $args);
	}

	//////////////////////////////////////////////////
	// 
	// Picture Frames Custom Post Meta Fields
	// 
	//////////////////////////////////////////////////

	add_action('add_meta_boxes', 'wpf_picture_frame_meta');

	function wpf_picture_frame_meta() {
		// add_meta_box('wpf_picture_frame_image', 'Picture Frame Image', 'wpf_picture_frame_image_meta', 'picture_frames', 'side', 'default');
		add_meta_box('wpf_picture_frame_type', 'Picture Frame Type', 'wpf_picture_frame_type_meta', 'picture_frames', 'side', 'default');
	}

	// Picture Frame Type select
	function wpf_picture_frame_type_meta() {
		global $post;

	    $picture_frame_type = get_post_meta($post->ID, "wpf_picture_frame_type", true);
	    $picture_frame_type = ($picture_frame_type != '') ? json_decode($picture_frame_type) : 'frame';
	    // Use nonce for verification
	    echo '<input type="hidden" name="wpf_type_nonce" value="'. wp_create_nonce(basename(__FILE__)) . '" />';

	    ?>
	    <table class="form-table">
	    	<tbody>
	    		<tr>
	    			<th scope="row"><label for="wpf_picture_frame_type">Picture Frame Type:</label></th>
	    			<td>
						<select id="wpf_picture_frame_type" name="wpf_picture_frame_type">
							<option value="frame" <?php echo $picture_frame_type == 'frame' ? ' selected="selected"' : ''; ?> >Frame</option>
							<option value="mount" <?php echo $picture_frame_type == 'mount' ? ' selected="selected"' : ''; ?> >Mount</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<p><?php echo $picture_frame_type ; ?></p>
		<?php

	}

	//////////////////////////////////////////////////
	// 
	// Save Picture Frames Custom Post Meta Fields
	// 
	//////////////////////////////////////////////////

	add_action('save_post', 'wpf_picture_frame_type_save');

	function wpf_picture_frame_type_save($post_id) {
		if (!isset($_POST['wpf_type_nonce']) || !wp_verify_nonce($_POST['wpf_type_nonce'], basename(__FILE__))) { return $post_id; }
		
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return $post_id; }

		if ('picture_frames' == $_POST['post_type'] && current_user_can('edit_post', $post_id)) {

			$picture_frame_type = (isset($_POST['wpf_picture_frame_type']) ? $_POST['wpf_picture_frame_type'] : 'frame');

			$picture_frame_type = strip_tags(json_encode($picture_frame_type));

			update_post_meta($post_id, "wpf_picture_frame_type", $picture_frame_type);

    	} else {

			return $post_id;

		}
	}


	//////////////////////////////////////////////////
	// 
	// Plugin deactivation
	// 
	//////////////////////////////////////////////////

	function wc_picture_framer_deactivation() {
		// Deactivation code here
	}
	
	register_deactivation_hook(__FILE__, 'wc_picture_framer_deactivation');

} // end Woocommerce detection

?>