<?php
/**
 * Plugin Name: Show My Social Icons
 * Plugin URI: https://makingtheimpact.com
 * Description: Display customizable social media icons anywhere on your WordPress site using shortcodes, widgets, Gutenberg blocks, or in the main menu.
 * Version: 1.0.76  
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Tested up to: 6.6.2
 * Stable tag: 1.0.76
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: show-my-social-icons
 * Domain Path: /languages
 * Author: Making The Impact LLC
 * Author URI: https://makingtheimpact.com
 *
 * @package ShowMySocialIcons
 */

 /* To Do:
 - Make it so people can create their own custom icons and upload their own icons to the plugin.
 - Add a new shortcode, block, and widget for displaying one or more icons they select in a group instead of all the icons or make it so they can turn off the group display and just display the icons they select in a group.
 - Add Flickr, foursquare and any other platforms that are missing.
 */

// Prevent direct access.
if (!defined('WPINC')) {
    die;
}

if (!defined('SMSI_VERSION')) {
    define('SMSI_VERSION', '1.0.76');
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
require_once plugin_dir_path(__FILE__) . 'includes/class-smsi-select-icons-widget.php';
require_once plugin_dir_path(__FILE__) . 'includes/github-updater.php';

define('SMSI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SMSI_PLUGIN_FILE', __FILE__);

$smsi_plugin_dir_path = plugin_dir_url(__FILE__);

/**
 * Register Blocks.
 */
function smsi_register_block() {
    // Register the block editor script
    wp_register_script(
        'smsi-block-editor',
        plugins_url('build/index.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor'),
        file_exists(plugin_dir_path(__FILE__) . 'build/index.asset.php') ? include(plugin_dir_path(__FILE__) . 'build/index.asset.php') : array(),
        true
    );

    // Register the editor styles
    wp_register_style(
        'smsi-block-editor',
        plugins_url('admin/assets/css/admin-style.css', __FILE__),
        array(),
        file_exists(plugin_dir_path(__FILE__) . 'admin/assets/css/admin-style.css') ? filemtime(plugin_dir_path(__FILE__) . 'admin/assets/css/admin-style.css') : '1.0.0'
    );

    // Register the frontend styles
    wp_register_style(
        'smsi-frontend-styles',
        plugins_url('assets/css/style.css', __FILE__),
        array(),
        '1.0.0'
    );

    // Register the "All Icons" dynamic block
    register_block_type('show-my-social-icons/all-icons', array(
        'editor_script'   => 'smsi-block-editor',
        'editor_style'    => 'smsi-block-editor',
        'style'           => 'smsi-frontend-styles',
        'render_callback' => 'smsi_render_all_icons_block',
        'attributes'      => array(
            'iconType'      => array('type' => 'string', 'default' => 'PNG'),
            'iconSize'      => array('type' => 'string', 'default' => '30px'),
            'iconStyle'     => array('type' => 'string', 'default' => 'Icon only full color'),
            'iconAlignment' => array('type' => 'string', 'default' => 'Center'),
            'customColor'   => array('type' => 'string', 'default' => '#000000'),
            'spacing'       => array('type' => 'string', 'default' => '10px'),
            'marginTop'     => array('type' => 'string', 'default' => '0px'),
            'marginRight'   => array('type' => 'string', 'default' => '0px'),
            'marginBottom'  => array('type' => 'string', 'default' => '0px'),
            'marginLeft'    => array('type' => 'string', 'default' => '0px'),
            'linkMargins'   => array('type' => 'boolean', 'default' => false),
        ),
    ));

    // Register the "Single Icon" dynamic block
    register_block_type('show-my-social-icons/single-icon', array(
        'editor_script'   => 'smsi-block-editor',
        'editor_style'    => 'smsi-block-editor',
        'style'           => 'smsi-frontend-styles',
        'render_callback' => 'smsi_render_single_icon_block',
        'attributes'      => array(
            'platform'      => array('type' => 'string', 'default' => 'Facebook'),
            'iconType'      => array('type' => 'string', 'default' => 'PNG'),
            'iconSize'      => array('type' => 'string', 'default' => '30px'),
            'iconStyle'     => array('type' => 'string', 'default' => 'Icon only full color'),
            'iconAlignment' => array('type' => 'string', 'default' => 'Center'),
            'customColor'   => array('type' => 'string', 'default' => '#000000'),
            'marginTop'     => array('type' => 'string', 'default' => '0px'),
            'marginRight'   => array('type' => 'string', 'default' => '0px'),
            'marginBottom'  => array('type' => 'string', 'default' => '0px'),
            'marginLeft'    => array('type' => 'string', 'default' => '0px'),
            'linkMargins'   => array('type' => 'boolean', 'default' => false),
            'inline'        => array('type' => 'boolean', 'default' => false),
        ),
    ));

    // Register the "Select Icons" dynamic block
    register_block_type('show-my-social-icons/select-icons', array(
        'editor_script'   => 'smsi-block-editor',
        'editor_style'    => 'smsi-block-editor',
        'style'           => 'smsi-frontend-styles',
        'render_callback' => 'smsi_render_select_icons_block',
        'attributes'      => array(
            'platforms'    => array('type' => 'array', 'default' => array()),
            'iconType'     => array('type' => 'string', 'default' => 'PNG'),
            'iconSize'     => array('type' => 'string', 'default' => '30px'),
            'iconStyle'    => array('type' => 'string', 'default' => 'Icon only full color'),
            'iconAlignment'=> array('type' => 'string', 'default' => 'Center'),
            'customColor'  => array('type' => 'string', 'default' => '#000000'),
            'marginTop'    => array('type' => 'string', 'default' => '0px'),
            'marginRight'  => array('type' => 'string', 'default' => '0px'),
            'marginBottom' => array('type' => 'string', 'default' => '0px'),
            'marginLeft'   => array('type' => 'string', 'default' => '0px'),
            'inline'       => array('type' => 'boolean', 'default' => false),
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
    register_widget('SMSI_Select_Icons_Widget');
}
add_action('widgets_init', 'smsi_register_widgets');

function smsi_render_all_icons_block($attributes) {
    $icon_type = isset($attributes['iconType']) ? $attributes['iconType'] : 'PNG';
    $icon_size = isset($attributes['iconSize']) ? $attributes['iconSize'] : '30px';
    $icon_style = isset($attributes['iconStyle']) ? $attributes['iconStyle'] : 'Icon only full color';
    $icon_alignment = isset($attributes['iconAlignment']) ? $attributes['iconAlignment'] : 'Center';
    $custom_color = isset($attributes['customColor']) ? $attributes['customColor'] : '#000000';
    $spacing = isset($attributes['spacing']) ? $attributes['spacing'] : '10px';

    $margin_top = smsi_sanitize_unit_value(isset($attributes['marginTop']) ? $attributes['marginTop'] : '0');
    $margin_right = smsi_sanitize_unit_value(isset($attributes['marginRight']) ? $attributes['marginRight'] : '0');
    $margin_bottom = smsi_sanitize_unit_value(isset($attributes['marginBottom']) ? $attributes['marginBottom'] : '0');
    $margin_left = smsi_sanitize_unit_value(isset($attributes['marginLeft']) ? $attributes['marginLeft'] : '0');

    return do_shortcode("[show_my_social_icons type=\"$icon_type\" size=\"$icon_size\" style=\"$icon_style\" alignment=\"$icon_alignment\" custom_color=\"$custom_color\" margin_top=\"$margin_top\" margin_right=\"$margin_right\" margin_bottom=\"$margin_bottom\" margin_left=\"$margin_left\" spacing=\"$spacing\"]");
}

function smsi_render_single_icon_block($attributes) {
    $platform = isset($attributes['platform']) ? $attributes['platform'] : '';
    $icon_type = isset($attributes['iconType']) ? $attributes['iconType'] : 'PNG';
    $icon_size = isset($attributes['iconSize']) ? $attributes['iconSize'] : '30px';
    $icon_style = isset($attributes['iconStyle']) ? $attributes['iconStyle'] : 'Icon only full color';
    $icon_alignment = isset($attributes['iconAlignment']) ? $attributes['iconAlignment'] : 'Center';
    $custom_color = isset($attributes['customColor']) ? $attributes['customColor'] : '#000000';
    $inline = isset($attributes['inline']) ? $attributes['inline'] : false;

    $margin_top = smsi_sanitize_unit_value(isset($attributes['marginTop']) ? $attributes['marginTop'] : '0');
    $margin_right = smsi_sanitize_unit_value(isset($attributes['marginRight']) ? $attributes['marginRight'] : '0');
    $margin_bottom = smsi_sanitize_unit_value(isset($attributes['marginBottom']) ? $attributes['marginBottom'] : '0');
    $margin_left = smsi_sanitize_unit_value(isset($attributes['marginLeft']) ? $attributes['marginLeft'] : '0');

    return do_shortcode("[my_social_icon platform=\"$platform\" type=\"$icon_type\" size=\"$icon_size\" style=\"$icon_style\" alignment=\"$icon_alignment\" custom_color=\"$custom_color\" margin_top=\"$margin_top\" margin_right=\"$margin_right\" margin_bottom=\"$margin_bottom\" margin_left=\"$margin_left\" inline=\"$inline\"]");
}

function smsi_render_select_icons_block($attributes) {
    $platforms = isset($attributes['platforms']) ? (array) $attributes['platforms'] : [];
    $platform_ids = array_map(function($platform) {
        return $platform['id'];
    }, $platforms);
    
    //error_log('SMSI: Platforms: ' . print_r($platforms, true));
    //error_log('SMSI: Platform IDs: ' . print_r($platform_ids, true));

    $platforms_string = implode(',', $platform_ids); // Convert array to comma-separated string    

    //error_log('SMSI: Platforms: ' . $platforms_string);

    $icon_type = isset($attributes['iconType']) ? $attributes['iconType'] : 'PNG';
    $icon_size = isset($attributes['iconSize']) ? $attributes['iconSize'] : '30px';
    $icon_style = isset($attributes['iconStyle']) ? $attributes['iconStyle'] : 'Icon only full color';
    $icon_alignment = isset($attributes['iconAlignment']) ? $attributes['iconAlignment'] : 'Center';
    $custom_color = isset($attributes['customColor']) ? $attributes['customColor'] : '#000000';
    $spacing = isset($attributes['spacing']) ? $attributes['spacing'] : '10px';

    $margin_top = isset($attributes['marginTop']) ? $attributes['marginTop'] : '0px';
    $margin_right = isset($attributes['marginRight']) ? $attributes['marginRight'] : '0px';
    $margin_bottom = isset($attributes['marginBottom']) ? $attributes['marginBottom'] : '0px';
    $margin_left = isset($attributes['marginLeft']) ? $attributes['marginLeft'] : '0px';

    // Render the block using a shortcode
    return do_shortcode("[select_my_social_icons platforms=\"$platforms_string\" type=\"$icon_type\" size=\"$icon_size\" style=\"$icon_style\" alignment=\"$icon_alignment\" custom_color=\"$custom_color\" margin_top=\"$margin_top\" margin_right=\"$margin_right\" margin_bottom=\"$margin_bottom\" margin_left=\"$margin_left\" spacing=\"$spacing\"]");
}

/**
 * Enqueue Block Editor Assets.
 */
function smsi_enqueue_block_editor_assets() {
    $asset_file_path = plugin_dir_path(__FILE__) . 'build/index.asset.php';

    if ( ! file_exists($asset_file_path) ) {
        error_log('SMSI: Missing build/index.asset.php file.');
        return;
    }

    $asset_file = include($asset_file_path);

    wp_enqueue_script(
        'smsi-block-editor',
        plugins_url('build/index.js', __FILE__),
        $asset_file['dependencies'],
        $asset_file['version'],
        true
    );

    wp_script_add_data('smsi-block-editor', 'type', 'module');

    // Localize script with nonce
    wp_localize_script('smsi-block-editor', 'smsiData', array(
        'nonce' => wp_create_nonce('wp_rest'), // Nonce for REST API
    ));
}
add_action('enqueue_block_editor_assets', 'smsi_enqueue_block_editor_assets');

// Register REST API Route
function smsi_register_rest_route() {
    register_rest_route('smsi/v1', '/platforms', array(
        'methods'             => 'GET',
        'callback'            => 'smsi_get_platforms',
        'permission_callback' => function () {
            return current_user_can('edit_posts'); // Adjust capabilities as needed
        },
    ));
    register_rest_route('smsi/v1', '/active-platforms', array(
        'methods'             => 'GET',
        'callback'            => 'smsi_get_platforms',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
    ));
}
add_action('rest_api_init', 'smsi_register_rest_route');

/**
 * Get the list of platforms that have a URL set.
 *
 * @return array List of platforms with a URL set.
 */
function smsi_get_platforms() {
    $platforms = smsi_get_platform_list();
    // return list of platforms that have a URL set but return the platform_id and label
    $platforms_with_url = array_filter($platforms, function($platform) {
        $platform_url = get_option($platform['platform_id'] . '_url', '');
        return !empty($platform_url);
    });
    // return the platform_id and label
    return array_map(function($platform) {
        return [
            'id' => $platform['platform_id'],
            'name' => $platform['label'],
        ];
    }, $platforms_with_url);
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
    if ( get_option('smsi_force_load_styles', '0') === '1' ) {
        wp_enqueue_style(
            'smsi-styles',
            plugins_url('assets/css/style.css', __FILE__),
            array(),
            '1.0.0'
        );
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
        wp_enqueue_style(
            'smsi-styles',
            plugins_url('assets/css/style.css', __FILE__),
            array(),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'smsi_enqueue_styles');

/**
 * Enqueue frontend scripts and styles.
 */
function smsi_enqueue_frontend_scripts() {
    // Only enqueue if not in admin
    if (!is_admin()) {
        // Add console log to verify function is running
        error_log('Attempting to enqueue frontend script');
        
        $script_path = plugin_dir_url(__FILE__) . 'assets/js/frontend.js';
        error_log('Script path: ' . $script_path);
        
        wp_enqueue_script(
            'smsi-frontend-script',
            $script_path,
            array('jquery'), 
            SMSI_VERSION,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'smsi_enqueue_frontend_scripts', 10);

/**
 * Enqueue scripts and styles for the admin area.
 *
 * @param string $hook The current admin page.
 * @return void
 */
function smsi_admin_enqueue_scripts($hook) {
    global $main_page_hook, $docs_page_hook, $icon_preview_page_hook; // Access global variables

    // Check if we are on any of the relevant pages
    $allowed_hooks = array(
        $main_page_hook,
        $docs_page_hook,
        $icon_preview_page_hook,        
        'widgets.php',
        'post.php',
        'post-new.php',
    );

    if ( ! in_array($hook, $allowed_hooks) ) {
        return;
    }

    // Enqueue your styles and scripts
    wp_enqueue_style('smsi-admin-styles', plugin_dir_url(__FILE__) . 'admin/assets/css/admin-style.css', array(), '1.0.0');

    // Enqueue jQuery UI Sortable
    wp_enqueue_script('jquery-ui-sortable');

    // Enqueue wp-color-picker style
    wp_enqueue_style('wp-color-picker');

    // Enqueue admin script with dependencies including wp-color-picker
    wp_enqueue_script('smsi-admin-script', plugin_dir_url(__FILE__) . 'admin/assets/js/admin-script.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker'), '1.0.0', true);
    
    // Enqueue front-end styles
    global $smsi_plugin_dir_path;
    wp_enqueue_style('smsi-frontend-styles', $smsi_plugin_dir_path . 'assets/css/style.css', array(), '1.0.0');

    // Pass data to the script
    wp_localize_script('smsi-admin-script', 'smsiData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('smsi_nonce')
    ));

    $asset_file = include(plugin_dir_path(__FILE__) . 'build/index.asset.php');

    wp_enqueue_script(
        'smsi-block-editor',
        plugins_url('build/index.js', __FILE__),
        $asset_file['dependencies'],
        filemtime(plugin_dir_path(__FILE__) . 'build/index.js'),
        true
    );

    wp_localize_script('smsi-block-editor', 'smsiPlatforms', array(
        'platforms' => smsi_get_platform_list()
    ));
}
add_action('admin_enqueue_scripts', 'smsi_admin_enqueue_scripts');

/**
 * Plugin Activation Hook
 */
function smsi_activate_plugin() {
    // Set 'smsi_force_load_styles' to '1' if it doesn't exist
    if (false === get_option('smsi_force_load_styles')) {
        update_option('smsi_force_load_styles', '1');
    }
}
register_activation_hook(__FILE__, 'smsi_activate_plugin');

/**
 * Append Social Icons to the Selected Menu Location
 *
 * @param string $items The HTML list content for the menu items.
 * @param stdClass $args An object containing wp_nav_menu() arguments.
 * @return string Modified menu items with social icons appended.
 */
function smsi_add_icons_to_menu($items, $args) {
    if (get_option('display_in_menu', '1') !== '1') {
        return $items;
    }

    $menu_location = get_option('smsi_menu_location', 'header');

    if ($args->theme_location === $menu_location) {
        $menu_icons = get_option('smsi_menu_icons', []);
        
        if (!empty($menu_icons) && is_array($menu_icons)) {
            $icon_type = get_option('icon_type', 'PNG');
            $icon_size = get_option('icon_size', '30px');
            $icon_style = get_option('icon_style', 'Icon only full color');
            $custom_color = get_option('icon_custom_color', '');
            $icon_spacing = get_option('icon_spacing', '10px');
            $margin_top = get_option('icon_container_margin_top', '0px');
            $margin_bottom = get_option('icon_container_margin_bottom', '0px');
            $margin_left = get_option('icon_container_margin_left', '0px');
            $margin_right = get_option('icon_container_margin_right', '0px');
            $hover_effect = get_option('icon_hover_effect', 'style1');

            // Start building the icons HTML
            $icons_html = '<li class="menu-item nav-item smsi-menu-social-icons" style="margin-top:' . esc_attr($margin_top) . '; margin-bottom:' . esc_attr($margin_bottom) . '; margin-left:' . esc_attr($margin_left) . '; margin-right:' . esc_attr($margin_right) . '; display:flex; align-items:center;">';

            // Apply hover styles via CSS classes
            $hover_class = '';
            if ($hover_effect === 'style2') {
                $hover_class = ' smsi-icon-hover-style2';
            } elseif ($hover_effect === 'style1') {
                $hover_class = ' smsi-icon-hover-style1';
            } elseif ($hover_effect === 'style3') {
                $hover_class = ' smsi-icon-hover-style3';
            } else {
                $hover_class = ' smsi-icon-hover-style1';
            }

            $spacing = isset($atts['spacing']) ? smsi_sanitize_unit_value($atts['spacing']) : '10px';
            $spacing_parts = smsi_split_css_value($spacing);
            $spacing_value = $spacing_parts['value'];
            $spacing_unit = $spacing_parts['unit'];

            $single_icon_margin_left = ($spacing_value / 2) . $spacing_unit;
            $single_icon_margin_right = ($spacing_value / 2) . $spacing_unit;

            // Style attributes
            $style = 'width:' . esc_attr($icon_size) . '; height: auto; margin-left:' . esc_attr($single_icon_margin_left) . '; margin-right:' . esc_attr($single_icon_margin_right) . ';';   

            foreach ($menu_icons as $platform_id) {
                $platform = smsi_get_platform($platform_id);
                // Check if the platform has a URL set
                $platform_url = get_option($platform_id . '_url', '');
                $icon_path = smsi_get_single_social_icon_path($platform_id, $icon_type, $icon_size, $icon_style);

                if ($platform && !empty($platform_url)) {

                    // if SVG
                    if (strtoupper($icon_type) === 'SVG') {
                        $svg_content = smsi_get_file_contents($icon_path);

                        $unique_id = 'smsi-' . $platform_id . '-' . uniqid();

                        // Determine the fill color
                        $svg_fill = '#000000';
                        if ($icon_style === 'Icon only custom color' && !empty($custom_color)) {
                            $svg_fill = $custom_color;
                        } elseif ($icon_style === 'Icon only white') {
                            $svg_fill = '#FFFFFF';
                        } elseif ($icon_style === 'Icon only black') {
                            $svg_fill = '#000000';
                        }

                        // Remove the existing style tag
                        $svg_content = preg_replace('/<style>.*?<\/style>/s', '', $svg_content);

                        // Add a new style tag with scoped styles
                        $svg_content = preg_replace('/<svg /', '<svg style="width:' . esc_attr($icon_size) . '; height: auto; fill:' . esc_attr($svg_fill) . '" ', $svg_content);

                        // Update class names to be unique
                        $svg_content = preg_replace('/class="cls-([0-9]+)"/', 'class="' . $unique_id . ' cls-$1"', $svg_content);

                        $output_html = "<div class='smsi-menu-icon smsi-menu-svg" . esc_attr($hover_class) . "' style='" . esc_attr($style) . "'><a href='" . esc_url($platform_url) . "' target='_blank'>$svg_content</a></div>";

                        $icons_html .= $output_html;
                    } else {
                        $icons_html .= '<a href="' . esc_url($platform_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($platform['label']) . '">';
                        $icons_html .= '<img src="' . esc_url($icon_path) . '" alt="' . esc_attr($platform['label']) . '" style="' . esc_attr($style) . '" class="smsi-menu-icon smsi-menu-png' . esc_attr($hover_class) . '">';
                        $icons_html .= '</a>';
                    }                    
                }
            }

            $icons_html .= '</li>';

            // Append the icons to the existing menu items
            $items .= $icons_html;
        }
    }

    return $items;
}
add_filter('wp_nav_menu_items', 'smsi_add_icons_to_menu', 10, 2);

