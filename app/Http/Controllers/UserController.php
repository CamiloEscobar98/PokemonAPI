<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $rememberToken = $request->header('Authorization');
        $authEmail = User::where('remember_token', $rememberToken)->get(['email'])->first()->email;
        $users = User::where('email', '!=', $authEmail)->get(['uuid', 'fullname', 'email', 'phone', 'nickname', 'birth_date']);

        if (!empty($users)) {
            return response()->json(['status' => 'success', 'data' => $users]);
        } else {
            return response()->json(['status' => 'empty']);
        }
    }

    public function show($uuid)
    {
        $user = User::where('uuid', $uuid)->get(['uuid', 'fullname', 'email', 'phone', 'nickname', 'birth_date'])->first();
        if (!empty($user)) {
            return response()->json(['status' => 'success', 'user' => $user]);
        } else {
            return response()->json(['status' => 'fail'], 404);
        }
    }
}
