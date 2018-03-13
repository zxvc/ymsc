<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//用户类路由
Route::group(['prefix' => '', 'middleware' => ['BeforeRequest']], function () {
    //支付结果通知    请勿调用
    Route::any('order/notify', 'API\WechatController@wechatNotify');
});