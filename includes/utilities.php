<?php

// Sanitization and Validation
function show_my_social_icons_sanitize_url($url) {
    return esc_url_raw(trim($url));
}

function show_my_social_icons_validate_url($url) {
    return (filter_var($url, FILTER_VALIDATE_URL) !== false);
}

/* URL Validation */
function validate_url($new_url, $old_url) {
    if (filter_var($new_url, FILTER_VALIDATE_URL) === false) {
        add_settings_error('show_my_social_icons_page_options', 'invalid_url', 'Please enter a valid URL.');
        return $old_url;
    }
    return $new_url;
}

/* Order Input Validation */
function generate_order_validator($option_name) {
    return function ($input) use ($option_name) {
        // Checking if input is an integer and more than zero
        if (filter_var($input, FILTER_VALIDATE_INT, array("options" => array("min_range"=>0))) === false) {
            add_settings_error(
                $option_name . '_order_error',
                esc_attr( 'settings_updated' ),
                'Please enter a valid order number for ' . $option_name . ' icon.',
                'error'
            );
            return get_option($option_name . '_order');
        } else {
            return $input;
        }
    };
}

/* Checkbox validation */
function validate_display_in_menu($display_in_menu) {
    return $display_in_menu == '1' ? '1' : '0';
}

/* Checkbox to display icons in menu */
function display_in_menu_callback() { 
    $setting = esc_attr(get_option('display_in_menu', '1')); // 1 is default (checked)
    echo '<input type="checkbox" name="display_in_menu" value="1"'.checked(1, $setting, false).' />';
}

// Order Validation
function show_my_social_icons_validate_order($order) {
    $order = intval($order);
    // Assuming you want the order to be between 0 and 100
    if ($order >= 0 && $order <= 100) {
        return $order;
    }
    return 1; // Default order
}

// Sanitize Checkbox
function smsi_sanitize_display_in_menu($input) {
    return $input === '1' ? '1' : '0';
}

/**
 * Helper function to split a CSS value into numeric and unit parts.
 *
 * @param string $value The CSS value (e.g., '2em').
 * @return array An array with 'value' and 'unit'.
 */
function smsi_split_css_value($value) {
    if (preg_match('/^(\d*\.?\d+)(px|em|rem|%|vh|vw)$/', $value, $matches)) {
        return [
            'value' => floatval($matches[1]),
            'unit' => $matches[2],
        ];
    }
    // Default fallback
    return [
        'value' => 0,
        'unit' => 'px',
    ];
}

/**
 * Sanitizes unit value (px, em, rem, %, vh, vw)
 *
 * @param string $value The value to sanitize.
 * @return string Sanitized value.
 */
function smsi_sanitize_unit_value($value) {
    if (!empty($value)) {        
        $value = trim($value);
        if (is_numeric($value)) {
            return $value . 'px';
        }
        if (preg_match('/^(\d*\.?\d+)(px|em|rem|%|vh|vw)$/', $value, $matches)) { // Check if the value is a number followed by a unit
            return $value;
        }
    }
    return '0px';
}

// Helper Functions
/**
 * Helper function to generate field IDs.
 *
 * @param string $field_name The name of the field.
 * @param string $widget_id  The ID of the widget (if applicable).
 * @return string The generated field ID.
 */
function smsi_get_field_id($field_name, $widget_id = '') {
    if ($widget_id) {
        return 'widget-' . esc_attr($widget_id) . '-' . esc_attr($field_name);
    }
    return 'smsi-' . esc_attr($field_name);
}

function show_my_social_icons_get_social_urls() {
    $urls = [];
    global $social_platforms;

    foreach ($social_platforms as $platform) {
        $urls[$platform] = get_option($platform . '_url', '');
    }
    return $urls;
}

function show_my_social_icons_file_path($type, $size, $style) {
    if ($type == 'SVG') {
        $icon_path_dir = "assets/svg/";
        $icon_file_name_start = "social_media_";
        $icon_path_style = "icons_black_";
        $icon_path_style_dir = "ic-b/";
        $icon_file_name_style = "_icon";
        $icon_path_start = $icon_path_dir . $icon_path_style_dir . $icon_file_name_start . $icon_path_style; 
        $icon_file_name_end = $icon_file_name_style . ".svg";
    } else {
        $icon_path_dir = "assets/png/";
        switch($size) {
            case '100px': 
                $icon_dir_size = '100w/';
                $icon_path_size = '100px';
                break; 
            case '150px':
                $icon_dir_size = '150w/';
                $icon_path_size = '150px';
                break;
            case '200px':
                $icon_dir_size = '200w/';
                $icon_path_size = '200px';
                break;
            case '300px':
                $icon_dir_size = '300w/';
                $icon_path_size = '300px';
                break;
            default:
                $icon_dir_size = '500w/';
                $icon_path_size = '500px';
                break;
        }
        switch($style) {
            case 'Full logo horizontal': 
                $icon_path_style = "_logo_";
                $icon_path_style_dir = "hz/";
                break; 
            case 'Full logo square': 
                $icon_path_style = "_logo_";
                $icon_path_style_dir = "sq/";
                break; 
            case 'Icon only full color': 
                $icon_path_style = "_icon_";
                $icon_path_style_dir = "ic-c/";
                break; 
            case 'Icon only black': 
                $icon_path_style = "_icon_";
                $icon_path_style_dir = "ic-b/";
                break; 
            case 'Icon only white': 
                $icon_path_style = "_icon_";
                $icon_path_style_dir = "ic-w/";
                break; 
            default: 
                $icon_path_style = "_icon_";
                $icon_path_style_dir = "ic-c/";
                break;
        }
        $icon_path_start = $icon_path_dir . $icon_path_style_dir . $icon_dir_size; 
        $icon_file_name_end = $icon_path_style . $icon_path_size . ".png";
    }
    return [$icon_path_start, $icon_file_name_end];
}

/* Function to assemble full icon path with platform ID */
function smsi_get_single_social_icon_path($platform_id, $icon_type, $icon_size, $icon_style) {
    list($icon_path_start, $icon_file_name_end) = show_my_social_icons_file_path($icon_type, $icon_size, $icon_style); 

    // Get the plugin URL
    $plugin_url = plugin_dir_url(dirname(__FILE__, 2)) . '/show-my-social-icons/'; 
    
    $icon_path = $plugin_url . $icon_path_start . strtolower($platform_id) . $icon_file_name_end;
    return $icon_path;
}

function smsi_render_social_icons_block($attributes) {
    $icon_type = isset($attributes['iconType']) ? $attributes['iconType'] : 'PNG';
    $icon_size = isset($attributes['iconSize']) ? $attributes['iconSize'] : '30px';
    $icon_style = isset($attributes['iconStyle']) ? $attributes['iconStyle'] : 'Icon only full color';
    $icon_alignment = isset($attributes['iconAlignment']) ? $attributes['iconAlignment'] : 'Center';

    return do_shortcode("[show_my_social_icons type=\"$icon_type\" size=\"$icon_size\" style=\"$icon_style\" alignment=\"$icon_alignment\"]");
}

function smsi_hex_to_filter($hex) {
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return "invert(" . round($r / 2.55) . "%) sepia(" . round($g / 2.55) . "%) saturate(" . round($b / 2.55) . "%) hue-rotate(" . round(($r + $g + $b) / 765 * 360) . "deg)";
}

function smsi_get_file_contents($file_path) {
    global $wp_filesystem;
    require_once (ABSPATH . '/wp-admin/includes/file.php');
    WP_Filesystem();
    return $wp_filesystem->get_contents($file_path);
}

register_setting('show_my_social_icons', 'smsi_force_load_styles', 'intval');

function smsi_sanitize_boolean($input) {
    return filter_var($input, FILTER_VALIDATE_BOOLEAN);
}
