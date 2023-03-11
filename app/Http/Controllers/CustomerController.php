<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Dishes;
use App\Services\DishManagementService;
use App\Services\OrderManagementService;
use App\Services\OrderedDishManagementService;

class CustomerController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * メニューの取得
     * @param Request $request リクエスト
     * @return \Illuminate\Http\JSONResponse
     */
    public function customerMenuGet(Request $request)
    {
        $menu = DishManagementService::getDishList($request->restaurant_id);
        return response()->json($menu, 200);
    }

    /**
     * 料理の詳細を取得
     * @param Request $request リクエスト
     * @param string $dishId 料理のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function customerMenuDishGet(Request $request, string $dishId)
    {
        // 店舗IDを取得
        $restaurantID = $request->restaurant_id;

        // 料理が指定したレストランのものか確認(存在しない場合は、400エラーを返す)
        if (!Dishes::where('restaurant_id', $restaurantID)->where('dish_id', $dishId)->exists()) {
            return response()->json(['error' => '当店では、指定した料理が存在しません'], 400);
        }

        // 料理の詳細を取得
        $dish = DishManagementService::getDishInfo($dishId);
        return response()->json($dish, 200);
    }

    /**
     * 注文履歴、合計金額の取得
     * @param Request $request リクエスト
     * @return \Illuminate\Http\JSONResponse
     */
    public function customerOrderGet(Request $request)
    {
        // 座席に対応した未会計かつ最新の注文を取得
        $orderID = OrderManagementService::getOrderBySeat($request->seat_id);

        // 注文が存在しない場合は、400エラーを返す
        if ($orderID == null) {
            return response()->json(['error' => '注文履歴が存在しません'], 400);
        }

        // 注文履歴の取得
        try {
            $orderHistory = OrderManagementService::getOrderInfo($orderID);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json($orderHistory, 200);
    }

    /**
     * 注文、追加注文送信
     * @param Request $request リクエスト
     * @return \Illuminate\Http\JSONResponse
     */
    public function customerOrderPost(Request $request)
    {
        // リクエストボディのバリデーション
        try {
            $this->validate($request, [
                'seat_id' => 'required|integer',
                'ordered_dishes' => 'required|array',
                'ordered_dishes.*.dish_id' => 'required|integer',
                'ordered_dishes.*.quantity' => 'required|integer|min:1',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'リクエストボディの形式が不正です'], 400);
        }

        // 座席を取得
        $seatID = $request->seat_id;

        try {
            // 座席に対応した未会計かつ最新の注文を取得
            $orderID = OrderManagementService::getOrderBySeat($seatID);

            // 注文が存在しない場合は、新規に注文を作成
            if ($orderID == null) {
                $orderID = OrderManagementService::createOrder($seatID);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // 注文が既に会計済みまたは締切の場合は、400エラーを返す
        if (Orders::find($orderID)->is_paid || Orders::find($orderID)->is_order_finished) {
            return response()->json(['error' => '注文は会計済みまたは締切済みです'], 400);
        }

        // 注文に対する料理の追加
        try {
            foreach ($request->ordered_dishes as $ordered_dish) {
                // 料理が指定したレストランのものか確認(存在しない場合は、400エラーを返す)
                if (!Dishes::where('restaurant_id', $request->restaurant_id)->where('dish_id', $ordered_dish['dish_id'])->exists()) {
                    return response()->json(['error' => '当店では、指定した料理が存在しません'], 400);
                }

                // 在庫数のチェックと更新
                $dishID = $ordered_dish['dish_id'];
                $dish = Dishes::find($dishID);
                $updatedAvailableNum = $dish->available_num - $ordered_dish['quantity'];
                if ($updatedAvailableNum < 0) {
                    return response()->json(['error' => $dish->dish_name . 'の在庫が不足しています'], 400);
                }
                DishManagementService::editDish($dishID, ['available_num' => $updatedAvailableNum]);

                // 注文に対する料理の追加
                OrderedDishManagementService::createOrderedDish($orderID ?? '', $ordered_dish['dish_id'], $ordered_dish['quantity']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '注文が完了しました'], 200);
    }

    /**
     * 注文確定・会計依頼
     * @param Request $request リクエスト
     * @return \Illuminate\Http\JSONResponse
     */
    public function customerOrderFinish(Request $request)
    {
        // 座席に対応した未会計かつ最新の注文を取得
        $orderID = OrderManagementService::getOrderBySeat($request->seat_id);

        // 注文が存在しない場合は、400エラーを返す
        if ($orderID == null) {
            return response()->json(['error' => '注文履歴が存在しません'], 400);
        }

        // 注文確定
        try {
            OrderManagementService::finishOrder($orderID);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '注文が確定しました'], 200);
    }
}