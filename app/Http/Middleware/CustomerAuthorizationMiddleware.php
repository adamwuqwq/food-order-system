<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Seats;
use Illuminate\Http\Request;
use \Illuminate\Http\JSONResponse;

class CustomerAuthorizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // へッダーからQRコードトークンを取得
        $qrCodeToken = $request->header('qr_code_token');
        
        // QRコードトークンが存在するか確認
        $seat = Seats::where('qr_code_token', $qrCodeToken)->first();

        // QRコードトークンと一致する席がない場合はエラー
        if (!$seat) {
            return new JSONResponse(['error' => 'QRコードトークンが無効です'], 401);
        }

        $request->merge([
            'seat_id' => $seat->seat_id,
            'restaurant_id' => $seat->restaurant_id,
        ]);

        return $next($request);
    }
}
