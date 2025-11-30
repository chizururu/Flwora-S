<?php

namespace App\Http\Requests\Device;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class DeviceCreateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:4', 'max:20', $this->uniqueDeviceNamePerUserRule()],
            'sector_id' => ['sometimes', 'string'],
            'mac_address' => ['required', 'string', 'unique:devices,mac_address']
        ];
    }

    protected function uniqueDeviceNamePerUserRule(): Unique
    {
        // Nama device harus unik per user pada tabel devices
        return Rule::unique('devices', 'name')->whereIn('sector_id', $this->user()->sectors->pluck('id')->all());
    }
}
