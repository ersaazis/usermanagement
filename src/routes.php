<?php

Route::group(['middleware' => ['web', \ersaazis\cb\middlewares\CBBackend::class], 'prefix' => cb()->getAdminPath(), 'namespace' => 'ersaazis\usermanagement\controllers'], function () {
    cb()->routeController("users",'\ersaazis\usermanagement\controllers\UserManagementController');
});