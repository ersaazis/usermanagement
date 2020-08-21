<?php

Route::group(['middleware' => ['web', \ersaazis\cb\middlewares\CBBackend::class], 'prefix' => cb()->getAdminPath()], function () {
    cb()->routeController("users",'\App\Http\Controllers\Crud\UserManagementController');
});