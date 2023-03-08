<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ManagementController;

/**
 * get customerMenuGet
 * Summary: メニューの取得
 * Output-Formats: [application/json]
 */
Route::get('/customer/dish', [CustomerController::class, 'customerMenuGet']);

/**
 * get customerMenuDishGet
 * Summary: 料理の詳細を取得
 * Output-Formats: [application/json]
 */
Route::get('/customer/dish/{dish_id}', [CustomerController::class, 'customerMenuDishGet']);

/**
 * get customerOrderGet
 * Summary: 注文履歴、合計金額の取得
 * Output-Formats: [application/json]
 */
Route::get('/customer/order', [CustomerController::class, 'customerOrderGet']);

/**
 * post customerOrderPost
 * Summary: 注文・追加注文送信
 * Output-Formats: [application/json]
 */
Route::post('/customer/order', [CustomerController::class, 'customerOrderPost']);

/**
 * post customerOrderFinish
 * Summary: 注文確定・会計依頼
 * Notes: お会計ボタンを押すとそこで注文が締め切られる。is_order_finishedの状態を変更する(false → true)。
 */
Route::post('/customer/order/finish', [CustomerController::class, 'customerOrderFinish']);

/**
 * get adminList
 * Summary: 管理者アカウントの一覧取得 (ownerは自分の店舗に所属しているアカウントのみ取得可能)
 * Output-Formats: [application/json]
 */
Route::get('/management/account', [ManagementController::class, 'adminList']);

/**
 * post adminSignUp
 * Summary: 新規管理者アカウント(owner, counter, kitchen)発行
 * Output-Formats: [application/json]
 */
Route::post('/management/account', [ManagementController::class, 'adminSignUp']);

/**
 * delete adminDelete
 * Summary: 管理者アカウント(owner, counter, kitchen)削除
 */
Route::delete('/management/account/{admin_id}', [ManagementController::class, 'adminDelete']);

/**
 * get adminGet
 * Summary: 管理者アカウント情報取得
 * Output-Formats: [application/json]
 */
Route::get('/management/account/{admin_id}', [ManagementController::class, 'adminGet']);

/**
 * put adminModify
 * Summary: 管理者アカウント(owner, counter, kitchen)情報編集
 */
Route::put('/management/account/{admin_id}', [ManagementController::class, 'adminModify']);

/**
 * post menuCreate
 * Summary: メニューに料理を追加(新規作成)
 * Output-Formats: [application/json]
 */
Route::post('/management/dish/byRestaurant/{restaurant_id}', [ManagementController::class, 'menuCreate']);

/**
 * get menuList
 * Summary: 料理メニューの一覧取得
 * Output-Formats: [application/json]
 */
Route::get('/management/dish/byRestaurant/{restaurant_id}', [ManagementController::class, 'menuList']);

/**
 * delete dishDelete
 * Summary: 料理の削除
 */
Route::delete('/management/dish/{dish_id}', [ManagementController::class, 'dishDelete']);

/**
 * get dishGet
 * Summary: 指定した料理情報取得
 * Output-Formats: [application/json]
 */
Route::get('/management/dish/{dish_id}', [ManagementController::class, 'dishGet']);

/**
 * put dishModify
 * Summary: 料理情報編集
 */
Route::put('/management/dish/{dish_id}', [ManagementController::class, 'dishModify']);

/**
 * post adminLogin
 * Summary: 管理者(system, owner, counter, kitchen)ログイン
 * Output-Formats: [application/json]
 */
Route::post('/management/login', [ManagementController::class, 'adminLogin']);

/**
 * put orderedDishCancel
 * Summary: 注文した(+未提供)料理のキャンセル
 */
Route::put('/management/order/byOrderedDish/{ordered_dish_id}/cancel', [ManagementController::class, 'orderedDishCancel']);

/**
 * put orderedDishDelivery
 * Summary: 注文ステータスを提供済みにする
 */
Route::put('/management/order/byOrderedDish/{ordered_dish_id}/deliver', [ManagementController::class, 'orderedDishDelivery']);

/**
 * get orderList
 * Summary: 指定した店舗の注文一覧の取得
 * Output-Formats: [application/json]
 */
Route::get('/management/order/byRestaurant/{restaurant_id}', [ManagementController::class, 'orderList']);

/**
 * get unservedDishList
 * Summary: 指定した店舗の未提供料理一覧を取得 (注文時間順)
 * Output-Formats: [application/json]
 */
Route::get('/management/order/byRestaurant/{restaurant_id}/unserved', [ManagementController::class, 'unservedDishList']);

/**
 * get orderGet
 * Summary: 注文詳細の取得
 * Output-Formats: [application/json]
 */
Route::get('/management/order/{order_id}', [ManagementController::class, 'orderGet']);

/**
 * put orderPut
 * Summary: (会計済みボタン) 注文を完了する
 * Notes: 注文状態(is_paid)、座席のQRコードトークンを更新する。
 */
Route::put('/management/order/{order_id}/checkout', [ManagementController::class, 'orderPut']);

/**
 * get restaurantList
 * Summary: 店舗の一覧取得 (ownerは自分の店舗のみ取得可能)
 * Output-Formats: [application/json]
 */
Route::get('/management/restaurant', [ManagementController::class, 'restaurantList']);

/**
 * post restaurantSignUp
 * Summary: 新規店舗登録
 * Output-Formats: [application/json]
 */
Route::post('/management/restaurant', [ManagementController::class, 'restaurantSignUp']);

/**
 * delete restaurantDelete
 * Summary: 店舗の削除
 */
Route::delete('/management/restaurant/{restaurant_id}', [ManagementController::class, 'restaurantDelete']);

/**
 * get restaurantGet
 * Summary: 指定した店舗情報取得
 * Output-Formats: [application/json]
 */
Route::get('/management/restaurant/{restaurant_id}', [ManagementController::class, 'restaurantGet']);

/**
 * put restaurantModify
 * Summary: 店舗情報編集
 */
Route::put('/management/restaurant/{restaurant_id}', [ManagementController::class, 'restaurantModify']);

/**
 * post seatAdd
 * Summary: 座席の追加
 * Output-Formats: [application/json]
 */
Route::post('/management/seat/byRestaurant/{restaurant_id}', [ManagementController::class, 'seatAdd']);

/**
 * get seatList
 * Summary: 指定した店舗の座席情報一覧を取得
 * Output-Formats: [application/json]
 */
Route::get('/management/seat/byRestaurant/{restaurant_id}', [ManagementController::class, 'seatList']);

/**
 * delete seatDelete
 * Summary: 座席の削除
 */
Route::delete('/management/seat/{seat_id}', [ManagementController::class, 'seatDelete']);

/**
 * put seatEdit
 * Summary: 座席の編集 (編集後、自動QRコードトークン再発行)
 * Output-Formats: [application/json]
 */
Route::put('/management/seat/{seat_id}', [ManagementController::class, 'seatEdit']);

/**
 * get seatInfo
 * Summary: 指定した座席の情報を取得
 * Output-Formats: [application/json]
 */
Route::get('/management/seat/{seat_id}', [ManagementController::class, 'seatInfo']);

/**
 * put seatRefresh
 * Summary: 座席のQRコードトークンを再発行
 * Output-Formats: [application/json]
 */
Route::put('/management/seat/{seat_id}/refresh', [ManagementController::class, 'seatRefresh']);

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
