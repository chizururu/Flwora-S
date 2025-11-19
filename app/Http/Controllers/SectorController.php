<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sector\SectorRequest;
use App\Models\Sector;
use Illuminate\Http\Response;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Fetch daftar sektor
            $sectors = Sector::where('user_id', auth()->id())
                ->get();

            $data['status'] = true;
            $data['message'] = 'Berhasil mengambil data sektor';
            $data['data'] = $sectors;

            return response()->json($data, Response::class::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Kesalahan server, silahkan coba lagi dan hubungi customer service'
            ], Response::class::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectorRequest $request)
    {
        try {
            // Validasi data input
            $validatedData = $request->validated();

            $validatedData['user_id'] = auth()->id();

            // Buat data sektor baru
            Sector::create($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Sektor berhasil dibuat',
            ], Response::class::HTTP_CREATED);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Kesalahan server, silahkan coba lagi dan hubungi customer service'
            ], Response::class::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sector $sector)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sector $sector)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectorRequest $request, Sector $sector)
    {
        try {
            // Validasi data input
            $validatedData = $request->validated();

            // Update data sektor
            $sector->update($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Sektor berhasil diperbarui',
            ], Response::class::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
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
            // Hitung jumlah perangkat dalam sektor
            $deviceCount = $sector->devices()->count();

            // Check apakah sektor ini memiliki perangkat
            if ($deviceCount > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sektor ini masih memiliki perangkat, silahkan pindahkan dan hapus perangkat terlebih dahulu'
                ], Response::class::HTTP_OK);
            }

            // Hapus sektor jika tidak ada perangkat
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
