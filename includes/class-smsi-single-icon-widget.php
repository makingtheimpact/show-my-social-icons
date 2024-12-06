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
            '(Single Icon) Show My Social Icon',
            array('description' => 'Display a single social media icon'),
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

        $platform = isset($instance['platform']) ? esc_attr($instance['platform']) : '';
        
        // Extract and sanitize variables
        $icon_type = isset($instance['icon_type']) ? esc_attr($instance['icon_type']) : 'PNG';
        $icon_alignment = isset($instance['icon_alignment']) ? esc_attr($instance['icon_alignment']) : 'Center';
        $custom_color = isset($instance['custom_color']) ? esc_attr($instance['custom_color']) : '#000000';
        $icon_size = isset($instance['icon_size']) ? smsi_sanitize_unit_value($instance['icon_size']) : '30px';
        $icon_style = isset($instance['icon_style']) ? esc_attr($instance['icon_style']) : 'Icon only full color';
        
        // Split margin values into value and unit
        $margin_top = isset($instance['margin_top']) ? smsi_sanitize_unit_value($instance['margin_top']) : '0px';
        $margin_right = isset($instance['margin_right']) ? smsi_sanitize_unit_value($instance['margin_right']) : '0px';
        $margin_bottom = isset($instance['margin_bottom']) ? smsi_sanitize_unit_value($instance['margin_bottom']) : '0px';
        $margin_left = isset($instance['margin_left']) ? smsi_sanitize_unit_value($instance['margin_left']) : '0px';


        echo do_shortcode(sprintf(
            '[my_social_icon platform="%s" type="%s" size="%s" style="%s" alignment="%s" custom_color="%s" margin_top="%s" margin_right="%s" margin_bottom="%s" margin_left="%s"]',
            $platform,
            $icon_type,
            $icon_size,
            $icon_style,
            $icon_alignment,
            $custom_color,
            $margin_top,
            $margin_right,
            $margin_bottom,
            $margin_left
        ));
        echo wp_kses_post($args['after_widget']);
    }

    public function form($instance) {
        $widget_id = isset($this->id) ? $this->id : '';

        $defaults = [
            'platform'       => '',
            'icon_type'      => 'PNG',
            'icon_size'      => '30px',
            'icon_style'     => 'Icon only full color',
            'icon_alignment' => 'Center',
            'custom_color'   => '#000000',
            'margin_top'     => '0px',
            'margin_right'   => '0px',
            'margin_bottom'  => '0px',
            'margin_left'    => '0px',
            'link_margins'   => false,
        ];
        // Merge user-defined settings with defaults
        $instance = wp_parse_args((array) $instance, $defaults);

        // Extract variables for easy access
        $platform           = esc_attr($instance['platform'] ?? '');
        $icon_type          = esc_attr($instance['icon_type']);
        $icon_size          = esc_attr($instance['icon_size']);
        $icon_style         = esc_attr($instance['icon_style']);
        $icon_alignment     = esc_attr($instance['icon_alignment']);
        $custom_color       = esc_attr($instance['custom_color']);
        $margin_top         = esc_attr($instance['margin_top']);
        $margin_right       = esc_attr($instance['margin_right']);
        $margin_bottom      = esc_attr($instance['margin_bottom']);
        $margin_left        = esc_attr($instance['margin_left']);
        $link_margins       = esc_attr($instance['link_margins']);

        $platforms = smsi_get_platform_list();
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('platform')); ?>">Platform:</label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('platform')); ?>" name="<?php echo esc_attr($this->get_field_name('platform')); ?>">
                <?php foreach ($platforms as $key => $platform_info) : ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($platform, $key); ?>><?php echo esc_html($platform_info['label']); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_type')); ?>">Icon Type:</label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_type')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_type')); ?>">
                <option value="PNG" <?php selected($icon_type, 'PNG'); ?>>PNG</option>
                <option value="SVG" <?php selected($icon_type, 'SVG'); ?>>SVG</option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_size')); ?>">Icon Size:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_size')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_size')); ?>" type="text" value="<?php echo esc_attr($icon_size); ?>" />
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

        $instance = array();
        $instance['platform']        = isset($new_instance['platform']) ? sanitize_text_field($new_instance['platform']) : '';
        $instance['icon_type']       = isset($new_instance['icon_type']) ? sanitize_text_field($new_instance['icon_type']) : 'PNG';
        $instance['icon_size']       = isset($new_instance['icon_size']) ? smsi_sanitize_unit_value($new_instance['icon_size']) : '30px';
        $instance['icon_style']      = isset($new_instance['icon_style']) ? sanitize_text_field($new_instance['icon_style']) : 'Icon only full color';
        $instance['icon_alignment']  = isset($new_instance['icon_alignment']) ? sanitize_text_field($new_instance['icon_alignment']) : 'Center';
        if (isset($new_instance['custom_color']) && $new_instance['icon_type'] === 'SVG') {
            $instance['custom_color'] = sanitize_hex_color($new_instance['custom_color']);
        } else {
            $instance['custom_color'] = '#000000';
        }
        $instance['margin_top']      = isset($new_instance['margin_top']) ? smsi_sanitize_unit_value($new_instance['margin_top']) : '0px';
        $instance['margin_right']    = isset($new_instance['margin_right']) ? smsi_sanitize_unit_value($new_instance['margin_right']) : '0px';
        $instance['margin_bottom']   = isset($new_instance['margin_bottom']) ? smsi_sanitize_unit_value($new_instance['margin_bottom']) : '0px';
        $instance['margin_left']     = isset($new_instance['margin_left']) ? smsi_sanitize_unit_value($new_instance['margin_left']) : '0px';
        $instance['link_margins'] = isset($new_instance['link_margins']) ? (bool) $new_instance['link_margins'] : false;

        return $instance;
    }
}