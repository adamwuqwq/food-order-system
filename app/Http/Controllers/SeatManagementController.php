<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use App\Services\RestaurantManagementService;
use App\Services\SeatManagementService;

use App\Models\Seats;

class SeatManagementController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * 座席の追加
     * @param Request $request りクエストボディ
     * @param string $restaurantId 座席を追加したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatAdd(Request $request, string $restaurantId)
    {
        // リクエストボディのバリデーション
        try {
            $this->validate($request, [
                'seat_name' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        // 指定した店舗が存在するか確認(存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        $seatName = $request->input('seat_name');

        // 指定した店舗に同じ名前の座席が存在するか確認(存在する場合は、400エラーを返す)
        if (Seats::where('restaurant_id', $restaurantId)->where('seat_name', $seatName)->exists()) {
            return response()->json(['error' => '指定した店舗に同じ名前の座席が存在します'], 400);
        }

        // 座席の追加
        $seatInfo = SeatManagementService::addSeat($restaurantId, $seatName);

        $jsonResponse = [
            'seat_id' => $seatInfo->seat_id,
            'qr_code_token' => $seatInfo->qr_code_token,
        ];

        return response()->json($jsonResponse, 200);
    }

    /**
     * 指定した店舗の座席情報一覧を取得
     * @param string $restaurantId 座席情報を取得したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatList(string $restaurantId)
    {
        // 指定した店舗が存在するか確認(存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        $seatList = SeatManagementService::getSeatList($restaurantId);
        return response()->json($seatList, 200);
    }

    /**
     * 座席の削除
     * @param string $seatId 削除したい座席のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatDelete(string $seatId)
    {
        // 指定した座席が存在するか確認(存在しない場合は、400エラーを返す)
        if (!SeatManagementService::isExist($seatId)) {
            return response()->json(['error' => '指定した座席は存在しません'], 400);
        }

        // 座席の削除
        try {
            SeatManagementService::deleteSeat($seatId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '座席を削除しました'], 200);
    }

    /**
     * 座席の編集
     * @param Request $request リクエストボディ
     * @param string $seatId 編集したい座席のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatEdit(Request $request, string $seatId)
    {
        // リクエストボディのバリデーション
        try {
            $this->validate($request, [
                'seat_name' => 'string',
                'is_available' => 'boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'リクエストの形式または内容に誤りがある'], 400);
        }

        // 指定した座席が存在するか確認(存在しない場合は、400エラーを返す)
        if (!SeatManagementService::isExist($seatId)) {
            return response()->json(['error' => '指定した座席は存在しません'], 400);
        }

        // 座席情報編集
        try {
            SeatManagementService::editSeat($seatId, $request->input('seat_name'), $request->input('is_available'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => '座席情報を更新しました'], 200);
    }

    /**
     * 指定した座席の情報を取得
     * @param string $seatId 座席情報を取得したい座席のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatInfo(string $seatId)
    {
        // 指定した座席が存在するか確認(存在しない場合は、400エラーを返す)
        if (!SeatManagementService::isExist($seatId)) {
            return response()->json(['error' => '指定した座席は存在しません'], 400);
        }

        // 座席情報を取得
        try {
            $seatInfo = SeatManagementService::getSeatInfo($seatId);

            // 座席に対応した注文IDを取得し、レスポンスに追加
            $orderID = SeatManagementService::getOrderId($seatId);
            if ($orderID !== null) {
                $seatInfo['order_id'] = $orderID;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json($seatInfo, 200);
    }

    /**
     * 座席のQRコードトークンを再発行
     * @param string $seatId QRコードトークンを再発行したい座席のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatRefresh(string $seatId)
    {
        // 指定した座席が存在するか確認(存在しない場合は、400エラーを返す)
        if (!SeatManagementService::isExist($seatId)) {
            return response()->json(['error' => '指定した座席は存在しません'], 400);
        }

        // 座席のQRコードトークンを再発行
        try {
            $qrCodeToken = SeatManagementService::updateQrCodeToken($seatId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['qr_code_token' => $qrCodeToken], 200);
    }

    /**
     * 新規店舗の座席一括登録
     * @param string $restaurantId 座席を登録したい店舗のID (required)
     * @param string $seat_num 座席数 (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function multipleSeatAdd(string $restaurantId, string $seat_num)
    {
        // 店舗が存在するか確認(存在しない場合は、400エラーを返す)
        if (!RestaurantManagementService::isExist($restaurantId)) {
            return response()->json(['error' => '指定した店舗は存在しません'], 400);
        }

        // 座席数が正の整数か確認(正の整数でない場合は、400エラーを返す)
        if (!ctype_digit($seat_num) || $seat_num <= 0) {
            return response()->json(['error' => '座席数は正の整数で指定してください'], 400);
        }

        // 座席一括登録
        try {
            SeatManagementService::addMultipleSeats($restaurantId, $seat_num);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // 座席のseat_idとseat_tokenを取得
        $seats = SeatManagementService::getSeatList($restaurantId);
        $seats = $seats->map(function ($seat) {
            return [
                'seat_id' => $seat->seat_id,
                'qr_code_token' => $seat->qr_code_token,
            ];
        });

        return response()->json($seats, 200);
    }
}
