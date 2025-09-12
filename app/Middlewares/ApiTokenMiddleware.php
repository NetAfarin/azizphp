<?php
namespace App\Middlewares;

use App\Core\Request;
use App\Models\User;

class ApiTokenMiddleware
{
    public function handle(Request $request, $next)
    {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';

        if (str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        $user = User::where('api_token', $token)->first();
        if (!$user) {
            return json_response(['error' => 'Unauthorized'], 401);
        }

        $request->user = $user; // برای استفاده در کنترلرها
        return $next($request);
    }
}

