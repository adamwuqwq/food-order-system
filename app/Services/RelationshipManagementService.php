<?php

namespace App\Services;

use App\Models\AdminRestaurantRelationships;
use App\Models\Admins;
use App\Models\Restaurants;
use Exception;

class RelationshipManagementService
{
    /**
     * 店舗と管理者のリレーション作成
     * @param Admins $admin 管理者アカウントID
     * @param array $restaurantId 店舗IDの配列
     */
    public static function createRelationship(Admins $admin, array $restaurantId)
    {
        if (($admin->admin_role === 'kitchen' || $admin->admin_role === 'counter') && count($restaurantId) > 1) {
            throw new Exception('キッチンとカウンターアカウントは、一つの店舗にしか所属できません');
        }

        foreach ($restaurantId as $id) {
            // もし既にリレーションがある場合は、作成しない
            if (!AdminRestaurantRelationships::where('admin_id', $admin->admin_id)->where('restaurant_id', $id)->exists()) {
                $relationship = new AdminRestaurantRelationships();
                $relationship->admin_id = $admin->admin_id;
                $relationship->restaurant_id = $id;
                $relationship->admin_role = $admin->admin_role;
                $relationship->save();
            }
        }
    }

    /**
     * 店舗と管理者のリレーション削除
     * @param Admins|null $admin 管理者アカウント
     * @param Restaurants|null $restaurant 店舗
     * @throws \Exception
     */
    public static function deleteRelationship(?Admins $admin = null, ?Restaurants $restaurant = null)
    {
        $query = AdminRestaurantRelationships::query();

        if ($admin !== null) {
            $query->where('admin_id', $admin->admin_id);
        }
        if ($restaurant !== null) {
            $query->where('restaurant_id', $restaurant->restaurant_id);
        }

        if (!$query->exists()) {
            return;
        }

        if ($query->delete() === 0) {
            throw new \Exception('リレーションの削除に失敗しました');
        }
    }
}
