<?php
/**
 * Settings Page
 *
 * This file contains the settings page for the plugin.
 *
 * @package ShowMySocialIcons
 */

/**
 * Enqueue scripts and styles for the admin area.
 *
 * @param string $hook The current admin page.
 * @return void
 */
function smsi_admin_enqueue_scripts($hook) {
    global $main_page_hook, $docs_page_hook, $icon_preview_page_hook; // Access global variables

    // Check if we are on any of the relevant pages
    if ($hook !== $main_page_hook && $hook !== $docs_page_hook && $hook !== $icon_preview_page_hook) {
        return;
    }

    // Enqueue your styles and scripts
    wp_enqueue_style('smsi-admin-styles', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css', array(), '1.0.0');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('smsi-admin-script', plugin_dir_url(__FILE__) . 'assets/js/admin-script.js', array('jquery', 'jquery-ui-sortable'), '1.0.0', true);
    
    // Enqueue front-end styles
    global $smsi_plugin_dir_path;
    wp_enqueue_style('smsi-frontend-styles', $smsi_plugin_dir_path . 'assets/css/style.css', array(), '1.0.0');

    // Pass data to the script
    wp_localize_script('smsi-admin-script', 'smsiData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('smsi_nonce')
    ));

    $asset_file = include(plugin_dir_path(dirname(__FILE__)) . 'build/index.asset.php');

    wp_enqueue_script(
        'smsi-block-editor',
        plugins_url('build/index.js', dirname(__FILE__)),
        $asset_file['dependencies'],
        filemtime(plugin_dir_path(dirname(__FILE__)) . 'build/index.js'),
        true
    );

    wp_localize_script('smsi-block-editor', 'smsiPlatforms', array(
        'platforms' => my_social_media_platforms()
    ));
}
add_action('admin_enqueue_scripts', 'smsi_admin_enqueue_scripts');


/**
 * Add admin menu
 *
 * @return void
 */
function smsi_add_admin_menu() {
    global $main_page_hook, $docs_page_hook, $icon_preview_page_hook; // Make them global

    $icon_file_path = plugins_url('assets/svg/show-my-social-icons-plugin-icon.svg', dirname(__FILE__));
    
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
}

/**
 * Force Load Styles Callback
 *
 * @return void
 */
function smsi_force_load_styles_callback() {
    $value = get_option('smsi_force_load_styles', '0');
    echo '<input type="checkbox" name="smsi_force_load_styles" value="1"' . checked(1, $value, false) . ' /> ';
    echo "<span class='description'>Check this box if the icons are not displaying correctly.</span>";
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

function smsi_menu_location_callback() {
    $value = get_option('smsi_menu_location', 'primary');
    $menus = get_registered_nav_menus();
    echo "<select name='smsi_menu_location'>";
    foreach ($menus as $location => $description) {
        echo "<option value='" . esc_attr($location) . "' " . selected($value, $location, false) . ">" . esc_html($description) . "</option>";
    }
    echo "</select>";
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

    $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'shortcodes';

    $tabs = [
        'shortcodes' => 'Shortcodes',
        'faqs' => 'FAQs',
        'troubleshooting' => 'Troubleshooting'
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
    <h3>I am having trouble with the icons not displaying, what do I do?</h3>
    <p>Check the troubleshooting section for possible solutions. If you have tried those solutions and the icons are still not displaying correctly, please contact us at <a href='mailto:customerservice@maketheimpact.com'>customerservice@maketheimpact.com</a>.</p>
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
    <h3>How do I troubleshoot the CSS styles not being loaded?</h3>
    <p>If the CSS styles are not being loaded, you can try the following:</p>
    <ul>
        <li>Check the <i>Force Load Styles</i> option in the settings.</li>
        <li>Clear the website cache and if necessary, adjust the settings.</li>
        <li>Clear the browser cache.</li>
    </ul>
    <h3>How do I troubleshoot the icons not displaying?</h3>
    <p>If the icons are not displaying, you can try the following:</p>
    <ul>
        <li>Ensure the platform URL is set correctly in the settings.</li>
        <li>Check the settings for the blocks and widgets to ensure they are set to the correct options.</li>
        <li>Ensure that the icons are not being blocked by any ad blockers or browser extensions.</li>
        <li>Enable the <i>Force Load Styles</i> option in the settings.</li>
        <li>Clear the website cache and adjust the settings if necessary.</li>
        <li>Clear the browser cache.</li>
        <li>Check for plugin or theme conflicts. Sometimes other plugins or themes can interfere with the icons displaying correctly.</li>
    </ul>
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
    $shortcode_info_page_content = "
    <h2>Shortcode Info</h2>

    <p>Shortcodes are used to display the social media icons on your website. You can use the shortcodes to display the icons in your posts, pages, or custom post types.</p>

    <p>The shortcodes are <strong>[show_my_social_icons]</strong> and <strong>[my_social_icon]</strong>.</p>

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
        <li><strong>margin_top</strong> - Specifies the margin-top value (e.g., '10px', '1em').</li>
        <li><strong>margin_bottom</strong> - Specifies the margin-bottom value (e.g., '10px', '1em').</li>
        <li><strong>margin_left</strong> - Specifies the margin-left value (e.g., '10px', '1em').</li>
        <li><strong>margin_right</strong> - Specifies the margin-right value (e.g., '10px', '1em').</li>
    </ul>
    <div class='copy-container'>
        <textarea id='shortcode2' class='copy-text' style='width: 90%; height: 50px;'>[my_social_icon platform=\"facebook\" type=\"SVG\" size=\"50px\" style=\"Icon only custom color\" custom_color=\"#FFA500\" alignment=\"Center\"]</textarea><br>
        <button class='copy-button'>Copy Shortcode</button><button class='preview-button'>Preview Shortcode</button><br><br>
    </div>
    <div class='shortcode-preview' id='preview2'></div>

    <h2><strong>Platforms Supported</strong></h2>
    <p>Below is a list of the platforms this plugin currently supports. Use the platform ID in the shortcode attributes.</p>

    <table class='smsi-platform-list' cellspacing='0'><thead><th>Platform</th><th>Platform ID</th></thead>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/behance_icon_100px.png' class='smsi-icon'> Behance</td><td>behance</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/bitchute_icon_100px.png' class='smsi-icon'> Bitchute</td><td>bitchute</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/cashapp_icon_100px.png' class='smsi-icon'> CashApp</td><td>cashapp</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/clouthub_icon_100px.png' class='smsi-icon'> CloutHub</td><td>clouthub</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/digg_icon_100px.png' class='smsi-icon'> Digg</td><td>digg</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/discord_icon_100px.png' class='smsi-icon'> Discord</td><td>discord</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/facebook_icon_100px.png' class='smsi-icon'> Facebook</td><td>facebook</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/fiverr_icon_100px.png' class='smsi-icon'> Fiverr</td><td>fiverr</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/gab_icon_100px.png' class='smsi-icon'> Gab</td><td>gab</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/givesendgo_icon_100px.png' class='smsi-icon'> GiveSendGo</td><td>givesendgo</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/instagram_icon_100px.png' class='smsi-icon'> Instagram</td><td>instagram</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/linkedin_icon_100px.png' class='smsi-icon'> LinkedIn</td><td>linkedin</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/linktree_icon_100px.png' class='smsi-icon'> Linktree</td><td>linktree</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/locals_icon_100px.png' class='smsi-icon'> Locals</td><td>locals</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/minds_icon_100px.png' class='smsi-icon'> Minds</td><td>minds</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/myspace_icon_100px.png' class='smsi-icon'> MySpace</td><td>myspace</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/odysee_icon_100px.png' class='smsi-icon'> Odysee</td><td>odysee</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/parler_icon_100px.png' class='smsi-icon'> Parler</td><td>parler</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/patreon_icon_100px.png' class='smsi-icon'> Patreon</td><td>patreon</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/paypal_icon_100px.png' class='smsi-icon'> PayPal</td><td>paypal</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/pinterest_icon_100px.png' class='smsi-icon'> Pinterest</td><td>pinterest</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/publicsq_icon_100px.png' class='smsi-icon'> Public Square</td><td>publicsq</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/quora_icon_100px.png' class='smsi-icon'> Quora</td><td>quora</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/reddit_icon_100px.png' class='smsi-icon'> Reddit</td><td>reddit</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/rokfin_icon_100px.png' class='smsi-icon'> Rokfin</td><td>rokfin</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/rumble_icon_100px.png' class='smsi-icon'> Rumble</td><td>rumble</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/snapchat_icon_100px.png' class='smsi-icon'> Snapchat</td><td>snapchat</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/substack_icon_100px.png' class='smsi-icon'> Substack</td><td>substack</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/telegram_icon_100px.png' class='smsi-icon'> Telegram</td><td>telegram</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/tiktok_icon_100px.png' class='smsi-icon'> TikTok</td><td>tiktok</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/truth_social_icon_100px.png' class='smsi-icon'> Truth Social</td><td>truth_social</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/twitch_icon_100px.png' class='smsi-icon'> Twitch</td><td>twitch</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/twitter_x_icon_100px.png' class='smsi-icon'> X (formerly Twitter)</td><td>twitter_x</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/unite_icon_100px.png' class='smsi-icon'> Unite</td><td>unite</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/venmo_icon_100px.png' class='smsi-icon'> Venmo</td><td>venmo</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/vimeo_icon_100px.png' class='smsi-icon'> Vimeo</td><td>vimeo</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/vk_icon_100px.png' class='smsi-icon'> vk</td><td>vk</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/whatsapp_icon_100px.png' class='smsi-icon'> WhatsApp</td><td>whatsapp</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/youtube_icon_100px.png' class='smsi-icon'> YouTube</td><td>youtube</td></tr>
    <tr><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/100w/zelle_icon_100px.png' class='smsi-icon'> Zelle</td><td>zelle</td></tr>
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
    
    $icon_preview_page_html = "
    <div class='wrap smsi-icon-preview-page'>
        <img src='" . esc_url($logo_url) . "' alt='Show My Social Icons' class='smsi-plugin-logo'>
        <h2>Social Media Icon Preview</h2>
        <p>Below is a table of all the social media icons currently supported by this plugin.</p><p>The icons have a <strong>transparent background</strong> and the icons in the PNG format are available in sizes 100px, 150px, 200px, 300px, and 500px. An animated background has been applied to give you an idea of what the icons will look like on different colored backgrounds.</p>
        <table class='smsi-icon-preview-table' cellspacing='0'><thead><th class='smsi-thead-left'>Platform</th><th class='smsi-thead-left'>Platform ID</th><th>Horizontal</th><th>Square</th><th>Icon Only Full Color</th><th>Icon Only Black</th><th>Icon Only White</th></thead>
        <tr><td>Behance</td><td>behance</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/behance_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/behance_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/behance_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/behance_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/behance_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Bitchute</td><td>bitchute</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/bitchute_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/bitchute_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/bitchute_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/bitchute_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/bitchute_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>CashApp</td><td>cashapp</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/cashapp_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/cashapp_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/cashapp_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/cashapp_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/cashapp_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>CloutHub</td><td>clouthub</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/clouthub_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/clouthub_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/clouthub_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/clouthub_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/clouthub_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Digg</td><td>digg</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/digg_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/digg_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/digg_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/digg_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/digg_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Discord</td><td>discord</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/discord_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/discord_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/discord_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/discord_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/discord_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Facebook</td><td>facebook</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/facebook_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/facebook_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/facebook_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/facebook_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/facebook_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Fiverr</td><td>fiverr</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/fiverr_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/fiverr_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/fiverr_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/fiverr_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/fiverr_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Gab</td><td>gab</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/gab_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/gab_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/gab_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/gab_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/gab_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>GiveSendGo</td><td>givesendgo</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/givesendgo_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/givesendgo_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/givesendgo_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/givesendgo_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/givesendgo_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Instagram</td><td>instagram</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/instagram_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/instagram_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/instagram_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/instagram_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/instagram_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>LinkedIn</td><td>linkedin</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/linkedin_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/linkedin_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/linkedin_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/linkedin_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/linkedin_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Linktree</td><td>linktree</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/linktree_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/linktree_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/linktree_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/linktree_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/linktree_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Locals</td><td>locals</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/locals_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/locals_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/locals_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/locals_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/locals_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Minds</td><td>minds</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/minds_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/minds_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/minds_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/minds_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/minds_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>MySpace</td><td>myspace</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/myspace_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/myspace_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/myspace_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/myspace_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/myspace_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Odysee</td><td>odysee</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/odysee_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/odysee_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/odysee_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/odysee_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/odysee_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Parler</td><td>parler</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/parler_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/parler_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/parler_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/parler_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/parler_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Patreon</td><td>patreon</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/patreon_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/patreon_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/patreon_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/patreon_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/patreon_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>PayPal</td><td>paypal</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/paypal_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/paypal_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/paypal_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/paypal_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/paypal_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Pinterest</td><td>pinterest</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/pinterest_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/pinterest_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/pinterest_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/pinterest_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/pinterest_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Public Square</td><td>publicsq</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/publicsq_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/publicsq_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/publicsq_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/publicsq_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/publicsq_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Quora</td><td>quora</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/quora_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/quora_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/quora_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/quora_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/quora_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Reddit</td><td>reddit</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/reddit_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/reddit_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/reddit_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/reddit_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/reddit_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Rokfin</td><td>rokfin</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/rokfin_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/rokfin_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/rokfin_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/rokfin_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/rokfin_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Rumble</td><td>rumble</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/rumble_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/rumble_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/rumble_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/rumble_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/rumble_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Snapchat</td><td>snapchat</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/snapchat_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/snapchat_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/snapchat_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/snapchat_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/snapchat_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Substack</td><td>substack</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/substack_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/substack_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/substack_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/substack_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/substack_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Telegram</td><td>telegram</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/telegram_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/telegram_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/telegram_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/telegram_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/telegram_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>TikTok</td><td>tiktok</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/tiktok_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/tiktok_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/tiktok_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/tiktok_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/tiktok_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Truth Social</td><td>truth_social</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/truth_social_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/truth_social_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/truth_social_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/truth_social_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/truth_social_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Twitch</td><td>twitch</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/twitch_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/twitch_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/twitch_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/twitch_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/twitch_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>X (formerly Twitter)</td><td>twitter_x</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/twitter_x_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/twitter_x_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/twitter_x_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/twitter_x_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/twitter_x_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Unite</td><td>unite</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/unite_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/unite_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/unite_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/unite_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/unite_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Venmo</td><td>venmo</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/venmo_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/venmo_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/venmo_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/venmo_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/venmo_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Vimeo</td><td>vimeo</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/vimeo_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/vimeo_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/vimeo_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/vimeo_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/vimeo_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>vk</td><td>vk</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/vk_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/vk_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/vk_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/vk_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/vk_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>WhatsApp</td><td>whatsapp</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/whatsapp_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/whatsapp_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/whatsapp_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/whatsapp_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/whatsapp_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>YouTube</td><td>youtube</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/youtube_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/youtube_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/youtube_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/youtube_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/youtube_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
        <tr><td>Zelle</td><td>zelle</td><td><img src='" . $smsi_plugin_dir_path . "assets/png/hz/150w/zelle_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/sq/150w/zelle_logo_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-c/150w/zelle_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-b/150w/zelle_icon_150px.png' class='smsi-icon smsi-icon-light-bg'></td><td><img src='" . $smsi_plugin_dir_path . "assets/png/ic-w/150w/zelle_icon_150px.png' class='smsi-icon smsi-icon-dark-bg'></td></tr>
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