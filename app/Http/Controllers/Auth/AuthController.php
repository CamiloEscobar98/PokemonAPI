<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'nickname' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only(['nickname', 'password']);

        $user = User::where('nickname', $credentials['nickname'])->first();
        if (Hash::check($credentials['password'], $user->password)) {
            $apikey = base64_encode(Str::random(40));
            User::where('nickname', $credentials['nickname'])->update(['remember_token' => "$apikey"]);;
            return response()->json(['status' => 'success', 'auth_token' => $apikey]);
        } else {
            return response()->json(['status' => 'fail'], 401);
        }
    }
}
