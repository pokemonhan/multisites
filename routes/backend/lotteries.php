<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 4/17/2019
 * Time: 5:30 PM
 */

//游戏
Route::group(['prefix' => 'lotteries', 'namespace' => 'Game\Lottery'], function () {
    $namePrefix = 'lotteries.';
    $controller = 'LotteriesController@';
    //游戏series获取接口
    Route::match(['get', 'options'], 'series-lists', ['as' => $namePrefix . 'series-lists', 'uses' => $controller . 'seriesLists']);
    //彩种列表获取接口
    Route::match(['post', 'options'], 'lotteries-lists', ['as' => $namePrefix . 'lotteries-lists', 'uses' => $controller . 'lotteriesLists']);
    //彩种玩法展示接口
    Route::match(['get', 'options'], 'lotteries-method-lists', ['as' => $namePrefix . 'lotteries-method-lists', 'uses' => $controller . 'lotteriesMethodLists']);
    //奖期展示
    Route::match(['post', 'options'], 'lotteries-issue-lists', ['as' => $namePrefix . 'lotteries-issue-lists', 'uses' => $controller . 'lotteryIssueLists']);
    //奖期生成接口
    Route::match(['post', 'options'], 'lotteries-issue-generate', ['as' => $namePrefix . 'lotteries-issue-generate', 'uses' => $controller . 'generateIssue']);
    //彩种开关
    Route::match(['post', 'options'], 'lotteries-switch', ['as' => $namePrefix . 'lotteries-issue-generate', 'uses' => $controller . 'lotteriesSwitch']);
});
