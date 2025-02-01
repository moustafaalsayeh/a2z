<?php

return [
    'name' => 'Media',
    'photos' => [
        'path' => env('APP_PHOTOS_PATH', 'public/photos/'),
    ],

    'videos' => [
        'path' => env('APP_VIDEOS_PATH', 'public/videos/'),
        'types' => [
            'video',
            'video-url',
        ],
    ],
    'files' => [
        'path' => env('APP_FILES_PATH', 'public/files/'),
    ],
];
