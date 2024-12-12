<?php 
/**
 * SMSI Select Icons Widget
 *
 * This file contains the SMSI Select Icons Widget class.
 *
 * @package ShowMySocialIcons
 */

class SMSI_Select_Icons_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'smsi_select_icons_widget',
            '(Select Icons) Show My Social Icons',
            array('description' => 'Display selected social media icons'),
            array('customize_selective_refresh' => true)
        );
    }

    /**
     * Render the widget output on the frontend.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget settings.
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];

        // Ensure $platforms is an array
        $platforms = isset($instance['platforms']) && is_array($instance['platforms']) ? $instance['platforms'] : explode(',', $instance['platforms']);
        error_log('Rendering platforms: ' . print_r($platforms, true));

        // Convert $platforms array to a comma-separated string
        $platforms_string = implode(',', $platforms);

        // Extract and sanitize variables
        $icon_type = isset($instance['icon_type']) ? esc_attr($instance['icon_type']) : 'PNG';
        $icon_alignment = isset($instance['icon_alignment']) ? esc_attr($instance['icon_alignment']) : 'Center';
        $custom_color = isset($instance['custom_color']) ? esc_attr($instance['custom_color']) : '#000000';
        $icon_size = isset($instance['icon_size']) ? smsi_sanitize_unit_value($instance['icon_size']) : '30px';
        $icon_style = isset($instance['icon_style']) ? esc_attr($instance['icon_style']) : 'Icon only full color';
        $spacing = isset($instance['spacing']) ? smsi_sanitize_unit_value($instance['spacing']) : '10px';
        
        // Split margin values into value and unit
        $margin_top = isset($instance['margin_top']) ? smsi_sanitize_unit_value($instance['margin_top']) : '0px';
        $margin_right = isset($instance['margin_right']) ? smsi_sanitize_unit_value($instance['margin_right']) : '0px';
        $margin_bottom = isset($instance['margin_bottom']) ? smsi_sanitize_unit_value($instance['margin_bottom']) : '0px';
        $margin_left = isset($instance['margin_left']) ? smsi_sanitize_unit_value($instance['margin_left']) : '0px';
        $spacing = smsi_sanitize_unit_value($instance['spacing']);

        echo do_shortcode(sprintf(
            '[select_my_social_icons platforms="%s" type="%s" size="%s" style="%s" alignment="%s" custom_color="%s" margin_top="%s" margin_right="%s" margin_bottom="%s" margin_left="%s" spacing="%s"]',
            $platforms_string,
            $icon_type,
            $icon_size,
            $icon_style,
            $icon_alignment,
            $custom_color,
            $margin_top,
            $margin_right,
            $margin_bottom,
            $margin_left,
            $spacing
        ));
        echo wp_kses_post($args['after_widget']);
    }

    public function form($instance) {
        $widget_id = isset($this->id) ? $this->id : '';
        error_log('Widget ID: ' . $widget_id);

        $defaults = [
            'platforms'      => [],
            'icon_type'      => 'PNG',
            'icon_size'      => '30px',
            'icon_style'     => 'Icon only full color',
            'icon_alignment' => 'Center',
            'custom_color'   => '#000000',
            'spacing'        => '10px',
            'margin_top'     => '0px',
            'margin_right'   => '0px',
            'margin_bottom'  => '0px',
            'margin_left'    => '0px',
            'link_margins'   => false,
        ];
        $instance = wp_parse_args((array) $instance, $defaults);

        $platforms = smsi_get_platform_list();
        $selected_platforms = isset($instance['platforms']) ? $instance['platforms'] : [];

        // Extract variables for easy access
        $icon_type          = esc_attr($instance['icon_type']);
        $icon_size          = esc_attr($instance['icon_size']);
        $icon_style         = esc_attr($instance['icon_style']);
        $icon_alignment     = esc_attr($instance['icon_alignment']);
        $custom_color       = esc_attr($instance['custom_color']);
        $spacing            = esc_attr($instance['spacing']);
        $margin_top         = esc_attr($instance['margin_top']);
        $margin_right       = esc_attr($instance['margin_right']);
        $margin_bottom      = esc_attr($instance['margin_bottom']);
        $margin_left        = esc_attr($instance['margin_left']);
        $link_margins       = esc_attr($instance['link_margins']);

        ?>

        <!-- Dropdown to select platforms -->
        <div class="smsi-widget <?php echo esc_attr($widget_id); ?>">
            <p>Select Platforms:</p>
            <?php foreach ($platforms as $key => $platform_info) { ?>
                <label>
                    <input type="checkbox" class="smsi-platform-checkbox" name="<?php echo esc_attr($this->get_field_name('platforms')); ?>[]" value="<?php echo esc_attr($key); ?>" <?php checked(in_array($key, $selected_platforms)); ?>>
                    <?php echo esc_html($platform_info['label']); ?>
                </label><br>
            <?php } ?>
        </div>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_type')); ?>">Icon Type:</label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_type')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_type')); ?>">
                <option value="SVG" <?php selected($icon_type, 'SVG'); ?>>SVG</option>
                <option value="PNG" <?php selected($icon_type, 'PNG'); ?>>PNG</option>
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
                <option value="Icon only custom color" <?php selected($icon_style, 'Icon only custom color'); ?>>Icon only custom color</option>
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
            <input class="widefat" 
                   id="<?php echo esc_attr($this->get_field_id('custom_color')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('custom_color')); ?>" 
                   type="text" 
                   value="<?php echo esc_attr($custom_color); ?>" 
                   placeholder="#000000" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('spacing')); ?>">Icon Spacing:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('spacing')); ?>" name="<?php echo esc_attr($this->get_field_name('spacing')); ?>" type="text" value="<?php echo esc_attr($spacing); ?>">
        </p>
        <!-- Container Margins -->
        <p>
            <label>Container Margins:</label>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <!-- Top Margin -->
                <div style="width: 23%;">
                    <label for="<?php echo esc_attr($this->get_field_id('margin_top')); ?>">Top</label>
                    <input type="text" 
                           id="<?php echo esc_attr($this->get_field_id('margin_top')); ?>" 
                           name="<?php echo esc_attr($this->get_field_name('margin_top')); ?>" 
                           value="<?php echo esc_attr($margin_top); ?>" 
                           placeholder="0px" 
                           style="width: 100%;" />
                </div>
                
                <!-- Right Margin -->
                <div style="width: 23%;">
                    <label for="<?php echo esc_attr($this->get_field_id('margin_right')); ?>">Right</label>
                    <input type="text" 
                           id="<?php echo esc_attr($this->get_field_id('margin_right')); ?>" 
                           name="<?php echo esc_attr($this->get_field_name('margin_right')); ?>" 
                           value="<?php echo esc_attr($margin_right); ?>" 
                           placeholder="0px" 
                           style="width: 100%;" />
                </div>
                
                <!-- Bottom Margin -->
                <div style="width: 23%;">
                    <label for="<?php echo esc_attr($this->get_field_id('margin_bottom')); ?>">Bottom</label>
                    <input type="text" 
                           id="<?php echo esc_attr($this->get_field_id('margin_bottom')); ?>" 
                           name="<?php echo esc_attr($this->get_field_name('margin_bottom')); ?>" 
                           value="<?php echo esc_attr($margin_bottom); ?>" 
                           placeholder="0px" 
                           style="width: 100%;" />
                </div>
                
                <!-- Left Margin -->
                <div style="width: 23%;">
                    <label for="<?php echo esc_attr($this->get_field_id('margin_left')); ?>">Left</label>
                    <input type="text" 
                           id="<?php echo esc_attr($this->get_field_id('margin_left')); ?>" 
                           name="<?php echo esc_attr($this->get_field_name('margin_left')); ?>" 
                           value="<?php echo esc_attr($margin_left); ?>" 
                           placeholder="0px" 
                           style="width: 100%;" />
                </div>
            </div>
        </p>
        <!-- Link Margins Button -->
        <p>
            <button type="button" class="button" id="<?php echo esc_attr($this->get_field_id('link_margins')); ?>" <?php echo $link_margins ? 'data-linked="true"' : 'data-linked="false"'; ?>><?php echo $link_margins ? 'Unlink Margins' : 'Link Margins'; ?></button>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
    
        // Parse platforms as an array from the comma-separated string
        $instance['platforms'] = (!empty($new_instance['platforms']) && is_array($new_instance['platforms'])) ? array_map('sanitize_text_field', $new_instance['platforms']) : [];

        error_log('Updated platforms: ' . print_r($instance['platforms'], true));
    
        $instance['icon_type'] = (!empty($new_instance['icon_type'])) ? wp_strip_all_tags($new_instance['icon_type']) : 'PNG';
        $instance['icon_size'] = (!empty($new_instance['icon_size'])) ? smsi_sanitize_unit_value($new_instance['icon_size']) : '30px';
        $instance['icon_style'] = (!empty($new_instance['icon_style'])) ? wp_strip_all_tags($new_instance['icon_style']) : 'Icon only full color';
        $instance['icon_alignment'] = (!empty($new_instance['icon_alignment'])) ? wp_strip_all_tags($new_instance['icon_alignment']) : 'Center';
        $instance['custom_color'] = (isset($new_instance['custom_color'])) ? sanitize_hex_color($new_instance['custom_color']) : '#000000';
        $instance['spacing'] = (!empty($new_instance['spacing'])) ? smsi_sanitize_unit_value($new_instance['spacing']) : '10px';
        $instance['margin_top'] = (!empty($new_instance['margin_top'])) ? smsi_sanitize_unit_value($new_instance['margin_top']) : '0px';
        $instance['margin_right'] = (!empty($new_instance['margin_right'])) ? smsi_sanitize_unit_value($new_instance['margin_right']) : '0px';
        $instance['margin_bottom'] = (!empty($new_instance['margin_bottom'])) ? smsi_sanitize_unit_value($new_instance['margin_bottom']) : '0px';
        $instance['margin_left'] = (!empty($new_instance['margin_left'])) ? smsi_sanitize_unit_value($new_instance['margin_left']) : '0px';
        $instance['link_margins'] = isset($new_instance['link_margins']) ? (bool) $new_instance['link_margins'] : false;
    
        return $instance;
    }
}

function smsi_register_select_icons_widget() {
    register_widget('SMSI_Select_Icons_Widget');
}
add_action('widgets_init', 'smsi_register_select_icons_widget');