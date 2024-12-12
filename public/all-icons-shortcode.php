<?php
/**
 * Shortcode to display all social icons.
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output of social icons.
 */
function smsi_show_my_social_icons_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'type'          => get_option('icon_type', 'PNG'),
            'size'          => get_option('icon_size', '30px'),
            'style'         => get_option('icon_style', 'Icon only full color'),
            'alignment'     => get_option('icon_alignment', 'Center'),
            'custom_color'  => get_option('icon_custom_color', '#000000'),
            'margin_top'    => get_option('icon_container_margin_top', '0px'),
            'margin_right'  => get_option('icon_container_margin_right', '0px'),
            'margin_bottom' => get_option('icon_container_margin_bottom', '0px'),
            'margin_left'   => get_option('icon_container_margin_left', '0px'),
            'spacing'       => get_option('spacing', '10px')
        ), 
        $atts,
        'show_my_social_icons'
    );

    $platforms = smsi_get_platform_list();
    $container_style = "text-align: " . strtolower(esc_attr($atts['alignment'])) . "; " .
                       "margin-top: " . smsi_sanitize_unit_value($atts['margin_top']) . "; " .
                       "margin-right: " . smsi_sanitize_unit_value($atts['margin_right']) . "; " .
                       "margin-bottom: " . smsi_sanitize_unit_value($atts['margin_bottom']) . "; " .
                       "margin-left: " . smsi_sanitize_unit_value($atts['margin_left']) . ";";

    // Icon spacing
    $spacing = isset($atts['spacing']) ? smsi_sanitize_unit_value($atts['spacing']) : '10px';
    $spacing_parts = smsi_split_css_value($spacing);
    $spacing_value = $spacing_parts['value'];
    $spacing_unit = $spacing_parts['unit'];

    if ($atts['alignment'] === 'Center') {
        $single_icon_margin_left = ($spacing_value / 2) . $spacing_unit;
        $single_icon_margin_right = ($spacing_value / 2) . $spacing_unit;
    } elseif ($atts['alignment'] === 'Left') {
        $single_icon_margin_right = $spacing;
        $single_icon_margin_left = '0' . $spacing_unit;
    } else { // Right
        $single_icon_margin_left = $spacing;
        $single_icon_margin_right = '0' . $spacing_unit;
    }

    $ordered_icons = array();
    foreach ($platforms as $platform => $config) {
        $order = intval(get_option($platform . '_order', 0));
        $shortcode = "[my_social_icon platform='$platform' type='{$atts['type']}' size='{$atts['size']}' style='{$atts['style']}' alignment='{$atts['alignment']}' custom_color='{$atts['custom_color']}']";
        $icon_html = do_shortcode($shortcode);
        if (!empty($icon_html)) {
            $ordered_icons[$order][] = "<div class='smsi-icon-wrapper' style='margin-right: " . esc_attr($single_icon_margin_right) . "; margin-left: " . esc_attr($single_icon_margin_left) . ";'>{$icon_html}</div>";
        }
    }
    ksort($ordered_icons);

    $icon_output = '';
    foreach ($ordered_icons as $icons) {
        $icon_output .= implode('', $icons);
    }

    $html = "<div class='smsi-icons-wrapper' style='" . esc_attr($container_style) . "'>" . $icon_output . "</div>";
    return $html;
}
add_shortcode('show_my_social_icons', 'smsi_show_my_social_icons_shortcode');
