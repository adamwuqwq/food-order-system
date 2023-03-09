<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use App\Services\AdminAccountManagementService;
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

        try {
            $admins = AdminAccountManagementService::getAdminList();

            // アカウント毎のrestaurant_idとrestaurant_nameを取得し、レスポンスに追加
            foreach ($admins as $admin) {
                $restaurants = AdminAccountManagementService::getRelatedRestaurant($admin->admin_id);
                $admin['restaurant_id'] =  array_column($restaurants, 'restaurant_id');
                $admin['restaurant_name'] = array_column($restaurants, 'restaurant_name');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => '管理者アカウントの一覧取得に失敗しました'], 500);
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
                'admin_role' => 'required|string',
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
            return response()->json(['error' => '管理者アカウントの作成に失敗しました'], 500);
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
        if (AdminAccountManagementService::deleteAdmin($adminId)) {
            return response()->json(['message' => '管理者アカウントを削除しました'], 200);
        } else {
            return response()->json(['error' => '管理者アカウントの削除に失敗しました'], 500);
        }
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
            $admin['restaurant_id'] =  array_column($restaurants, 'restaurant_id');
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
        if (AdminAccountManagementService::editAdmin($adminId, $adminInfo)) {
            return response()->json(['message' => '管理者アカウントを変更しました'], 200);
        } else {
            return response()->json(['error' => '管理者アカウントの編集に失敗しました'], 500);
        }
    }


    /**
     * 店舗の一覧取得 (owner, counter, kitchenは自分の店舗のみ取得可能).
     * @return \Illuminate\Http\JSONResponse
     */
    public function restaurantList()
    {
        // TODO: Autherizationヘッダーを使って管理者ロールを取得 (403エラーを返す)

        // 店舗の一覧を取得 (取得に失敗した場合は、500エラーを返す)
        try {
            $restaurants = RestaurantManagementService::getRestaurantList();
            // 店舗毎のowner_admin_idを取得し、レスポンスに追加
            foreach ($restaurants as $restaurant) {
                $owner = RestaurantManagementService::getOwner($restaurant->restaurant_id);
                if ($owner !== null) {
                    $restaurant['owner_admin_id'] = $owner->admin_id;
                } else {
                    $restaurant['owner_admin_id'] = null;
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => '店舗の一覧取得に失敗しました'], 500);
        }

        return response()->json($restaurants, 200);
    }

    /**
     * 新規店舗登録
     * @param Request $request リクエストボディ
     * @return \Illuminate\Http\JSONResponse
     */
    public function restaurantSignUp(Request $request)
    {
        // リクエストボディのバリデーション (400エラーを返す)
        try {
            $request->validate([
                'restaurant_name' => 'required|string',
                'owner_admin_id' => 'integer',
                'restaurant_address' => 'string',
                'restaurant_image_url' => 'string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        $restaurantInfo = json_decode($request->getContent(), true);

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、発行可否を判定 (403エラーを返す)

        // 店舗を作成 (作成に失敗した場合は、500エラーを返す)
        try {
            $restaurantId = RestaurantManagementService::createRestaurant($restaurantInfo);
        } catch (\Exception $e) {
            return response()->json(['error' => '店舗の作成に失敗しました'], 500);
        }

        return response()->json(['restaurant_id' => $restaurantId], 200);
    }

    /**
     * 店舗の削除
     * @param string $restaurantId 削除したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function restaurantDelete(string $restaurantId)
    {
        // 指定した店舗存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、削除可否を判定 (403エラーを返す)

        // 店舗を削除 (削除に失敗した場合は、500エラーを返す)
        try {
            RestaurantManagementService::deleteRestaurant($restaurantId);
        } catch (\Exception $e) {
            return response()->json(['error' => '店舗の削除に失敗しました'], 500);
        }

        return response()->json(['message' => '店舗を削除しました'], 200);
    }

    /**
     * 指定した店舗情報取得
     * @param string $restaurantId 情報取得したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function restaurantGet(string $restaurantId)
    {
        // 指定した店舗存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、取得可否を判定 (403エラーを返す)

        // 店舗情報を取得 (取得に失敗した場合は、500エラーを返す)
        $restaurant = RestaurantManagementService::getRestaurantInfo($restaurantId);
        if ($restaurant) {
            // owner_admin_idを取得し、レスポンスに追加
            $owner = RestaurantManagementService::getOwner($restaurant->restaurant_id);
            if ($owner !== null) {
                $restaurant['owner_admin_id'] = $owner->admin_id;
            } else {
                $restaurant['owner_admin_id'] = null;
            }

            return response()->json($restaurant, 200);
        } else {
            return response()->json(['error' => '店舗情報の取得に失敗しました'], 500);
        }
    }

    /**
     * 店舗情報編集
     * @param Request $request リクエストボディ
     * @param string $restaurantId 情報編集したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function restaurantModify(Request $request, string $restaurantId)
    {
        // リクエストボディのバリデーション (400エラーを返す)
        try {
            $request->validate([
                'restaurant_name' => 'string',
                'owner_admin_id' => 'integer',
                'restaurant_address' => 'string',
                'restaurant_image_url' => 'string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        $restaurantInfo = json_decode($request->getContent(), true);

        // 指定した店舗存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、編集可否を判定 (403エラーを返す)

        // 店舗情報を編集 (編集に失敗した場合は、500エラーを返す)
        try {
            RestaurantManagementService::editRestaurant($restaurantId, $restaurantInfo);
        } catch (\Exception $e) {
            return response()->json(['error' => '店舗情報の編集に失敗しました'], 500);
        }

        return response()->json(['message' => '店舗情報を変更しました'], 200);
    }


    /**
     * メニューに料理を追加(新規作成)
     * @param string $restaurantId 料理を追加したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function menuCreate(Request $request, string $restaurantId)
    {
        // 指定した店舗存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // リクエストボディのバリデーション (400エラーを返す)
        try {
            $request->validate([
                'dish_name' => 'required|string',
                'dish_category' => 'string',
                'dish_price' => 'required|integer',
                'available_num' => 'required|integer',
                'image_url' => 'string',
                'dish_description' => 'string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、追加可否を判定 (403エラーを返す)

        // リクエストボディから料理情報を取得
        $dishInfo = json_decode($request->getContent(), true);

        // 料理を追加 (追加に失敗した場合は、500エラーを返す)
        try {
            $dishId = DishManagementService::createDish($dishInfo, $restaurantId);
        } catch (\Exception $e) {
            return response()->json(['error' => '料理の追加に失敗しました'], 500);
        }

        return response()->json(['dish_id' => $dishId], 200);
    }
    
    /**
     * 料理メニューの一覧取得
     * @param string $restaurantId メニューの一覧を取得したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function menuList($restaurantId)
    {
        // 指定した店舗存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、取得可否を判定 (403エラーを返す)

        return response()->json(DishManagementService::getDishList($restaurantId), 200);
    }

    /**
     * Operation dishDelete
     * @param string $dishId 削除したい料理のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function dishDelete(string $dishId)
    {
        // 指定した料理存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!DishManagementService::isExist($dishId)) {
            return response()->json(['error' => '指定した料理は存在しません'], 400);
        }

        // TODO: Autherizationヘッダーを使って管理者ロールを取得、削除可否を判定 (403エラーを返す)

        // 料理を削除 (削除に失敗した場合は、500エラーを返す)
        try {
            DishManagementService::deleteDish($dishId);
        } catch (\Exception $e) {
            return response()->json(['error' => '料理の削除に失敗しました'], 500);
        }

        return response()->json(['message' => '料理を削除しました'], 200);
    }

    /**
     * 指定した料理情報取得
     * @param string $dishId 情報取得したい料理のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function dishGet($dishId)
    {
        // 指定した料理存在するかをチェック (存在しない場合は、400エラーを返す)
        if (!DishManagementService::isExist($dishId)) {
            return response()->json(['error' => '指定した料理は存在しません'], 400);
        }

        return response()->json(DishManagementService::getDishInfo($dishId), 200);
    }

    /**
     * 料理情報編集
     * @param int $dishId 編集したい料理のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function dishModify($dishId)
    {
    }


    /**
     * Operation adminLogin
     *
     * 管理者(system, owner, counter, kitchen)ログイン.
     *
     *
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
     */
    public function orderPut($orderId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing orderPut as a put method ?');
    }
    /**
     * Operation seatAdd
     *
     * 座席の追加.
     *
     * @param int $restaurantId 座席を追加したい店舗のID (required)
     *
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
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
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatRefresh($seatId)
    {
        //  $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing seatRefresh as a put method ?');
    }
}