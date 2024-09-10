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
    'givesendgo' => [
        'label' => 'GiveSendGo',
        'default_order' => 10,
    ],
    'instagram' => [
        'label' => 'Instagram',
        'default_order' => 11,
    ],
    'linkedin' => [
        'label' => 'LinkedIn',
        'default_order' => 12,
    ],
    'linktree' => [
        'label' => 'Linktree',
        'default_order' => 13,
    ],
    'locals' => [
        'label' => 'Locals',
        'default_order' => 143,
    ],
    'minds' => [
        'label' => 'Minds',
        'default_order' => 15,
    ],
    'myspace' => [
        'label' => 'MySpace',
        'default_order' => 16,
    ],
    'odysee' => [
        'label' => 'Odysee',
        'default_order' => 17,
    ],
    'parler' => [
        'label' => 'Parler',
        'default_order' => 18,
    ],
    'patreon' => [
        'label' => 'Patreon',
        'default_order' => 19,
    ],
    'paypal' => [
        'label' => 'PayPal',
        'default_order' => 20,
    ],
    'pinterest' => [
        'label' => 'Pinterest',
        'default_order' => 21,
    ],
    'publicsq' => [
        'label' => 'Public Square',
        'default_order' => 22,
    ],
    'quora' => [
        'label' => 'Quora',
        'default_order' => 23,
    ],
    'reddit' => [
        'label' => 'Reddit',
        'default_order' => 24,
    ],
    'rokfin' => [
        'label' => 'Rokfin',
        'default_order' => 25,
    ],
    'rumble' => [
        'label' => 'Rumble',
        'default_order' => 26,
    ],
    'snapchat' => [
        'label' => 'Snapchat',
        'default_order' => 27,
    ],
    'substack' => [
        'label' => 'Substack',
        'default_order' => 28,
    ],
    'telegram' => [
        'label' => 'Telegram',
        'default_order' => 29,
    ],
    'tiktok' => [
        'label' => 'TikTok',
        'default_order' => 30,
    ],
    'truth_social' => [
        'label' => 'Truth Social',
        'default_order' => 31,
    ],
    'twitch' => [
        'label' => 'Twitch',
        'default_order' => 32,
    ],
    'twitter_x' => [
        'label' => 'X (formerly Twitter)',
        'default_order' => 33,
    ],
    'unite' => [
        'label' => 'Unite',
        'default_order' => 34,
    ],
    'venmo' => [
        'label' => 'Venmo',
        'default_order' => 35,
    ],
    'vimeo' => [
        'label' => 'Vimeo',
        'default_order' => 36,
    ],    
    'vk' => [
        'label' => 'vk',
        'default_order' => 37,
    ],
    'whatsapp' => [
        'label' => 'WhatsApp',
        'default_order' => 38,
    ],    
    'youtube' => [
        'label' => 'YouTube',
        'default_order' => 39,
    ],
    'zelle' => [
        'label' => 'Zelle',
        'default_order' => 40,
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