<?php

namespace App\Services;

use App\Models\Dishes;
use App\Models\Restaurants;

class DishManagementService
{
    /**
     * 料理の一覧取得
     * @param string|null $restaurantID 店舗ID(指定されている場合は、その店舗の料理のみ取得)
     * @return array 料理一覧
     */
    public static function getDishList(?string $restaurantID = null)
    {
        // 店舗IDが指定されていない場合は、全ての料理を取得
        if ($restaurantID === null) {
            $dishes = Dishes::all();
        } else {
            $dishes = Dishes::where('restaurant_id', $restaurantID)->get();
        }

        // カテゴリー別に料理を分類
        $dishesByCategory = $dishes->groupBy('dish_category');
        $dishesByCategory = $dishesByCategory->map(function ($categoryDishes, $category) {
            return [
                'category' => $category,
                'dishes' => $categoryDishes->map(function ($dish) {
                    unset($dish['dish_category']);
                    return $dish;
                }
                ),
            ];
        })->values()->toArray();

        return $dishesByCategory;
    }

    /**
     * 料理の詳細情報取得
     * @param string $dishID 料理ID
     * @return Dishes 料理情報
     */
    public static function getDishInfo(string $dishID)
    {
        return Dishes::find($dishID);
    }

    /**
     * 料理の新規作成
     * @param array $dishData 料理情報
     * @return string 作成された料理のID
     * @throws \Exception 作成に失敗した
     */
    public static function createDish(array $dishData, string $restaurantID)
    {
        $dish = new Dishes();
        $dish->restaurant_id = $restaurantID;
        $dish->dish_name = $dishData['dish_name'];
        $dish->dish_category = $dishData['dish_category'] ?? 'unspecified';
        $dish->dish_price = $dishData['dish_price'];
        $dish->dish_description = $dishData['dish_description'] ?? null;
        $dish->image_url = $dishData['image_url'] ?? null;
        $dish->available_num = $dishData['available_num'];

        if (!$dish->save()) {
            throw new \Exception('料理の作成に失敗しました');
        }

        return $dish->dish_id;
    }

    /**
     * 料理の情報更新
     * @param string $dishID 料理ID
     * @param array $dishData 料理情報
     * @return void
     * @throws \Exception 更新に失敗した
     */
    public static function editDish(string $dishID, array $dishData)
    {
        $dish = Dishes::find($dishID);

        // 存在しない料理の場合はfalseを返す
        if ($dish === null) {
            throw new \Exception('存在しない料理です');
        }

        // 料理のデータを更新
        $dish->restaurant_id = $dishData['restaurant_id'] ?? $dish->restaurant_id;
        $dish->dish_name = $dishData['dish_name'] ?? $dish->dish_name;
        $dish->dish_category = $dishData['dish_category'] ?? $dish->dish_category;
        $dish->dish_price = $dishData['dish_price'] ?? $dish->dish_price;
        $dish->dish_description = $dishData['dish_description'] ?? $dish->dish_description;
        $dish->image_url = $dishData['image_url'] ?? $dish->image_url;
        $dish->available_num = $dishData['available_num'] ?? $dish->available_num;

        return $dish->save();
    }

    /**
     * 料理の削除
     * @param string $dishID 料理ID
     * @return void
     * @throws \Exception 削除に失敗した
     */
    public static function deleteDish(string $dishID)
    {
        $dish = Dishes::find($dishID);

        // 存在しない料理の場合はfalseを返す
        if ($dish === null) {
            throw new \Exception('存在しない料理です');
        }

        return $dish->delete();
    }

    /**
     * 料理が存在するかをチェック
     * @param string $dishID 料理ID
     * @return bool 存在するか
     */
    public static function isExist(string $dishID)
    {
        return Dishes::find($dishID) !== null;
    }
}