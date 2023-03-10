<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\DishManagementService;
use App\Services\OrderManagementService;
use App\Services\OrderedDishManagementService;

class CustomerController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * メニューの取得
     * @return \Illuminate\Http\JSONResponse
     */
    public function customerMenuGet()
    {
        
    }

    /**
     * 料理の詳細を取得
     * @param string  $dishId 料理のID (required)
     * @return \Illuminate\Http\JSONResponse
     */
    public function customerMenuDishGet($dishId)
    {

    }

    /**
     * 注文履歴、合計金額の取得
     * @return \Illuminate\Http\JSONResponse
     */
    public function customerOrderGet()
    {

    }

    /**
     * 注文・追加注文送信
     * @return \Illuminate\Http\JSONResponse
     */
    public function customerOrderPost()
    {

    }

    /**
     * 注文確定・会計依頼
     * @return \Illuminate\Http\JSONResponse
     */
    public function customerOrderFinish()
    {

    }
}