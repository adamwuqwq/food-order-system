<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use App\Services\AdminAccountManagementService;
use App\Services\RelationshipManagementService;
use App\Services\RestaurantManagementService;
use App\Services\MenuManagementService;
use App\Services\DishManagementService;
use App\Services\OrderManagementService;

class ManagementController extends Controller
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

        $admins = AdminAccountManagementService::getAdminList();

        return response()->json($admins, 200);
    }

    /**
     * 新規管理者アカウント(owner, counter, kitchen)発行
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
                'admin_role' => 'required|string',
                'restaurant_id' => 'array',
                'restaurant_id.*' => 'integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        $adminInfo = json_decode($request->getContent(), true);

        // TODO: 店舗が存在するかをチェック (存在しない場合は、400エラーを返す)

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、発行可否を判定 (403エラーを返す)

        $adminId = AdminAccountManagementService::createAdmin($adminInfo);

        return response()->json(['admin_id' => $adminId], 200);
    }


    /**
     * 管理者アカウント(owner, counter, kitchen)削除
     * @param int $adminId 削除したい管理者アカウントのID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function adminDelete($adminId)
    {
        // 指定した管理者存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!AdminAccountManagementService::isExist($adminId)) {
            return response()->json(['error' => '指定した管理者は存在しません'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、削除可否を判定 (403エラーを返す)

        // 管理者アカウントを削除 (削除に失敗した場合は、500エラーを返す)
        if (AdminAccountManagementService::deleteAdmin($adminId)) {
            return response()->json(['message' => '管理者アカウントを削除しました'], 200);
        } else {
            return response()->json(['error' => '管理者アカウントの削除に失敗しました'], 500);
        }
    }

    /**
     * 管理者アカウント情報取得
     * @param int $adminId 情報取得したい管理者アカウントのID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function adminGet($adminId)
    {
        // 指定した管理者存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!AdminAccountManagementService::isExist($adminId)) {
            return response()->json(['error' => '指定した管理者は存在しません'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、取得可否を判定 (403エラーを返す)

        // 管理者アカウントを取得 (取得に失敗した場合は、500エラーを返す)
        $admin = AdminAccountManagementService::getAdminInfo($adminId);
        if ($admin) {
            return response()->json($admin, 200);
        } else {
            return response()->json(['error' => '管理者アカウントの取得に失敗しました'], 500);
        }
    }

    /**
     * 管理者アカウント(owner, counter, kitchen)情報編集.
     * @param int $adminId 編集したい管理者アカウントのID (required)
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
        if (AdminAccountManagementService::editAdmin($adminId, $adminInfo)) {
            return response()->json(['message' => '管理者アカウントを編集しました'], 200);
        } else {
            return response()->json(['error' => '管理者アカウントの編集に失敗しました'], 500);
        }
    }

    /**
     * Operation menuCreate
     *
     * メニューに料理を追加(新規作成).
     *
     * @param string $restaurantId 料理を追加したい店舗のID (required)
     *
     * @return Http response
     */
    public function menuCreate($restaurantId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing menuCreate as a post method ?');
    }
    /**
     * Operation menuList
     *
     * 料理メニューの一覧取得.
     *
     * @param string $restaurantId メニューの一覧を取得したい店舗のID (required)
     *
     * @return Http response
     */
    public function menuList($restaurantId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing menuList as a get method ?');
    }
    /**
     * Operation dishDelete
     *
     * 料理の削除.
     *
     * @param int $dishId 削除したい料理のID (required)
     *
     * @return Http response
     */
    public function dishDelete($dishId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing dishDelete as a delete method ?');
    }
    /**
     * Operation dishGet
     *
     * 指定した料理情報取得.
     *
     * @param int $dishId 情報取得したい料理のID (required)
     *
     * @return Http response
     */
    public function dishGet($dishId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing dishGet as a get method ?');
    }
    /**
     * Operation dishModify
     *
     * 料理情報編集.
     *
     * @param int $dishId 編集したい料理のID (required)
     *
     * @return Http response
     */
    public function dishModify($dishId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing dishModify as a put method ?');
    }
    /**
     * Operation adminLogin
     *
     * 管理者(system, owner, counter, kitchen)ログイン.
     *
     *
     * @return Http response
     */
    public function adminLogin()
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation
        // $adminLoginRequest = $input['adminLoginRequest'];


        return response('How about implementing adminLogin as a post method ?');
    }
    /**
     * Operation orderedDishCancel
     *
     * 注文した(+未提供)料理のキャンセル.
     *
     * @param int $orderedDishId キャンセルしたい料理の注文ID (required)
     *
     * @return Http response
     */
    public function orderedDishCancel($orderedDishId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing orderedDishCancel as a put method ?');
    }
    /**
     * Operation orderedDishDelivery
     *
     * 注文ステータスを提供済みにする.
     *
     * @param int $orderedDishId 注文した料理の注文ID (required)
     *
     * @return Http response
     */
    public function orderedDishDelivery($orderedDishId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing orderedDishDelivery as a put method ?');
    }
    /**
     * Operation orderList
     *
     * 指定した店舗の注文一覧の取得.
     *
     * @param int $restaurantId 注文一覧を取得したい店舗のID (required)
     *
     * @return Http response
     */
    public function orderList($restaurantId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing orderList as a get method ?');
    }
    /**
     * Operation unservedDishList
     *
     * 指定した店舗の未提供料理一覧を取得 (注文時間順).
     *
     * @param int $restaurantId 未提供料理一覧を取得したい店舗のID (required)
     *
     * @return Http response
     */
    public function unservedDishList($restaurantId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing unservedDishList as a get method ?');
    }
    /**
     * Operation orderGet
     *
     * 注文詳細の取得.
     *
     * @param int $orderId 注文詳細を取得したい注文のID (required)
     *
     * @return Http response
     */
    public function orderGet($orderId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing orderGet as a get method ?');
    }
    /**
     * Operation orderPut
     *
     * (会計済みボタン) 注文を完了する.
     *
     * @param int $orderId 注文のID (required)
     *
     * @return Http response
     */
    public function orderPut($orderId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing orderPut as a put method ?');
    }
    /**
     * Operation restaurantList
     *
     * 店舗の一覧取得 (ownerは自分の店舗のみ取得可能).
     *
     *
     * @return Http response
     */
    public function restaurantList()
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['authorization'])) {
            throw new \InvalidArgumentException('Missing the required parameter $authorization when calling restaurantList');
        }
        // $authorization = $input['authorization'];


        return response('How about implementing restaurantList as a get method ?');
    }
    /**
     * Operation restaurantSignUp
     *
     * 新規店舗登録.
     *
     *
     * @return Http response
     */
    public function restaurantSignUp()
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['authorization'])) {
            throw new \InvalidArgumentException('Missing the required parameter $authorization when calling restaurantSignUp');
        }
        // $authorization = $input['authorization'];

        // $restaurantSignUpRequest = $input['restaurantSignUpRequest'];


        return response('How about implementing restaurantSignUp as a post method ?');
    }
    /**
     * Operation restaurantDelete
     *
     * 店舗の削除.
     *
     * @param int $restaurantId 削除したい店舗のID (required)
     *
     * @return Http response
     */
    public function restaurantDelete($restaurantId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing restaurantDelete as a delete method ?');
    }
    /**
     * Operation restaurantGet
     *
     * 指定した店舗情報取得.
     *
     * @param int $restaurantId 情報取得したい店舗のID (required)
     *
     * @return Http response
     */
    public function restaurantGet($restaurantId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing restaurantGet as a get method ?');
    }
    /**
     * Operation restaurantModify
     *
     * 店舗情報編集.
     *
     * @param int $restaurantId 情報編集したい店舗のID (required)
     *
     * @return Http response
     */
    public function restaurantModify($restaurantId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing restaurantModify as a put method ?');
    }
    /**
     * Operation seatAdd
     *
     * 座席の追加.
     *
     * @param int $restaurantId 座席を追加したい店舗のID (required)
     *
     * @return Http response
     */
    public function seatAdd($restaurantId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing seatAdd as a post method ?');
    }
    /**
     * Operation seatList
     *
     * 指定した店舗の座席情報一覧を取得.
     *
     * @param int $restaurantId 座席情報を取得したい店舗のID (required)
     *
     * @return Http response
     */
    public function seatList($restaurantId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing seatList as a get method ?');
    }
    /**
     * Operation seatDelete
     *
     * 座席の削除.
     *
     * @param int $seatId 削除したい座席のID (required)
     *
     * @return Http response
     */
    public function seatDelete($seatId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing seatDelete as a delete method ?');
    }
    /**
     * Operation seatEdit
     *
     * 座席の編集 (編集後、自動QRコードトークン再発行).
     *
     * @param int $seatId 編集したい座席のID (required)
     *
     * @return Http response
     */
    public function seatEdit($seatId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing seatEdit as a put method ?');
    }
    /**
     * Operation seatInfo
     *
     * 指定した座席の情報を取得.
     *
     * @param int $seatId 座席情報を取得したい座席のID (required)
     *
     * @return Http response
     */
    public function seatInfo($seatId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing seatInfo as a get method ?');
    }
    /**
     * Operation seatRefresh
     *
     * 座席のQRコードトークンを再発行.
     *
     * @param int $seatId QRコードトークンを再発行したい座席のID (required)
     *
     * @return Http response
     */
    public function seatRefresh($seatId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing seatRefresh as a put method ?');
    }
}