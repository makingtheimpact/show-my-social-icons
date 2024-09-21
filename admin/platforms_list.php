<?php
// Define an array of all supported platforms
$supported_platforms = [
    'behance' => 'Behance',
    'bitchute' => 'Bitchute',
    'cashapp' => 'CashApp',
    'clouthub' => 'CloutHub',
    'digg' => 'Digg',
    'discord' => 'Discord',
    'facebook' => 'Facebook',
    'fiverr' => 'Fiverr',
    'gab' => 'Gab',
    'github' => 'GitHub',
    'givesendgo' => 'GiveSendGo',
    'instagram' => 'Instagram',
    'linkedin' => 'LinkedIn',
    'linktree' => 'Linktree',
    'locals' => 'Locals',
    'mastodon' => 'Mastodon',
    'minds' => 'Minds',
    'myspace' => 'MySpace',
    'odysee' => 'Odysee',
    'parler' => 'Parler',
    'patreon' => 'Patreon',
    'paypal' => 'PayPal',
    'pinterest' => 'Pinterest',
    'publicsq' => 'Public Square',
    'quora' => 'Quora',
    'reddit' => 'Reddit',
    'rokfin' => 'Rokfin',
    'rumble' => 'Rumble',
    'snapchat' => 'Snapchat',
    'substack' => 'Substack',
    'telegram' => 'Telegram',
    'tiktok' => 'TikTok',
    'truth_social' => 'Truth Social',
    'twitch' => 'Twitch',
    'twitter_x' => 'X (formerly Twitter)',
    'unite' => 'Unite',
    'venmo' => 'Venmo',
    'vimeo' => 'Vimeo',
    'vk' => 'VK',
    'whatsapp' => 'WhatsApp',
    'youtube' => 'YouTube',
    'zelle' => 'Zelle'
];

function smsi_platform_setup() {
    global $supported_platforms;
    
    add_settings_section(
        'show_my_social_icons_platform_settings',
        'Platform Links and Order Settings',
        'smsi_platform_settings_description_callback',
        'show_my_social_icons'
    );

    add_settings_field(
        "platform_search",
        "Search Platforms",
        'smsi_platform_search_callback',
        'show_my_social_icons',
        'show_my_social_icons_platform_settings'
    );

    add_settings_field(
        "platform_fields",
        "",
        'smsi_platform_fields_callback',
        'show_my_social_icons',
        'show_my_social_icons_platform_settings'
    );

    foreach ($supported_platforms as $platform_id => $platform_name) {
        register_setting('show_my_social_icons_all_settings', "{$platform_id}_url", 'esc_url_raw');
        register_setting('show_my_social_icons_all_settings', "{$platform_id}_order", 'smsi_validate_order');
    }
}
add_action('admin_init', 'smsi_platform_setup');

function smsi_platform_search_callback() {
    echo '<input type="text" id="smsi-platform-search" placeholder="Search platforms..." class="regular-text">';
}

function smsi_platform_fields_callback() {
    global $supported_platforms;
    echo '<div id="smsi-platform-container">';
    
    $set_platforms = [];
    $unset_platforms = [];
    
    foreach ($supported_platforms as $platform_id => $platform_name) {
        $url = get_option("{$platform_id}_url", '');
        $order = get_option("{$platform_id}_order", 0);
        
        $platform_data = [
            'id' => $platform_id,
            'name' => $platform_name,
            'url' => $url,
            'order' => $order
        ];
        
        if (!empty($url)) {
            $set_platforms[] = $platform_data;
        } else {
            $unset_platforms[] = $platform_data;
        }
    }
    
    usort($set_platforms, function($a, $b) {
        return $a['order'] - $b['order'];
    });
    
    usort($unset_platforms, function($a, $b) {
        return strcmp($a['name'], $b['name']);
    });
    
    $all_platforms = array_merge($set_platforms, $unset_platforms);
    
    // Add this code above the platform list
    echo "<div style='text-align: right; margin-bottom: 10px;'>
    <input type='submit' name='submit' id='submit' class='button button-primary' value='Save Changes'>
    </div>";

    global $smsi_plugin_dir_path;
    foreach ($all_platforms as $platform) {
        $icon_url = $smsi_plugin_dir_path . "assets/png/ic-c/100w/{$platform['id']}_icon_100px.png";
        echo "<div class='smsi-platform-fields' data-platform='" . esc_attr($platform['name']) . "' data-id='" . esc_attr($platform['id']) . "'>";
        echo "<span class='smsi-drag-handle dashicons dashicons-move'></span>";
        echo "<img src='" . esc_url($icon_url) . "' alt='" . esc_attr($platform['name']) . " icon' class='smsi-platform-icon' />";
        echo "<label class='smsi-platform-label'>" . esc_html($platform['name']) . "</label>";
        echo "<input type='url' name='" . esc_attr($platform['id']) . "_url' value='" . esc_attr($platform['url']) . "' class='regular-text smsi-url-field' placeholder='" . esc_attr__('Enter URL', 'show-my-social-icons') . "' />";
        echo "<input type='number' name='" . esc_attr($platform['id']) . "_order' value='" . esc_attr($platform['order']) . "' class='small-text smsi-order-field' readonly />";
        echo "</div>";
    }
    echo '</div>';
}

function smsi_validate_order($order) {
    $order = intval($order);
    return ($order >= 0) ? $order : 0;
}
