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
        echo do_shortcode("[my_social_icon platform=\"{$instance['platform']}\" type=\"{$instance['icon_type']}\" size=\"{$instance['icon_size']}\" style=\"{$instance['icon_style']}\" alignment=\"{$instance['icon_alignment']}\"]");
        echo $args['after_widget'];
    }

    public function form($instance) {
        $platform = !empty($instance['platform']) ? $instance['platform'] : '';
        $icon_type = !empty($instance['icon_type']) ? $instance['icon_type'] : 'PNG';
        $icon_size = !empty($instance['icon_size']) ? $instance['icon_size'] : '30px';
        $icon_style = !empty($instance['icon_style']) ? $instance['icon_style'] : 'Icon only full color';
        $icon_alignment = !empty($instance['icon_alignment']) ? $instance['icon_alignment'] : 'Center';

        $platforms = my_social_media_platforms();

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('platform'); ?>">Platform:</label>
            <select class="widefat" id="<?php echo $this->get_field_id('platform'); ?>" name="<?php echo $this->get_field_name('platform'); ?>">
                <?php foreach ($platforms as $platform_id => $platform_name) : ?>
                    <option value="<?php echo esc_attr($platform_id); ?>" <?php selected($platform, $platform_id); ?>><?php echo esc_html($platform_name); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('icon_type'); ?>">Icon Type:</label>
            <select class="widefat" id="<?php echo $this->get_field_id('icon_type'); ?>" name="<?php echo $this->get_field_name('icon_type'); ?>">
                <option value="PNG" <?php selected($icon_type, 'PNG'); ?>>PNG</option>
                <option value="SVG" <?php selected($icon_type, 'SVG'); ?>>SVG</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('icon_size'); ?>">Icon Size:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('icon_size'); ?>" name="<?php echo $this->get_field_name('icon_size'); ?>" type="text" value="<?php echo esc_attr($icon_size); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('icon_style'); ?>">Icon Style:</label>
            <select class="widefat" id="<?php echo $this->get_field_id('icon_style'); ?>" name="<?php echo $this->get_field_name('icon_style'); ?>">
                <option value="Icon only full color" <?php selected($icon_style, 'Icon only full color'); ?>>Icon only full color</option>
                <option value="Full logo horizontal" <?php selected($icon_style, 'Full logo horizontal'); ?>>Full logo horizontal</option>
                <option value="Full logo square" <?php selected($icon_style, 'Full logo square'); ?>>Full logo square</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('icon_alignment'); ?>">Icon Alignment:</label>
            <select class="widefat" id="<?php echo $this->get_field_id('icon_alignment'); ?>" name="<?php echo $this->get_field_name('icon_alignment'); ?>">
                <option value="Left" <?php selected($icon_alignment, 'Left'); ?>>Left</option>
                <option value="Center" <?php selected($icon_alignment, 'Center'); ?>>Center</option>
                <option value="Right" <?php selected($icon_alignment, 'Right'); ?>>Right</option>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['platform'] = (!empty($new_instance['platform'])) ? strip_tags($new_instance['platform']) : '';
        $instance['icon_type'] = (!empty($new_instance['icon_type'])) ? strip_tags($new_instance['icon_type']) : '';
        $instance['icon_size'] = (!empty($new_instance['icon_size'])) ? strip_tags($new_instance['icon_size']) : '';
        $instance['icon_style'] = (!empty($new_instance['icon_style'])) ? strip_tags($new_instance['icon_style']) : '';
        $instance['icon_alignment'] = (!empty($new_instance['icon_alignment'])) ? strip_tags($new_instance['icon_alignment']) : '';
        return $instance;
    }
}