<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function user()
    {
        return Auth::user();
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw new AuthenticationException();
        }
        
        return response([
            'access_token' => $user->createToken($request->device_name)->plainTextToken,
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response(null, 204);
    }
}
