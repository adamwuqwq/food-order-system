<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AccountManagementController;
use App\Http\Controllers\RestaurantManagementController;
use App\Http\Controllers\DishManagementController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\SeatManagementController;
use App\Http\Controllers\AuthorizationController;

/**
 * Summary: 客側のAPI
 */
Route::group(['middleware' => ['customerAuthorization']], function () {
    // メニューの取得
    Route::get('/customer/dish', [CustomerController::class, 'customerMenuGet']);

    // 料理の詳細を取得
    Route::get('/customer/dish/{dish_id}', [CustomerController::class, 'customerMenuDishGet']);

    // 注文履歴、合計金額の取得
    Route::get('/customer/order', [CustomerController::class, 'customerOrderGet']);

    // 注文・追加注文送信
    Route::post('/customer/order', [CustomerController::class, 'customerOrderPost']);

    // 注文確定・会計依頼
    Route::post('/customer/order/finish', [CustomerController::class, 'customerOrderFinish']);
});

/**
 * Summary: 管理者ログイン
 * Output-Formats: [application/json]
 */
Route::post('/management/login', [AuthorizationController::class, 'adminLogin']);


/**
 * Summary: 店舗管理者(owner)のAPI
 */
Route::group(['middleware' => ['auth:sanctum']], function () {

    // 管理者アカウントの一覧取得 (ownerは自分の店舗に所属しているアカウントのみ取得可能)
    Route::get('/management/account', [AccountManagementController::class, 'adminList']);

    // 新規管理者アカウント(owner, counter, kitchen)発行
    Route::post('/management/account', [AccountManagementController::class, 'adminSignUp']);

    // 管理者アカウント(owner, counter, kitchen)削除
    Route::delete('/management/account/{admin_id}', [AccountManagementController::class, 'adminDelete']);

    // 管理者アカウント情報取得
    Route::get('/management/account/{admin_id}', [AccountManagementController::class, 'adminGet']);

    // Summary: 管理者アカウント(owner, counter, kitchen)情報編集
    Route::put('/management/account/{admin_id}', [AccountManagementController::class, 'adminModify']);

    // メニューに料理を追加(新規作成)
    Route::post('/management/dish/byRestaurant/{restaurant_id}', [DishManagementController::class, 'dishCreate']);

    // 料理メニューの一覧取得
    Route::get('/management/dish/byRestaurant/{restaurant_id}', [DishManagementController::class, 'dishList']);

    // 料理の削除
    Route::delete('/management/dish/{dish_id}', [DishManagementController::class, 'dishDelete']);

    // 指定した料理情報取得
    Route::get('/management/dish/{dish_id}', [DishManagementController::class, 'dishGet']);

    // 料理情報編集
    Route::put('/management/dish/{dish_id}', [DishManagementController::class, 'dishModify']);

    // 注文した(+未提供)料理のキャンセル
    Route::put('/management/order/byOrderedDish/{ordered_dish_id}/cancel', [OrderManagementController::class, 'orderedDishCancel']);

    // 注文ステータスを提供済みにする
    Route::put('/management/order/byOrderedDish/{ordered_dish_id}/deliver', [OrderManagementController::class, 'orderedDishDelivery']);

    // 指定した店舗の注文一覧の取得
    Route::get('/management/order/byRestaurant/{restaurant_id}', [OrderManagementController::class, 'orderList']);

    // 指定した店舗の未提供料理一覧を取得 (注文時間順)
    Route::get('/management/order/byRestaurant/{restaurant_id}/unserved', [OrderManagementController::class, 'unservedDishList']);

    // 注文詳細の取得
    Route::get('/management/order/{order_id}', [OrderManagementController::class, 'orderGet']);

    // (会計済みボタン) 注文を完了する
    Route::put('/management/order/{order_id}/checkout', [OrderManagementController::class, 'orderComplete']);

    // 店舗の一覧取得 (ownerは自分の店舗のみ取得可能)
    Route::get('/management/restaurant', [RestaurantManagementController::class, 'restaurantList']);

    // 新規店舗登録
    Route::post('/management/restaurant', [RestaurantManagementController::class, 'restaurantSignUp']);

    // 店舗の削除
    Route::delete('/management/restaurant/{restaurant_id}', [RestaurantManagementController::class, 'restaurantDelete']);

    // 指定した店舗情報取得
    Route::get('/management/restaurant/{restaurant_id}', [RestaurantManagementController::class, 'restaurantGet']);

    // 店舗情報編集
    Route::put('/management/restaurant/{restaurant_id}', [RestaurantManagementController::class, 'restaurantModify']);

    // 座席の追加
    Route::post('/management/seat/byRestaurant/{restaurant_id}', [SeatManagementController::class, 'seatAdd']);

    // 指定した店舗の座席情報一覧を取得
    Route::get('/management/seat/byRestaurant/{restaurant_id}', [SeatManagementController::class, 'seatList']);

    // 座席の削除
    Route::delete('/management/seat/{seat_id}', [SeatManagementController::class, 'seatDelete']);

    // 座席の編集 (編集後、自動QRコードトークン再発行)
    Route::put('/management/seat/{seat_id}', [SeatManagementController::class, 'seatEdit']);

    // 指定した座席の情報を取得
    Route::get('/management/seat/{seat_id}', [SeatManagementController::class, 'seatInfo']);

    // 座席のQRコードトークンを再発行
    Route::put('/management/seat/{seat_id}/refresh', [SeatManagementController::class, 'seatRefresh']);

    // 新規店舗の座席一括登録
    Route::post('/management/seat/byRestaurant/{restaurant_id}/{seat_num}', [SeatManagementController::class, 'multipleSeatAdd']);
});
