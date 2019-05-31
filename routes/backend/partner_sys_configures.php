<?php

//配置相关
Route::group(['prefix' => 'partner-sys-configures', 'namespace' => 'Admin'], function () {
    $namePrefix = 'backend-api.configures.';
    $controller = 'ConfiguresController@';
    //配置列表
    Route::match(['get', 'options'], 'detail', ['as' => $namePrefix . 'detail', 'uses' => $controller . 'getConfiguresList']);
    //添加配置
    Route::match(['post', 'options'], 'add', ['as' => $namePrefix . 'add', 'uses' => $controller . 'add']);
    //编辑配置
    Route::match(['post', 'options'], 'edit', ['as' => $namePrefix . 'edit', 'uses' => $controller . 'edit']);
    //删除配置
    Route::match(['post', 'options'], 'delete', ['as' => $namePrefix . 'delete', 'uses' => $controller . 'delete']);
    //配置开关
    Route::match(['post', 'options'], 'switch', ['as' => $namePrefix . 'switch', 'uses' => $controller . 'configSwitch']);
    //配置ico
    Route::match(['post', 'options'], 'upload-ico', ['as' => $namePrefix . 'upload-ico', 'uses' => $controller . 'uploadIco']);
});
