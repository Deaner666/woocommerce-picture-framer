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
	// Admin Menu Page
	// 
	//////////////////////////////////////////////////

	add_action( 'admin_menu', 'wc_picture_framer_menus' );

	function wc_picture_framer_menus() {
		add_menu_page('All Picture Frames', 'Picture Frames', 'manage_options', 'wc_picture_framer', 'wc_picture_framer_index');
		add_submenu_page('wc_picture_framer', 'Add New Picture Frame', 'Add New', 'manage_options', 'wc_picture_framer_add', 'wc_picture_framer_add');
	}

	function wc_picture_framer_index() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>Here is where the form would go if I actually had options.</p>';
		echo '</div>';
	}

	function wc_picture_framer_add() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>Here is where the form would go if I actually had options.</p>';
		echo '</div>';
	}

} // end Woocommerce detection

?>