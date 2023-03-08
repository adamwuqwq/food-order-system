<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

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
     * @return Http response
     */
    public function customerMenuGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['authorization'])) {
            throw new \InvalidArgumentException('Missing the required parameter $authorization when calling customerMenuGet');
        }
        $authorization = $input['authorization'];


        return response('How about implementing customerMenuGet as a get method ?');
    }

    /**
     * Operation customerMenuDishGet
     * 料理の詳細を取得
     * @param int $dishId 料理のID (required)
     * @return Http response
     */
    public function customerMenuDishGet($dishId)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing customerMenuDishGet as a get method ?');
    }

    /**
     * Operation customerOrderGet
     * 注文履歴、合計金額の取得
     * @return Http response
     */
    public function customerOrderGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['authorization'])) {
            throw new \InvalidArgumentException('Missing the required parameter $authorization when calling customerOrderGet');
        }
        $authorization = $input['authorization'];


        return response('How about implementing customerOrderGet as a get method ?');
    }

    /**
     * Operation customerOrderPost
     * 注文・追加注文送信
     * @return Http response
     */
    public function customerOrderPost()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['authorization'])) {
            throw new \InvalidArgumentException('Missing the required parameter $authorization when calling customerOrderPost');
        }
        $authorization = $input['authorization'];

        if (!isset($input['customerOrderPostRequest'])) {
            throw new \InvalidArgumentException('Missing the required parameter $customerOrderPostRequest when calling customerOrderPost');
        }
        $customerOrderPostRequest = $input['customerOrderPostRequest'];


        return response('How about implementing customerOrderPost as a post method ?');
    }

    /**
     * 注文確定・会計依頼.
     * @return Http response
     */
    public function customerOrderFinish()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['authorization'])) {
            throw new \InvalidArgumentException('Missing the required parameter $authorization when calling customerOrderFinish');
        }
        $authorization = $input['authorization'];


        return response('How about implementing customerOrderFinish as a post method ?');
    }
}