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
    $icon_type = get_option('icon_type', 'PNG');
    $icon_style = get_option('icon_style', 'Icon only full color');
    $custom_color = get_option('icon_custom_color', '');

    $css = "
    .my-social-icons, .my-social-icon-single {
        text-align: " . strtolower($icon_alignment) . "
    }
    .my-social-icons img, .my-social-icon-single img,
    .my-social-icons svg, .my-social-icon-single svg {
        width: " . $icon_size .";
        height: auto;
    }
    ";

    if($icon_type === 'SVG' && $icon_style === 'Icon only custom color' && !empty($custom_color)) {
        $css .= "
        .my-social-icons svg, .my-social-icon-single svg {
            fill: " . $custom_color . ";
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
            'type' => get_option('icon_type', 'PNG'),
            'size' => get_option('icon_size', '30px'),
            'style' => get_option('icon_style', 'Icon only full color'),
            'alignment' => get_option('icon_alignment', 'Center'),
            'custom_color' => get_option('icon_custom_color', '#000000'),
            'spacing' => get_option('icon_spacing', '10px'),
            'margin_top' => get_option('icon_container_margin_top', '0px'),
            'margin_right' => get_option('icon_container_margin_right', '0px'),
            'margin_bottom' => get_option('icon_container_margin_bottom', '0px'),
            'margin_left' => get_option('icon_container_margin_left', '0px')
        ), 
        $atts
    );

    $platforms = my_social_media_platforms();
    $container_style = "text-align: " . esc_attr(strtolower($atts['alignment'])) . "; " .
                       "margin-top: " . smsi_sanitize_margin($atts['margin_top']) . "; " .
                       "margin-right: " . smsi_sanitize_margin($atts['margin_right']) . "; " .
                       "margin-bottom: " . smsi_sanitize_margin($atts['margin_bottom']) . "; " .
                       "margin-left: " . smsi_sanitize_margin($atts['margin_left']) . ";";

    $ordered_icons = array();
    foreach ($platforms as $platform => $config) {
        $order = get_option($platform . '_order', 0);
        $shortcode = "[my_social_icon platform='$platform' type='{$atts['type']}' size='{$atts['size']}' style='{$atts['style']}' alignment='{$atts['alignment']}' custom_color='{$atts['custom_color']}']";
        $icon_html = do_shortcode($shortcode);
        if (!empty($icon_html)) {
            $ordered_icons[$order] = "<div class='smsi-icon-wrapper' style='display: inline-block; margin-right: {$atts['spacing']};'>{$icon_html}</div>";
        }
    }
    ksort($ordered_icons);

    $html = "<div class='my-social-icons' style='" . $container_style . "'>" . implode('', $ordered_icons) . "</div>";
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
    $platforms = my_social_media_platforms();
    $atts = shortcode_atts(
        array(
            'platform' => '',
            'type' => get_option('icon_type', 'PNG'),
            'size' => get_option('icon_size', '30px'),
            'style' => get_option('icon_style', 'Icon only full color'),
            'alignment' => get_option('icon_alignment', 'Center'),
            'custom_color' => get_option('icon_custom_color', '#000000'),
            'margin_top' => get_option('icon_container_margin_top', '0px'),
            'margin_right' => get_option('icon_container_margin_right', '0px'),
            'margin_bottom' => get_option('icon_container_margin_bottom', '0px'),
            'margin_left' => get_option('icon_container_margin_left', '0px')
        ), 
        $atts
    );

    if ($atts['type'] === 'SVG') {
        if (!in_array($atts['style'], ['Icon only black', 'Icon only white', 'Icon only custom color'])) {
            $atts['style'] = 'Icon only black';
        }
    } else {
        if ($atts['style'] === 'Icon only custom color') {
            $atts['style'] = 'Icon only full color';
        }
    }

    $container_style = "text-align: " . esc_attr(strtolower($atts['alignment'])) . "; " .
                       "margin-top: " . esc_attr($atts['margin_top']) . "; " .
                       "margin-right: " . esc_attr($atts['margin_right']) . "; " .
                       "margin-bottom: " . esc_attr($atts['margin_bottom']) . "; " .
                       "margin-left: " . esc_attr($atts['margin_left']) . ";";

    $icon_inline_styles = "style='width: " . esc_attr($atts['size']) . "; height: auto;";

    if ($atts['style'] === 'Icon only custom color') {
        $icon_inline_styles .= " fill: " . esc_attr($atts['custom_color']) . ";";
    }
    $icon_inline_styles .= "'";

    $platform = strtolower($atts['platform']);
    if (isset($platforms[$platform])) {
        list($icon_path_start, $icon_file_name_end) = show_my_social_icons_file_path($atts['type'], $atts['size'], $atts['style']);

        $url = get_option($platform . '_url');
        if ($url) {
            $icon_path = SMSI_PLUGIN_DIR . $icon_path_start . $platform . $icon_file_name_end;
            if ($atts['type'] === 'SVG') {
                $svg_content = file_get_contents($icon_path);

                // Generate a unique ID for this SVG
                $unique_id = 'smsi-' . $platform . '-' . uniqid();

                // Remove the existing style tag
                $svg_content = preg_replace('/<style>.*?<\/style>/s', '', $svg_content);

                // Add a new style tag with scoped styles
                $svg_content = preg_replace('/<svg /', '<svg ' . $icon_inline_styles . ' ', $svg_content);
                $svg_content = str_replace('<defs>', '<defs><style>.' . $unique_id . ' { fill: url(#' . $unique_id . '-gradient); }</style>', $svg_content);

                // Update class names and gradient IDs to be unique
                $svg_content = preg_replace('/class="cls-1"/', 'class="' . $unique_id . '"', $svg_content);
                $svg_content = preg_replace('/id="linear-gradient"/', 'id="' . $unique_id . '-gradient"', $svg_content);

                if ($atts['style'] !== 'Icon only full color') {
                    $fill_color = '#000000';
                    if ($atts['style'] === 'Icon only white') {
                        $fill_color = '#FFFFFF';
                    } elseif ($atts['style'] === 'Icon only custom color' && !empty($atts['custom_color'])) {
                        $fill_color = $atts['custom_color'];
                    }

                    // Replace the gradient with a solid color
                    $svg_content = preg_replace('/<linearGradient.*?<\/linearGradient>/s', '', $svg_content);
                    $svg_content = str_replace('url(#' . $unique_id . '-gradient)', $fill_color, $svg_content);
                }

                return "<div class='smsi-single-icon-wrapper' style='" . $container_style . "'><a href='" . esc_url($url) . "' target='_blank'>$svg_content</a></div>";
            } else {
                return "<div class='smsi-single-icon-wrapper' style='" . $container_style . "'><a href='" . esc_url($url) . "' target='_blank'><img src='" . plugin_dir_url(__FILE__) . '../' . $icon_path_start . $platform . $icon_file_name_end . "' class='smsi-icon smsi-icon-$platform' $icon_inline_styles /></a></div>";
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

function smsi_sanitize_margin($value) {
    $value = trim($value);
    if (is_numeric($value)) {
        return $value . 'px';
    }
    if (preg_match('/^(\d+)(px|em|rem|%)$/', $value)) {
        return $value;
    }
    return '0';
}