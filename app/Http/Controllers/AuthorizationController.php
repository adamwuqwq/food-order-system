<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Validation\ValidationException;
use App\Services\AdminAccountManagementService;
use App\Models\Admins;
use Illuminate\Support\Facades\Hash;

class AuthorizationController extends Controller
{
    /**
     * 管理者(system, owner, counter, kitchen)ログイン.
     * @return \Illuminate\Http\JSONResponse
     */
    public function adminLogin(Request $request)
    {
        // リクエストボディのバリデーション (400エラーを返す)
        try {
            $request->validate([
                'login_id' => 'required|string',
                'password' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        // ログインIDとパスワードが一致する管理者が存在するか確認
        $admin = Admins::where('login_id', $request->login_id)->first();
        
        if (!$admin || !Hash::check($request->password, $admin->hashed_password)) {
            return response()->json(['error' => 'ユーザーネームまたはパスワードが無効です'], 401);
        }

        // トークンを発行
        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}