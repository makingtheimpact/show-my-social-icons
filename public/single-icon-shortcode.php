<?php
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
            'margin_top'   => get_option('icon_container_margin_top', '0px'),
            'margin_right' => get_option('icon_container_margin_right', '0px'),
            'margin_bottom'=> get_option('icon_container_margin_bottom', '0px'),
            'margin_left'  => get_option('icon_container_margin_left', '0px'),
            'inline'       => get_option('icon_inline', false),
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

    $show_inline = smsi_sanitize_boolean($atts['inline']);
    if ($show_inline) {
        $inline_container_start = "<div class='smsi-single-icon-inline'>";
        $inline_container_end = "</div>";
    } else {
        $inline_container_start = "";
        $inline_container_end = "";
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

                $output_html = $inline_container_start . "<div class='smsi-single-icon-wrapper " . esc_attr($hover_effect_class) . "' style='" . esc_attr($container_style) . "'><a href='" . esc_url($url) . "' target='_blank'>$svg_content</a></div>" . $inline_container_end;
                return $output_html;
            } else {
                // Get the hover effect class
                $hover_effect_class = 'smsi-icon-hover-' . esc_attr(get_option('icon_hover_effect', 'style1'));
                $output_html = $inline_container_start . "<div class='smsi-single-icon-wrapper " . esc_attr($hover_effect_class) . "' style='" . esc_attr($icon_inline_styles) . esc_attr($container_style) . "'><a href='" . esc_url($url) . "' target='_blank'><img src='" . esc_url($icon_path) . "' class='smsi-icon smsi-icon-" . esc_attr($platform) . "' alt='" . esc_attr($platform) . " icon'></a></div>" . $inline_container_end;
                return $output_html;
            }
        }
    }
    return '';
}
add_shortcode('my_social_icon', 'smsi_show_my_social_icon_shortcode');