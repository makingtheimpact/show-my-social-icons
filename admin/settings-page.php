<?php
/**
 * Settings Page
 *
 * This file contains the settings page for the plugin.
 *
 * @package ShowMySocialIcons
 */


/**
 * Add admin menu
 *
 * @return void
 */
function smsi_add_admin_menu() {
    global $main_page_hook, $docs_page_hook, $icon_preview_page_hook; // Make them global

    $icon_file_path = plugins_url('assets/svg/show_my_social_icons_plugin_icon.svg', dirname(__FILE__));
    
    // Main Plugin Settings Page
    $main_page_hook = add_menu_page(
        'Show My Social Icons', // Page title
        'My Social Icons', // Menu title
        'manage_options', // Capability
        'show_my_social_icons', // Menu slug
        'smsi_show_my_social_icons_page_content', // Function to display the page content
        $icon_file_path, // Icon file path
        100
    );

    // Documentation Submenu Page
    $docs_page_hook = add_submenu_page(
        'show_my_social_icons', // Parent slug
        'Documentation', // Page title
        'Documentation', // Menu title
        'manage_options', // Capability
        'smsi_documentation', // Menu slug
        'smsi_documentation_page_content' // Function to display the page content
    );

    // Icon Preview Submenu Page
    $icon_preview_page_hook = add_submenu_page(
        'show_my_social_icons', // Parent slug
        'Social Media Icon Preview', // Page title
        'Icon Preview', // Menu title
        'manage_options', // Capability
        'smsi_icon_preview', // Menu slug
        'smsi_icon_preview_page_content' // Function to display the page content
    );
}
add_action('admin_menu', 'smsi_add_admin_menu');
 
/**
 * Settings Page Content
 *
 * @return void
 */
function smsi_show_my_social_icons_page_content() { 
    $nonce = wp_create_nonce('smsi_settings_nonce');
    $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'platform_settings';

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'smsi_settings_nonce')) {
            wp_die('Security check failed');
        }
    }

    $logo_url = plugins_url('assets/images/plugin-logo.png', dirname(__FILE__));
    ?>
    <div class="wrap smsi-settings-page">
        <img src="<?php echo esc_url($logo_url); ?>" alt="Show My Social Icons" class="smsi-plugin-logo">
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_url(add_query_arg(array('tab' => 'platform_settings', '_wpnonce' => $nonce), admin_url('admin.php?page=show_my_social_icons'))); ?>" class="nav-tab <?php echo $active_tab == 'platform_settings' ? 'nav-tab-active' : ''; ?>">Platform Settings</a>
            <a href="<?php echo esc_url(add_query_arg(array('tab' => 'advanced_settings', '_wpnonce' => $nonce), admin_url('admin.php?page=show_my_social_icons'))); ?>" class="nav-tab <?php echo $active_tab == 'advanced_settings' ? 'nav-tab-active' : ''; ?>">Icon Style Settings</a>
        </h2>
        
        <form method="post" action="options.php" id="smsi-settings-form">
            <?php wp_nonce_field('smsi_settings_nonce'); ?>
            <?php
            if ($active_tab == 'platform_settings') {
                settings_fields('show_my_social_icons_all_settings');
                do_settings_sections('show_my_social_icons');
                submit_button('Save Changes', 'primary', 'submit', false);
            } else {
                settings_fields('show_my_social_icons_advanced_settings');
                do_settings_sections('show_my_social_icons_advanced');
                submit_button('Save Changes');
            } 
            ?>
        </form>
    </div>
    <?php
}

/**
 * Display admin notices
 *
 * @return void
 */
add_action('admin_notices', 'smsi_admin_notices');
function smsi_admin_notices() {
    settings_errors();  // This function will print the settings errors registered with `add_settings_error`
}

/**
 * Validate the input data and add errors
 *
 * @param array $input The input data to validate.
 * @return array The validated input data.
 */
function smsi_validate_options($input) {
    // Validate the input data and add errors
    if (!valid_input($input)) {
        add_settings_error(
            'smsi_options',   // Setting title
            'smsi_error',     // Error ID
            'Invalid Input: Please enter valid data.',  // Error message
            'error'           // Type of message
        );
    }
    return $input;  // Return the sanitized/validated input
}

/**
 * Icon Style Settings
 *
 * @return void
 */
function smsi_icon_style_description_callback() {
    echo '<p>Customize how social media icons appear on your site.</p>';
}
add_action('admin_init', 'smsi_setup_icon_style_settings');

/**
 * Setup the icon style settings
 *
 * @return void
 */
function smsi_setup_icon_style_settings() {    
    // Register a new section for the icon style settings
    add_settings_section(
        'show_my_social_icons_settings_section', // Section ID
        'Default Icon Style Options', // Section title
        'smsi_icon_style_description_callback',
        'show_my_social_icons_advanced' // Menu slug
    );

    // Icon Style Setting Options
    add_settings_field('icon_type', 'Icon Type', 'smsi_icon_type_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_type', 'esc_attr');

    add_settings_field('icon_size', 'Icon Size', 'smsi_icon_size_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_size', 'esc_attr');

    add_settings_field('icon_style', 'Icon Style', 'smsi_icon_style_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_style', 'esc_attr');

    add_settings_field('icon_alignment', 'Icon Alignment', 'smsi_icon_alignment_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_alignment', 'esc_attr');

    add_settings_field('icon_custom_color', 'Icon Custom Color (SVG Only)', 'smsi_icon_custom_color_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_custom_color', 'esc_attr');

    add_settings_field('display_in_menu', 'Show All Icons in Main Menu', 'smsi_display_in_menu_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'display_in_menu', 'smsi_sanitize_display_in_menu');

    add_settings_field(
        'smsi_menu_location',
        'Select Menu Location',
        'smsi_menu_location_callback',
        'show_my_social_icons_advanced',
        'show_my_social_icons_settings_section'
    );
    register_setting('show_my_social_icons_advanced_settings', 'smsi_menu_location');

    add_settings_field(
        'smsi_menu_icons',
        'Select Icons to Display in Menu',
        'smsi_menu_icons_callback',
        'show_my_social_icons_advanced',
        'show_my_social_icons_settings_section'
    );
    register_setting('show_my_social_icons_advanced_settings', 'smsi_menu_icons', 'smsi_sanitize_menu_icons');

    add_settings_field('icon_spacing', 'Icon Spacing', 'smsi_icon_spacing_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_spacing', 'esc_attr');

    add_settings_field('icon_container_margin_top', 'Container Top Margin', 'smsi_icon_container_margin_top_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_container_margin_top', 'esc_attr');

    add_settings_field('icon_container_margin_right', 'Container Right Margin', 'smsi_icon_container_margin_right_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_container_margin_right', 'esc_attr');

    add_settings_field('icon_container_margin_bottom', 'Container Bottom Margin', 'smsi_icon_container_margin_bottom_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_container_margin_bottom', 'esc_attr');

    add_settings_field('icon_container_margin_left', 'Container Left Margin', 'smsi_icon_container_margin_left_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_container_margin_left', 'esc_attr');

    register_setting('show_my_social_icons_advanced_settings', 'icon_custom_filter', 'esc_attr');

    add_settings_field('smsi_menu_location', 'Navigation Menu Location', 'smsi_menu_location_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'smsi_menu_location', 'esc_attr');

    add_settings_field('smsi_force_load_styles', 'Force Load Styles', 'smsi_force_load_styles_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'smsi_force_load_styles', 'intval');

    // Add a new setting for hover effect
    add_settings_field(
        'icon_hover_effect',
        'Icon Hover Effect',
        'show_my_social_icons_hover_effect_callback',
        'show_my_social_icons_advanced',
        'show_my_social_icons_settings_section'
    );

    register_setting('show_my_social_icons_advanced_settings', 'icon_hover_effect');

    function show_my_social_icons_hover_effect_callback() {
        $value = get_option('icon_hover_effect', 'style1');
        echo "<select id='icon_hover_effect' name='icon_hover_effect'>
                <option value='style1'" . selected($value, 'style1', false) . ">Default (Opacity)</option>
                <option value='style2'" . selected($value, 'style2', false) . ">Scale Up</option>
                <option value='style3'" . selected($value, 'style3', false) . ">Rotate</option>
              </select>";
    }

    add_settings_field('icon_inline', 'Display Icons Inline', 'smsi_icon_inline_callback', 'show_my_social_icons_advanced', 'show_my_social_icons_settings_section');
    register_setting('show_my_social_icons_advanced_settings', 'icon_inline', 'smsi_sanitize_boolean');
}

/**
 * Force Load Styles Callback
 *
 * @return void
 */
function smsi_force_load_styles_callback() {
    $value = get_option('smsi_force_load_styles', '1');
    echo '<input type="checkbox" name="smsi_force_load_styles" value="1"' . checked(1, $value, false) . ' /> ';
    echo "<span class='description'>Force load CSS styles for the social icons.</span>";
}

/**
 * Icon Style Settings Callbacks
 *
 * @return void
 */
function smsi_icon_type_callback() {
    $value = get_option('icon_type', 'PNG');
    echo "<select id='icon_type' name='icon_type'>
            <option value='SVG' " . selected($value, 'SVG', false) . ">SVG</option>
            <option value='PNG' " . selected($value, 'PNG', false) . ">PNG (default)</option>
          </select>";
}

/**
 * Icon Size Callback
 *
 * @return void
 */
function smsi_icon_size_callback() {
    $value = get_option('icon_size', '100px');
    echo "<input type='text' name='icon_size' value='" . esc_attr($value) . "' />";
}

/**
 * Icon Style Callback
 *
 * @return void
 */
function smsi_icon_style_callback() {
    $value = get_option('icon_style', 'Icon only full color');
    echo "<select id='icon_style' name='icon_style'>
            <option value='Icon only full color' " . selected($value, 'Icon only full color', false) . ">Icon Only in full color</option>
            <option value='Icon only black' " . selected($value, 'Icon only black', false) . ">Icon Only in black</option>
            <option value='Icon only white' " . selected($value, 'Icon only white', false) . ">Icon Only in white</option>
            <option value='Icon only custom color' " . selected($value, 'Icon only custom color', false) . ">Icon Only in custom color</option>
            <option value='Full logo horizontal' " . selected($value, 'Full logo horizontal', false) . ">Full logo (horizontal)</option>
            <option value='Full logo square' " . selected($value, 'Full logo square', false) . ">Full logo (square)</option>
          </select>";
}

/**
 * Icon Alignment Callback
 *
 * @return void
 */
function smsi_icon_alignment_callback() {
    $value = get_option('icon_alignment', 'Left');
    echo "<select name='icon_alignment'>
            <option value='Left' " . selected($value, 'Left', false) . ">Left</option>
            <option value='Center' " . selected($value, 'Center', false) . ">Center</option>
            <option value='Right' " . selected($value, 'Right', false) . ">Right</option>
          </select>";
}

/**
 * Icon Custom Color Callback
 *
 * @return void
 */
function smsi_icon_custom_color_callback() {
    $color_value = get_option('icon_custom_color', '');
    
    echo "<input type='color' id='icon_custom_color' name='icon_custom_color' value='" . esc_attr($color_value) . "' />
            <button type='button' id='clear_custom_color' class='button button-secondary'>Clear Custom Color</button>
            <script>
                document.getElementById('clear_custom_color').addEventListener('click', function() {
                    document.getElementById('icon_custom_color').value = '';
                    document.getElementById('icon_custom_color').dispatchEvent(new Event('change'));
                });
            </script>";
}

/**
 * Display in Menu Callback
 *
 * @return void
 */
function smsi_display_in_menu_callback() {
    $value = get_option('display_in_menu', '0'); // Assuming '0' means not displayed, '1' means displayed
    echo '<input type="checkbox" name="display_in_menu" value="1"' . checked(1, $value, false) . ' />';
}

/**
 * Menu Location Callback
 *
 * @return void
 */
function smsi_menu_location_callback() {
    $value = get_option('smsi_menu_location', 'primary');
    $menus = get_registered_nav_menus();

    if(empty($menus)) {
        echo '<p>No registered menus found. Please register a menu location first.</p>';
        return;
    }

    echo "<select name='smsi_menu_location'>";
    foreach ($menus as $location => $description) {
        echo "<option value='" . esc_attr($location) . "' " . selected($value, $location, false) . ">" . esc_html($description) . "</option>";
    }
    echo "</select>";
}

/**
 * Select and Order Menu Icons Callback
 *
 * @return void
 */
function smsi_menu_icons_callback() {
    $platforms = smsi_get_platform_list(); 
    $selected_icons = get_option('smsi_menu_icons', []);

    if (!is_array($selected_icons)) {
        $selected_icons = [];
    }

    // Filter platforms that have URLs set
    $available_platforms = array_filter($platforms, function($platform_id) {
        return !empty(get_option("{$platform_id}_url"));
    }, ARRAY_FILTER_USE_KEY);

    ?>
    <div id="smsi-menu-icons-wrapper">
        <ul id="smsi-menu-icons-list">
            <?php foreach ($available_platforms as $platform_id => $platform) : 
                $icon_path = smsi_get_single_social_icon_path($platform_id, 'PNG', '30px', 'Icon only full color'); 
                ?>
                <li class="smsi-menu-icon-item">
                    <input type="checkbox" name="smsi_menu_icons[]" value="<?php echo esc_attr($platform_id); ?>" <?php checked(in_array($platform_id, $selected_icons)); ?> />
                    <img src="<?php echo esc_url($icon_path); ?>" alt="<?php echo esc_attr($platform['label']); ?>" style="width: 20px; height: 20px; margin-right: 5px;" />
                    <?php echo esc_html($platform['label']); ?>
                </li>
            <?php endforeach; 
            if (empty($available_platforms)) : ?>
                <li class="smsi-menu-icon-item">
                    <p>No platforms have URLs set. Please set the URLs in the <a href='?page=smsi_settings'>Platform Settings</a>.</p>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <p>Drag and drop to order the icons as desired.</p>
    <style>
        #smsi-menu-icons-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .smsi-menu-icon-item {
            display: flex;
            align-items: center;
            padding: 5px;
            cursor: move;
            background: #f9f9f9;
            border: 1px solid #ddd;
            margin-bottom: 3px;
            max-width: 300px;
        }
    </style>
    <script>
       jQuery(document).ready(function($){
            $('#smsi-menu-icons-list').sortable({
                axis: 'y',
                placeholder: "ui-state-highlight",
                update: function(event, ui) {
                    var order = [];
                    $('#smsi-menu-icons-list li').each(function(){
                        var platformId = $(this).find('input[type=checkbox]').val();
                        if (platformId) {
                            order.push(platformId);
                        }
                    });

                    $.ajax({
                        url: smsiData.ajaxurl, // Correctly referencing smsiData
                        type: 'POST',
                        data: {
                            action: 'smsi_save_menu_icon_order',
                            order: order,
                            nonce: smsiData.nonce // Correctly referencing smsiData
                        },
                        success: function(response) {
                            if(response.success){
                                // Optionally, display a success message
                                console.log('Order saved successfully.');
                            } else {
                                // Handle the error
                                console.error('Failed to save order.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                        }
                    });
                }
            });
            $('#smsi-menu-icons-list').disableSelection();
        });
   </script>
    <?php
}

 /**
    * Handle AJAX Request to Save Menu Icon Order
    */
    function smsi_save_menu_icon_order() {
        // Verify the nonce - updated to 'smsi_nonce' to match the JavaScript
        check_ajax_referer('smsi_nonce', 'nonce');

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        // Retrieve and sanitize the order
        $order = isset($_POST['order']) ? array_map('sanitize_text_field', $_POST['order']) : [];

        // Get available platforms
        $platforms = smsi_get_platform_list();
        $available_platforms = array_filter($platforms, function($platform_id) {
            return !empty(get_option("{$platform_id}_url"));
        }, ARRAY_FILTER_USE_KEY);

        // Filter the order to include only available platforms
        $filtered_order = array_intersect($order, array_keys($available_platforms));

        // Update the option
        update_option('smsi_menu_icons', $filtered_order);

        wp_send_json_success();
    }
    add_action('wp_ajax_smsi_save_menu_icon_order', 'smsi_save_menu_icon_order');

/**
 * Retrieve a Single Platform's Data
 *
 * @param string $platform_id The platform ID.
 * @return array|false
 */
function smsi_get_platform($platform_id) {
    $platforms = smsi_get_platform_list();
    if(isset($platforms[$platform_id])) {
        $platform = $platforms[$platform_id];
        // Retrieve the URL set in Platform Settings
        $platform['url'] = get_option('smsi_platform_url_' . $platform_id, '');
        return $platform;
    }
    return false;
}

/**
 * Icon Spacing Callback
 *
 * @return void
 */
function smsi_icon_spacing_callback() {
    $value = get_option('icon_spacing', '10px');
    echo "<input type='text' name='icon_spacing' value='" . esc_attr($value) . "' />
            <p class='description'>Enter the spacing between icons (e.g., 10px, 1em)</p>";
}

/**
 * Container Margin Callbacks
 *
 * @return void
 */
function smsi_icon_container_margin_top_callback() {
    $value = get_option('icon_container_margin_top', '0px');
    echo "<input type='text' name='icon_container_margin_top' value='" . esc_attr($value) . "' />
            <p class='description'>Enter the top margin for the icon container (e.g., 10px, 1em)</p>";
}

function smsi_icon_container_margin_right_callback() {
    $value = get_option('icon_container_margin_right', '0px');
    echo "<input type='text' name='icon_container_margin_right' value='" . esc_attr($value) . "' />
            <p class='description'>Enter the right margin for the icon container (e.g., 10px, 1em)</p>";
}

function smsi_icon_container_margin_bottom_callback() {
    $value = get_option('icon_container_margin_bottom', '0px');
    echo "<input type='text' name='icon_container_margin_bottom' value='" . esc_attr($value) . "' />
            <p class='description'>Enter the bottom margin for the icon container (e.g., 10px, 1em)</p>";
}

function smsi_icon_container_margin_left_callback() {
    $value = get_option('icon_container_margin_left', '0px');
    echo "<input type='text' name='icon_container_margin_left' value='" . esc_attr($value) . "' />
            <p class='description'>Enter the left margin for the icon container (e.g., 10px, 1em)</p>";
}

/**
 * Sanitize Menu Icons
 *
 * @param array $input The input array from the form.
 * @return array Sanitized array of platform IDs.
 */
function smsi_sanitize_menu_icons($input) {
    if (!is_array($input)) {
        return [];
    }
    // Sanitize each platform ID
    return array_map('sanitize_text_field', $input);
}

/**
 * Platform Settings Description Callback
 *
 * @return void
 */
function smsi_platform_settings_description_callback() {
    echo '<p>Paste in the URL for the platforms you want to link to then specify a number to set the order you want them to appear. Platforms without valid URLs will not be shown.</p>';
}

/**
 * Setup Social Fields
 *
 * @return void
 */
function smsi_setup_social_fields() {
    global $social_platforms; // Ensure this is defined somewhere globally accessible

    add_settings_section(
        'show_my_social_icons_platform_settings', // Section ID
        'Platform Links and Order Settings', // Section title
        'smsi_platform_settings_description_callback',
        'show_my_social_icons' // Menu slug
    );
}

/**
 * Platform URL and Order Settings
 *
 * @return void
 */
require_once plugin_dir_path(__FILE__) . 'platforms_list.php';

/**
 * Shortcode Information Page Content
 *
 * @return void
 */
function smsi_documentation_page_content() {
    $logo_url = plugins_url('assets/images/plugin-logo.png', dirname(__FILE__));

    $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'instructions';

    $tabs = [
        'instructions' => 'Instructions',
        'shortcodes' => 'Shortcodes',
        'faqs' => 'FAQs',
        'troubleshooting' => 'Troubleshooting',
        
    ];

    echo '<div class="wrap smsi-settings-page">';
    echo '<img src="' . esc_url($logo_url) . '" alt="Show My Social Icons" class="smsi-plugin-logo">';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ($tab === $active_tab) ? 'nav-tab-active' : '';
        echo '<a class="nav-tab ' . esc_attr($class) . '" href="?page=smsi_documentation&tab=' . esc_attr($tab) . '">' . esc_html($name) . '</a>';
    }
    echo '</h2>
    <div class="smsi-info-page">';

    switch ($active_tab) {
        case 'instructions':
            smsi_instructions_tab_content();
            break;
        case 'faqs':
            smsi_faqs_tab_content();
            break;
        case 'troubleshooting':
            smsi_troubleshooting_tab_content();
            break;
        case 'shortcodes':
        default:
            smsi_shortcodes_tab_content();
            break;
    }

    echo '</div>
    </div>';
}

/**
 * Instructions Tab Content
 *
 * @return void
 */
function smsi_instructions_tab_content() {
    $docs_instructions_content = "
    <h2>Instructions</h2>
    <p>This plugin allows you to display social media icons on your website. You can use the blocks, widgets, or shortcodes to display the icons. You can also add them to your navigation menu.</p>
    ";
    // Explain the platform and default settings
    $docs_instructions_content .= "
    <h3>Plugin Settings</h3>
    <p>The plugin settings are located on the <a href='?page=smsi_settings'>Settings</a> page. Set the URL for each platform you want to display. You can reorder the platforms by dragging and dropping them.</p>
    <p>You can set the icon type, size, style, alignment, spacing, and margin values for the icons. You can also set the display options for the icons.</p>
    ";  

    // Explain how to display the icons using blocks, widgets, and shortcodes
    $docs_instructions_content .= "
    <h3>Displaying the Icons</h3>
    <p>You can display the icons using the following methods:</p>
    <h4>Gutenberg Blocks</h4>
    <p>The plugin includes two Gutenberg blocks: <i>All Icons</i> and <i>Single Icon</i>.</p>

    <h4>Legacy Widgets</h4>
    <p>The plugin includes two legacy widgets: <i>(All Icons) Show My Social Icons</i> and <i>(Single Icon) My Social Icon</i>.</p>

    <h4>Shortcodes</h4>
    <p>The plugin includes two shortcodes: <i>[show_my_social_icons]</i> and <i>[my_social_icon]</i>.</p>
    ";
    // Explain the settings for blocks, widgets, and shortcodes
    $docs_instructions_content .= "
    <h3>Icon Settings</h3>
    <p>You can set the icon type, size, style, alignment, spacing, and margin values for the icons in the block, widget, or shortcode attributes or in the plugin settings. The settings apply to all icons. The block, widget, or shortcode attributes apply to the individual block, widget, or shortcode.</p>

    <h4>All Icons Block</h4>
    <p>The <i>All Icons</i> block displays all active social icons. You can customize this block with the following attributes:</p>
    <ul>
        <li><strong>Type</strong> - Specifies the icon type (SVG or PNG). Default is 'PNG'.</li>
        <li><strong>Size</strong> - Specifies the size of the icons (e.g., '100px', '200px', '50%', '100%'). Default is '30px'.</li>
        <li><strong>Style</strong> - Specifies the style of the icons (e.g., 'Icon only full color', 'Full logo horizontal'). See above for available options according to icon type. Default is 'Icon only full color'.</li>
        <li><strong>Alignment</strong> - Aligns the icons (Left, Center, Right). Default is 'Center'.</li>
        <li><strong>Spacing</strong> - Specifies the spacing between icons (e.g., '10px', '1em').</li>
        <li><strong>Custom Color (SVG only)</strong> - Specifies the color of the icons (e.g., 'red', '#000', '#fff'). Default is '#000'.</li>
        <li><strong>Margin Top</strong> - Specifies the margin-top value (e.g., '10px', '1em').</li>
        <li><strong>Margin Bottom</strong> - Specifies the margin-bottom value (e.g., '10px', '1em').</li>
        <li><strong>Margin Left</strong> - Specifies the margin-left value (e.g., '10px', '1em').</li>
        <li><strong>Margin Right</strong> - Specifies the margin-right value (e.g., '10px', '1em').</li>
    </ul>

    <h4>Single Icon Block</h4>
    <p>The <i>Single Icon</i> block displays a single social icon. You can customize this block with the following attributes:</p>
    <ul>
        <li><strong>Platform</strong> - Specifies the platform icon to display. The icon will only display if the platform URL is set in the settings.</li>
        <li><strong>Size</strong> - Specifies the size of the icon (e.g., '100px', '200px', '50%', '100%'). Default is '30px'.</li>
        <li><strong>Style</strong> - Specifies the style of the icon (e.g., 'Icon only full color', 'Full logo horizontal'). See above for available options according to icon type. Default is 'Icon only full color'.</li>
        <li><strong>Alignment</strong> - Aligns the icon (Left, Center, Right). Default is 'Center'.</li>
        <li><strong>Margin Top</strong> - Specifies the margin-top value (e.g., '10px', '1em').</li>
        <li><strong>Margin Bottom</strong> - Specifies the margin-bottom value (e.g., '10px', '1em').</li>
        <li><strong>Margin Left</strong> - Specifies the margin-left value (e.g., '10px', '1em').</li>
        <li><strong>Margin Right</strong> - Specifies the margin-right value (e.g., '10px', '1em').</li>
    </ul>
    ";

    // Explain the Menu Icons and Settings
    $docs_instructions_content .= "
    <h3>Menu Icons and Settings</h3>
    <p>You can add the icons to your menu by enabling the <i>Display in Menu</i> option in the plugin settings. You can select the menu location and the icons to display in the menu. The size of the icons will be set according to the default size you set in the plugin settings.</p>
    ";

    // Explain the CSS classes used for the icons
    $docs_instructions_content .= "
    <h3>CSS Classes</h3>
    <h4>Menu Icons</h4>
    <p>The plugin uses the following CSS classes for the menu icons:</p>
    <ul>
        <li><strong>.smsi-icon-wrapper</strong> - Used for the icon wrapper.</li>
        <li><strong>.smsi-menu-social-icons</strong> - Used for the menu icons.</li>
        <li><strong>.smsi-menu-icon</strong> - Used for the menu icon.</li>
        <li><strong>.smsi-icon-hover-style1</strong> - Used for the first hover effect.</li>
        <li><strong>.smsi-icon-hover-style2</strong> - Used for the second hover effect.</li>
        <li><strong>.smsi-icon-hover-style3</strong> - Used for the third hover effect.</li>
    </ul>
    <h4>All Icons Block</h4>
    <p>The plugin uses the following CSS classes for the icons:</p>
    <ul>
        <li><strong>.smsi-icon-wrapper</strong> - Used for the individual icon wrapper.</li>
        <li><strong>.smsi-icon</strong> - Used for the icon.</li>
    </ul>
    <h4>Single Icon Block</h4>
    <p>The plugin uses the following CSS classes for the icons:</p>
    <ul>
        <li><strong>.smsi-single-icon-wrapper</strong> - Used for the individual icon container.</li>
        <li><strong>.smsi-icon</strong> - Used for the icon.</li>
        <li><strong>.smsi-icon-[platform]</strong> - Used for the specific platform icon.</li>
    </ul>
    ";
    echo wp_kses_post($docs_instructions_content);
}   

/**
 * FAQs Tab Content
 *
 * @return void
 */
function smsi_faqs_tab_content() {
    // Add more FAQs as needed
    $docs_faq_content = "
    <h2>FAQs</h2>
    <h3>How do I add social icons to my website?</h3>
    <p>You can add social icons to your website using the Gutenberg blocks, legacy widgets, or shortcodes. You can also add them to your navigation menu.</p>
    <h3>How do I add social icons to my menu?</h3>
    <p>To add social icons to your menu, go to the settings page and enable the <i>Display in Menu</i> option.</p>

    <h3>Can I customize the color of the icons?</h3>
    <p>Yes, you can customize the color of the icons by selecting SVG from the icon type dropdown, selecting the <i>Icon only custom color</i> option, and specifying the color. These options are available in the icon settings for each block and widget.</p>
    <h3>What is the difference between SVG and PNG icons?</h3>
    <p>SVG icons are scalable without loss of quality, while PNG icons have a fixed resolution. SVG icons are also smaller in file size for simple icons, but not supported by all browsers (especially older ones). PNG icons are supported by all browsers, but may pixelate when enlarged beyond 500px.</p>
    <h3>I need a social media platform added, what do I do?</h3>
    <p>If you need a social media platform added, please contact us at <a href='mailto:customerservice@maketheimpact.com'>customerservice@maketheimpact.com</a>.</p>
    <h3>The icons are not displaying, what do I do?</h3>
    <p>Check the troubleshooting section for possible solutions. If you have tried those solutions and the icons are still not displaying correctly, please contact us at <a href='mailto:customerservice@maketheimpact.com'>customerservice@maketheimpact.com</a>.</p>
    <h3>How do I display the icons inline?</h3>
    <p>You can display the icons inline by enabling the <i>Display Inline</i> option in the block or widget settings.</p>
    <h3>Can I upload my own icons?</h3>
    <p>Not currently. We are working on a feature to allow you to upload your own icons.</p>
    <h3>I want to display multiple icons side by side, how do I do this?</h3>
    <p>You can use the All Icons shortcode, widget, or block, or you can use the Single Icon version and enable the <i>Display Inline</i> option in the shortcode, block, or widget settings.</p>
    ";
    echo wp_kses_post($docs_faq_content);
}

/**
 * Troubleshooting Tab Content
 *
 * @return void
 */
function smsi_troubleshooting_tab_content() {
    $docs_troubleshooting_content = "
    <h2>Troubleshooting</h2>
    <h3>The CSS styles are not being loaded, what do I do?</h3>
    <p>If the CSS styles are not being loaded, you can try the following:</p>
    <ul>
        <li>Check the <i>Force Load Styles</i> option in the settings.</li>
        <li>Clear the website cache and if necessary, adjust the settings.</li>
        <li>Clear the browser cache.</li>
    </ul>
    <h3>The icons are not displaying or displaying incorrectly, what do I do?</h3>
    <p>If the icons are not displaying or displaying incorrectly, check the following:</p>
    <ul>
        <li>Ensure the platform URL is set in the Platform Settings.</li>
        <li>Check the settings for the blocks, widgets, and shortcodes to ensure they are set to the correct options that are actually supported.</li>
        <li>Ensure that the icons are not being blocked by any ad blockers or browser extensions.</li>
        <li>Enable the <i>Force Load Styles</i> option in the settings.</li>
        <li>Clear the website cache and adjust the settings if necessary.</li>
        <li>Clear the browser cache.</li>
        <li>Check for plugin or theme conflicts. Sometimes other plugins or themes can interfere with the icons displaying correctly.</li>
    </ul>
    <h3>The single icons are stacking and not displaying side by side. How do I fix this?</h3>
    <p>If the single icons are stacking and not displaying side by side, you can enable the <i>Display Inline</i> option in the block or widget settings.</p>
    <p>If you have checked all of the above and the icons are still not displaying correctly, please contact us at <a href='mailto:customerservice@maketheimpact.com'>customerservice@maketheimpact.com</a>.</p>
    ";
    echo wp_kses_post($docs_troubleshooting_content);
}

/**
 * Shortcodes Tab Content
 *
 * @return void
 */
function smsi_shortcodes_tab_content() {
    global $smsi_plugin_dir_path;
    $platforms = smsi_get_platform_list();
    $platform_list_html = "";
    foreach ($platforms as $platform_id => $platform) {
        $platform_list_html .= "<tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/{$platform_id}_icon_100px.png' class='smsi-icon'> {$platform['label']}</td><td>{$platform_id}</td></tr>";
    }
    $shortcode_info_page_content = "
    <h2>Shortcode Info</h2>

    <p>Shortcodes are used to display the social media icons on your website. You can use the shortcodes to display the icons in your posts, pages, or custom post types.</p>

    <p>The shortcodes are <strong>[show_my_social_icons]</strong>, <strong>[my_social_icon]</strong>, and <strong>[select_my_social_icons]</strong>.</p>

    <p>The shortcodes have different attributes that you can use to customize the icons. The attributes are:</p>

    <ul>
        <li><strong>type</strong> - Specifies the icon type (SVG or PNG). Default is 'PNG'.</li>
        <li><strong>size</strong> - Specifies the size of the icons (e.g., '100px', '200px', '50%', '100%'). Default is '30px'.</li>
        <li><strong>style</strong> - Specifies the style of the icons (e.g., 'Icon only full color', 'Full logo horizontal'). Default is 'Icon only full color'.</li>
        <li><strong>alignment</strong> - Aligns the icons (Left, Center, Right). Default is 'Center'.</li>
        <li><strong>spacing</strong> - Specifies the spacing between icons (e.g., '10px', '1em').</li>
        <li><strong>custom_color (SVG only)</strong> - Specifies the color of the icons (e.g., 'red', '#000', '#fff'). Default is '#000'.</li>
        <li><strong>margin_top</strong> - Specifies the margin-top value (e.g., '10px', '1em').</li>
        <li><strong>margin_bottom</strong> - Specifies the margin-bottom value (e.g., '10px', '1em').</li>
        <li><strong>margin_left</strong> - Specifies the margin-left value (e.g., '10px', '1em').</li>
        <li><strong>margin_right</strong> - Specifies the margin-right value (e.g., '10px', '1em').</li>
    </ul>

    <p>The shortcode attributes must be set correctly for the icons to display properly. To help you know what options are available, a breakdown of the shortcodes, attributes, and options is below.</p>

    <h3><strong>Icon Types</strong></h3>
    <p>This plugin supports two types of icons:</p>
    <ul>
        <li>SVG</li>
        <li>PNG</li>
    </ul>
    <p>SVG icons are scalable without loss of quality, while PNG icons have a fixed resolution. SVG icons are also smaller in file size for simple icons, but not supported by all browsers (especially older ones). PNG icons are supported by all browsers, but may pixelate when enlarged beyond 500px.</p>

    <h3><strong>Icon Styles</strong></h3>
    <p>There are certain style options available, depending on the icon type and the style you select. The style options available are in the table below</p>
    <table border='1' class='smsi-icon-styles-table'>
        <tr>
            <td><strong>SVG Icons:</strong></td>
            <td><strong>PNG Icons:</strong></td>
        </tr>
        <tr>
            <td>Icon only black</td>
            <td>Full logo horizontal</td>
        </tr>
        <tr>
            <td>Icon only white</td>
            <td>Full logo square</td>
        </tr>
        <tr>
            <td>Icon only custom color</td>
            <td>Icon only full color</td>
        </tr>
        <tr>
            <td></td>
            <td>Icon only black</td>
        </tr>
        <tr>
            <td></td>
            <td>Icon only white</td>
        </tr>
    </table>
    
    <h2><strong>How to Use the Shortcodes</strong></h2>
    <p>You can use the shortcodes to display the social media icons on your website. The shortcodes are:</p>

    <h3><strong>[show_my_social_icons]</strong></h3>
    <p>Displays all active social icons. You can customize this shortcode with the following attributes:</p>
    <ul>
        <li><strong>type</strong> - Specifies the icon type (SVG or PNG). Default is 'PNG'.</li>
        <li><strong>size</strong> - Specifies the size of the icons (e.g., '100px', '200px', '50%', '100%'). Default is '30px'.</li>
        <li><strong>style</strong> - Specifies the style of the icons (e.g., 'Icon only full color', 'Full logo horizontal'). See above for available options according to icon type. Default is 'Icon only full color'.</li>
        <li><strong>alignment</strong> - Aligns the icons (Left, Center, Right). Default is 'Center'.</li>
        <li><strong>spacing</strong> - Specifies the spacing between icons (e.g., '10px', '1em').</li>
        <li><strong>custom_color (SVG only)</strong> - Specifies the color of the icons (e.g., 'red', '#000', '#fff'). Default is '#000'.</li>
        <li><strong>margin_top</strong> - Specifies the margin-top value (e.g., '10px', '1em').</li>
        <li><strong>margin_bottom</strong> - Specifies the margin-bottom value (e.g., '10px', '1em').</li>
        <li><strong>margin_left</strong> - Specifies the margin-left value (e.g., '10px', '1em').</li>
        <li><strong>margin_right</strong> - Specifies the margin-right value (e.g., '10px', '1em').</li>
    </ul>
    <div class='copy-container'>
        <textarea id='shortcode1' class='copy-text' style='width: 90%; height: 50px;'>[show_my_social_icons type=\"PNG\" size=\"30px\" style=\"Icon only full color\" alignment=\"Center\" spacing=\"10px\"]</textarea><br>
        <button class='copy-button'>Copy Shortcode</button><button class='preview-button'>Preview Shortcode</button><br><br>
    </div>
    <div class='shortcode-preview' id='preview1'></div>

    <h3><strong>[my_social_icon]</strong></h3>
    <p>Displays a single social icon based on the platform specified. Attributes include:</p>
    <ul>
        <li><strong>platform</strong> - The platform for which to show the icon (e.g., 'facebook', 'twitter').</li>
        <li><strong>type</strong> - SVG or PNG. Default is 'PNG'.</li>
        <li><strong>size</strong> - Icon size like '50px', '100px' or '100%'. Default is '30px'.</li>
        <li><strong>style</strong> - Specifies the style of the icons (e.g., 'Icon only full color', 'Full logo horizontal'). See above for available options according to icon type. Default is 'Icon only full color'.</li>
        <li><strong>alignment</strong> - Text alignment (Left, Center, Right). Default is 'Center'.</li>
        <li><strong>custom_color (SVG only)</strong> - Icon color. Default is '#000'.</li>
        <li><strong>inline</strong> - Determines if the icon should be displayed inline. Accepts 'true' or 'false'. Default is 'false'.</li>
        <li><strong>margin_top</strong> - Specifies the margin-top value (e.g., '10px', '1em').</li>
        <li><strong>margin_bottom</strong> - Specifies the margin-bottom value (e.g., '10px', '1em').</li>
        <li><strong>margin_left</strong> - Specifies the margin-left value (e.g., '10px', '1em').</li>
        <li><strong>margin_right</strong> - Specifies the margin-right value (e.g., '10px', '1em').</li>
    </ul>
    <div class='copy-container'>
        <textarea id='shortcode2' class='copy-text' style='width: 90%; height: 50px;'>[my_social_icon platform=\"facebook\" type=\"SVG\" size=\"50px\" style=\"Icon only custom color\" custom_color=\"#FFA500\" alignment=\"Center\" inline=\"true\"]</textarea><br>
        <button class='copy-button'>Copy Shortcode</button><button class='preview-button'>Preview Shortcode</button><br><br>
    </div>
    <div class='shortcode-preview' id='preview2'></div>

    <h3><strong>[select_my_social_icons]</strong></h3>
    <p>Displays a group of social icons based on the platforms specified. Attributes include:</p>
    <ul>
        <li><strong>platforms</strong> - Comma-separated list of platform IDs to display (e.g., 'facebook,twitter,instagram').</li>
        <li><strong>size</strong> - Specifies the size of the icons (e.g., '100px', '200px', '50%', '100%'). Default is '30px'.</li>
        <li><strong>style</strong> - Specifies the style of the icons (e.g., 'Icon only full color', 'Full logo horizontal'). See above for available options according to icon type. Default is 'Icon only full color'.</li>
        <li><strong>alignment</strong> - Aligns the icons (Left, Center, Right). Default is 'Center'.</li>
        <li><strong>spacing</strong> - Specifies the spacing between icons (e.g., '10px', '1em').</li>
        <li><strong>custom_color (SVG only)</strong> - Specifies the color of the icons (e.g., 'red', '#000', '#fff'). Default is '#000'.</li>
        <li><strong>margin_top</strong> - Specifies the margin-top value (e.g., '10px', '1em').</li>
        <li><strong>margin_bottom</strong> - Specifies the margin-bottom value (e.g., '10px', '1em').</li>
        <li><strong>margin_left</strong> - Specifies the margin-left value (e.g., '10px', '1em').</li>
        <li><strong>margin_right</strong> - Specifies the margin-right value (e.g., '10px', '1em').</li>
    </ul>   
    <div class='copy-container'>
        <textarea id='shortcode3' class='copy-text' style='width: 90%; height: 50px;'>[select_my_social_icons platforms=\"facebook,twitter,instagram\" size=\"30px\" style=\"Icon only full color\" alignment=\"Center\" spacing=\"10px\"]</textarea><br>
        <button class='copy-button'>Copy Shortcode</button><button class='preview-button'>Preview Shortcode</button><br><br>
    </div>
    <div class='shortcode-preview' id='preview3'></div>

    <h2><strong>Platforms Supported</strong></h2>
    <p>Below is a list of the platforms this plugin currently supports. Use the platform ID in the shortcode attributes.</p>

    <table class='smsi-platform-list' cellspacing='0'><thead><th>Platform</th><th>Platform ID</th></thead>
    {$platform_list_html}
    </table>
    ";
    echo wp_kses_post($shortcode_info_page_content);
}

/**
 * Preview Shortcode Callback
 *
 * @return void
 */
function smsi_preview_shortcode_callback() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'smsi_nonce')) {
        wp_die('Security check failed');
    }

    if (isset($_POST['shortcode'])) {
        // Remove slashes added by PHP's escaping
        $shortcode = stripslashes(sanitize_textarea_field($_POST['shortcode']));
        
        // Log the shortcode after stripslashes to confirm it's clean
        error_log('Received shortcode after stripslashes: ' . $shortcode);
        
        // Output the processed shortcode
        echo do_shortcode($shortcode);
    }
    wp_die();
}
add_action('wp_ajax_preview_shortcode', 'smsi_preview_shortcode_callback');

/**
 * Icon Preview Page Content
 *
 * @return void
 */
function smsi_icon_preview_page_content() {
    global $smsi_plugin_dir_path;
    $logo_url = plugins_url('assets/images/plugin-logo.png', dirname(__FILE__));
    $platform_list_html = "";
    $platform_list = smsi_get_platform_list();
    foreach ($platform_list as $platform_id => $platform) {
        // Horizontal icon 150px
        $hz_icon_path = smsi_get_single_social_icon_path($platform_id, 'PNG', '150px', 'Full logo horizontal');
        // Square icon 150px
        $sq_icon_path = smsi_get_single_social_icon_path($platform_id, 'PNG', '150px', 'Full logo square');
        // Icon only full color 150px   
        $ic_c_icon_path = smsi_get_single_social_icon_path($platform_id, 'PNG', '150px', 'Icon only full color');
        // Icon only black 150px
        $ic_b_icon_path = smsi_get_single_social_icon_path($platform_id, 'PNG', '150px', 'Icon only black');
        // Icon only white 150px
        $ic_w_icon_path = smsi_get_single_social_icon_path($platform_id, 'PNG', '150px', 'Icon only white');
        $platform_list_html .= "
        <tr>
            <td>" . $platform['label'] . "</td>
            <td>" . $platform_id . "</td>
            <td><img src='" . $hz_icon_path . "' class='smsi-icon smsi-icon-light-bg'></td>
            <td><img src='" . $sq_icon_path . "' class='smsi-icon smsi-icon-light-bg'></td>
            <td><img src='" . $ic_c_icon_path . "' class='smsi-icon smsi-icon-light-bg'></td>
            <td><img src='" . $ic_b_icon_path . "' class='smsi-icon smsi-icon-light-bg'></td>
            <td><img src='" . $ic_w_icon_path . "' class='smsi-icon smsi-icon-dark-bg'></td>
        </tr>";
    }
    
    $icon_preview_page_html = "
    <div class='wrap smsi-icon-preview-page'>
        <img src='" . esc_url($logo_url) . "' alt='Show My Social Icons' class='smsi-plugin-logo'>
        <h2>Social Media Icon Preview</h2>
        <p>Below is a table of all the social media icons currently supported by this plugin.</p><p>The icons have a <strong>transparent background</strong> and the icons in the PNG format are available in sizes 100px, 150px, 200px, 300px, and 500px. An animated background has been applied to give you an idea of what the icons will look like on different colored backgrounds.</p>
        <table class='smsi-icon-preview-table' cellspacing='0'><thead><th class='smsi-thead-left'>Platform</th><th class='smsi-thead-left'>Platform ID</th><th>Horizontal</th><th>Square</th><th>Icon Only Full Color</th><th>Icon Only Black</th><th>Icon Only White</th></thead>
        {$platform_list_html}
        </table>
    ";
    echo wp_kses_post($icon_preview_page_html);
}

/** 
 * Save the platform order
 * 
 * @since 1.0.0
 */
function smsi_save_platform_order() {
    check_ajax_referer('smsi_nonce', 'nonce');

    $order = isset($_POST['order']) ? $_POST['order'] : array();
    if (!empty($order) && is_array($order)) {
        foreach ($order as $index => $platform) {
            update_option($platform . '_order', $index);
        }
        error_log('smsi_save_platform_order: Order saved successfully.');
        wp_send_json_success();
    } else {
        error_log('smsi_save_platform_order: Error saving order.');
        wp_send_json_error();
    }
}
add_action('wp_ajax_smsi_save_platform_order', 'smsi_save_platform_order');


/** 
 * Save the platform URL
 * 
 * @since 1.0.0
 */
function smsi_save_platform_url() {
    check_ajax_referer('smsi_nonce', 'nonce');

    $platform = sanitize_text_field($_POST['platform']);
    $url = esc_url_raw($_POST['url']);

    if (!empty($platform) && !empty($url)) {
        update_option($platform . '_url', $url);
        error_log('smsi_save_platform_url: URL saved successfully.');
        wp_send_json_success();
    } else {
        error_log('smsi_save_platform_url: Error saving URL.');
        wp_send_json_error();
    }
}
add_action('wp_ajax_smsi_save_platform_url', 'smsi_save_platform_url');

function smsi_icon_inline_callback() {
    $value = get_option('icon_inline', false);
    echo '<input type="checkbox" name="icon_inline" value="1"' . checked(1, $value, false) . ' />';
    echo "<span class='description'>Enable to display icons inline by default.</span>";
}

function smsi_register_settings() {
    add_settings_field(
        'icon_inline',
        'Display Icons Inline',
        'smsi_icon_inline_callback',
        'show_my_social_icons_advanced',
        'show_my_social_icons_settings_section'
    );
    register_setting('show_my_social_icons_advanced_settings', 'icon_inline', 'smsi_sanitize_boolean');
}

add_action('admin_init', 'smsi_register_settings');