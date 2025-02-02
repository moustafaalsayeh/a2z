<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Modules\\CustomFields\\Database\\Seeders\\CustomFieldsDatabaseSeeder' => $baseDir . '/Database/Seeders/CustomFieldsDatabaseSeeder.php',
    'Modules\\CustomFields\\Entities\\Text' => $baseDir . '/Entities/Text.php',
    'Modules\\CustomFields\\Entities\\TextField' => $baseDir . '/Entities/TextField.php',
    'Modules\\CustomFields\\Helpers\\Textable' => $baseDir . '/Helpers/Textable.php',
    'Modules\\CustomFields\\Http\\Controllers\\CustomFieldsController' => $baseDir . '/Http/Controllers/CustomFieldsController.php',
    'Modules\\CustomFields\\Http\\Controllers\\TextFieldController' => $baseDir . '/Http/Controllers/TextFieldController.php',
    'Modules\\CustomFields\\Http\\Requests\\StoreTextFieldRequest' => $baseDir . '/Http/Requests/StoreTextFieldRequest.php',
    'Modules\\CustomFields\\Http\\Requests\\UpdateTextFieldRequest' => $baseDir . '/Http/Requests/UpdateTextFieldRequest.php',
    'Modules\\CustomFields\\Providers\\CustomFieldsServiceProvider' => $baseDir . '/Providers/CustomFieldsServiceProvider.php',
    'Modules\\CustomFields\\Providers\\RouteServiceProvider' => $baseDir . '/Providers/RouteServiceProvider.php',
);
