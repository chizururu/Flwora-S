<?php

namespace App\Http\Requests\Device;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class DeviceUpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Jenis aksi yang dipilih pada pembaruan perangkat
            'action' => ['required', 'in:rename_device,move_sector'],

            // Aturan validasi untuk aksi rename perangkat
            'name' => [
                'required_if:action,rename_device',
                'string',
                'min:4',
                'max:20',
                $this->uniqueDeviceNamePerUserRule()
            ],

            // Aturan validasi untuk aksi memindahkan perangkat ke sektor lain
            'sector_id' => [
                'sometimes',
                'required_if:action,move_sector',
                'integer',
                'exists:sectors,id'
            ],
        ];
    }

    protected function uniqueDeviceNamePerUserRule(): Unique
    {
        return Rule::unique('devices', 'name')->whereIn('sector_id', $this->user()->sectors->pluck('id')->all())->ignore($this->route('device'));
    }
}
