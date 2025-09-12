<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, \Closure $next, ...$roles)
    {
        // ตรวจสอบว่า user login และ role ตรงกับ allowed roles
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return $next($request);
    }
}
