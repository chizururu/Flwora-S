<?php

namespace App\Http\Controllers;


use App\Http\Requests\User\UserRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        try {
            // Validasi input
            $userData = $request->validated();

            // Ambil user yang akan diupdate
            $user = User::findOrFail($id);

            // Tentukan pesan sesuai aksi
            $message = match ($userData['action']) {
                'update' => 'Berhasil mengubah profile',
                'reset_password' => 'Berhasil mengubah password'
            };

            // Update nama profile
            if ($userData['action'] === 'update') {
                $user->name = $userData['name'];
            }

            // Update password
            if ($userData['action'] === 'reset_password') {
                $user->password = Hash::make($userData['password']);
            }

            // Simpan perubahan user
            $user->save();

            return response()->json([
                "status" => true,
                'message' => $message
            ], Response::class::HTTP_OK);
        } catch (\Throwable $e) {
            // Jika terjadi kesalahan pada server
            return response()->json([
                'status' => false,
                'message' => 'Kesalahan server, silahkan coba lagi dan hubungi customer service'
            ], Response::class::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
