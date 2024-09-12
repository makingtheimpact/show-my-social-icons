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

// Helper Functions
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
        switch($style) {
            case 'Full logo horizontal': 
                $icon_path_style = "logos_set_hz_";
                $icon_path_style_dir = "hz/";
                $icon_file_name_style = "_logo";
                break; 
            case 'Full logo square': 
                $icon_path_style = "logos_set_sq_";
                $icon_path_style_dir = "sq/";
                $icon_file_name_style = "_logo";
                break; 
            case 'Icon only full color': 
                $icon_path_style = "icons_full_color_";
                $icon_path_style_dir = "ic-c/";
                $icon_file_name_style = "_icon";
                break; 
            case 'Icon only black': 
                $icon_path_style = "icons_black_";
                $icon_path_style_dir = "ic-b/";
                $icon_file_name_style = "_icon";
                break; 
            case 'Icon only white': 
                $icon_path_style = "icons_white_";
                $icon_path_style_dir = "ic-w/";
                $icon_file_name_style = "_icon";
                break; 
            case 'Icon only custom color': 
                $icon_path_style = "icons_black_";
                $icon_path_style_dir = "ic-b/";
                $icon_file_name_style = "_icon";
                break; 
            default: 
                $icon_path_style = "logos_set_sq_";
                $icon_path_style_dir = "sq/";
                $icon_file_name_style = "_icon";
                break;
        }
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
                $icon_path_style = "_logo_";
                $icon_path_style_dir = "sq/";
                break;
        }
        $icon_path_start = $icon_path_dir . $icon_path_style_dir . $icon_dir_size; 
        $icon_file_name_end = $icon_path_style . $icon_path_size . ".png";
    }
    return [$icon_path_start, $icon_file_name_end];
}

/* Add the social media links to the main menu 
    If the option is set (it is by default), it will add the icons to the main menu of the site.
*/
function my_social_icons_add_menu_icons($items, $args) {
    // Check if icons should be displayed in the menu
    if (get_option('display_in_menu', '1') != '1') {
        return $items;
    }

    $setting_type = get_option('icon_type', 'PNG');
    $setting_size = get_option('icon_size', '30px');
    $setting_style = get_option('icon_style', 'Icon only full color');
    $setting_alignment = get_option('icon_alignment', 'Center');
    $setting_color = get_option('icon_custom_color', '#000000');

    $platforms = my_social_media_platforms();
    $icons = array();

    global $smsi_plugin_dir_path;
    
    foreach($platforms as $platform => $icon_class) {
        $url = get_option($platform . '_url');
        $order = get_option($platform . '_order');

        // Build the image path and file name
        list($icon_path_start, $icon_file_name_end) = show_my_social_icons_file_path($setting_type, $setting_size, $setting_style);

        if($url) {
            // Set the icon image path
            $icon_path = $icon_path_start . strtolower($platform) . $icon_file_name_end;
            $style = "width: " . esc_attr($setting_size) . "; text-align: " . esc_attr($setting_alignment) . "; color: " . esc_attr($setting_color) . ";";
            // Generate the HTML for the menu items
            $icon_html = '<li class="menu-item"><a href="' . esc_url($url) . '" target="_blank"><img src="' . $smsi_plugin_dir_path . $icon_path . '" style="' . $style . '" /></a></li>';
            // Add to the icons array at the position specified by the order option
            $icons[$order] = $icon_html;
        }
    }
    
    // sort the icons array by key to get them in the correct order
    ksort($icons);
    
    // append the icons to the menu items
    $items .= implode($icons);
    
    return $items;
}
add_filter('wp_nav_menu_items', 'my_social_icons_add_menu_icons', 10, 2);

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