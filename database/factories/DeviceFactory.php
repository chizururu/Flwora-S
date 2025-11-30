<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DeviceFactory extends Factory
{
    protected $model = Device::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'mac_address' => $this->faker->address(),
            'status' => $this->faker->boolean(),
            'irrigation_state' => $this->faker->randomNumber(),
            'ai_status' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'sector_id' => Sector::factory(),
        ];
    }
}
