<?php
/**
 * Plugin Name: Show My Social Icons
 * Plugin URI: https://makingtheimpact.com
 * Description: Adds your social media icons to the main menu of the site and lets you place them anywhere using a shortcode.
 * Version: 1.0.68
 * Author: Making The Impact LLC
 * Author URI: https://makingtheimpact.com
 */

 $smsi_plugin_dir_path = plugin_dir_url(__FILE__);

 // List of social media platforms supported
 require_once plugin_dir_path(__FILE__) . 'includes/social-platforms-config.php';

 // Admin Settings page
 require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';

 // Creates setting options
 require_once plugin_dir_path(__FILE__) . 'includes/utilities.php';
 
 // Shortcodes to display the icons
 require_once plugin_dir_path(__FILE__) . 'public/shortcodes.php'; 

 // Styles and Scripts
function show_my_social_icons_enqueue_scripts() {
    wp_enqueue_style('my-plugin-styles', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('my-plugin-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'show_my_social_icons_enqueue_scripts');
add_action('admin_enqueue_scripts', 'show_my_social_icons_enqueue_scripts');