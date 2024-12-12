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

// Include the shortcodes
include_once(plugin_dir_path(__FILE__) . 'all-icons-shortcode.php');
include_once(plugin_dir_path(__FILE__) . 'single-icon-shortcode.php');
include_once(plugin_dir_path(__FILE__) . 'select-icons-shortcode.php');
