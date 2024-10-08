<?php
/**
 * Plugin Name: Show My Social Icons
 * Plugin URI: https://makingtheimpact.com
 * Description: Display customizable social media icons anywhere on your WordPress site using shortcodes, widgets, Gutenberg blocks, or in the main menu.
 * Version: 1.0.73
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Tested up to: 6.6.2
 * Stable tag: 1.0.73
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

/**
 * Require necessary files
 */
require_once plugin_dir_path(__FILE__) . 'includes/social-platforms-config.php';
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities.php';
require_once plugin_dir_path(__FILE__) . 'public/shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-smsi-all-icons-widget.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-smsi-single-icon-widget.php';

define('SMSI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SMSI_PLUGIN_FILE', __FILE__);

$smsi_plugin_dir_path = plugin_dir_url(__FILE__);



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
        filemtime(plugin_dir_path(__FILE__) . 'admin/assets/css/admin-style.css')
    );

    register_block_type('show-my-social-icons/all-icons', array(
        'editor_script' => 'smsi-block-editor',
        'editor_style' => 'smsi-block-editor',
        'render_callback' => 'smsi_render_all_icons_block',
        'attributes' => array(
            'iconType' => array('type' => 'string', 'default' => 'PNG'),
            'iconSize' => array('type' => 'string', 'default' => '30px'),
            'iconStyle' => array('type' => 'string', 'default' => 'Icon only full color'),
            'iconAlignment' => array('type' => 'string', 'default' => 'Center'),
            'customColor' => array('type' => 'string', 'default' => '#000000'),
            'marginTop' => array('type' => 'string', 'default' => '0px'),
            'marginRight' => array('type' => 'string', 'default' => '0px'),
            'marginBottom' => array('type' => 'string', 'default' => '0px'),
            'marginLeft' => array('type' => 'string', 'default' => '0px'),
            'linkMargins' => array('type' => 'boolean', 'default' => false),
        ),
    ));

    register_block_type('show-my-social-icons/single-icon', array(
        'editor_script' => 'smsi-block-editor',
        'editor_style' => 'smsi-block-editor',
        'render_callback' => 'smsi_render_single_icon_block',
        'attributes' => array(
            'platform' => array('type' => 'string', 'default' => ''),
            'iconType' => array('type' => 'string', 'default' => 'PNG'),
            'iconSize' => array('type' => 'string', 'default' => '30px'),
            'iconStyle' => array('type' => 'string', 'default' => 'Icon only full color'),
            'iconAlignment' => array('type' => 'string', 'default' => 'Center'),
            'customColor' => array('type' => 'string', 'default' => '#000000'),
            'marginTop' => array('type' => 'string', 'default' => '0px'),
            'marginRight' => array('type' => 'string', 'default' => '0px'),
            'marginBottom' => array('type' => 'string', 'default' => '0px'),
            'marginLeft' => array('type' => 'string', 'default' => '0px'),
            'linkMargins' => array('type' => 'boolean', 'default' => false),
        ),
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
    global $wp_widget_factory;
    $wp_widget_factory->register('SMSI_All_Icons_Widget');
    register_widget('SMSI_Single_Icon_Widget');
}
add_action('widgets_init', 'smsi_register_widgets');

function smsi_render_all_icons_block($attributes) {
    $icon_type = isset($attributes['iconType']) ? $attributes['iconType'] : 'PNG';
    $icon_size = isset($attributes['iconSize']) ? $attributes['iconSize'] : '30px';
    $icon_style = isset($attributes['iconStyle']) ? $attributes['iconStyle'] : 'Icon only full color';
    $icon_alignment = isset($attributes['iconAlignment']) ? $attributes['iconAlignment'] : 'Center';
    $custom_color = isset($attributes['customColor']) ? $attributes['customColor'] : '#000000';

    $margin_top = smsi_sanitize_margin(isset($attributes['marginTop']) ? $attributes['marginTop'] : '0');
    $margin_right = smsi_sanitize_margin(isset($attributes['marginRight']) ? $attributes['marginRight'] : '0');
    $margin_bottom = smsi_sanitize_margin(isset($attributes['marginBottom']) ? $attributes['marginBottom'] : '0');
    $margin_left = smsi_sanitize_margin(isset($attributes['marginLeft']) ? $attributes['marginLeft'] : '0');

    return do_shortcode("[show_my_social_icons type=\"$icon_type\" size=\"$icon_size\" style=\"$icon_style\" alignment=\"$icon_alignment\" custom_color=\"$custom_color\" margin_top=\"$margin_top\" margin_right=\"$margin_right\" margin_bottom=\"$margin_bottom\" margin_left=\"$margin_left\"]");}

function smsi_render_single_icon_block($attributes) {
    $platform = isset($attributes['platform']) ? $attributes['platform'] : '';
    $icon_type = isset($attributes['iconType']) ? $attributes['iconType'] : 'PNG';
    $icon_size = isset($attributes['iconSize']) ? $attributes['iconSize'] : '30px';
    $icon_style = isset($attributes['iconStyle']) ? $attributes['iconStyle'] : 'Icon only full color';
    $icon_alignment = isset($attributes['iconAlignment']) ? $attributes['iconAlignment'] : 'Center';
    $custom_color = isset($attributes['customColor']) ? $attributes['customColor'] : '#000000';

    $margin_top = smsi_sanitize_margin(isset($attributes['marginTop']) ? $attributes['marginTop'] : '0');
    $margin_right = smsi_sanitize_margin(isset($attributes['marginRight']) ? $attributes['marginRight'] : '0');
    $margin_bottom = smsi_sanitize_margin(isset($attributes['marginBottom']) ? $attributes['marginBottom'] : '0');
    $margin_left = smsi_sanitize_margin(isset($attributes['marginLeft']) ? $attributes['marginLeft'] : '0');

    return do_shortcode("[my_social_icon platform=\"$platform\" type=\"$icon_type\" size=\"$icon_size\" style=\"$icon_style\" alignment=\"$icon_alignment\" custom_color=\"$custom_color\" margin_top=\"$margin_top\" margin_right=\"$margin_right\" margin_bottom=\"$margin_bottom\" margin_left=\"$margin_left\"]");}

function smsi_enqueue_block_editor_assets() {
    $asset_file = include(plugin_dir_path(__FILE__) . 'blocks/index.asset.php');

    wp_enqueue_script(
        'smsi-block-editor',
        plugins_url('build/index.js', __FILE__),
        $asset_file['dependencies'],
        filemtime(plugin_dir_path(__FILE__) . 'build/index.js'),
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

function smsi_get_platforms() {
    return my_social_media_platforms();
}

/**
 * Enqueue styles for the public-facing side of the site.
 *
 * @return void
 */
function smsi_enqueue_styles() {
    if (is_admin()) {
        return;
    }

    // Check if the force load styles option is enabled
    if (get_option('smsi_force_load_styles', '0') === '1') {
        wp_enqueue_style('smsi-styles', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '1.0.0');
        error_log('smsi_enqueue_styles: Scripts and styles enqueued (force load).');
        return;
    }

    global $post;
    $enqueue_assets = false;

    // Check if the post or page contains the shortcode
    if (isset($post->post_content) && (has_shortcode($post->post_content, 'my_social_icon') || has_shortcode($post->post_content, 'show_my_social_icons'))) {
        $enqueue_assets = true;
    }

    // Check if the social icons are displayed in the menu
    if (get_option('display_in_menu', '1') === '1') {
        $enqueue_assets = true;
    }

    // Check if the shortcode is used in widgets
    if (is_active_widget(false, false, 'smsi_all_icons_widget', true) || is_active_widget(false, false, 'smsi_single_icon_widget', true)) {
        $enqueue_assets = true;
    }

    // Check if the shortcode is used in theme templates
    if (has_shortcode(do_shortcode('[my_social_icon]'), 'my_social_icon') || has_shortcode(do_shortcode('[show_my_social_icons]'), 'show_my_social_icons')) {
        $enqueue_assets = true;
    }

    if ($enqueue_styles) {
        wp_enqueue_style('smsi-styles', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '1.0.0');
        error_log('smsi_enqueue_styles: Scripts and styles enqueued.');
    } else {
        error_log('smsi_enqueue_styles: Scripts and styles NOT enqueued.');
    }
}
add_action('wp_enqueue_scripts', 'smsi_enqueue_styles');