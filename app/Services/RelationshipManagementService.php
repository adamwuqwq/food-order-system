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
     * @praram adminId 管理者アカウントID
     * @praram restaurantId 店舗IDの配列
     */
    public static function createRelationship(Admins $admin, array $restaurantId)
    {
        if (($admin->admin_role === 'kitchen' || $admin->admin_rold === 'counter') && count($restaurantId) > 1) {
            throw new Exception('キッチンとカウンターアカウントは、一つの店舗にしか所属できません。');
        }

        foreach ($restaurantId as $id) {
            // もし既にリレーションがある場合は、作成しない
            if (!AdminRestaurantRelationships::where('admin_id', $admin->admin_id)->where('restaurant_id', $id)->exists()) {
                $relationship = new AdminRestaurantRelationships();
                $relationship->admin_id = $admin->admin_id;
                $relationship->restaurant_id = $id;
                $relationship->save();
            }
        }
    }

    /**
     * 店舗と管理者のリレーション編集
     * @praram adminId 管理者アカウントID
     * @praram restaurantId 店舗IDの配列
     */
    public static function editRelationship(Admins $admin, array $restaurantId)
    {
        if (($admin->admin_role === 'kitchen' || $admin->admin_rold === 'counter') && count($restaurantId) > 1) {
            throw new Exception('キッチンとカウンターアカウントは、一つの店舗にしか所属できません。');
        }

        // 既存のリレーションを削除
        RelationshipManagementService::deleteRelationshipByAdmin($admin);

        // 新しいリレーションを作成
        RelationshipManagementService::createRelationship($admin, $restaurantId);
    }

    /**
     * 店舗と管理者のリレーション削除 (by adminId)
     * @praram adminId 管理者アカウントID
     */
    public static function deleteRelationshipByAdmin(Admins $admin)
    {
        // もしりレーションが存在しない場合は、削除しない
        if (!AdminRestaurantRelationships::where('admin_id', $admin->admin_id)->exists()) {
            return;
        }

        if ($admin->adminRestaurantRelationships()->delete() === 0) {
            throw new Exception('リレーションの削除に失敗しました。');
        }
    }

    /**
     * 店舗と管理者のリレーション削除 (by restaurantId)
     * @praram restaurantId 店舗ID
     */
    public static function deleteRelationshipByRestaurant(Restaurants $restaurant)
    {
        // もしりレーションが存在しない場合は、削除しない
        if (!AdminRestaurantRelationships::where('restaurant_id', $restaurant->restaurant_id)->exists()) {
            return;
        }
        
        if ($restaurant->restaurantAdminRelationships()->delete() === 0) {
            throw new Exception('リレーションの削除に失敗しました。');
        }
    }
}