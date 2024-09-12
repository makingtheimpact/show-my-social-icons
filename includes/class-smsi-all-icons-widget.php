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
            array('description' => 'Display all your social media icons')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo do_shortcode("[show_my_social_icons type=\"{$instance['icon_type']}\" size=\"{$instance['icon_size']}\" style=\"{$instance['icon_style']}\" alignment=\"{$instance['icon_alignment']}\"]");
        echo $args['after_widget'];
    }

    public function form($instance) {
        $icon_type = !empty($instance['icon_type']) ? $instance['icon_type'] : 'PNG';
        $icon_size = !empty($instance['icon_size']) ? $instance['icon_size'] : '30px';
        $icon_style = !empty($instance['icon_style']) ? $instance['icon_style'] : 'Icon only full color';
        $icon_alignment = !empty($instance['icon_alignment']) ? $instance['icon_alignment'] : 'Center';

        ?>
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
        $instance['icon_type'] = (!empty($new_instance['icon_type'])) ? strip_tags($new_instance['icon_type']) : '';
        $instance['icon_size'] = (!empty($new_instance['icon_size'])) ? strip_tags($new_instance['icon_size']) : '';
        $instance['icon_style'] = (!empty($new_instance['icon_style'])) ? strip_tags($new_instance['icon_style']) : '';
        $instance['icon_alignment'] = (!empty($new_instance['icon_alignment'])) ? strip_tags($new_instance['icon_alignment']) : '';
        return $instance;
    }
}