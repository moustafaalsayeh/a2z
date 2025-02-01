<?php

return [
    'locale' => 'en',

    'locales' => ['en', 'ar'],

    'locale_codes' => [
        'ar' => env('LOCALIZATION_CODE_AR', 'ar_EG.utf8'),
        'en' => env('LOCALIZATION_CODE_EN', 'en_US.utf8'),
    ],

    'fallback_locale' => 'en',

    'notTranslatableSegments' => [],
];
