<?php
// includes/social-platforms-config.php

$social_platforms = [
    'behance' => [
        'label' => 'Behance',
        'default_order' => 1,
    ],
    'bitchute' => [
        'label' => 'Bitchute',
        'default_order' => 2,
    ],
    'cashapp' => [
        'label' => 'CashApp',
        'default_order' => 3,
    ],
    'clouthub' => [
        'label' => 'CloutHub',
        'default_order' => 4,
    ],
    'digg' => [
        'label' => 'Digg',
        'default_order' => 5,
    ],
    'discord' => [
        'label' => 'Discord',
        'default_order' => 6,
    ],
    'facebook' => [
        'label' => 'Facebook',
        'default_order' => 7,
    ],
    'fiverr' => [
        'label' => 'Fiverr',
        'default_order' => 8,
    ],
    'gab' => [
        'label' => 'Gab',
        'default_order' => 9,
    ],
    'github' => [
        'label' => 'GitHub',
        'default_order' => 10,
    ],
    'givesendgo' => [
        'label' => 'GiveSendGo',
        'default_order' => 11,
    ],
    'instagram' => [
        'label' => 'Instagram',
        'default_order' => 12,
    ],
    'linkedin' => [
        'label' => 'LinkedIn',
        'default_order' => 13,
    ],
    'linktree' => [
        'label' => 'Linktree',
        'default_order' => 14,
    ],
    'locals' => [
        'label' => 'Locals',
        'default_order' => 15,
    ],
    'mastodon' => [
        'label' => 'Mastodon',
        'default_order' => 16,
    ],
    'minds' => [
        'label' => 'Minds',
        'default_order' => 17,
    ],
    'myspace' => [
        'label' => 'MySpace',
        'default_order' => 18,
    ],
    'odysee' => [
        'label' => 'Odysee',
        'default_order' => 19,
    ],
    'parler' => [
        'label' => 'Parler',
        'default_order' => 20,
    ],
    'patreon' => [
        'label' => 'Patreon',
        'default_order' => 21,
    ],
    'paypal' => [
        'label' => 'PayPal',
        'default_order' => 22,
    ],
    'pinterest' => [
        'label' => 'Pinterest',
        'default_order' => 23,
    ],
    'publicsq' => [
        'label' => 'Public Square',
        'default_order' => 24,
    ],
    'quora' => [
        'label' => 'Quora',
        'default_order' => 25,
    ],
    'reddit' => [
        'label' => 'Reddit',
        'default_order' => 26,
    ],
    'rokfin' => [
        'label' => 'Rokfin',
        'default_order' => 27,
    ],
    'rumble' => [
        'label' => 'Rumble',
        'default_order' => 28,
    ],
    'snapchat' => [
        'label' => 'Snapchat',
        'default_order' => 29,
    ],
    'substack' => [
        'label' => 'Substack',
        'default_order' => 30,
    ],
    'telegram' => [
        'label' => 'Telegram',
        'default_order' => 31,
    ],
    'tiktok' => [
        'label' => 'TikTok',
        'default_order' => 32,
    ],
    'truth_social' => [
        'label' => 'Truth Social',
        'default_order' => 33,
    ],
    'twitch' => [
        'label' => 'Twitch',
        'default_order' => 34,
    ],
    'twitter_x' => [
        'label' => 'X (formerly Twitter)',
        'default_order' => 35,
    ],
    'unite' => [
        'label' => 'Unite',
        'default_order' => 36,
    ],
    'venmo' => [
        'label' => 'Venmo',
        'default_order' => 37,
    ],
    'vimeo' => [
        'label' => 'Vimeo',
        'default_order' => 38,
    ],    
    'vk' => [
        'label' => 'vk',
        'default_order' => 39,
    ],
    'whatsapp' => [
        'label' => 'WhatsApp',
        'default_order' => 40,
    ],    
    'youtube' => [
        'label' => 'YouTube',
        'default_order' => 41,
    ],
    'zelle' => [
        'label' => 'Zelle',
        'default_order' => 42,
    ],
];

// Social Media Platforms and Icons
function my_social_media_platforms() {
    global $social_platforms;  
    $platforms = [];
    foreach ($social_platforms as $key => $value) {
        $platforms[$key] = [
            'label' => $value['label'],
            'default_order' => $value['default_order']
        ];
    }
    return $platforms;
}