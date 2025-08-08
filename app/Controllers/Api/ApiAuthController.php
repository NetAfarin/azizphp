<?php
namespace App\Controllers\Api;

use App\Models\User;

class ApiAuthController
{
    public function login($request)
    {
        $data = $request->input();
        $user = User::where('phone_number', $data['phone_number'])->first();

        if (!$user || !password_verify($data['password'], $user->password)) {
            return json_response(['error' => 'Invalid credentials'], 401);
        }

        // Generate random token
        $user->api_token = bin2hex(random_bytes(32));
        $user->save();

        return json_response([
            'token' => $user->api_token,
            'user' => [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name
            ]
        ]);
    }
}
