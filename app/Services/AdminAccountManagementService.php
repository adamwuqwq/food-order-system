<?php

namespace App\Services;

use App\Models\Admins;
use App\Models\AdminRestaurantRelationships;
use App\Services\RelationshipManagementService;
use Illuminate\Database\Eloquent\Collection;

class AdminAccountManagementService
{
    // 全体TODO：エラーハンドリング

    /**
     * パスワードのハッシュ化
     * @param string $password パスワード
     * @return string ハッシュ化されたパスワード
     */
    private static function hashPassword(string $password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 管理者アカウントの一覧取得
     * @param string|null $restaurantID 店舗ID(指定されている場合は、その店舗に所属しているアカウントのみ取得)
     * @return Collection 管理者アカウント一覧
     */
    public static function getAdminList(?string $restaurantID = null)
    {
        if ($restaurantID === null) {
            return Admins::get();
        } else {
            return Admins::where('restaurant_id', $restaurantID)->get();
        }
    }

    /**
     * 管理者アカウントの情報取得
     * @param string $adminId 管理者アカウントID
     * @return Admins 管理者アカウント情報
     */
    public static function getAdminInfo(string $adminId)
    {
        return Admins::find($adminId);
    }

    /**
     * 管理者に関連する店舗情報取得
     * @param string $adminId 管理者アカウントID
     * @return array 関連した店舗の情報(複数可)
     */
    public static function getRelatedRestaurant(string $adminId)
    {
        return AdminRestaurantRelationships::find($adminId)->restaurants()->get();
    }

    /**
     * 管理者アカウントの新規作成
     * @param array $adminData 管理者アカウント情報
     * @return string 新規作成した管理者アカウントのID
     * @throws Exception 作成に失敗した
     */
    public static function createAdmin(array $adminData)
    {
        // アカウント作成
        $admin = new Admins();
        $admin->admin_name = $adminData['admin_name'];
        $admin->login_id = $adminData['login_id'];
        $admin->hashed_password = self::hashPassword($adminData['password']);
        $admin->admin_role = $adminData['admin_role'];
        $admin->save();

        // 店舗とのリレーション作成
        if (array_key_exists('restaurant_id', $adminData)) {
            RelationshipManagementService::createRelationship($admin, $adminData['restaurant_id']);
        }

        return $admin->admin_id;
    }

    /**
     * 管理者アカウントの情報更新
     * @param string $adminId 管理者アカウントID
     * @param array $adminData 管理者アカウント情報
     * @return bool false:失敗 true:成功
     */
    public static function editAdmin(string $adminId, array $adminData)
    {
        $admin = Admins::find($adminId);

        // もしadminIdに対応したアカウント存在しない場合は、falseを返す
        if ($admin === null) {
            return false;
        }

        // 管理者アカウントの情報を更新
        if (array_key_exists('admin_name', $adminData)) {
            $admin->admin_name = $adminData['admin_name'];
        }
        if (array_key_exists('login_id', $adminData)) {
            $admin->login_id = $adminData['login_id'];
        }
        if (array_key_exists('password', $adminData)) {
            $admin->hashed_password = self::hashPassword($adminData['password']);
        }

        // 店舗とのリレーションを更新
        try {
            if (array_key_exists('restaurant_id', $adminData)) {
                RelationshipManagementService::deleteRelationship($admin);
                RelationshipManagementService::createRelationship($admin, $adminData['restaurant_id']);
            }
        }
        catch (\Exception $e) {
            return false;
        }

        return $admin->save();
    }

    /**
     * 管理者アカウントの削除
     * @param string $adminId 管理者アカウントID
     * @return bool false:失敗 true:成功
     */
    public static function deleteAdmin(string $adminId)
    {
        $admin = Admins::find($adminId);

        // もしadminIdに対応したアカウント存在しない場合は、falseを返す
        if ($admin === null) {
            return false;
        }

        // 店舗とのリレーションを削除
        try {
            RelationshipManagementService::deleteRelationship($admin);
        } catch (\Exception $e) {
            return false;
        }

        // アカウントを削除、成功したらtrueを返す
        return $admin->delete();
    }

    /**
     * 管理者アカウント存在するか確認
     * @param string|null $adminId 管理者アカウントID
     * @param string|null $loginId ログインID
     * @return bool false:存在しない true:存在する
     */
    public static function isExist(?string $adminId = null, ?string $loginId = null)
    {
        $query = Admins::where('admin_id', $adminId);
    
        if ($adminId != null) {
            $query->where('admin_id', $adminId);
        }
        if ($loginId !== null) {
            $query->where('login_id', $loginId);
        }
    
        return $query->exists();
    }
}