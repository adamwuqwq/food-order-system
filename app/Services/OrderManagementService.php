<?php

namespace App\Services;

use App\Models\Orders;
use App\Models\Seats;
use App\Services\OrderedDishManagementService;
use App\Services\SeatManagementService;

class OrderManagementService
{
    /**
     * 注文の一覧取得
     * @param string|null $restaurantID 店舗ID(指定されている場合は、その店舗の注文のみ取得)
     * @return array 注文一覧
     */
    public static function getOrderList(?string $restaurantID = null)
    {
        // 店舗IDが指定されていない場合は、全ての注文を取得
        if ($restaurantID === null) {
            $orders = Orders::get();
        } else {
            $orders = Orders::where('restaurant_id', $restaurantID)->get();
        }

        // 注文の総額を計算、未提供料理があるかどうかを判定
        foreach ($orders as $order) {
            $order['is_all_delivered'] = count(OrderedDishManagementService::getUnservedDishListByOrder($order->id)) === 0;
            $order['total_price'] = OrderedDishManagementService::getTotalPrice($order->id);
        }

        return $orders;
    }

    /**
     * 注文の詳細情報取得
     * @param string $orderID 注文ID
     * @return array 注文の詳細情報(ordered_dishes, total_priceを含む)
     */
    public static function getOrderInfo(string $orderID)
    {
        $order = Orders::find($orderID);

        // 注文が存在しない場合はnullを返す
        if ($order === null) {
            return null;
        }

        // 注文済み料理の一覧を取得
        $orderedDishes = OrderedDishManagementService::getOrderedDishListByOrder($orderID);
        $order['ordered_items'] = $orderedDishes;

        // 注文済み料理の総額を取得
        $totalPrice = OrderedDishManagementService::getTotalPrice($orderID);
        $order['total_price'] = $totalPrice;

        // 未提供料理があるかどうかを判定
        $unservedDishes = OrderedDishManagementService::getUnservedDishListByOrder($orderID);
        $order['is_all_delivered'] = count($unservedDishes) === 0;

        return $order;
    }

    /**
     * 注文の新規作成(来店)
     * @param string $seatID 座席ID
     * @return string|null 注文ID
     */
    public static function createOrder(string $seatID)
    {
        $seat = Seats::find($seatID);

        // 座席が存在しないまたは座席の状態が「空席」でない場合はnullを返す
        if ($seat === null || !$seat->is_available) {
            return null;
        }

        // 座席の状態を「使用中」に変更
        $seat->is_available = false;
        $seat->save();

        // 注文の新規作成
        $order = new Orders();
        $order->restaurant_id = Seats::find($seatID)->restaurant_id;
        $order->seat_id = $seatID;
        $order->is_order_finished = false;
        $order->is_paid = false;
        $order->save();

        return $order->id;
    }

    /**
     * 注文を完了(支払い、退店)
     * @param string $orderID 注文ID
     * @return bool 注文の完了に成功したかどうか
     */
    public static function checkOutOrder(string $orderID)
    {
        $order = Orders::find($orderID);

        // 注文が存在しない場合はfalseを返す
        if ($order === null) {
            return false;
        }

        // 未提供料理が存在する場合はfalseを返す
        $unservedDishes = OrderedDishManagementService::getUnservedDishListByOrder($orderID);
        if (count($unservedDishes) > 0) {
            return false;
        }

        // 注文の状態を「支払い済み」に変更
        $order->is_paid = true;
        $order->is_order_finished = true;

        return $order->save();
    }

    /**
     * 注文確定、会計依頼
     * @param string $orderID 注文ID
     * @return bool 注文確定、会計依頼に成功したかどうか
     */
    public static function finishOrder(string $orderID)
    {
        $order = Orders::find($orderID);

        // 注文が存在しない場合はnullを返す
        if ($order === null) {
            return false;
        }

        // 未提供料理が存在する場合はfalseを返す
        $unservedDishes = OrderedDishManagementService::getUnservedDishListByOrder($orderID);
        if (count($unservedDishes) > 0) {
            return false;
        }

        // 注文の状態を「会計依頼」に変更
        $order->is_order_finished = true;

        return $order->save();
    }

    /**
     * 注文が存在するかを判定
     * @param string $orderID 注文ID
     * @return bool 注文が存在するか
     */
    public static function isExist(string $orderID)
    {
        return Orders::find($orderID) !== null;
    }
}
