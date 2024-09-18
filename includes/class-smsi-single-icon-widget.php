<?php
/**
 * SMSI Single Icon Widget
 *
 * This file contains the SMSI Single Icon Widget class.
 *
 * @package ShowMySocialIcons
 */

class SMSI_Single_Icon_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'smsi_single_icon_widget',
            esc_html__('Social Media Icon (Single)', 'show-my-social-icons'),
            array('description' => esc_html__('Display a single social media icon', 'show-my-social-icons'))
        );
    }

    public function widget($args, $instance) {
        echo wp_kses_post($args['before_widget']);
        $margin_top = isset($instance['margin_top']) ? intval($instance['margin_top']) : 0;
        $margin_right = isset($instance['margin_right']) ? intval($instance['margin_right']) : 0;
        $margin_bottom = isset($instance['margin_bottom']) ? intval($instance['margin_bottom']) : 0;
        $margin_left = isset($instance['margin_left']) ? intval($instance['margin_left']) : 0;
        echo do_shortcode(sprintf(
            '[my_social_icon platform="%s" type="%s" size="%s" style="%s" alignment="%s" custom_color="%s" margin_top="%dpx" margin_right="%dpx" margin_bottom="%dpx" margin_left="%dpx"]',
            esc_attr($instance['platform']),
            esc_attr($instance['icon_type']),
            esc_attr($instance['icon_size']),
            esc_attr($instance['icon_style']),
            esc_attr($instance['icon_alignment']),
            esc_attr($instance['custom_color']),
            $margin_top,
            $margin_right,
            $margin_bottom,
            $margin_left
        ));
        echo wp_kses_post($args['after_widget']);
    }

    public function form($instance) {
        $platform = !empty($instance['platform']) ? $instance['platform'] : '';
        $icon_type = !empty($instance['icon_type']) ? $instance['icon_type'] : 'PNG';
        $icon_size = !empty($instance['icon_size']) ? $instance['icon_size'] : '30px';
        $icon_style = !empty($instance['icon_style']) ? $instance['icon_style'] : 'Icon only full color';
        $icon_alignment = !empty($instance['icon_alignment']) ? $instance['icon_alignment'] : 'Center';
        $custom_color = !empty($instance['custom_color']) ? $instance['custom_color'] : '#000000';
        $margin_top = !empty($instance['margin_top']) ? intval($instance['margin_top']) : 0;
        $margin_right = !empty($instance['margin_right']) ? intval($instance['margin_right']) : 0;
        $margin_bottom = !empty($instance['margin_bottom']) ? intval($instance['margin_bottom']) : 0;
        $margin_left = !empty($instance['margin_left']) ? intval($instance['margin_left']) : 0;

        $platforms = my_social_media_platforms();

        $this->render_select_field('icon_type', esc_html__('Icon Type:', 'show-my-social-icons'), ['SVG' => 'SVG', 'PNG' => 'PNG'], $icon_type);
        $this->render_select_field('icon_style', esc_html__('Icon Style:', 'show-my-social-icons'), [
            'Icon only full color' => esc_html__('Icon only full color', 'show-my-social-icons'),
            'Icon only black' => esc_html__('Icon only black', 'show-my-social-icons'),
            'Icon only white' => esc_html__('Icon only white', 'show-my-social-icons'),
            'Icon only custom color' => esc_html__('Icon only custom color', 'show-my-social-icons'),
            'Full logo horizontal' => esc_html__('Full logo horizontal', 'show-my-social-icons'),
            'Full logo square' => esc_html__('Full logo square', 'show-my-social-icons')
        ], $icon_style);
        $this->render_color_field('custom_color', esc_html__('Custom Color:', 'show-my-social-icons'), $custom_color, $icon_type === 'PNG' || $icon_style !== 'Icon only custom color');
        $this->render_select_field('platform', esc_html__('Platform:', 'show-my-social-icons'), $platforms, $platform);
        $this->render_text_field('icon_size', esc_html__('Icon Size:', 'show-my-social-icons'), $icon_size);
        $this->render_select_field('icon_alignment', esc_html__('Icon Alignment:', 'show-my-social-icons'), [
            'Left' => esc_html__('Left', 'show-my-social-icons'),
            'Center' => esc_html__('Center', 'show-my-social-icons'),
            'Right' => esc_html__('Right', 'show-my-social-icons')
        ], $icon_alignment);
        $this->render_margin_fields($margin_top, $margin_right, $margin_bottom, $margin_left);

        wp_enqueue_script('smsi-widget-admin', plugin_dir_url(__FILE__) . '../assets/js/widget-admin.js', array('jquery'), '1.0', true);
        wp_localize_script('smsi-widget-admin', 'smsiWidgetData', array(
            'widgetId' => $this->id
        ));
    }

    private function render_select_field($name, $label, $options, $selected) {
        printf(
            '<p><label for="%1$s">%2$s</label><select class="widefat" id="%1$s" name="%3$s">',
            esc_attr($this->get_field_id($name)),
            esc_html($label),
            esc_attr($this->get_field_name($name))
        );
        foreach ($options as $value => $option_label) {
            printf(
                '<option value="%s"%s>%s</option>',
                esc_attr($value),
                selected($selected, $value, false),
                esc_html($option_label)
            );
        }
        echo '</select></p>';
    }

    private function render_color_field($name, $label, $value, $disabled) {
        printf(
            '<p><label for="%1$s">%2$s</label><input class="widefat" id="%1$s" name="%3$s" type="color" value="%4$s"%5$s></p>',
            esc_attr($this->get_field_id($name)),
            esc_html($label),
            esc_attr($this->get_field_name($name)),
            esc_attr($value),
            $disabled ? ' disabled' : ''
        );
    }

    private function render_text_field($name, $label, $value) {
        printf(
            '<p><label for="%1$s">%2$s</label><input class="widefat" id="%1$s" name="%3$s" type="text" value="%4$s"></p>',
            esc_attr($this->get_field_id($name)),
            esc_html($label),
            esc_attr($this->get_field_name($name)),
            esc_attr($value)
        );
    }

    private function render_margin_fields($top, $right, $bottom, $left) {
        echo '<p><label>' . esc_html__('Container Margins (px):', 'show-my-social-icons') . '</label>';
        echo '<div style="display: flex; justify-content: space-between; margin-bottom: 10px;">';
        $this->render_number_field('margin_top', esc_html__('Top', 'show-my-social-icons'), $top);
        $this->render_number_field('margin_bottom', esc_html__('Bottom', 'show-my-social-icons'), $bottom);
        echo '</div><div style="display: flex; justify-content: space-between;">';
        $this->render_number_field('margin_right', esc_html__('Right', 'show-my-social-icons'), $right);
        $this->render_number_field('margin_left', esc_html__('Left', 'show-my-social-icons'), $left);
        echo '</div></p>';
        echo '<p><button type="button" class="button" id="' . esc_attr($this->get_field_id('link_margins')) . '">' . esc_html__('Link Margins', 'show-my-social-icons') . '</button></p>';
    }

    private function render_number_field($name, $placeholder, $value) {
        printf(
            '<input style="width: 48%%;" type="number" id="%1$s" name="%2$s" value="%3$s" placeholder="%4$s">',
            esc_attr($this->get_field_id($name)),
            esc_attr($this->get_field_name($name)),
            esc_attr($value),
            esc_attr($placeholder)
        );
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['platform'] = (!empty($new_instance['platform'])) ? sanitize_text_field($new_instance['platform']) : '';
        $instance['icon_type'] = (!empty($new_instance['icon_type'])) ? sanitize_text_field($new_instance['icon_type']) : '';
        $instance['icon_size'] = (!empty($new_instance['icon_size'])) ? sanitize_text_field($new_instance['icon_size']) : '';
        $instance['icon_style'] = (!empty($new_instance['icon_style'])) ? sanitize_text_field($new_instance['icon_style']) : '';
        $instance['icon_alignment'] = (!empty($new_instance['icon_alignment'])) ? sanitize_text_field($new_instance['icon_alignment']) : '';
        $instance['custom_color'] = (!empty($new_instance['custom_color'])) ? sanitize_hex_color($new_instance['custom_color']) : '';
        $instance['margin_top'] = (!empty($new_instance['margin_top'])) ? intval($new_instance['margin_top']) : 0;
        $instance['margin_right'] = (!empty($new_instance['margin_right'])) ? intval($new_instance['margin_right']) : 0;
        $instance['margin_bottom'] = (!empty($new_instance['margin_bottom'])) ? intval($new_instance['margin_bottom']) : 0;
        $instance['margin_left'] = (!empty($new_instance['margin_left'])) ? intval($new_instance['margin_left']) : 0;
        return $instance;
    }
}