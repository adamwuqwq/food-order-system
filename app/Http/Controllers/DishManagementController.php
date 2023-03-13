<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use App\Services\RestaurantManagementService;
use App\Services\DishManagementService;

class DishManagementController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * メニューに料理を追加(新規作成)
     * @param string $restaurantId 料理を追加したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function dishCreate(Request $request, string $restaurantId)
    {
        // 指定した店舗存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // リクエストボディのバリデーション (400エラーを返す)
        try {
            $request->validate([
                'dish_name' => 'required|string',
                'dish_category' => 'string',
                'dish_price' => 'required|integer',
                'available_num' => 'required|integer',
                'image_url' => 'string',
                'dish_description' => 'string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        // リクエストボディから料理情報を取得
        $dishInfo = json_decode($request->getContent(), true);

        // 料理を追加 (追加に失敗した場合は、500エラーを返す)
        try {
            $dishId = DishManagementService::createDish($dishInfo, $restaurantId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['dish_id' => $dishId], 200);
    }
    
    /**
     * 料理メニューの一覧取得
     * @param string $restaurantId メニューの一覧を取得したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function dishList($restaurantId)
    {
        // 指定した店舗存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        return response()->json(DishManagementService::getDishList($restaurantId), 200);
    }

    /**
     * Operation dishDelete
     * @param string $dishId 削除したい料理のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function dishDelete(string $dishId)
    {
        // 指定した料理存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!DishManagementService::isExist($dishId)) {
            return response()->json(['error' => '指定した料理は存在しません'], 400);
        }

        // 料理を削除 (削除に失敗した場合は、500エラーを返す)
        try {
            DishManagementService::deleteDish($dishId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '料理を削除しました'], 200);
    }

    /**
     * 指定した料理情報取得
     * @param string $dishId 情報取得したい料理のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function dishGet($dishId)
    {
        // 指定した料理存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!DishManagementService::isExist($dishId)) {
            return response()->json(['error' => '指定した料理は存在しません'], 400);
        }

        return response()->json(DishManagementService::getDishInfo($dishId), 200);
    }

    /**
     * 料理情報編集
     * @param Request $request リクエストボディ (required)
     * @param string $dishId 編集したい料理のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function dishModify(Request $request, string $dishId)
    {
        // 指定した料理存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!DishManagementService::isExist($dishId)) {
            return response()->json(['error' => '指定した料理は存在しません'], 400);
        }

        // リクエストボディのバリデーション (400エラーを返す)
        try {
            $request->validate([
                'dish_name' => 'string',
                'dish_category' => 'string',
                'dish_price' => 'integer',
                'available_num' => 'integer',
                'image_url' => 'string',
                'dish_description' => 'string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        // リクエストボディから料理情報を取得
        $dishInfo = json_decode($request->getContent(), true);

        // 料理を編集 (編集に失敗した場合は、500エラーを返す)
        try {
            DishManagementService::editDish($dishId, $dishInfo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '料理の情報を変更しました'], 200);
    }
}
