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
            'Social Media Icon (Single)',
            array('description' => 'Display a single social media icon')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        $margin_top = isset($instance['margin_top']) ? $instance['margin_top'] : '0';
        $margin_right = isset($instance['margin_right']) ? $instance['margin_right'] : '0';
        $margin_bottom = isset($instance['margin_bottom']) ? $instance['margin_bottom'] : '0';
        $margin_left = isset($instance['margin_left']) ? $instance['margin_left'] : '0';
        echo do_shortcode("[my_social_icon platform=\"{$instance['platform']}\" type=\"{$instance['icon_type']}\" size=\"{$instance['icon_size']}\" style=\"{$instance['icon_style']}\" alignment=\"{$instance['icon_alignment']}\" custom_color=\"{$instance['custom_color']}\" margin_top=\"{$margin_top}px\" margin_right=\"{$margin_right}px\" margin_bottom=\"{$margin_bottom}px\" margin_left=\"{$margin_left}px\"]");
        echo $args['after_widget'];
    }

    public function form($instance) {
        $platform = !empty($instance['platform']) ? $instance['platform'] : '';
        $icon_type = !empty($instance['icon_type']) ? $instance['icon_type'] : 'PNG';
        $icon_size = !empty($instance['icon_size']) ? $instance['icon_size'] : '30px';
        $icon_style = !empty($instance['icon_style']) ? $instance['icon_style'] : 'Icon only full color';
        $icon_alignment = !empty($instance['icon_alignment']) ? $instance['icon_alignment'] : 'Center';
        $custom_color = !empty($instance['custom_color']) ? $instance['custom_color'] : '#000000';
        $margin_top = !empty($instance['margin_top']) ? $instance['margin_top'] : '0';
        $margin_right = !empty($instance['margin_right']) ? $instance['margin_right'] : '0';
        $margin_bottom = !empty($instance['margin_bottom']) ? $instance['margin_bottom'] : '0';
        $margin_left = !empty($instance['margin_left']) ? $instance['margin_left'] : '0';

        $platforms = my_social_media_platforms();

        $this->render_select_field('icon_type', 'Icon Type:', ['SVG' => 'SVG', 'PNG' => 'PNG'], $icon_type);
        $this->render_select_field('icon_style', 'Icon Style:', [
            'Icon only full color' => 'Icon only full color',
            'Icon only black' => 'Icon only black',
            'Icon only white' => 'Icon only white',
            'Icon only custom color' => 'Icon only custom color',
            'Full logo horizontal' => 'Full logo horizontal',
            'Full logo square' => 'Full logo square'
        ], $icon_style);
        $this->render_color_field('custom_color', 'Custom Color:', $custom_color, $icon_type === 'PNG' || $icon_style !== 'Icon only custom color');
        $this->render_select_field('platform', 'Platform:', $platforms, $platform);
        $this->render_text_field('icon_size', 'Icon Size:', $icon_size);
        $this->render_select_field('icon_alignment', 'Icon Alignment:', [
            'Left' => 'Left',
            'Center' => 'Center',
            'Right' => 'Right'
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
            $this->get_field_id($name),
            $label,
            $this->get_field_name($name)
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
            $this->get_field_id($name),
            $label,
            $this->get_field_name($name),
            esc_attr($value),
            $disabled ? ' disabled' : ''
        );
    }

    private function render_text_field($name, $label, $value) {
        printf(
            '<p><label for="%1$s">%2$s</label><input class="widefat" id="%1$s" name="%3$s" type="text" value="%4$s"></p>',
            $this->get_field_id($name),
            $label,
            $this->get_field_name($name),
            esc_attr($value)
        );
    }

    private function render_margin_fields($top, $right, $bottom, $left) {
        echo '<p><label>Container Margins (px):</label>';
        echo '<div style="display: flex; justify-content: space-between; margin-bottom: 10px;">';
        $this->render_number_field('margin_top', 'Top', $top);
        $this->render_number_field('margin_bottom', 'Bottom', $bottom);
        echo '</div><div style="display: flex; justify-content: space-between;">';
        $this->render_number_field('margin_right', 'Right', $right);
        $this->render_number_field('margin_left', 'Left', $left);
        echo '</div></p>';
        echo '<p><button type="button" class="button" id="' . $this->get_field_id('link_margins') . '">Link Margins</button></p>';
    }

    private function render_number_field($name, $placeholder, $value) {
        printf(
            '<input style="width: 48%%;" type="number" id="%1$s" name="%2$s" value="%3$s" placeholder="%4$s">',
            $this->get_field_id($name),
            $this->get_field_name($name),
            esc_attr($value),
            $placeholder
        );
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['platform'] = (!empty($new_instance['platform'])) ? strip_tags($new_instance['platform']) : '';
        $instance['icon_type'] = (!empty($new_instance['icon_type'])) ? strip_tags($new_instance['icon_type']) : '';
        $instance['icon_size'] = (!empty($new_instance['icon_size'])) ? strip_tags($new_instance['icon_size']) : '';
        $instance['icon_style'] = (!empty($new_instance['icon_style'])) ? strip_tags($new_instance['icon_style']) : '';
        $instance['icon_alignment'] = (!empty($new_instance['icon_alignment'])) ? strip_tags($new_instance['icon_alignment']) : '';
        $instance['custom_color'] = (!empty($new_instance['custom_color'])) ? strip_tags($new_instance['custom_color']) : '';
        $instance['margin_top'] = (!empty($new_instance['margin_top'])) ? strip_tags($new_instance['margin_top']) : '';
        $instance['margin_right'] = (!empty($new_instance['margin_right'])) ? strip_tags($new_instance['margin_right']) : '';
        $instance['margin_bottom'] = (!empty($new_instance['margin_bottom'])) ? strip_tags($new_instance['margin_bottom']) : '';
        $instance['margin_left'] = (!empty($new_instance['margin_left'])) ? strip_tags($new_instance['margin_left']) : '';
        return $instance;
    }
}