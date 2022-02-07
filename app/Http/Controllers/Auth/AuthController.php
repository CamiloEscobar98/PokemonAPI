<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Validation\Rule;

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

    public function profile(Request $request)
    {
        $rememberToken = $request->header('Authorization');
        $user = User::where('remember_token', $rememberToken)->get(['fullname', 'email', 'phone', 'birth_date', 'nickname', 'real_password'])->first();
        if (!empty($user)) {
            return response()->json(['status' => 'success', 'user' => $user]);
        } else {
            return response()->json(['status' => 'not found'], 404);
        }
    }

    public function update(Request $request)
    {
        $rememberToken = $request->header('Authorization');
        $user = User::where('remember_token', $rememberToken)->get()->first();
        $rules = [
            'fullname' => ['required', 'string'],
            'email' => ['required',  Rule::unique('users', 'email')->ignore($user->uuid, 'uuid')],

            'phone' => ['required', 'numeric'],
            'birth_date' => ['required', 'date'],
            'nickname' => ['required',  Rule::unique('users', 'nickname')->ignore($user->uuid, 'uuid')],

            'real_password' => ['required', 'string']
        ];
        $this->validate($request, $rules);

        $updated = $user->update($request->all());
        if ($updated) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'fail'], 500);
        }
    }
}
