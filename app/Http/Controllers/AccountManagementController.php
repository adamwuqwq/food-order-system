<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use App\Services\AdminAccountManagementService;

class AccountManagementController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * 管理者アカウントの一覧取得 (ownerは自分の店舗に所属しているアカウントのみ取得可能)
     * @return \Illuminate\Http\JSONResponse
     */
    public function adminList()
    {
        // TODO: Autherizationヘッダーを使って管理者ロールを取得 (403エラーを返す)

        try {
            $admins = AdminAccountManagementService::getAdminList();

            // アカウント毎のrestaurant_idとrestaurant_nameを取得し、レスポンスに追加
            foreach ($admins as $admin) {
                $restaurants = AdminAccountManagementService::getRelatedRestaurant($admin->admin_id);
                $admin['restaurant_id'] = array_column($restaurants, 'restaurant_id');
                $admin['restaurant_name'] = array_column($restaurants, 'restaurant_name');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json($admins, 200);
    }

    /**
     * 新規管理者アカウント(owner, counter, kitchen)発行
     * @param Request $request リクエストボディ
     * @return \Illuminate\Http\JSONResponse
     */
    public function adminSignUp(Request $request)
    {
        // リクエストボディのバリデーション (400エラーを返す)
        try {
            $request->validate([
                'admin_name' => 'required|string',
                'login_id' => 'required|string|unique:admins,login_id',
                'password' => 'required|string',
                'admin_role' => 'required|string|in:system,owner,counter,kitchen',
                'restaurant_id' => 'array',
                'restaurant_id.*' => 'integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        $adminInfo = json_decode($request->getContent(), true);

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、発行可否を判定 (403エラーを返す)

        // 管理者アカウントを作成 (作成に失敗した場合は、500エラーを返す)
        try {
            $adminId = AdminAccountManagementService::createAdmin($adminInfo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['admin_id' => $adminId], 200);
    }

    /**
     * 管理者アカウント(owner, counter, kitchen)削除
     * @param string $adminId 削除したい管理者アカウントのID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function adminDelete(string $adminId)
    {
        // 指定した管理者存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!AdminAccountManagementService::isExist($adminId)) {
            return response()->json(['error' => '指定した管理者は存在しません'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、削除可否を判定 (403エラーを返す)

        // 管理者アカウントを削除 (削除に失敗した場合は、500エラーを返す)
        try {
            AdminAccountManagementService::deleteAdmin($adminId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '管理者アカウントを削除しました'], 200);
    }

    /**
     * 管理者アカウント情報取得
     * @param string $adminId 情報取得したい管理者アカウントのID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function adminGet(string $adminId)
    {
        // 指定した管理者存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!AdminAccountManagementService::isExist($adminId)) {
            return response()->json(['error' => '指定した管理者は存在しません'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、取得可否を判定 (403エラーを返す)

        // 管理者アカウントを取得 (取得に失敗した場合は、500エラーを返す)
        $admin = AdminAccountManagementService::getAdminInfo($adminId);
        if ($admin) {
            // アカウント毎のrestaurant_idとrestaurant_nameを取得し、レスポンスに追加
            $restaurants = AdminAccountManagementService::getRelatedRestaurant($admin->admin_id);
            $admin['restaurant_id'] = array_column($restaurants, 'restaurant_id');
            $admin['restaurant_name'] = array_column($restaurants, 'restaurant_name');

            return response()->json($admin, 200);
        } else {
            return response()->json(['error' => '管理者アカウントの取得に失敗しました'], 500);
        }
    }

    /**
     * 管理者アカウント(owner, counter, kitchen)情報編集
     * @param string $adminId 編集したい管理者アカウントのID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function adminModify(Request $request, string $adminId)
    {
        // リクエストボディのバリデーション (400エラーを返す)
        try {
            $request->validate([
                'admin_name' => 'string',
                'login_id' => 'string|unique:admins,login_id',
                'password' => 'string',
                'restaurant_id' => 'array',
                'restaurant_id.*' => 'integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        $adminInfo = json_decode($request->getContent(), true);

        // 指定した管理者存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!AdminAccountManagementService::isExist($adminId)) {
            return response()->json(['error' => '指定した管理者は存在しません'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、編集可否を判定 (403エラーを返す)

        // 管理者アカウントを編集 (編集に失敗した場合は、500エラーを返す)
        try {
            AdminAccountManagementService::editAdmin($adminId, $adminInfo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '管理者アカウントの情報を変更しました'], 200);
    }
}