<?php

namespace App\Http\Controllers;

use App\Http\Requests\Device\DeviceCreateRequest;
use App\Http\Requests\Device\DeviceUpdateRequest;
use App\Http\Requests\Sector\SectorRequest;
use App\Models\Device;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeviceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(DeviceCreateRequest $request)
    {
        try {
            // Validasi data
            $deviceData = $request->validated();

            // Ambil id dari sektor default "Home" milik user
            // Catatan: setiap user memiliki sektor 'Home' secara otomatis dan terkunci
            $defaultSectorId = auth()->user()->sectors()->where('name', 'Home')->value('id');

            // Set devices baru agar otomatis masuk ke sektor default
            $deviceData['sector_id'] = $defaultSectorId;

            // Simpan perangkat
            $device = Device::create($deviceData);

            // Increment jumlah perangkat pada sektor
            $device->sector()->increment('devices_count');

            return response()->json([
                "status" => true,
                'message' => 'Perangkat berhasil ditambahkan'
            ], Response::class::HTTP_CREATED);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Kesalahan server, silahkan coba lagi dan hubungi customer service'
            ], Response::class::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeviceUpdateRequest $request, Device $device)
    {
        try {
            // Validasi data input
            $deviceData = $request->validated();

            if ($deviceData['action'] === 'rename_device') {
                // Update data device (name)
                $device->name = $deviceData['name'];
                $device->save();
            }

            if ($deviceData['action'] === 'move_sector') {
                // Update data device (sector_id)
                $oldSectorId = $device->sector_id;
                $newSectorId = $deviceData['sector_id'];

                if ($oldSectorId != $newSectorId) {
                    $device->sector_id = $newSectorId;
                    $device->save();

                    // Kurangi jumlah device di sektor lama
                    Sector::where('id', $oldSectorId)->decrement('devices_count');

                    // Tambahkan jumlah perangkat di sektor baru
                    Sector::where('id', $newSectorId)->increment('devices_count');
                }
            }

            return response()->json([
                "status" => true,
                'message' => 'Perangkat berhasil diperbarui'
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
    public function destroy(Device $device)
    {
        try {
            $sectorId = $device->sector_id;

            // Hapus data perangkat
            $device->delete();

            // Kurangi jumlah sektor
            Sector::where('id', $sectorId)->decrement('devices_count');

            return response()->json([
                "status" => true,
                'message' => 'Perangkat berhasil dihapus'
            ], Response::class::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Kesalahan server, silahkan coba lagi dan hubungi customer service'
            ], Response::class::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
