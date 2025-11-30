<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            // Validasi input
            $credentials = $request->validated();

            // Cari user berdasarkan email yang diberikan
            $user = User::where('email', $credentials['email'])->first();

            // Jika user tidak ditemukan atau password tidak cocok kembalikan response error secara spesifik
            if (!$user || Hash::check($credentials['password'], $user->password)) {
                if (!$user) {
                    $error = ['email' => ['Email tidak ditemukan']];
                    $status = Response::class::HTTP_NOT_FOUND;
                } else {
                    $error = ['email' => ['Password salah']];
                    $status = Response::class::HTTP_UNAUTHORIZED;
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Kesalahan login',
                    'errors' => $error,
                ], $status);
            }

            // Generate token akses
            $token = $user->createToken('auth_token')->plainTextToken;

            // Ambil semua sektor yang dimiliki user
            $sectors = $user->sectors()->get();

            // Ambil daftar id sektor untuk mengambil perangkat terkait
            $sectorIds = $sectors->pluck('id');

            // Ambil semua perangkat yang berada pada sektor
            $devices = Device::whereIn('sector_id', $sectorIds)->get();

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'token' => $token,
                    ],
                    'sector' => $sectors,
                    'device' => $devices
                ]
            ], Response::class::HTTP_OK);
        } catch (\Throwable $e) {
            // Jika terjadi kesalahan pada server
            return response()->json([
                'status' => false,
                'message' => 'Kesalahan server, silahkan coba lagi dan hubungi customer service'
            ], Response::class::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            // Validasi input
            $credentials = $request->validated();

            // Password akan dienkripsikan
            $credentials['password'] = Hash::make($credentials['password']);

            // User yang sudah registrasi
            $user = User::create($credentials);

            // Generate token akses
            $token = $user->createToken('auth_token')->plainTextToken;

            // Buat sektor default yaitu home yang tidak dapat diubah bahkan dihapus
            $sectors = $user->sectors()->create([
                'name' => 'Home'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registrasi berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'token' => $token,
                    ],
                    'sector' => [$sectors],
                ]
            ], Response::class::HTTP_CREATED);
        } catch (\Throwable $e) {
            // Jika terjadi kesalahan pada server
            return response()->json([
                'status' => false,
                'message' => 'Kesalahan server, silahkan coba lagi dan hubungi customer service'. $e
            ], Response::class::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
