<?php 

/**
 * Shortcode functionality for Show My Social Icons plugin.
 *
 * @package ShowMySocialIcons
 */

/**
 * Add custom styles for social icons.
 *
 * @return void
 */
function smsi_add_custom_styles() {
    $icon_size = get_option('icon_size', '30px');
    $icon_alignment = get_option('icon_alignment', 'Center');
    $custom_color = get_option('icon_custom_color', '#000000');

    $css = "
    .my-social-icons, .my-social-icon-single {
        text-align: " . strtolower($icon_alignment) . "
    }
    .my-social-icons img, .my-social-icon-single img {
        width: " . $icon_size .";
        height: auto;
    }
    ";

    if($custom_color !== '#000000') {
        $css .= "
        .my-social-icons img[src$='.svg'], .my-social-icon-single img[src$='.svg'] {
            filter: brightness(0) saturate(100%) invert(0%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(100%) contrast(100%);
        }
        .my-social-icons img[src$='.svg'], .my-social-icon-single img[src$='.svg'] {
            filter: brightness(0) saturate(100%) " . smsi_hex_to_filter($custom_color) . ";
        }
        ";
    }

    echo '<style>' . $css . '</style>';
}
add_action('wp_head', 'smsi_add_custom_styles');

/**
 * Shortcode to display all social icons.
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output of social icons.
 */
function smsi_show_my_social_icons_shortcode($atts) {
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
        $container_inline_styles = "style='text-align: " . esc_attr(strtolower($atts['alignment'])) . ";'";
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
add_shortcode('show_my_social_icons', 'smsi_show_my_social_icons_shortcode');

/**
 * Shortcode to display individual icon.
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output of the individual icon.
 */
function smsi_show_my_social_icon_shortcode($atts) {
    $platforms = my_social_media_platforms();
    global $smsi_plugin_dir_path;
    $atts = shortcode_atts(
        array(
            'platform' => '',
            'type' => get_option('icon_type', 'PNG'),
            'size' => get_option('icon_size', '30px'),
            'style' => get_option('icon_style', 'Icon only full color'),
            'alignment' => get_option('icon_alignment', 'Center'),
            'custom_color' => get_option('icon_custom_color', '#000000')
        ), 
        $atts
    );

    $filter = get_option('icon_custom_filter', '');
    $filter_styles = $filter ? "filter: $filter;" : "";

    $container_inline_styles = "style='text-align: " . esc_attr(strtolower($atts['alignment'])) . ";'";
    $icon_inline_styles = "style='width: " . esc_attr($atts['size']) . "; " . $filter_styles . "'";

    $platform = strtolower($atts['platform']);
    if (isset($platforms[$platform])) {
        list($icon_path_start, $icon_file_name_end) = show_my_social_icons_file_path($atts['type'], $atts['size'], $atts['style']);

        $url = get_option($platform . '_url');
        if ($url) {
            $icon_path = $icon_path_start . $platform . $icon_file_name_end;
            return "<div class='smsi-single-icon-wrapper' $container_inline_styles><a href='" . esc_url($url) . "' target='_blank'><img src='" . $smsi_plugin_dir_path . $icon_path . "' class='smsi-icon smsi-icon-$platform' $icon_inline_styles /></a></div>";
        }
    }
    return '';
}
add_shortcode('my_social_icon', 'smsi_show_my_social_icon_shortcode');