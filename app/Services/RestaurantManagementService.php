<?php

namespace App\Services;

use App\Models\Restaurants;
use App\Models\Admins;
use App\Models\Dishes;
use App\Models\Seats;
use App\Models\Orders;
use App\Models\OrderedDishes;
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
     * @return Admins|null オーナーの情報
     */
    public static function getOwner(string $restaurantId)
    {
        $owner = AdminRestaurantRelationships::where('restaurant_id', $restaurantId)->where('admin_role', "owner")->first();

        if ($owner !== null) {
            return Admins::find($owner->admin_id);
        }
        return null;
    }

    /**
     * 店舗の新規作成
     * @param array $restaurantData 店舗情報
     * @return string 作成された店舗のID
     * @throws \Exception 作成に失敗した
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
            $admin = Admins::find($restaurantData['owner_admin_id']);

            // 例外を投げる
            if ($admin == null) {
                Restaurants::destroy($restaurant->restaurant_id);
                throw new \Exception('存在しないユーザーが指定されました');
            }
            if ($admin->admin_role !== 'owner') {
                throw new \Exception('オーナー権限がないユーザーが指定されました');
            }

            RelationshipManagementService::createRelationship(Admins::find($restaurantData['owner_admin_id']), array($restaurant->restaurant_id));
        }

        return $restaurant->restaurant_id;
    }

    /**
     * 店舗情報の編集
     * @param string $restaurantId 店舗ID
     * @param array $restaurantData 店舗情報
     * @return void
     * @throws \Exception 編集に失敗した
     */
    public static function editRestaurant(string $restaurantId, array $restaurantData)
    {
        $restaurant = Restaurants::find($restaurantId);

        // 店舗のデータを更新
        $restaurant->restaurant_name = $restaurantData['restaurant_name'] ?? $restaurant->restaurant_name;
        $restaurant->restaurant_address = $restaurantData['restaurant_address'] ?? $restaurant->restaurant_address;
        $restaurant->restaurant_image_url = $restaurantData['restaurant_image_url'] ?? $restaurant->restaurant_image_url;

        $isSaved = $restaurant->save();

        // オーナーと店舗のリレイションを更新
        if (array_key_exists('owner_admin_id', $restaurantData)) {
            $newOwner = Admins::find($restaurantData['owner_admin_id']);
            $oldOwner = self::getOwner($restaurantId);

            // 例外を投げる
            if ($newOwner == null) {
                throw new \Exception('存在しないユーザーが指定されました');
            }
            if ($newOwner->admin_role !== 'owner') {
                throw new \Exception('オーナー権限がないユーザーが指定されました');
            }

            // 元のオーナーの権限を削除
            if ($oldOwner != null) {
                RelationshipManagementService::deleteRelationship($oldOwner, $restaurant);
            }

            // 新しいオーナーの権限を追加
            RelationshipManagementService::createRelationship($newOwner, array($restaurant->restaurant_id));
        }
        return $isSaved;
    }

    /**
     * 店舗の削除
     * @param string $restaurantId 店舗ID
     * @return void
     * @throws \Exception 削除に失敗した
     */
    public static function deleteRestaurant(string $restaurantId)
    {
        $restaurant = Restaurants::find($restaurantId);
        $owner = self::getOwner($restaurantId);

        // オーナーと店舗のリレイションを削除
        if ($owner != null) {
            RelationshipManagementService::deleteRelationship($owner, $restaurant);
        }

        // 料理のデータを削除
        Dishes::where('restaurant_id', $restaurantId)->delete();

        // 座席のデータを削除
        Seats::where('restaurant_id', $restaurantId)->delete();

        // 注文のデータを削除
        Orders::where('restaurant_id', $restaurantId)->delete();

        // 注文料理のデータを削除
        OrderedDishes::where('restaurant_id', $restaurantId)->delete();

        $restaurant->delete();
    }

    /**
     * 店舗が存在するかをチェック
     * @param string $restaurantId 店舗ID
     * @return bool false:存在しない true:存在する
     */
    public static function isExist(string $restaurantId)
    {
        return Restaurants::find($restaurantId) != null;
    }
}