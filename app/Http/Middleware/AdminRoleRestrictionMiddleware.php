<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Illuminate\Http\JSONResponse;

class AdminRoleRestrictionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $admin = $request->user();

        if ($admin && in_array($admin->admin_role, ['system', 'owner'])) {
            return $next($request);
        }
    
        return response()->json(['message' => 'アクセス権限がありません'], 403);
    }
}
