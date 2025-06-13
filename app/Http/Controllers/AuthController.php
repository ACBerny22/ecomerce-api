<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();

            return response()->json([
                'status' => false,
                'errors' => $errors
            ], 400);
        }

        $incommingFields = $request->all();
        $user = User::where("email", $incommingFields["email"])->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'error' => "El usuario no existe."
            ], 400);
        }

        if (!Hash::check($incommingFields['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'error' => "La contraseña es incorrecta"
            ], 400);
        }

        Auth::login($user);

        return response()->json([
            'status' => true,
            'message' => "Inicio de sesión exitoso",
            'data' => [
                ...$user->toArray(),
                'token' => $user->createToken('authToken')->plainTextToken
            ]
        ]);
    }

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users,email',
            'password' => 'required|max:50|string',
            'role_id' => 'integer|required'
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();

            return response()->json([
                'status' => false,
                'errors' => $errors
            ], 400);
        }
        $hashedPassword = Hash::make($request['password']);
        $data = request()->except('password');
        $payload = [
            ...$data,
            'password' => $hashedPassword
        ];

        $user = User::create($payload);

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }
}
