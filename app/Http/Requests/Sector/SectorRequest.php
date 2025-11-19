<?php

namespace App\Http\Requests\Sector;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class SectorRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:8',
                'max:20',
                Rule::unique('sectors')->where('user_id', auth()->id()),
            ],
        ];
    }
}
