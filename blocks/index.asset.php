<?php
return array(
    'dependencies' => array(
        'wp-blocks',
        'wp-i18n',
        'wp-element',
        'wp-components',
        'wp-block-editor'
    ),
    'version' => filemtime(plugin_dir_path(__FILE__) . '../build/index.js')
);