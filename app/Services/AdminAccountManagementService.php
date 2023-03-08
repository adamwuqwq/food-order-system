<?php

namespace App\Services;

use App\Models\Admins;
use App\Services\RelationshipManagementService;
use Illuminate\Database\Eloquent\Collection;

class AdminAccountManagementService
{
    // 全体TODO：エラーハンドリング

    /**
     * パスワードのハッシュ化
     * @param string パスワード
     * @return string ハッシュ化されたパスワード
     */
    private static function hashPassword(string $password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 管理者アカウントの一覧取得
     * @param string 店舗ID(指定されている場合は、その店舗に所属しているアカウントのみ取得)
     * @return Collection 管理者アカウント一覧
     */
    public static function getAdminList(string $restaurantID = null)
    {
        if ($restaurantID === null) {
            return Admins::get();
        } else {
            return Admins::where('restaurant_id', $restaurantID)->get();
        }
    }

    /**
     * 管理者アカウントの情報取得
     * @param string 管理者アカウントID
     * @return Admins 管理者アカウント情報
     */
    public static function getAdminInfo(string $adminId)
    {
        return Admins::find($adminId);
    }

    /**
     * 管理者に関連する店舗情報取得
     * @param string 管理者アカウントID
     * @return \App\Models\AdminRestaurantRelationships 管理者と店舗のリレイション
     */
    public static function getRelatedRestaurant(string $adminId)
    {
        $admin = Admins::find($adminId);
        return $admin->adminRestaurantRelationships;
    }

    /**
     * 管理者アカウントの新規作成
     * @param array 管理者アカウント情報
     * @return string 新規作成した管理者アカウントのID
     */
    public static function createAdmin($adminData)
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
     * 管理者アカウントの情報編集
     * @param string 管理者アカウントID
     * @param array 管理者アカウント情報
     * @return bool false:失敗 true:成功
     */
    public static function editAdmin($adminId, $adminData)
    {
        $admin = Admins::find($adminId);

        // もしadminIdに対応したアカウント存在しない場合は、falseを返す
        if ($admin === null) {
            return false;
        }

        // 管理者アカウントの情報を編集
        if (array_key_exists('admin_name', $adminData)) {
            $admin->admin_name = $adminData['admin_name'];
        }
        if (array_key_exists('login_id', $adminData)) {
            $admin->login_id = $adminData['login_id'];
        }
        if (array_key_exists('password', $adminData)) {
            $admin->hashed_password = self::hashPassword($adminData['password']);
        }

        // 店舗とのリレーションを編集
        if (array_key_exists('restaurant_id', $adminData)) {
            RelationshipManagementService::editRelationship($admin, $adminData['restaurant_id']);
        }

        return $admin->save();
    }

    /**
     * 管理者アカウントの削除
     * @param string 管理者アカウントID
     * @return bool false:失敗 true:成功
     */
    public static function deleteAdmin($adminId)
    {
        $admin = Admins::find($adminId);

        // もしadminIdに対応したアカウント存在しない場合は、falseを返す
        if ($admin === null) {
            return false;
        }

        // 店舗とのリレーションを削除
        RelationshipManagementService::deleteRelationshipByAdmin($admin);

        // アカウントを削除、成功したらtrueを返す
        return $admin->delete() == 1;
    }

    /**
     * 管理者アカウント存在するか確認
     * @param string 管理者アカウントID
     * @return bool false:存在しない true:存在する
     */
    public static function isExist($adminId)
    {
        return Admins::find($adminId) !== null;
    }

    /**
     * ログインIDが存在するか確認
     * @param string ログインID
     * @return bool false:存在しない true:存在する
     */
    public static function isExistByLoginId($loginId)
    {
        return Admins::where('login_id', $loginId)->first() !== null;
    }
}
