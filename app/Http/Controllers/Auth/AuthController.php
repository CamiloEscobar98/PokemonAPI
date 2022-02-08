<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'fullname' => ['required', 'string', 'min: 8'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'numeric', 'unique:users,phone'],
            'birth_date' => ['date'],
            'nickname' => ['required', 'string', 'min:5', 'unique:users,nickname'],
            'real_password' => ['required', 'string', 'min:4']
        ];
        $this->validate($request, $rules);

        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'nickname' => $request->nickname,
            'real_password' => $request->real_password,
            'password' => Hash::make($request->real_password)
        ]);

        if (!empty($user)) {
            $rememberToken = base64_encode(Str::random(40));
            $user->remember_token = $rememberToken;
            $user->save();
            return response()->json(['status' => 'success', 'auth_token' => $rememberToken]);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'User not created']);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'nickname' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only(['nickname', 'password']);
        $user = User::where('nickname', $credentials['nickname'])->first();

        if (!empty($user)) {
            if (Hash::check($credentials['password'], $user->password)) {
                $apikey = base64_encode(Str::random(40));
                User::where('nickname', $credentials['nickname'])->update(['remember_token' => "$apikey"]);;
                return response()->json(['status' => 'success', 'auth_token' => $apikey]);
            } else {
                return response()->json(['status' => 'fail', 'message' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['status' => 'fail', 'message' => 'User Not Found'], 404);
        }
    }

    public function logout(Request $request)
    {
        $rememberToken = $request->header('Authorization');
        $user = User::where('remember_token', $rememberToken)->first();
        if (!empty($user)) {
            $user->remember_token = null;
            $isLogout =  $user->save();
            if ($isLogout) {
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'fail'], 500);
            }
        } else {
            return response()->json(['status' => 'fail', 'message' => 'User not found']);
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

        $updated = $user->update([
            'fullname' => $user->fullname,
            'nickname' => $user->nickname,
            'email' => $user->email,
            'phone' => $user->phone,
            'birth_date' => $user->birth_date,
            'real_password' => $user->real_password,
            'password' => Hash::make($user->real_password),
        ]);
        if ($updated) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'fail'], 500);
        }
    }
}
