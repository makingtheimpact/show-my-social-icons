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
    $icon_size       = get_option('icon_size', '30px');
    $icon_alignment  = get_option('icon_alignment', 'Center');
    $icon_type       = get_option('icon_type', 'PNG');
    $icon_style      = get_option('icon_style', 'Icon only full color');
    $custom_color    = get_option('icon_custom_color', '');
    $spacing         = smsi_sanitize_unit_value(get_option('spacing', '10px'));
    $margin_top      = smsi_sanitize_unit_value(get_option('icon_container_margin_top', '0px'));
    $margin_right    = smsi_sanitize_unit_value(get_option('icon_container_margin_right', '0px'));
    $margin_bottom   = smsi_sanitize_unit_value(get_option('icon_container_margin_bottom', '0px'));
    $margin_left     = smsi_sanitize_unit_value(get_option('icon_container_margin_left', '0px'));

    $css = "
    .my-social-icons, .my-social-icon-single {
        text-align: " . strtolower(esc_attr($icon_alignment)) . ";
        margin-top: " . esc_attr($margin_top) . ";
        margin-right: " . esc_attr($margin_right) . ";
        margin-bottom: " . esc_attr($margin_bottom) . ";
        margin-left: " . esc_attr($margin_left) . ";
    }
    .my-social-icons img, .my-social-icon-single img,
    .my-social-icons svg, .my-social-icon-single svg {
        width: " . esc_attr($icon_size) . ";
        height: auto;
        margin-right: " . esc_attr($spacing) . "; /* Ensure this matches the dynamic spacing */
    }
    ";

    if ($icon_type === 'SVG' && $icon_style === 'Icon only custom color' && !empty($custom_color)) {
        $css .= "
        .my-social-icons svg, .my-social-icon-single svg {
            fill: " . esc_attr($custom_color) . ";
        }
        ";
    }

    wp_add_inline_style('smsi-frontend-styles', $css);
}
add_action('wp_head', 'smsi_add_custom_styles');

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

/**
 * Shortcode to display individual icon.
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output of the individual icon.
 */
function smsi_show_my_social_icon_shortcode($atts) {
    $platforms = smsi_get_platform_list();
    $atts = shortcode_atts(
        array(
            'platform'     => '',
            'type'         => get_option('icon_type', 'PNG'),
            'size'         => get_option('icon_size', '30px'),
            'style'        => get_option('icon_style', 'Icon only full color'),
            'alignment'    => get_option('icon_alignment', 'Center'),
            'custom_color' => get_option('icon_custom_color', '#000000'),
            'margin_top'       => get_option('icon_container_margin_top', '0px'),
            'margin_right'     => get_option('icon_container_margin_right', '0px'),
            'margin_bottom'    => get_option('icon_container_margin_bottom', '0px'),
            'margin_left'      => get_option('icon_container_margin_left', '0px')
        ),
        $atts,
        'my_social_icon'
    );

    $platform = strtolower($atts['platform']);
    $url      = get_option($platform . '_url');

    // Sanitize and prepare styles
    $icon_inline_styles  = "width: " . smsi_sanitize_unit_value($atts['size']) . "; height: auto;";
    $container_style     = "text-align: " . strtolower(esc_attr($atts['alignment'])) . "; " .
                           "margin-top: " . smsi_sanitize_unit_value($atts['margin_top']) . "; " .
                           "margin-right: " . smsi_sanitize_unit_value($atts['margin_right']) . "; " .
                           "margin-bottom: " . smsi_sanitize_unit_value($atts['margin_bottom']) . "; " .
                           "margin-left: " . smsi_sanitize_unit_value($atts['margin_left']) . ";";

    if ($atts['style'] === 'Icon only custom color' && $atts['type'] === 'SVG') {
        $icon_inline_styles .= " fill: " . esc_attr($atts['custom_color']) . ";";
    }

    if (isset($platforms[$platform])) {
        $icon_path = smsi_get_single_social_icon_path($platform, $atts['type'], $atts['size'], $atts['style']);
        $url       = get_option($platform . '_url');
        if ($url) {
            if ($atts['type'] === 'SVG') {
                $svg_content = smsi_get_file_contents($icon_path);
                // Generate a unique ID for this SVG
                $unique_id = 'smsi-' . $platform . '-' . uniqid();

                // Determine the fill color
                $svg_fill = '#000000';
                if ($atts['style'] === 'Icon only custom color' && !empty($atts['custom_color'])) {
                    $svg_fill = $atts['custom_color'];
                } elseif ($atts['style'] === 'Icon only white') {
                    $svg_fill = '#FFFFFF';
                } elseif ($atts['style'] === 'Icon only black') {
                    $svg_fill = '#000000';
                }

                // Remove the existing style tag
                $svg_content = preg_replace('/<style>.*?<\/style>/s', '', $svg_content);

                // Add a new style tag with scoped styles
                $svg_content = preg_replace('/<svg /', '<svg style="' . esc_attr($icon_inline_styles) . '" ', $svg_content);
                $svg_content = str_replace('<defs>', '<defs><style>.' . $unique_id . ' .cls-1 { fill: ' . esc_attr($svg_fill) . '; }</style>', $svg_content);

                // Update class names to be unique
                $svg_content = preg_replace('/class="cls-([0-9]+)"/', 'class="' . $unique_id . ' cls-$1"', $svg_content);

                // Get the hover effect class
                $hover_effect_class = 'smsi-icon-hover-' . esc_attr(get_option('icon_hover_effect', 'style1'));

                $output_html = "<div class='smsi-single-icon-wrapper " . esc_attr($hover_effect_class) . "' style='" . esc_attr($container_style) . "'><a href='" . esc_url($url) . "' target='_blank'>$svg_content</a></div>";
                return $output_html;
            } else {
                // Get the hover effect class
                $hover_effect_class = 'smsi-icon-hover-' . esc_attr(get_option('icon_hover_effect', 'style1'));
                $output_html        = "<div class='smsi-single-icon-wrapper " . esc_attr($hover_effect_class) . "' style='" . esc_attr($container_style) . "'><a href='" . esc_url($url) . "' target='_blank'><img src='" . esc_url($icon_path) . "' class='smsi-icon smsi-icon-" . esc_attr($platform) . "' style='" . esc_attr($icon_inline_styles) . "' /></a></div>";
                return $output_html;
            }
        }
    }
    return '';
}
add_shortcode('my_social_icon', 'smsi_show_my_social_icon_shortcode');

function smsi_apply_custom_color($html, $custom_color) {
    if ($custom_color !== '#000000') {
        $dom = new DOMDocument();
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $svg = $dom->getElementsByTagName('svg')->item(0);
        if ($svg) {
            $svg->setAttribute('fill', $custom_color);
            return $dom->saveHTML();
        }
    }
    return $html;
}