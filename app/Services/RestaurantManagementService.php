<?php

namespace App\Services;

use App\Models\Restaurants;
use App\Models\Admins;
use App\Models\AdminRestaurantRelationships;
use App\Services\RelationshipManagementService;
use Illuminate\Database\Eloquent\Collection;

class RestaurantManagementService
{
    /**
     * 店舗一覧取得
     * @param string|null $adminId 管理者ID(指定されている場合は、その管理者が所属している店舗のみ取得)
     * @return Collection 店舗一覧
     */
    public static function getRestaurantList(?string $adminId = null)
    {
        if ($adminId === null) {
            return Restaurants::get();
        } else {
            return Restaurants::where('admin_id', $adminId)->get();
        }
    }

    /**
     * 店舗の詳細情報取得
     * @param string $restaurantId 店舗ID
     * @return Restaurants 店舗情報
     */
    public static function getRestaurantInfo(string $restaurantId)
    {
        return Restaurants::find($restaurantId);
    }

    /**
     * 店舗のオーナー情報取得
     * @param string $restaurantId 店舗ID
     * @return Admins オーナーの情報
     */
    public static function getOwner(string $restaurantId)
    {
        return AdminRestaurantRelationships::where('restaurant_id', $restaurantId)->admins()->first();
    }

    /**
     * 店舗の新規作成
     * @param array $restaurantData 店舗情報
     * @return string 作成された店舗のID
     * @throws Exception 作成に失敗した
     */
    public static function createRestaurant(array $restaurantData)
    {
        $restaurant = new Restaurants();

        // 店舗のデータを作成
        $restaurant->restaurant_name = $restaurantData['restaurant_name'];
        if (array_key_exists('restaurant_address', $restaurantData)) {
            $restaurant->restaurant_address = $restaurantData['restaurant_address'];
        }
        if (array_key_exists('restaurant_image_url', $restaurantData)) {
            $restaurant->restaurant_image_url = $restaurantData['restaurant_image_url'];
        }

        $restaurant->save();

        // オーナーが指定されている場合は、オーナーと店舗のリレイションを作成
        if (array_key_exists('owner_admin_id', $restaurantData)) {
            // オーナー権限がないユーザーが指定された場合は、例外を投げる
            if (Admins::find($restaurantData['owner_admin_id'])->admin_role !== 'owner') {
                throw new Exception('オーナー権限がないユーザーが指定されました');
            }

            RelationshipManagementService::createRelationship(Admins::find($restaurantData['owner_admin_id']), $restaurant->id);
        }

        return $restaurant->id;
    }

    /**
     * 店舗情報の編集
     * @param string $restaurantId 店舗ID
     * @param array $restaurantData 店舗情報
     * @return bool false:失敗 true:成功
     */
    public static function editRestaurant(string $restaurantId, array $restaurantData)
    {
        $restaurant = Restaurants::find($restaurantId);

        // 店舗のデータを更新
        if (array_key_exists('restaurant_name', $restaurantData)) {
            $restaurant->restaurant_name = $restaurantData['restaurant_name'];
        }
        if (array_key_exists('restaurant_address', $restaurantData)) {
            $restaurant->restaurant_address = $restaurantData['restaurant_address'];
        }
        if (array_key_exists('restaurant_image_url', $restaurantData)) {
            $restaurant->restaurant_image_url = $restaurantData['restaurant_image_url'];
        }

        // オーナーと店舗のリレイションを更新
        try {
            if (array_key_exists('owner_admin_id', $restaurantData)) {
                $newOwner = Admins::find($restaurantData['owner_admin_id']);
                $oldOwner = self::getOwner($restaurantId);

                // オーナー権限がないユーザーが指定された場合は、例外を投げる
                if ($newOwner->admin_role !== 'owner') {
                    throw new Exception('オーナー権限がないユーザーが指定されました');
                }

                // 元のオーナーの権限を削除
                if ($oldOwner != null) {
                    RelationshipManagementService::deleteRelationship($oldOwner, $restaurant);
                }

                // 新しいオーナーの権限を追加
                RelationshipManagementService::createRelationship($newOwner, array($restaurant->id));
            }
        } catch (Exception $e) {
            return false;
        }

        return $restaurant->save();
    }

    /**
     * 店舗の削除
     * @param string $restaurantId 店舗ID
     * @return bool false:失敗 true:成功
     */
    public static function deleteRestaurant(string $restaurantId)
    {
        $restaurant = Restaurants::find($restaurantId);

        // オーナーと店舗のリレイションを削除
        $owner = self::getOwner($restaurantId);
        if ($owner != null) {
            RelationshipManagementService::deleteRelationship($owner, $restaurant);
        }

        // 店舗のデータを削除
        return $restaurant->delete();
    }
}