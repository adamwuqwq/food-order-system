<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use App\Models\Seats;
use App\Models\Orders;
use App\Services\RestaurantManagementService;
use App\Services\SeatManagementService;
use App\Services\OrderManagementService;
use App\Services\OrderedDishManagementService;

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
        // 注文した料理が存在するか確認(存在しない場合は、400エラーを返す)
        if (!OrderedDishManagementService::isExist($orderedDishId)) {
            return response()->json(['error' => '指定した料理は存在しません'], 400);
        }

        // 注文した料理のキャンセル
        try {
            OrderedDishManagementService::cancelOrderedDish($orderedDishId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '注文した料理をキャンセルしました'], 200);
    }

    /**
     * 注文ステータスを提供済みにする
     * @param string $orderedDishId 注文した料理の注文ID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function orderedDishDelivery(string $orderedDishId)
    {
        // 注文した料理が存在するか確認(存在しない場合は、400エラーを返す)
        if (!OrderedDishManagementService::isExist($orderedDishId)) {
            return response()->json(['error' => '指定した料理は存在しません'], 400);
        }

        // 注文した料理のステータスを提供済みにする
        try {
            OrderedDishManagementService::deliverOrderedDish($orderedDishId);
        } catch (\Exception $e) {
            return response()->json(['error' => '注文した料理のステータスの更新に失敗しました'], 500);
        }

        return response()->json(['message' => '注文した料理のステータスを提供済みにしました'], 200);
    }

    /**
     * 指定した店舗の注文一覧の取得
     * @param string $restaurantId 注文一覧を取得したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function orderList(string $restaurantId)
    {
        // 指定した店舗が存在するか確認(存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // 注文一覧の取得
        $orderList = OrderManagementService::getOrderList($restaurantId);

        return response()->json($orderList, 200);
    }

    /**
     * 指定した店舗の未提供料理一覧を取得 (注文時間順)
     * @param string $restaurantId 未提供料理一覧を取得したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function unservedDishList(string $restaurantId)
    {
        // 指定した店舗が存在するか確認(存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // 未提供料理一覧の取得
        try {
            $unservedDishList = OrderedDishManagementService::getUnservedDishListByRestaurant($restaurantId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json($unservedDishList, 200);
    }

    /**
     * 注文詳細の取得
     * @param string $orderId 注文詳細を取得したい注文のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function orderGet(string $orderId)
    {
        // 指定した注文が存在するか確認(存在しない場合は、400エラーを返す)
        if (!OrderManagementService::isExist($orderId)) {
            return response()->json(['error' => '指定した注文は存在しません'], 400);
        }

        // 注文詳細の取得
        $order = OrderManagementService::getOrderInfo($orderId);

        return response()->json($order, 200);
    }

    /**
     * (会計済みボタン) 注文を完了する
     * @param string $orderId 注文のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function orderComplete(string $orderId)
    {
        // 指定した注文が存在するか確認(存在しない場合は、400エラーを返す)
        if (!OrderManagementService::isExist($orderId)) {
            return response()->json(['error' => '指定した注文は存在しません'], 400);
        }

        // 注文を完了する
        try {
            // 注文のステータスを「完了」に変更
            OrderManagementService::checkoutOrder($orderId);

            // 座席の状態を「空席」に変更
            $seatId = Orders::find($orderId)->seat_id;
            $seat = Seats::find($seatId);
            $seat->is_available = true;
            $seat->save();

            // QRコードトークンの再発行
            SeatManagementService::updateQrCodeToken($seatId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '注文を完了しました'], 200);
    }
}