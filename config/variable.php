<?php

return [
    'app_domain' => env('APP_DOMAIN'),
    'image_domain' => env('IMAGE_DOMAIN','https://image.smilodon.explorecms.com/'),
    'app_lang_default' => env('APP_LANG_DEFAULT'),
    'app_lang_en' => env('APP_LANG_EN','en'),
    'paginate_limit' => env('PAGINATE_LIMIT'),
    // S3
    's3_spaces_key' => env('S3_SPACES_KEYS'),
    's3_spaces_secret' => env('S3_SPACES_SECRET'),
    // 图片
    'image_scaling' => env('IMAGE_SCALING'),
    // 文件路径
    'file_path' => base_path() . '/storage/buckets/'
];
