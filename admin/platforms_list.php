<?php
// Auto-generated PHP code for platform settings

function smsi_platform_setup() {
    // Behance Settings
    add_settings_field('behance_url', 'Behance URL', 'smsi_behance_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('behance_order', 'Behance Display Order', 'smsi_behance_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'behance_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'behance_order', generate_order_validator('behance'));

    // Bitchute Settings
    add_settings_field('bitchute_url', 'Bitchute URL', 'smsi_bitchute_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('bitchute_order', 'Bitchute Display Order', 'smsi_bitchute_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'bitchute_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'bitchute_order', generate_order_validator('bitchute'));

    // CashApp Settings
    add_settings_field('cashapp_url', 'CashApp URL', 'smsi_cashapp_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('cashapp_order', 'CashApp Display Order', 'smsi_cashapp_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'cashapp_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'cashapp_order', generate_order_validator('cashapp'));

    // CloutHub Settings
    add_settings_field('clouthub_url', 'CloutHub URL', 'smsi_clouthub_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('clouthub_order', 'CloutHub Display Order', 'smsi_clouthub_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'clouthub_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'clouthub_order', generate_order_validator('clouthub'));

    // Digg Settings
    add_settings_field('digg_url', 'Digg URL', 'smsi_digg_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('digg_order', 'Digg Display Order', 'smsi_digg_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'digg_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'digg_order', generate_order_validator('digg'));

    // Discord Settings
    add_settings_field('discord_url', 'Discord URL', 'smsi_discord_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('discord_order', 'Discord Display Order', 'smsi_discord_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'discord_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'discord_order', generate_order_validator('discord'));

    // Facebook Settings
    add_settings_field('facebook_url', 'Facebook URL', 'smsi_facebook_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('facebook_order', 'Facebook Display Order', 'smsi_facebook_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'facebook_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'facebook_order', generate_order_validator('facebook'));

    // Fiverr Settings
    add_settings_field('fiverr_url', 'Fiverr URL', 'smsi_fiverr_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('fiverr_order', 'Fiverr Display Order', 'smsi_fiverr_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'fiverr_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'fiverr_order', generate_order_validator('fiverr'));

    // Gab Settings
    add_settings_field('gab_url', 'Gab URL', 'smsi_gab_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('gab_order', 'Gab Display Order', 'smsi_gab_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'gab_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'gab_order', generate_order_validator('gab'));

    // GiveSendGo Settings
    add_settings_field('givesendgo_url', 'GiveSendGo URL', 'smsi_givesendgo_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('givesendgo_order', 'GiveSendGo Display Order', 'smsi_givesendgo_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'givesendgo_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'givesendgo_order', generate_order_validator('givesendgo'));

    // Instagram Settings
    add_settings_field('instagram_url', 'Instagram URL', 'smsi_instagram_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('instagram_order', 'Instagram Display Order', 'smsi_instagram_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'instagram_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'instagram_order', generate_order_validator('instagram'));

    // LinkedIn Settings
    add_settings_field('linkedin_url', 'LinkedIn URL', 'smsi_linkedin_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('linkedin_order', 'LinkedIn Display Order', 'smsi_linkedin_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'linkedin_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'linkedin_order', generate_order_validator('linkedin'));

    // Linktree Settings
    add_settings_field('linktree_url', 'Linktree URL', 'smsi_linktree_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('linktree_order', 'Linktree Display Order', 'smsi_linktree_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'linktree_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'linktree_order', generate_order_validator('linktree'));

    // Locals Settings
    add_settings_field('locals_url', 'Locals URL', 'smsi_locals_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('locals_order', 'Locals Display Order', 'smsi_locals_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'locals_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'locals_order', generate_order_validator('locals'));

    // Minds Settings
    add_settings_field('minds_url', 'Minds URL', 'smsi_minds_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('minds_order', 'Minds Display Order', 'smsi_minds_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'minds_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'minds_order', generate_order_validator('minds'));

    // MySpace Settings
    add_settings_field('myspace_url', 'MySpace URL', 'smsi_myspace_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('myspace_order', 'MySpace Display Order', 'smsi_myspace_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'myspace_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'myspace_order', generate_order_validator('myspace'));

    // Odysee Settings
    add_settings_field('odysee_url', 'Odysee URL', 'smsi_odysee_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('odysee_order', 'Odysee Display Order', 'smsi_odysee_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'odysee_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'odysee_order', generate_order_validator('odysee'));

    // Parler Settings
    add_settings_field('parler_url', 'Parler URL', 'smsi_parler_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('parler_order', 'Parler Display Order', 'smsi_parler_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'parler_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'parler_order', generate_order_validator('parler'));

    // Patreon Settings
    add_settings_field('patreon_url', 'Patreon URL', 'smsi_patreon_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('patreon_order', 'Patreon Display Order', 'smsi_patreon_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'patreon_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'patreon_order', generate_order_validator('patreon'));

    // PayPal Settings
    add_settings_field('paypal_url', 'PayPal URL', 'smsi_paypal_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('paypal_order', 'PayPal Display Order', 'smsi_paypal_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'paypal_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'paypal_order', generate_order_validator('paypal'));

    // Pinterest Settings
    add_settings_field('pinterest_url', 'Pinterest URL', 'smsi_pinterest_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('pinterest_order', 'Pinterest Display Order', 'smsi_pinterest_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'pinterest_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'pinterest_order', generate_order_validator('pinterest'));

    // Public Square Settings
    add_settings_field('publicsq_url', 'Public Square URL', 'smsi_publicsq_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('publicsq_order', 'Public Square Display Order', 'smsi_publicsq_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'publicsq_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'publicsq_order', generate_order_validator('publicsq'));

    // Quora Settings
    add_settings_field('quora_url', 'Quora URL', 'smsi_quora_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('quora_order', 'Quora Display Order', 'smsi_quora_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'quora_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'quora_order', generate_order_validator('quora'));

    // Reddit Settings
    add_settings_field('reddit_url', 'Reddit URL', 'smsi_reddit_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('reddit_order', 'Reddit Display Order', 'smsi_reddit_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'reddit_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'reddit_order', generate_order_validator('reddit'));

    // Rokfin Settings
    add_settings_field('rokfin_url', 'Rokfin URL', 'smsi_rokfin_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('rokfin_order', 'Rokfin Display Order', 'smsi_rokfin_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'rokfin_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'rokfin_order', generate_order_validator('rokfin'));

    // Rumble Settings
    add_settings_field('rumble_url', 'Rumble URL', 'smsi_rumble_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('rumble_order', 'Rumble Display Order', 'smsi_rumble_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'rumble_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'rumble_order', generate_order_validator('rumble'));

    // Snapchat Settings
    add_settings_field('snapchat_url', 'Snapchat URL', 'smsi_snapchat_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('snapchat_order', 'Snapchat Display Order', 'smsi_snapchat_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'snapchat_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'snapchat_order', generate_order_validator('snapchat'));

    // Substack Settings
    add_settings_field('substack_url', 'Substack URL', 'smsi_substack_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('substack_order', 'Substack Display Order', 'smsi_substack_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'substack_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'substack_order', generate_order_validator('substack'));

    // Telegram Settings
    add_settings_field('telegram_url', 'Telegram URL', 'smsi_telegram_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('telegram_order', 'Telegram Display Order', 'smsi_telegram_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'telegram_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'telegram_order', generate_order_validator('telegram'));

    // TikTok Settings
    add_settings_field('tiktok_url', 'TikTok URL', 'smsi_tiktok_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('tiktok_order', 'TikTok Display Order', 'smsi_tiktok_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'tiktok_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'tiktok_order', generate_order_validator('tiktok'));

    // Truth Social Settings
    add_settings_field('truth_social_url', 'Truth Social URL', 'smsi_truth_social_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('truth_social_order', 'Truth Social Display Order', 'smsi_truth_social_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'truth_social_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'truth_social_order', generate_order_validator('truth_social'));

    // Twitch Settings
    add_settings_field('twitch_url', 'Twitch URL', 'smsi_twitch_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('twitch_order', 'Twitch Display Order', 'smsi_twitch_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'twitch_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'twitch_order', generate_order_validator('twitch'));

    // X (formerly Twitter) Settings
    add_settings_field('twitter_x_url', 'X (formerly Twitter) URL', 'smsi_twitter_x_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('twitter_x_order', 'X (formerly Twitter) Display Order', 'smsi_twitter_x_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'twitter_x_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'twitter_x_order', generate_order_validator('twitter_x'));

    // Unite Settings
    add_settings_field('unite_url', 'Unite URL', 'smsi_unite_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('unite_order', 'Unite Display Order', 'smsi_unite_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'unite_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'unite_order', generate_order_validator('unite'));

    // Venmo Settings
    add_settings_field('venmo_url', 'Venmo URL', 'smsi_venmo_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('venmo_order', 'Venmo Display Order', 'smsi_venmo_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'venmo_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'venmo_order', generate_order_validator('venmo'));

    // Vimeo Settings
    add_settings_field('vimeo_url', 'Vimeo URL', 'smsi_vimeo_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('vimeo_order', 'Vimeo Display Order', 'smsi_vimeo_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'vimeo_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'vimeo_order', generate_order_validator('vimeo'));

    // vk Settings
    add_settings_field('vk_url', 'vk URL', 'smsi_vk_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('vk_order', 'vk Display Order', 'smsi_vk_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'vk_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'vk_order', generate_order_validator('vk'));

    // WhatsApp Settings
    add_settings_field('whatsapp_url', 'WhatsApp URL', 'smsi_whatsapp_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('whatsapp_order', 'WhatsApp Display Order', 'smsi_whatsapp_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'whatsapp_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'whatsapp_order', generate_order_validator('whatsapp'));

    // YouTube Settings
    add_settings_field('youtube_url', 'YouTube URL', 'smsi_youtube_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('youtube_order', 'YouTube Display Order', 'smsi_youtube_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'youtube_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'youtube_order', generate_order_validator('youtube'));

    // Zelle Settings
    add_settings_field('zelle_url', 'Zelle URL', 'smsi_zelle_url_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    add_settings_field('zelle_order', 'Zelle Display Order', 'smsi_zelle_order_callback', 'show_my_social_icons', 'show_my_social_icons_platform_settings');
    register_setting('show_my_social_icons_all_settings', 'zelle_url', 'esc_url_raw');
    register_setting('show_my_social_icons_all_settings', 'zelle_order', generate_order_validator('zelle'));

}
add_action('admin_init', 'smsi_platform_setup');

function smsi_behance_url_callback() {
    $url = get_option('behance_url', '');
    echo "<input type='url' name='behance_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_bitchute_url_callback() {
    $url = get_option('bitchute_url', '');
    echo "<input type='url' name='bitchute_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_cashapp_url_callback() {
    $url = get_option('cashapp_url', '');
    echo "<input type='url' name='cashapp_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_clouthub_url_callback() {
    $url = get_option('clouthub_url', '');
    echo "<input type='url' name='clouthub_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_digg_url_callback() {
    $url = get_option('digg_url', '');
    echo "<input type='url' name='digg_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_discord_url_callback() {
    $url = get_option('discord_url', '');
    echo "<input type='url' name='discord_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_facebook_url_callback() {
    $url = get_option('facebook_url', '');
    echo "<input type='url' name='facebook_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_fiverr_url_callback() {
    $url = get_option('fiverr_url', '');
    echo "<input type='url' name='fiverr_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_gab_url_callback() {
    $url = get_option('gab_url', '');
    echo "<input type='url' name='gab_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_givesendgo_url_callback() {
    $url = get_option('givesendgo_url', '');
    echo "<input type='url' name='givesendgo_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_instagram_url_callback() {
    $url = get_option('instagram_url', '');
    echo "<input type='url' name='instagram_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_linkedin_url_callback() {
    $url = get_option('linkedin_url', '');
    echo "<input type='url' name='linkedin_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_linktree_url_callback() {
    $url = get_option('linktree_url', '');
    echo "<input type='url' name='linktree_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_locals_url_callback() {
    $url = get_option('locals_url', '');
    echo "<input type='url' name='locals_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_minds_url_callback() {
    $url = get_option('minds_url', '');
    echo "<input type='url' name='minds_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_myspace_url_callback() {
    $url = get_option('myspace_url', '');
    echo "<input type='url' name='myspace_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_odysee_url_callback() {
    $url = get_option('odysee_url', '');
    echo "<input type='url' name='odysee_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_parler_url_callback() {
    $url = get_option('parler_url', '');
    echo "<input type='url' name='parler_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_patreon_url_callback() {
    $url = get_option('patreon_url', '');
    echo "<input type='url' name='patreon_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_paypal_url_callback() {
    $url = get_option('paypal_url', '');
    echo "<input type='url' name='paypal_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_pinterest_url_callback() {
    $url = get_option('pinterest_url', '');
    echo "<input type='url' name='pinterest_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_publicsq_url_callback() {
    $url = get_option('publicsq_url', '');
    echo "<input type='url' name='publicsq_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_quora_url_callback() {
    $url = get_option('quora_url', '');
    echo "<input type='url' name='quora_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_reddit_url_callback() {
    $url = get_option('reddit_url', '');
    echo "<input type='url' name='reddit_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_rokfin_url_callback() {
    $url = get_option('rokfin_url', '');
    echo "<input type='url' name='rokfin_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_rumble_url_callback() {
    $url = get_option('rumble_url', '');
    echo "<input type='url' name='rumble_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_snapchat_url_callback() {
    $url = get_option('snapchat_url', '');
    echo "<input type='url' name='snapchat_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_substack_url_callback() {
    $url = get_option('substack_url', '');
    echo "<input type='url' name='substack_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_telegram_url_callback() {
    $url = get_option('telegram_url', '');
    echo "<input type='url' name='telegram_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_tiktok_url_callback() {
    $url = get_option('tiktok_url', '');
    echo "<input type='url' name='tiktok_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_truth_social_url_callback() {
    $url = get_option('truth_social_url', '');
    echo "<input type='url' name='truth_social_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_twitch_url_callback() {
    $url = get_option('twitch_url', '');
    echo "<input type='url' name='twitch_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_twitter_x_url_callback() {
    $url = get_option('twitter_x_url', '');
    echo "<input type='url' name='twitter_x_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_unite_url_callback() {
    $url = get_option('unite_url', '');
    echo "<input type='url' name='unite_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_venmo_url_callback() {
    $url = get_option('venmo_url', '');
    echo "<input type='url' name='venmo_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_vimeo_url_callback() {
    $url = get_option('vimeo_url', '');
    echo "<input type='url' name='vimeo_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_vk_url_callback() {
    $url = get_option('vk_url', '');
    echo "<input type='url' name='vk_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_whatsapp_url_callback() {
    $url = get_option('whatsapp_url', '');
    echo "<input type='url' name='whatsapp_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_youtube_url_callback() {
    $url = get_option('youtube_url', '');
    echo "<input type='url' name='youtube_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_zelle_url_callback() {
    $url = get_option('zelle_url', '');
    echo "<input type='url' name='zelle_url' value='" . esc_attr($url) . "' class='regular-text' />";
}

function smsi_behance_order_callback() {
    $order = get_option('behance_order', 0);
    echo "<input type='number' name='behance_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_bitchute_order_callback() {
    $order = get_option('bitchute_order', 0);
    echo "<input type='number' name='bitchute_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_cashapp_order_callback() {
    $order = get_option('cashapp_order', 0);
    echo "<input type='number' name='cashapp_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_clouthub_order_callback() {
    $order = get_option('clouthub_order', 0);
    echo "<input type='number' name='clouthub_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_digg_order_callback() {
    $order = get_option('digg_order', 0);
    echo "<input type='number' name='digg_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_discord_order_callback() {
    $order = get_option('discord_order', 0);
    echo "<input type='number' name='discord_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_facebook_order_callback() {
    $order = get_option('facebook_order', 0);
    echo "<input type='number' name='facebook_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_fiverr_order_callback() {
    $order = get_option('fiverr_order', 0);
    echo "<input type='number' name='fiverr_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_gab_order_callback() {
    $order = get_option('gab_order', 0);
    echo "<input type='number' name='gab_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_givesendgo_order_callback() {
    $order = get_option('givesendgo_order', 0);
    echo "<input type='number' name='givesendgo_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_instagram_order_callback() {
    $order = get_option('instagram_order', 0);
    echo "<input type='number' name='instagram_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_linkedin_order_callback() {
    $order = get_option('linkedin_order', 0);
    echo "<input type='number' name='linkedin_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_linktree_order_callback() {
    $order = get_option('linktree_order', 0);
    echo "<input type='number' name='linktree_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_locals_order_callback() {
    $order = get_option('locals_order', 0);
    echo "<input type='number' name='locals_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_minds_order_callback() {
    $order = get_option('minds_order', 0);
    echo "<input type='number' name='minds_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_myspace_order_callback() {
    $order = get_option('myspace_order', 0);
    echo "<input type='number' name='myspace_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_odysee_order_callback() {
    $order = get_option('odysee_order', 0);
    echo "<input type='number' name='odysee_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_parler_order_callback() {
    $order = get_option('parler_order', 0);
    echo "<input type='number' name='parler_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_patreon_order_callback() {
    $order = get_option('patreon_order', 0);
    echo "<input type='number' name='patreon_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_paypal_order_callback() {
    $order = get_option('paypal_order', 0);
    echo "<input type='number' name='paypal_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_pinterest_order_callback() {
    $order = get_option('pinterest_order', 0);
    echo "<input type='number' name='pinterest_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_publicsq_order_callback() {
    $order = get_option('publicsq_order', 0);
    echo "<input type='number' name='publicsq_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_quora_order_callback() {
    $order = get_option('quora_order', 0);
    echo "<input type='number' name='quora_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_reddit_order_callback() {
    $order = get_option('reddit_order', 0);
    echo "<input type='number' name='reddit_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_rokfin_order_callback() {
    $order = get_option('rokfin_order', 0);
    echo "<input type='number' name='rokfin_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_rumble_order_callback() {
    $order = get_option('rumble_order', 0);
    echo "<input type='number' name='rumble_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_snapchat_order_callback() {
    $order = get_option('snapchat_order', 0);
    echo "<input type='number' name='snapchat_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_substack_order_callback() {
    $order = get_option('substack_order', 0);
    echo "<input type='number' name='substack_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_telegram_order_callback() {
    $order = get_option('telegram_order', 0);
    echo "<input type='number' name='telegram_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_tiktok_order_callback() {
    $order = get_option('tiktok_order', 0);
    echo "<input type='number' name='tiktok_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_truth_social_order_callback() {
    $order = get_option('truth_social_order', 0);
    echo "<input type='number' name='truth_social_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_twitch_order_callback() {
    $order = get_option('twitch_order', 0);
    echo "<input type='number' name='twitch_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_twitter_x_order_callback() {
    $order = get_option('twitter_x_order', 0);
    echo "<input type='number' name='twitter_x_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_unite_order_callback() {
    $order = get_option('unite_order', 0);
    echo "<input type='number' name='unite_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_venmo_order_callback() {
    $order = get_option('venmo_order', 0);
    echo "<input type='number' name='venmo_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_vimeo_order_callback() {
    $order = get_option('vimeo_order', 0);
    echo "<input type='number' name='vimeo_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_vk_order_callback() {
    $order = get_option('vk_order', 0);
    echo "<input type='number' name='vk_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_whatsapp_order_callback() {
    $order = get_option('whatsapp_order', 0);
    echo "<input type='number' name='whatsapp_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_youtube_order_callback() {
    $order = get_option('youtube_order', 0);
    echo "<input type='number' name='youtube_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}

function smsi_zelle_order_callback() {
    $order = get_option('zelle_order', 0);
    echo "<input type='number' name='zelle_order' min='0' value='" . esc_attr($order) . "' class='number' />";
}
