<?php

namespace App\Services;

use App\Models\Seats;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Support\Str;

class SeatManagementService
{
    //'restaurant_id',
    //'seat_name',
    //'qr_code_token',
    //'is_available',

    /**
     * QRコードトークン(UUID)を生成する
     * @return string QRコードトークン
     */
    private static function generateQrCodeToken()
    {
        return Str::uuid();
    }

    /**
     * 座席のQRコードトークンを更新する
     * @param string $seatId 座席ID
     * @return string QRコードトークン
     */
    public static function updateQrCodeToken(string $seatId)
    {
        $seat = Seats::find($seatId);

        // 座席が存在しない場合はnullを返す
        if ($seat === null) {
            return false;
        }

        $seat->qr_code_token = self::generateQrCodeToken();
        $seat->save();

        return $seat->qr_code_token;
    }

    /**
     * 指定した店舗の座席一覧を取得する
     * @param string $restaurantId 店舗ID
     * @return Collection 座席一覧
     */
    public static function getSeatList(string $restaurantId)
    {
        return Seats::where('restaurant_id', $restaurantId)->get();
    }

    /**
     * 座席の詳細情報を取得する
     * @param string $seatId 座席ID
     * @return Seats 座席情報
     */
    public static function getSeatInfo(string $seatId)
    {
        return Seats::find($seatId);
    }

    /**
     * 座席を追加する
     * @param string $restaurantId 店舗ID
     * @param string $seatName 座席名
     * @return Seats 追加された座席情報
     */
    public static function addSeat(string $restaurantId, string $seatName)
    {
        $seat = new Seats();
        $seat->restaurant_id = $restaurantId;
        $seat->seat_name = $seatName;
        $seat->qr_code_token = self::generateQrCodeToken();
        $seat->is_available = true;

        $seat->save();

        return $seat;
    }

    /**
     * 座席を一括追加する(新規店舗のみ)
     * @param string $restaurantId 店舗ID
     * @param int $seatNum 座席数
     * @return Collection 追加された座席情報
     * @throws \Exception 既に座席が存在する
     */
    public static function addMultipleSeats(string $restaurantId, int $seatNum)
    {
        // 既に座席が存在する場合は追加しない
        if (Seats::where('restaurant_id', $restaurantId)->exists()) {
            throw new \Exception('既に座席が存在します');
        }

        $seats = new Collection();
        for ($i = 0; $i < $seatNum; $i++) {
            $seats->add(self::addSeat($restaurantId, '座席' . ($i + 1)));
        }

        return $seats;
    }

    /**
     * 座席を更新する
     * @param string $seatId 座席ID
     * @param string|null $seatName 座席名
     * @param bool|null $isAvailable 座席の利用可否
     * @return bool 更新に成功したか
     */
    public static function editSeat(string $seatId, ?string $seatName = null, ?bool $isAvailable = null)
    {
        $seat = Seats::find($seatId);

        // 座席が存在しない場合はnullを返す
        if ($seat === null) {
            return null;
        }

        // 座席の情報を更新する
        $seat->seat_name = $seatName !== null ? $seatName : $seat->seat_name;
        $seat->is_available = $isAvailable !== null ? $isAvailable : $seat->is_available;

        return $seat->save();
    }

    /**
     * 座席を削除する
     * @param string $seatId 座席ID
     * @return bool 削除に成功したかどうか
     */
    public static function deleteSeat(string $seatId)
    {
        $seat = Seats::find($seatId);

        // 座席が存在しない場合はfalseを返す
        if ($seat === null) {
            return false;
        }

        return $seat->delete();
    }

    /**
     * 座席が存在するかをチェック
     * @param string $seatId 座席ID
     * @return bool 存在するか
     */
    public static function isExist(string $seatId)
    {
        return Seats::find($seatId) !== null;
    }

    /**
     * 座席に対応した注文のIDを取得する
     * @param string $seatId 座席ID
     * @return string|null 注文ID
     */
    public static function getOrderId(string $seatId)
    {
        $seat = Seats::find($seatId);

        // 座席が存在しない、または空席の場合はnullを返す
        if ($seat === null || $seat->is_available) {
            return null;
        }

        return $seat->orders->where('is_paid', false)->latest('created_at')->first()->seat_id ?? null;
    }

    /**
     * 座席のQRコードトークンを利用して座席IDを取得する
     * @param string $qrCodeToken QRコードトークン
     * @return string|null 座席ID
     */
    public static function getSeatIdByQrCodeToken(string $qrCodeToken)
    {
        $seat = Seats::where('qr_code_token', $qrCodeToken)->first();

        // 座席が存在しない場合はnullを返す
        return $seat === null ? null : $seat->seat_id;
    }
}