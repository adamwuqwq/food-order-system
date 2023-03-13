<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;

use App\Services\RestaurantManagementService;

class RestaurantManagementController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * 店舗の一覧取得 (owner, counter, kitchenは自分の店舗のみ取得可能).
     * @return \Illuminate\Http\JSONResponse
     */
    public function restaurantList()
    {
        $restaurants = RestaurantManagementService::getRestaurantList();
        // 店舗毎のowner_admin_idを取得し、レスポンスに追加
        foreach ($restaurants as $restaurant) {
            $owner = RestaurantManagementService::getOwner($restaurant->restaurant_id);
            if ($owner !== null) {
                $restaurant['owner_admin_id'] = $owner->admin_id;
            } else {
                $restaurant['owner_admin_id'] = null;
            }
        }

        return response()->json($restaurants, 200);
    }

    /**
     * 新規店舗登録
     * @param Request $request リクエストボディ
     * @return \Illuminate\Http\JSONResponse
     */
    public function restaurantSignUp(Request $request)
    {
        // リクエストボディのバリデーション (400エラーを返す)
        try {
            $request->validate([
                'restaurant_name' => 'required|string|unique:restaurants,restaurant_name',
                'owner_admin_id' => 'integer',
                'restaurant_address' => 'string',
                'restaurant_image_url' => 'string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        $restaurantInfo = json_decode($request->getContent(), true);

        // 店舗を作成 (作成に失敗した場合は、500エラーを返す)
        try {
            $restaurantId = RestaurantManagementService::createRestaurant($restaurantInfo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['restaurant_id' => $restaurantId], 200);
    }

    /**
     * 店舗の削除
     * @param string $restaurantId 削除したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function restaurantDelete(string $restaurantId)
    {
        // 指定した店舗存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // 店舗を削除 (削除に失敗した場合は、500エラーを返す)
        try {
            RestaurantManagementService::deleteRestaurant($restaurantId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '店舗を削除しました'], 200);
    }

    /**
     * 指定した店舗情報取得
     * @param string $restaurantId 情報取得したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function restaurantGet(string $restaurantId)
    {
        // 指定した店舗存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // 店舗情報を取得 (取得に失敗した場合は、500エラーを返す)
        $restaurant = RestaurantManagementService::getRestaurantInfo($restaurantId);
        if ($restaurant) {
            // owner_admin_idを取得し、レスポンスに追加
            $owner = RestaurantManagementService::getOwner($restaurant->restaurant_id);
            if ($owner !== null) {
                $restaurant['owner_admin_id'] = $owner->admin_id;
            } else {
                $restaurant['owner_admin_id'] = null;
            }

            return response()->json($restaurant, 200);
        } else {
            return response()->json(['error' => '店舗情報の取得に失敗しました'], 500);
        }
    }

    /**
     * 店舗情報編集
     * @param Request $request リクエストボディ
     * @param string $restaurantId 情報編集したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function restaurantModify(Request $request, string $restaurantId)
    {
        // リクエストボディのバリデーション (400エラーを返す)
        try {
            $request->validate([
                'restaurant_name' => 'string',
                'owner_admin_id' => 'integer',
                'restaurant_address' => 'string',
                'restaurant_image_url' => 'string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        $restaurantInfo = json_decode($request->getContent(), true);

        // 指定した店舗存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // 店舗情報を編集 (編集に失敗した場合は、500エラーを返す)
        try {
            RestaurantManagementService::editRestaurant($restaurantId, $restaurantInfo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '店舗情報を変更しました'], 200);
    }
}