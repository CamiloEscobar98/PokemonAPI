<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get(['fullname', 'email', 'phone', 'nickname', 'birth_date']);

        if (!empty($users)) {
            return response()->json(['status' => 'success', 'data' => $users]);
        } else {
            return response()->json(['status' => 'empty']);
        }
    }
}
