<?php 

/* Shortcode to display all the icons - [show_my_social_icons]
    This displays the social icons within a div that has the class "my-social-icons" - which you need to style for your needs using CSS.
*/

function smsi_add_custom_styles() {
    // Get settings
    $icon_size = get_option('icon_size', '30px');
    $icon_alignment = get_option('icon_alignment', 'Center');
    $custom_color = get_option('icon_custom_color', '#000000');  // Assumes color is applicable to SVG via a CSS filter or similar.

    // Generate CSS
    $css = "
    .my-social-icons {
        text-align: " . strtolower($icon_alignment) . "
    }
    .my-social-icons img, .my-social-icon-single img {
        width: " . $icon_size .";
    }
    ";

    // Add custom color handling for SVG icons using a filter if applicable
    if($custom_color !== '#000000') {  // Assuming black doesn't need a change
        // This is an example to adjust brightness based on a hypothetical scenario
        // Real application might require more complex calculations or different filters
        $css .= "
        .my-social-icons img {
            filter: hue-rotate(45deg) brightness(120%);
        }
        ";
    }

    // Print CSS
    echo '<style>' . $css . '</style>';
}
add_action('wp_head', 'smsi_add_custom_styles');

function my_social_icons_shortcode($atts) {
    $atts = shortcode_atts(array(
        'type' => get_option('icon_type', 'PNG'),
        'size' => get_option('icon_size', '30px'),
        'style' => get_option('icon_style', 'Icon only full color'),
        'alignment' => get_option('icon_alignment', 'Center'),
        'custom_color' => get_option('icon_custom_color', '#000000')
    ), $atts);
    $user_specific_atts = $atts;
    $filter = get_option('icon_custom_filter', '');
    if ($filter != '') { 
        $filter_styles = "filter: ". $filter . ";";
    } else {
        $filter_styles = "";
    }
    $style = "width: " . esc_attr($atts['size']) . "; filter: " . $filter . ";";

    if (!empty($user_specific_atts)) {
        // Apply inline styles
        $container_inline_styles = "style='text-align: " . esc_attr(strtolower($atts['alignment'])) . ";";
        $icon_inline_styles = "style='width: " . $user_specific_atts['size'] . "; " . $filter_styles . ";'";
    } else {
        $icon_inline_styles = "style='" . $filter_styles . "'";
    }

    $platforms = my_social_media_platforms();
    $icons = array();

    global $smsi_plugin_dir_path;

    // Build the image path and file name
    list($icon_path_start, $icon_file_name_end) = show_my_social_icons_file_path($atts['type'], $atts['size'], $atts['style']);

    foreach ($platforms as $platform => $config) {
        $url = get_option($platform . '_url');
        $order = get_option($platform . '_order', 0);
        if ($url) {
            $icon_html = "<a href='" . esc_url($url) . "' target='_blank'>";
            $icon_path = $icon_path_start . strtolower($platform) . $icon_file_name_end;
            $icon_html .= "<img src='" . $smsi_plugin_dir_path . $icon_path . "' class='smsi-icon smsi-icon-" . strtolower($platform) . "' " . $icon_inline_styles . " />";
            $icon_html .= "</a>";
            $icons[(int)$order] = $icon_html;
        } 
    }

    ksort($icons);    
    $icons_html = implode(' ', $icons);
    return "<div class='my-social-icons' ".$container_inline_styles . "'>{$icons_html}</div>";
}
add_shortcode('show_my_social_icons', 'my_social_icons_shortcode');

/* Shortcode to display individual icon - [my_social_icon platform="PLATFORM"]
    Type the platform name in lowercase like in the array and it will display the icon and link if the link is set. Use the CSS class 'show-my-social-icons-single' to change the size and spacing of the icons. 
*/
function my_social_icon_shortcode($atts) {
    $platforms = my_social_media_platforms();
    global $smsi_plugin_dir_path;
    $atts = shortcode_atts(
        array(
            'platform' => '',
            'type' => get_option('icon_type', 'PNG'),  // Assuming default types can be PNG or SVG as needed
            'size' => get_option('icon_size', '30px'),  // Default size
            'style' => get_option('icon_style', 'Icon only full color'),
            'alignment' => get_option('icon_alignment', 'Center'),  // Default alignment
            'custom_color' => get_option('icon_custom_color', '#000000')  // Default custom color if applicable
        ), 
        $atts
    );
    $user_specific_atts = $atts;
    $filter = get_option('icon_custom_filter', '');
    if ($filter != '') { 
        $filter_styles = "filter: ". $filter . ";";
    } else {
        $filter_styles = "";
    }
    $style = "width: " . esc_attr($atts['size']) . "; filter: " . $filter . ";";

    if (!empty($user_specific_atts)) {
        // Apply inline styles
        $container_inline_styles = "style='text-align: " . esc_attr(strtolower($atts['alignment'])) . ";";
        $icon_inline_styles = "style='width: " . $user_specific_atts['size'] . "; " . $filter_styles . ";'";
    } else {
        $icon_inline_styles = "style='" . $filter_styles . "'";
    }

    $platform = strtolower($atts['platform']);
    if (isset($platforms[$platform])) {
        // Build the image path and file name
        list($icon_path_start, $icon_file_name_end) = show_my_social_icons_file_path($atts['type'], $atts['size'], $atts['style']);

        $url = get_option($platform . '_url');
        if ($url) {
            $icon_path = $icon_path_start . strtolower($platform) . $icon_file_name_end;
            return "<div class='my-social-icon-single my-social-icon-" . strtolower($platform) . "'><a href='" . esc_url($url) . "' target='_blank'><img src='" . $smsi_plugin_dir_path . $icon_path . "' class='smsi-icon smsi-icon-" . strtolower($platform) . "' " . $icon_inline_styles . " /></a></div>";
        }
    }
    return '';  // Return empty if the platform is not found or no URL is set
}
add_shortcode('my_social_icon', 'my_social_icon_shortcode');