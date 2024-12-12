<?php

namespace App\Http\Controllers\apisControllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // user register
    public function register(Request $request)
    {
        $credentials = $request->validate([
            'u_username' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email', //unique in users table, email column
            'password' => 'required|min:6|confirmed',
            'u_major_id' => 'nullable|exists:majors,m_id',  // Check if u_major_id exists in the "majors" table's "m_id" column
            'u_role_id' => 'required|exists:roles,r_id', // Check if u_role_id exists in the "roles" table's "r_id" column
        ]);

        $user = User::create([
            'u_username' => $credentials['u_username'],
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password']), //hash the password
            'u_major_id' => $credentials['u_major_id'],
            'u_role_id' => $credentials['u_role_id'],
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        // Save the token in the remember_token column
        // $user->update(['remember_token' => $token]);

        return response([
            'message' => 'user created',
            'user' => $user,
            'token' => $token,
        ], 200);
    }
}
