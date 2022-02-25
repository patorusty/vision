<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'compania' => 'string',
            'tipo_usuario_id' => 'int',
            'activo' => 'int',
            'avatar' => 'nullable|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'nombre' => $fields['nombre'],
            'apellido' => $fields['apellido'],
            'compania' => $fields['compania'],
            'tipo_usuario_id' => $fields['tipo_usuario_id'],
            'activo' => $fields['activo'],
            'avatar' => $fields['avatar'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        // $token = $user->createToken('visiontoken')->plainTextToken;

        // $response = [
        //     'user' => $user,
        //     // 'token' => $token
        // ];
        $user->load('tipo_usuario');
        $user::with('tipo_usuario');

        return response($user, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }
        $token = $user->createToken('visionToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(null, 204);
    }
}
