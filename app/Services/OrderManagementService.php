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
            $order['is_all_delivered'] = count(OrderedDishManagementService::getUnservedDishListByOrder($order->order_id)) === 0;
            $order['total_price'] = OrderedDishManagementService::getTotalPrice($order->order_id);
        }

        return $orders;
    }

    /**
     * 注文の詳細情報取得
     * @param string $orderID 注文ID
     * @return array 注文の詳細情報(ordered_dishes, total_priceを含む)
     * @throws \Exception 注文が存在しない場合
     */
    public static function getOrderInfo(string $orderID)
    {
        $order = Orders::find($orderID);

        // 注文が存在しない場合は例外を投げる
        if ($order === null) {
            throw new \Exception('注文が存在しません');
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

        return $order->order_id;
    }

    /**
     * 注文を完了(支払い、退店)
     * @param string $orderID 注文ID
     * @return \Exception
     */
    public static function checkOutOrder(string $orderID)
    {
        $order = Orders::find($orderID);

        // 注文が存在しない場合は例外を投げる
        if ($order === null) {
            throw new \Exception('注文が存在しません');
        }

        // 未提供料理が存在する場合は強制的に完了させる
        $unservedDishes = OrderedDishManagementService::getUnservedDishListByOrder($orderID);
        foreach ($unservedDishes as $unservedDish) {
            OrderedDishManagementService::deliverOrderedDish($unservedDish['ordered_dish_id']);
        }

        // 注文の状態を「支払い済み」に変更
        $order->is_paid = true;
        $order->is_order_finished = true;

        // 支払い時間をtimestamp記録
        $order->paid_at = now();

        return $order->save();
    }

    /**
     * 注文確定、会計依頼
     * @param string $orderID 注文ID
     * @return void
     * @throws \Exception
     */
    public static function finishOrder(string $orderID)
    {
        $order = Orders::find($orderID);

        // 注文が存在しない場合は例外を投げる
        if ($order === null) {
            throw new \Exception('注文が存在しません');
        }

        // 未提供料理が存在する場合は例外を投げる
        $unservedDishes = OrderedDishManagementService::getUnservedDishListByOrder($orderID);
        if (count($unservedDishes) > 0) {
            throw new \Exception('未提供料理が存在するため、注文を完了できません');
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

    /**
     * 座席に対応した最新の未完了注文を取得
     * @param string $seatID 座席ID
     * @return string|null 最新の未完了注文のID
     */
    public static function getOrderBySeat(string $seatID)
    {
        $order = Orders::where('seat_id', $seatID)
            ->where('is_paid', false)
            ->orderBy('created_at', 'desc')
            ->first();

        return $order === null ? null : $order->order_id;
    }
}