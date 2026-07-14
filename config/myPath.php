<?php
return [
    'assets' => [
        'theme' => [
            'panel' => [
                'css' => 'admin/assets/css/',
                'js' => 'admin/assets/js/',
                'fonts' => 'admin/assets/fonts/',
                'plugins' => 'admin/assets/plugins/',
                'pivot' => 'admin/assets/pivot'
            ],
            'frontEnd' => [
                'css' => 'frontend/assets/css/',
                'js' => 'frontend/assets/js/',
                'lib' => 'frontend/assets/lib/',
            ],
        ],
        'panel_image_folders' => [
            'users' => 'users',
            'sliders' => 'sliders',
            'page_content' => 'page_content', 
            'site-config' => 'site-config',
            'news' => 'news',
            'staffs' => 'staffs',
            'gallery' => 'gallery',
            'start_up_notice' => 'start_up_notice',
            'media' => 'media',
            'audio' => 'audio',
        ],
    ],
    'image-dimensions' => [
        'users' => [
            ['width' => 50, 'height' => 50],
            ['width' => 300, 'height' => 200],
        ],
        'page_content' => [
            ['width' => 300, 'height' => 250],
        ],
    ],
    'default_languages' => [
        'en',
        'np'
    ],

    'show_recaptcha' => env('RECAPTCHA_ENABLE', true),

    'mail_from'      => env('MAIL_FROM_ADDRESS', ''),
    'mail_from_name' => env('MAIL_FROM_NAME', ''),

];