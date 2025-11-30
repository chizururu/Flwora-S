<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sector\SectorRequest;
use App\Models\Sector;
use Illuminate\Http\Response;

class SectorController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(SectorRequest $request)
    {
        try {
            // Validasi data input
            $sectorData = $request->validated();
            $sectorData['user_id'] = auth()->id();

            // Simpan sektor baru
            Sector::create($sectorData);

            return response()->json([
                'status' => true,
                'message' => 'Sektor berhasil ditambahkan'
            ], Response::class::HTTP_CREATED);
        } catch (\Throwable $e) {
            // Jika terjadi kesalahan pada server
            return response()->json([
                'status' => false,
                'message' => 'Kesalahan server, silahkan coba lagi dan hubungi customer service'
            ], Response::class::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectorRequest $request, Sector $sector)
    {
        try {
            // Validasi data input
            $sectorData = $request->validated();

            // Update data sektor
            $sector->update($sectorData);

            return response()->json([
                'status' => true,
                'message' => 'Sektor berhasil diperbarui',
            ], Response::class::HTTP_OK);

        } catch (\Throwable $e) {
            return response()->json([
                // Jika terjadi kesalahan pada server
                'status' => false,
                'message' => 'Kesalahan server, silahkan coba lagi dan hubungi customer service'
            ], Response::class::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sector $sector)
    {
        try {
            // Hitung total perangkat yang terkait pada sektor
            $totalDevices = $sector->devices()->count();

            // Jika total > 0, maka tidak bisa dihapus
            if ($totalDevices > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sektor ini masih memiliki perangkat, silahkan pindahkan dan hapus perangkat terlebih dahulu'
                ], Response::class::HTTP_CONFLICT);
            }

            // Hapus sektor
            $sector->delete();

            return response([
                'status' => true,
                'message' => 'Sektor telah berhasil dihapus',
            ], Response::class::HTTP_OK);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Kesalahan server, silahkan coba lagi dan hubungi customer service'
            ], Response::class::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
