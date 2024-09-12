<?php
/**
 * Plugin Name: Show My Social Icons
 * Plugin URI: https://makingtheimpact.com
 * Description: Adds your social media icons to the main menu of the site and lets you place them anywhere using a shortcode.
 * Version: 1.0.71
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Tested up to: 6.0
 * Stable tag: 1.0.71
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: show-my-social-icons
 * Domain Path: /languages
 * Author: Making The Impact LLC
 * Author URI: https://makingtheimpact.com
 *
 * @package ShowMySocialIcons
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

$smsi_plugin_dir_path = plugin_dir_url(__FILE__);

/**
 * Require necessary files
 */
require_once plugin_dir_path(__FILE__) . 'includes/social-platforms-config.php';
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities.php';
require_once plugin_dir_path(__FILE__) . 'public/shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-smsi-all-icons-widget.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-smsi-single-icon-widget.php';



/**
 * Enqueue scripts and styles for the public-facing side of the site.
 *
 * @return void
 */
function smsi_enqueue_scripts() {
    wp_enqueue_style('smsi-styles', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('smsi-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'smsi_enqueue_scripts');

/**
 * Enqueue scripts and styles for the admin area.
 *
 * @param string $hook The current admin page.
 * @return void
 */
function smsi_admin_enqueue_scripts($hook) {
    if ('toplevel_page_show_my_social_icons' !== $hook) {
        return;
    }

    wp_enqueue_style('smsi-admin-styles', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css', array(), '1.0.0');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('smsi-admin-script', plugin_dir_url(__FILE__) . 'assets/js/admin-script.js', array('jquery', 'jquery-ui-sortable'), '1.0.0', true);

    wp_localize_script('smsi-admin-script', 'smsiData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('smsi_nonce')
    ));

    wp_enqueue_script(
        'smsi-block-editor',
        plugin_dir_url(__FILE__) . 'build/index.js',
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor'),
        filemtime(plugin_dir_path(__FILE__) . 'build/index.js')
    );

    wp_localize_script('smsi-block-editor', 'smsiPlatforms', array(
        'platforms' => my_social_media_platforms()
    ));
}
add_action('admin_enqueue_scripts', 'smsi_admin_enqueue_scripts');

/**
 * Register the Gutenberg block for social icons.
 *
 * @return void
 */
function smsi_register_block() {
    wp_register_script(
        'smsi-block-editor',
        plugin_dir_url(__FILE__) . 'build/index.js',
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor'),
        filemtime(plugin_dir_path(__FILE__) . 'build/index.js'),
        true
    );

    wp_register_style(
        'smsi-block-editor',
        plugin_dir_url(__FILE__) . 'assets/css/admin-style.css',
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'assets/css/admin-style.css')
    );

    register_block_type('show-my-social-icons/all-icons', array(
        'editor_script' => 'smsi-block-editor',
        'editor_style' => 'smsi-block-editor',
        'render_callback' => 'smsi_render_all_icons_block'
    ));

    register_block_type('show-my-social-icons/single-icon', array(
        'editor_script' => 'smsi-block-editor',
        'editor_style' => 'smsi-block-editor',
        'render_callback' => 'smsi_render_single_icon_block'
    ));

    if (function_exists('wp_set_script_translations')) {
        wp_set_script_translations('smsi-block-editor', 'show-my-social-icons');
    }
}
add_action('init', 'smsi_register_block');

/**
 * Register the widget for social icons.
 *
 * @return void
 */
function smsi_register_widgets() {
    register_widget('SMSI_All_Icons_Widget');
    register_widget('SMSI_Single_Icon_Widget');
}
add_action('widgets_init', 'smsi_register_widgets');

function smsi_render_all_icons_block($attributes) {
    $icon_type = isset($attributes['iconType']) ? $attributes['iconType'] : 'PNG';
    $icon_size = isset($attributes['iconSize']) ? $attributes['iconSize'] : '30px';
    $icon_style = isset($attributes['iconStyle']) ? $attributes['iconStyle'] : 'Icon only full color';
    $icon_alignment = isset($attributes['iconAlignment']) ? $attributes['iconAlignment'] : 'Center';

    return do_shortcode("[show_my_social_icons type=\"$icon_type\" size=\"$icon_size\" style=\"$icon_style\" alignment=\"$icon_alignment\"]");
}

function smsi_render_single_icon_block($attributes) {
    $platform = isset($attributes['platform']) ? $attributes['platform'] : '';
    $icon_type = isset($attributes['iconType']) ? $attributes['iconType'] : 'PNG';
    $icon_size = isset($attributes['iconSize']) ? $attributes['iconSize'] : '30px';
    $icon_style = isset($attributes['iconStyle']) ? $attributes['iconStyle'] : 'Icon only full color';
    $icon_alignment = isset($attributes['iconAlignment']) ? $attributes['iconAlignment'] : 'Center';

    return do_shortcode("[my_social_icon platform=\"$platform\" type=\"$icon_type\" size=\"$icon_size\" style=\"$icon_style\" alignment=\"$icon_alignment\"]");
}

function smsi_enqueue_block_editor_assets() {
    $asset_file = include(plugin_dir_path(__FILE__) . 'blocks/index.asset.php');

    wp_enqueue_script(
        'smsi-block-editor',
        plugins_url('build/index.js', __FILE__),
        $asset_file['dependencies'],
        $asset_file['version'],
        true
    );
    wp_script_add_data('smsi-block-editor', 'type', 'module');
}
add_action('enqueue_block_editor_assets', 'smsi_enqueue_block_editor_assets');

function smsi_register_rest_route() {
    register_rest_route('smsi/v1', '/platforms', array(
        'methods' => 'GET',
        'callback' => 'smsi_get_platforms',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'smsi_register_rest_route');
    $icon_type = isset($attributes['iconType']) ? $attributes['iconType'] : 'PNG';
function smsi_get_platforms() {
    return my_social_media_platforms();
}