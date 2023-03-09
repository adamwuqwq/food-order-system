<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use App\Services\AdminAccountManagementService;
use App\Services\RestaurantManagementService;
use App\Services\MenuManagementService;
use App\Services\DishManagementService;
use App\Services\OrderManagementService;

class OrderManagementController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * 注文した(+未提供)料理のキャンセル
     * @param string $orderedDishId キャンセルしたい料理の注文ID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function orderedDishCancel(string $orderedDishId)
    {
    }

    /**
     * 注文ステータスを提供済みにする
     * @param string $orderedDishId 注文した料理の注文ID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function orderedDishDelivery(string $orderedDishId)
    {
    }

    /**
     * 指定した店舗の注文一覧の取得
     * @param string $restaurantId 注文一覧を取得したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function orderList(string $restaurantId)
    {
    }

    /**
     * 指定した店舗の未提供料理一覧を取得 (注文時間順)
     * @param string $restaurantId 未提供料理一覧を取得したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function unservedDishList(string $restaurantId)
    {
    }

    /**
     * 注文詳細の取得
     * @param string $orderId 注文詳細を取得したい注文のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function orderGet(string $orderId)
    {
    }

    /**
     * (会計済みボタン) 注文を完了する
     * @param string $orderId 注文のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function orderPut(string $orderId)
    {
    }
}
