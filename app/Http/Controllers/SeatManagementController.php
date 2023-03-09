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
     * @param string $restaurantId 座席を追加したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatAdd(string $restaurantId)
    {
    }

    /**
     * 指定した店舗の座席情報一覧を取得
     * @param string $restaurantId 座席情報を取得したい店舗のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatList(string $restaurantId)
    {
    }

    /**
     * 座席の削除
     * @param string $seatId 削除したい座席のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatDelete(string $seatId)
    {
    }

    /**
     * 座席の編集 (編集後、自動QRコードトークン再発行)
     * @param string $seatId 編集したい座席のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatEdit(string $seatId)
    {
    }

    /**
     * 指定した座席の情報を取得
     * @param string $seatId 座席情報を取得したい座席のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatInfo(string $seatId)
    {
    }

    /**
     * 座席のQRコードトークンを再発行
     * @param string $seatId QRコードトークンを再発行したい座席のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function seatRefresh(string $seatId)
    {
    }
}
