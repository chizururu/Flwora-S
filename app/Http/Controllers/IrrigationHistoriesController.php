<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\IrrigationHistories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IrrigationHistoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            // 1. Ambil dari query parameters
            $deviceId = request('device_id');
            $date = request('date');

            // 2. Fetch data device id yang dipilih
            $device = Device::findOrFail($deviceId);

            // 3. Ambil data irrigation histories
            $query = $device->irrigationHistories();

            // 4. Ambil data irrigation histories berdasarkan created_at (filter tanggal)
            $query = $query->whereDate('created_at', $date);

            $data = $query->latest()->get();

            return response()->json([
                "status" => true,
                'message' => 'Riwayat irigasi berhasil',
                'data' => ['history' => $data],
            ], Response::class::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(IrrigationHistories $irrigationHistories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IrrigationHistories $irrigationHistories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IrrigationHistories $irrigationHistories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IrrigationHistories $irrigationHistories)
    {
        //
    }
}
