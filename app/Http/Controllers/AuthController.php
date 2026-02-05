<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Fungsi pembungkus respon agar rapi
    private function formatResponse($data, $code = 200, $status = true)
    {
        return response()->json([
            'code' => (int)$code,
            'status' => $status ? true : false,
            'data' => $data
        ], $code);
    }

    public function register(Request $request)
    {
        // 1. Validasi Input (Pastikan menggunakan 'name' bukan 'nama')
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return $this->formatResponse($validator->errors()->first(), 422, false);
        }

        // 2. Simpan ke Database
        $user = User::create([
            'name' => $request->name,  // Pastikan ini 'name' sesuai kiriman Flutter
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // 3. Return sukses dengan kode 201 (Created)
        return $this->formatResponse("Registrasi Berhasil", 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->formatResponse('Email atau password salah.', 401, false);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        $data = [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ]
        ];
        
        // Return sukses dengan kode 200
        return $this->formatResponse($data, 200);
    }
}