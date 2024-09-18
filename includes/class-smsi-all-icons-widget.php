<?php
/**
 * SMSI All Icons Widget
 *
 * This file contains the SMSI All Icons Widget class.
 *
 * @package ShowMySocialIcons
 */

class SMSI_All_Icons_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'smsi_all_icons_widget',
            'Social Media Icons (All)',
            array('description' => 'Display all your social media icons'),
            array('customize_selective_refresh' => true)
        );
    }

    public function widget($args, $instance) {
        echo wp_kses_post($args['before_widget']);
        $margin_top = smsi_sanitize_margin(isset($instance['margin_top']) ? $instance['margin_top'] : '0');
        $margin_right = smsi_sanitize_margin(isset($instance['margin_right']) ? $instance['margin_right'] : '0');
        $margin_bottom = smsi_sanitize_margin(isset($instance['margin_bottom']) ? $instance['margin_bottom'] : '0');
        $margin_left = smsi_sanitize_margin(isset($instance['margin_left']) ? $instance['margin_left'] : '0');
        echo do_shortcode("[show_my_social_icons type=\"{$instance['icon_type']}\" size=\"{$instance['icon_size']}\" style=\"{$instance['icon_style']}\" alignment=\"{$instance['icon_alignment']}\" custom_color=\"{$instance['custom_color']}\" spacing=\"{$instance['spacing']}\" margin_top=\"$margin_top\" margin_right=\"$margin_right\" margin_bottom=\"$margin_bottom\" margin_left=\"$margin_left\"]");
        echo wp_kses_post($args['after_widget']);
    }

    public function form($instance) {
        $icon_type = !empty($instance['icon_type']) ? $instance['icon_type'] : 'PNG';
        $icon_size = !empty($instance['icon_size']) ? $instance['icon_size'] : '30px';
        $icon_style = !empty($instance['icon_style']) ? $instance['icon_style'] : 'Icon only full color';
        $icon_alignment = !empty($instance['icon_alignment']) ? $instance['icon_alignment'] : 'Center';
        $custom_color = !empty($instance['custom_color']) ? $instance['custom_color'] : '#000000';
        $spacing = !empty($instance['spacing']) ? $instance['spacing'] : '10px';
        $margin_top = !empty($instance['margin_top']) ? $instance['margin_top'] : '0';
        $margin_right = !empty($instance['margin_right']) ? $instance['margin_right'] : '0';
        $margin_bottom = !empty($instance['margin_bottom']) ? $instance['margin_bottom'] : '0';
        $margin_left = !empty($instance['margin_left']) ? $instance['margin_left'] : '0';

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_type')); ?>">Icon Type:</label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_type')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_type')); ?>">
                <option value="PNG" <?php selected($icon_type, 'PNG'); ?>>PNG</option>
                <option value="SVG" <?php selected($icon_type, 'SVG'); ?>>SVG</option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_size')); ?>">Icon Size:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_size')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_size')); ?>" type="text" value="<?php echo esc_attr($icon_size); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_style')); ?>">Icon Style:</label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_style')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_style')); ?>">
                <option value="Icon only full color" <?php selected($icon_style, 'Icon only full color'); ?>>Icon only full color</option>
                <option value="Icon only black" <?php selected($icon_style, 'Icon only black'); ?>>Icon only black</option>
                <option value="Icon only white" <?php selected($icon_style, 'Icon only white'); ?>>Icon only white</option>
                <option value="Icon only custom color" <?php selected($icon_style, 'Icon only custom color'); ?> <?php echo $icon_type === 'PNG' ? 'disabled' : ''; ?>>Icon only custom color</option>
                <option value="Full logo horizontal" <?php selected($icon_style, 'Full logo horizontal'); ?>>Full logo horizontal</option>
                <option value="Full logo square" <?php selected($icon_style, 'Full logo square'); ?>>Full logo square</option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_alignment')); ?>">Icon Alignment:</label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_alignment')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_alignment')); ?>">
                <option value="Left" <?php selected($icon_alignment, 'Left'); ?>>Left</option>
                <option value="Center" <?php selected($icon_alignment, 'Center'); ?>>Center</option>
                <option value="Right" <?php selected($icon_alignment, 'Right'); ?>>Right</option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('custom_color')); ?>">Custom Color:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('custom_color')); ?>" name="<?php echo esc_attr($this->get_field_name('custom_color')); ?>" type="text" value="<?php echo esc_attr($custom_color); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('spacing')); ?>">Icon Spacing:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('spacing')); ?>" name="<?php echo esc_attr($this->get_field_name('spacing')); ?>" type="text" value="<?php echo esc_attr($spacing); ?>">
        </p>
        <p>
            <label>Container Margins (px):</label>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <input style="width: 48%;" type="number" id="<?php echo esc_attr($this->get_field_id('margin_top')); ?>" name="<?php echo esc_attr($this->get_field_name('margin_top')); ?>" value="<?php echo esc_attr($margin_top); ?>" placeholder="Top">
                <input style="width: 48%;" type="number" id="<?php echo esc_attr($this->get_field_id('margin_bottom')); ?>" name="<?php echo esc_attr($this->get_field_name('margin_bottom')); ?>" value="<?php echo esc_attr($margin_bottom); ?>" placeholder="Bottom">
            </div>
            <div style="display: flex; justify-content: space-between;">
                <input style="width: 48%;" type="number" id="<?php echo esc_attr($this->get_field_id('margin_left')); ?>" name="<?php echo esc_attr($this->get_field_name('margin_left')); ?>" value="<?php echo esc_attr($margin_left); ?>" placeholder="Left">
                <input style="width: 48%;" type="number" id="<?php echo esc_attr($this->get_field_id('margin_right')); ?>" name="<?php echo esc_attr($this->get_field_name('margin_right')); ?>" value="<?php echo esc_attr($margin_right); ?>" placeholder="Right">
            </div>
        </p>
        <p>
            <button type="button" class="button" id="<?php echo esc_attr($this->get_field_id('link_margins')); ?>">Link Margins</button>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['icon_type'] = (!empty($new_instance['icon_type'])) ? wp_strip_all_tags($new_instance['icon_type']) : '';
        $instance['icon_size'] = (!empty($new_instance['icon_size'])) ? wp_strip_all_tags($new_instance['icon_size']) : '';
        $instance['icon_style'] = (!empty($new_instance['icon_style'])) ? wp_strip_all_tags($new_instance['icon_style']) : '';
        $instance['icon_alignment'] = (!empty($new_instance['icon_alignment'])) ? wp_strip_all_tags($new_instance['icon_alignment']) : '';
        $instance['custom_color'] = (!empty($new_instance['custom_color'])) ? wp_strip_all_tags($new_instance['custom_color']) : '';
        $instance['spacing'] = (!empty($new_instance['spacing'])) ? wp_strip_all_tags($new_instance['spacing']) : '';
        $instance['margin_top'] = (!empty($new_instance['margin_top'])) ? intval($new_instance['margin_top']) : 0;
        $instance['margin_right'] = (!empty($new_instance['margin_right'])) ? intval($new_instance['margin_right']) : 0;
        $instance['margin_bottom'] = (!empty($new_instance['margin_bottom'])) ? intval($new_instance['margin_bottom']) : 0;
        $instance['margin_left'] = (!empty($new_instance['margin_left'])) ? intval($new_instance['margin_left']) : 0;
        return $instance;
    }
}