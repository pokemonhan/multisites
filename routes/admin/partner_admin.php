<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/11/2019
 * Time: 12:22 PM
 */

//商户用户相关
Route::post('details', ['as' => 'detail', 'uses' => 'AuthController@details']);
Route::get('user', ['as' => 'user', 'uses' => 'AuthController@user']);
Route::match(['get', 'options'], 'logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
Route::match(['post', 'options'], 'partner-admin/register', ['as' => 'partnerAdmin.register', 'uses' => 'AuthController@register']);

//管理员相关
Route::group(['prefix' => 'partner-admin-user'], function () {
    $namePrefix = 'partnerAdmin.';
    $controller = 'AuthController@';
    Route::match(['get', 'options'], 'get-all-users', ['as' => $namePrefix . 'get-all-users', 'uses' => $controller . 'allUser']);
    Route::match(['post', 'options'], 'update-user-group', ['as' => $namePrefix . 'update-user-group', 'uses' => $controller . 'updateUserGroup']);
    Route::match(['post', 'options'], 'delete-user', ['as' => $namePrefix . 'delete-user', 'uses' => $controller . 'deletePartnerAdmin']);
    Route::match(['post', 'options'], 'reset-password', ['as' => $namePrefix . 'reset-password', 'uses' => $controller . 'updatePAdmPassword']);
    Route::match(['post', 'options'], 'self-reset-password', ['as' => $namePrefix . 'self-reset-password', 'uses' => $controller . 'selfResetPassword']);
});