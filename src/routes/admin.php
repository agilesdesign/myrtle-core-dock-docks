<?php

Route::resource('docks', \Myrtle\Core\Docks\Http\Controllers\Administrator\DocksController::class, ['only' => ['index']]);

Route::group(['middleware' => [\Myrtle\Core\Docks\Http\Middleware\CheckForDocksConfigFileMiddleware::class]], function () {

    Route::get('docks/{dock}/settings', [
        'uses' => \Myrtle\Core\Docks\Http\Controllers\Administrator\DockSettingsController::class . '@edit',
        'as' => 'docks.settings.edit'
    ]);

    Route::put('docks/{dock}/settings', [
        'uses' => \Myrtle\Core\Docks\Http\Controllers\Administrator\DockSettingsController::class . '@update',
        'as' => 'docks.settings.update'
    ]);

    Route::put('docks/{dock}/enable', [
        'uses' => \Myrtle\Core\Docks\Http\Controllers\Administrator\DocksController::class . '@enable',
        'as' => 'docks.enable'
    ]);

    Route::put('docks/{dock}/disable', [
        'uses' => \Myrtle\Core\Docks\Http\Controllers\Administrator\DocksController::class . '@disabled',
        'as' => 'docks.disable'
    ]);
});

Route::get('docks/{dock}/permissions', [
    'uses' => \Myrtle\Core\Docks\Http\Controllers\Administrator\DockPermissionsController::class . '@edit',
    'as' => 'docks.permissions.edit'
]);

Route::put('docks/{dock}/permissions', [
    'uses' => \Myrtle\Core\Docks\Http\Controllers\Administrator\DockPermissionsController::class . '@update',
    'as' => 'docks.permissions.update'
]);