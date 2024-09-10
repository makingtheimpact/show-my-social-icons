<?php
/* ADMIN SETTINGS PAGE */

// Include Admin Styles
function smsi_enqueue_admin_styles() {
    global $smsi_plugin_dir_path;
    wp_enqueue_style('smsi_admin_stylesheet', $smsi_plugin_dir_path . 'assets/css/admin-style.css');
}
add_action('admin_enqueue_scripts', 'smsi_enqueue_admin_styles');

// Include Admin JS
function smsi_enqueue_color_script() {
    global $smsi_plugin_dir_path;
    // Ensure jQuery is loaded as it seems you use `$` (jQuery)
    wp_enqueue_script('jquery');
    // Enqueue your custom script
    wp_enqueue_script('smsi-color-manipulation', $smsi_plugin_dir_path . 'assets/js/script.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'smsi_enqueue_color_script');


// Add the menu item and page
function smsi_create_plugin_settings_page() {
    global $smsi_plugin_dir_path;
    $page_title = 'Show My Social Icons';
    $menu_title = 'Social Icons';
    $capability = 'manage_options'; // Only users with the 'manage_options' capability (i.e., admins) can access this menu
    $slug = 'show_my_social_icons';
    $callback = 'smsi_show_my_social_icons_page_content';
    $icon_file_path = $smsi_plugin_dir_path . 'assets/svg/show-my-social-icons-plugin-icon.svg';
    $position = 100;

    add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon_file_path, $position);
}
add_action('admin_menu', 'smsi_create_plugin_settings_page');
 
// Settings Page Content
function smsi_show_my_social_icons_page_content() { 
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general_settings';
    ?>
    <div class="wrap smsi-settings-page">
        <h2>Show My Social Icons</h2>
        <h2 class="nav-tab-wrapper">
            <a href="?page=show_my_social_icons&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>">Platform Settings</a>
            <a href="?page=show_my_social_icons&tab=advanced_settings" class="nav-tab <?php echo $active_tab == 'advanced_settings' ? 'nav-tab-active' : ''; ?>">Icon Style Settings</a>
        </h2>
        
        <form method="post" action="options.php">
            <?php
                if ($active_tab == 'general_settings') {
                    settings_fields('show_my_social_icons_all_settings');
                    do_settings_sections('show_my_social_icons');
                } else if ($active_tab == 'advanced_settings') {
                    settings_fields('show_my_social_icons_advanced_settings');
                    do_settings_sections('show_my_social_icons_advanced');
                }
                submit_button();
            ?>
        </form>
    </div> <?php
}

add_action('admin_notices', 'smsi_admin_notices');
function smsi_admin_notices() {
    settings_errors();  // This function will print the settings errors registered with `add_settings_error`
}

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

/* Icon Style Settings */
function smsi_icon_style_description_callback() {
    echo '<p>Customize how social media icons appear on your site.</p>';
}
add_action('admin_init', 'smsi_setup_icon_style_settings');

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

    register_setting('show_my_social_icons_advanced_settings', 'icon_custom_filter', 'esc_attr');
}

// Icon Style Settings Callbacks
function smsi_icon_type_callback() {
    $value = get_option('icon_type', 'PNG');
    echo "<select name='icon_type'>
            <option value='SVG' " . selected($value, 'SVG', false) . ">SVG</option>
            <option value='PNG' " . selected($value, 'PNG', false) . ">PNG (default)</option>
          </select>";
}

function smsi_icon_size_callback() {
    $value = get_option('icon_size', '100px');
    echo "<input type='text' name='icon_size' value='" . esc_attr($value) . "' />";
}

function smsi_icon_style_callback() {
    $value = get_option('icon_style', 'Icon Only in full color');
    echo "<select name='icon_style'>
            <option value='Full logo horizontal' " . selected($value, 'Full logo horizontal', false) . ">Full logo (horizontal)</option>
            <option value='Full logo square' " . selected($value, 'Full logo square', false) . ">Full logo (square)</option>
            <option value='Icon only full color' " . selected($value, 'Icon only full color', false) . ">Icon Only in full color</option>
            <option value='Icon only black' " . selected($value, 'Icon only black', false) . ">Icon Only in black</option>
            <option value='Icon only white' " . selected($value, 'Icon only white', false) . ">Icon Only in white</option>
            <option value='Icon only custom color' " . selected($value, 'Icon only custom color', false) . ">Icon Only in custom color</option>
          </select>";
}

function smsi_icon_alignment_callback() {
    $value = get_option('icon_alignment', 'Left');
    echo "<select name='icon_alignment'>
            <option value='Left' " . selected($value, 'Left', false) . ">Left</option>
            <option value='Center' " . selected($value, 'Center', false) . ">Center</option>
            <option value='Right' " . selected($value, 'Right', false) . ">Right</option>
          </select>";
}

function smsi_icon_custom_color_callback() {
    $color_value = get_option('icon_custom_color', '#000000');
    echo "<input type='color' id='icon_custom_color' name='icon_custom_color' value='" . esc_attr($color_value) . "' />";
    echo "<script>
    document.getElementById('icon_custom_color').addEventListener('input', function(e) {
        const rgb = window.hexToRgb(e.target.value);
        const color = new window.Color(...rgb);
        const solver = new window.Solver(color);
        const result = solver.solve();
        console.log(result.filter); // You might want to update some DOM element with this value or handle it differently
    });
    </script>";
}



function smsi_display_in_menu_callback() {
    $value = get_option('display_in_menu', '0'); // Assuming '0' means not displayed, '1' means displayed
    $checked = checked(1, $value, false);
    echo "<input type='checkbox' name='display_in_menu' value='1'" . $checked . " />";
}

// Social Media Platform Link and Order Options
function smsi_platform_settings_description_callback() {
    echo '<p>Paste in the URL for the platforms you want to link to then specify a number to set the order you want them to appear. Platforms without valid URLs will not be shown.</p>';
}
add_action('admin_init', 'smsi_setup_social_fields');

function smsi_setup_social_fields() {
    global $social_platforms; // Ensure this is defined somewhere globally accessible

    add_settings_section(
        'show_my_social_icons_platform_settings', // Section ID
        'Platform Links and Order Settings', // Section title
        'smsi_platform_settings_description_callback',
        'show_my_social_icons' // Menu slug
    );
}

// Platform URL and Order Settings
require_once plugin_dir_path(__FILE__) . 'platforms_list.php';

// Shortcode Information Page
function smsi_add_shortcode_info_page() {
    add_submenu_page(
        'show_my_social_icons', // Parent slug
        'Shortcode Information', // Page title
        'Shortcode Info', // Menu title
        'manage_options', // Capability
        'smsi_shortcode_info', // Menu slug
        'smsi_shortcode_info_page_content' // Function to display the page content
    );
}
add_action('admin_menu', 'smsi_add_shortcode_info_page');

function smsi_shortcode_info_page_content() {
    global $smsi_plugin_dir_path;

    $shortcode_info_page_content = "
    <div class='wrap smsi-info-page'>
    <h2>How to Display Your Social Icons</h2>
        <p>Use the shortcodes below to display the icons anywhere on your site. You can customize their appearance using CSS and the shortcode attributes.</p>
        
        <p><strong>Logo or Icon Style Options</strong></p>
        <ul>
            <li>Full logo horizontal</li>
            <li>Full logo square</li>
            <li>Icon only full color</li>
            <li>Icon only black</li>
            <li>Icon only white</li>
            <li>Icon only custom color</li>
        </ul>

        <h3>[show_my_social_icons]</h3>
        <p>Displays all social icons. You can customize this shortcode with the following attributes:</p>
        <ul>
            <li><strong>type</strong> - Specifies the icon type (SVG or PNG). Default is 'PNG'.</li>
            <li><strong>size</strong> - Specifies the size of the icons (e.g., '100px', '200px', '50%', '100%'). Default is '30px'.</li>
            <li><strong>style</strong> - Specifies the style of the icons (e.g., 'Icon only full color', 'Full logo horizontal'). Default is 'Icon only full color'.</li>
            <li><strong>alignment</strong> - Aligns the icons (Left, Center, Left). Default is 'Center'.</li>
        </ul>
        <textarea id='shortcode1' style='width: 90%; height: 50px;'>[show_my_social_icons type=\"SVG\" size=\"150px\" style=\"Full logo square\" alignment=\"Center\"]</textarea><br>
        <button onclick=\"copyToClipboard('#shortcode1')\">Copy Shortcode</button><br><br>

        <h3>[my_social_icon]</h3>
        <p>Displays a single social icon based on the platform specified. Attributes include:</p>
        <ul>
            <li><strong>platform</strong> - The platform for which to show the icon (e.g., 'facebook', 'twitter').</li>
            <li><strong>type</strong> - SVG or PNG. Default is 'PNG'.</li>
            <li><strong>size</strong> - Icon size like '100px' or '100%'. Default is '30px'.</li>
            <li><strong>style</strong> - Specifies the style of the icons (e.g., 'Icon only full color', 'Full logo horizontal'). Default is 'Icon only full color'.</li>
            <li><strong>alignment</strong> - Text alignment. Default is 'Center'.</li>
        </ul>
        <textarea id='shortcode2' style='width: 90%; height: 50px;'>[my_social_icon platform=\"facebook\" type=\"SVG\" size=\"100px\" alignment=\"Right\"]</textarea><br>
        <button onclick=\"copyToClipboard('#shortcode2')\">Copy Shortcode</button><br><br>

    <h2>Shortcode Settings</h2>
    <p>You can control the type, size, style, and alignment of the icons using the shortcode attributes. All the possible attribute settings are detailed below.</p>
    
    <h2>Platforms Supported</h2>
    <p>Below is a list of the platforms this plugin currently supports.</p>
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
    </table></div>
    <script>
    function copyToClipboard(elementId) {
        var copyText = document.querySelector(elementId);
        copyText.select();
        document.execCommand('copy');
        alert('Copied to clipboard: ' + copyText.value);
    }
    </script>
    ";
    echo $shortcode_info_page_content;
}

// Shortcode Information Page
function smsi_add_icon_preview_page() {
    add_submenu_page(
        'show_my_social_icons', // Parent slug
        'Social Media Icon Preview', // Page title
        'Icon Preview', // Menu title
        'manage_options', // Capability
        'smsi_icon_preview', // Menu slug
        'smsi_icon_preview_page_content' // Function to display the page content
    );
}
add_action('admin_menu', 'smsi_add_icon_preview_page');

function smsi_icon_preview_page_content() {
    global $smsi_plugin_dir_path;
    $icon_preview_page_html = "
    <div class='wrap smsi-icon-preview-page'>
        <h2>Social Media Icon Preview</h2>
        <p>Below is a table of all the social media icons currently supported by this plugin.</p><p>The icons have a <strong>transparent background</strong> and the icons in the PNG format are available in sizes 100px, 150px, 200px, 300px, and 500px. An animated background has been applied to give you an idea of what the icons will look like on different colored backgrounds.</p><p><strong>Tip:</strong> For clear and crisp icons that stretch to any size, we recommend using the SVG format as they can be made into any size without losing quality.</p>
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
    echo $icon_preview_page_html;
}