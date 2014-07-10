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

	function wpf_activation() {
		// Activation code here
	}
	
	register_activation_hook(__FILE__, 'wpf_activation');

	//////////////////////////////////////////////////
	// 
	// Register scripts and styles
	// 
	//////////////////////////////////////////////////

	add_action('wp_enqueue_scripts', 'wpf_enqueue_scripts');

	function wpf_enqueue_scripts() {
		wp_enqueue_script( 'jquery-ui-core' );
	    wp_enqueue_script( 'jquery-ui-widget' );
	    wp_enqueue_script( 'jquery-ui-dialog' );
	    wp_enqueue_script( 'picture_framer_modal', plugins_url('/js/picture_framer_modal.js', __FILE__ ), array( 'jquery-ui-widget' ) );
	    wp_enqueue_style( 'picture_framer_modal', plugins_url('/css/picture_framer_modal.css', __FILE__ ) );
	    wp_enqueue_style( 'jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__ ) );
    }

	//////////////////////////////////////////////////
	// 
	// Picture Frames custom post type
	// 
	//////////////////////////////////////////////////

	add_action('init', 'wpf_register_picture_frames');

	function wpf_register_picture_frames() {
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
	       'show_in_nav_menus' => false,
	       'publicly_queryable' => false,
	       'exclude_from_search' => true,
	       'menu_position' => 62,
	       'has_archive' => false,
	       'query_var' => false,
	       'can_export' => true,
	       'rewrite' => false,
	       'capability_type' => 'post'
	    );

	    register_post_type('picture_frames', $args);
	}

	//////////////////////////////////////////////////
	// 
	// Picture Frames admin meta box
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
	    $picture_frame_sku = json_decode( get_post_meta($post->ID, "wpf_picture_frame_sku", true) );
	    // Use nonce for verification
	    echo '<input type="hidden" name="wpf_type_nonce" value="'. wp_create_nonce(basename(__FILE__)) . '" />';

	    ?>
	    <table class="form-table">
	    	<tbody>
	    		<tr>
	    			<th scope="row"><label for="wpf_picture_frame_type">Frame or mount?:</label></th>
	    			<td>
						<select id="wpf_picture_frame_type" name="wpf_picture_frame_type">
							<option value="frame" <?php echo $picture_frame_type == 'frame' ? ' selected="selected"' : ''; ?> >Frame</option>
							<option value="mount" <?php echo $picture_frame_type == 'mount' ? ' selected="selected"' : ''; ?> >Mount</option>
						</select>
						<p>Is this a picture frame or a mount?</p>
					</td>
				</tr>
				<tr>
	    			<th scope="row"><label for="wpf_picture_frame_sku">SKU:</label></th>
	    			<td>
						<input type="text" id="wpf_picture_frame_sku" name="wpf_picture_frame_sku" value="<?php echo $picture_frame_sku; ?>" />
					</td>
				</tr>
			</tbody>
		</table>
		<?php

	}

	//////////////////////////////////////////////////
	// 
	// Save Picture Frames meta fields
	// 
	//////////////////////////////////////////////////

	add_action('save_post', 'wpf_picture_frame_type_save');

	function wpf_picture_frame_type_save($post_id) {
		if (!isset($_POST['wpf_type_nonce']) || !wp_verify_nonce($_POST['wpf_type_nonce'], basename(__FILE__))) { return $post_id; }
		
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return $post_id; }

		if ('picture_frames' == $_POST['post_type'] && current_user_can('edit_post', $post_id)) {

			// Save picture_frame_type
			$picture_frame_type = (isset($_POST['wpf_picture_frame_type']) ? $_POST['wpf_picture_frame_type'] : 'frame');
			$picture_frame_type = strip_tags(json_encode($picture_frame_type));
			update_post_meta($post_id, "wpf_picture_frame_type", $picture_frame_type);

			// Save picture_Frame_sku
			$picture_frame_sku = (isset($_POST['wpf_picture_frame_sku']) ? $_POST['wpf_picture_frame_sku'] : '');
			$picture_frame_sku = strip_tags(json_encode($picture_frame_sku));
			update_post_meta($post_id, "wpf_picture_frame_sku", $picture_frame_sku);

    	} else {

			return $post_id;

		}
	}

	//////////////////////////////////////////////////
	// 
	// Hook into single-product.php to add
	// picture frame selector
	// 
	//////////////////////////////////////////////////

	add_action('woocommerce_after_single_product_summary', 'wpf_picture_frame_selector');

	function wpf_picture_frame_selector() {
		include_once 'templates/picture-frame-selector.php';
	}

	//////////////////////////////////////////////////
	// 
	// Hook into single-product.php to add
	// note about sizes
	// 
	//////////////////////////////////////////////////

	add_action('woocommerce_after_main_content', 'wpf_picture_frame_size_note');

	function wpf_picture_frame_size_note() {
		echo 'See detailed product description below for exact sizes.';
	}

	//////////////////////////////////////////////////
	// 
	// Hook into Gravity Forms gform_pre_render and dynamically
	// populate radio fields with picture frames and mounts
	// 
	//////////////////////////////////////////////////

	add_filter("gform_pre_render", "wpf_populate_frames");
	add_filter("gform_pre_render", "wpf_populate_mounts");

	// Note: when changing drop down values, we also need to use the gform_admin_pre_render so that the right values are displayed when editing the entry.
	add_filter("gform_admin_pre_render", "wpf_populate_frames");
	add_filter("gform_admin_pre_render", "wpf_populate_mounts");

	// Note: this will allow for the labels to be used during the submission process in case values are enabled
	add_filter('gform_pre_submission_filter', 'wpf_populate_frames');
	add_filter('gform_pre_submission_filter', 'wpf_populate_mounts');

	function wpf_populate_frames($form){

	    // only populating drop down for form id 5
	    // if($form["id"] != 5)
	    //    return $form;

	    // Pull in picture frames custom post type
	    $frame_type = json_encode('frame');
	    $args = array(
	    		'post_type' => 'picture_frames',
	    		'nopaging' => true,
		    	'meta_query' => array(
		    		array(
			    		'key' => 'wpf_picture_frame_type',
			    		'value' => $frame_type
		    		)
		    	)
		);
	    $posts = get_posts($args);

	    // Creating drop down item array.
	    $items = array();

	    // Adding initial blank value.
	    // $items[] = array("text" => "", "value" => "");

	    //Adding picture frame titles to the items array
	    foreach($posts as $post)
	        $items[] = array("value" => $post->post_title, "text" => $post->post_title);

	    //Adding items to picture frames field
	    foreach($form["fields"] as &$field)
	        if($field["id"] == 4){            
	            $field["choices"] = $items;
	        }

	    return $form;
	}

	function wpf_populate_mounts($form){

	    // only populating drop down for form id 5
	    // if($form["id"] != 5)
	    //    return $form;

	    // Pull in picture frames custom post type
	    $frame_type = json_encode('mount');
	    $args = array(
	    		'post_type' => 'picture_frames',
	    		'nopaging' => true,
		    	'meta_query' => array(
		    		array(
			    		'key' => 'wpf_picture_frame_type',
			    		'value' => $frame_type
		    		)
		    	)
		);
	    $posts = get_posts($args);

	    // Creating drop down item array.
	    $items = array();

	    // Adding initial blank value.
	    // $items[] = array("text" => "", "value" => "");

	    //Adding picture frame titles to the items array
	    foreach($posts as $post)
	        $items[] = array("value" => $post->post_title, "text" => $post->post_title);

	    //Adding items to picture frames field
	    foreach($form["fields"] as &$field)
	        if($field["id"] == 5){            
	            $field["choices"] = $items;
	        }

	    return $form;
	}

	//////////////////////////////////////////////////
	// 
	// Plugin deactivation
	// 
	//////////////////////////////////////////////////

	function wpf_deactivation() {
		// Deactivation code here
	}
	
	register_deactivation_hook(__FILE__, 'wpf_deactivation');

} // end Woocommerce detection

?>