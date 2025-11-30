<?php

namespace App\Http\Requests\Sector;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class SectorRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:4', 'max:20', $this->uniqueNamePerUserRule()]
        ];
    }

    protected function uniqueNamePerUserRule(): Unique
    {
        return Rule::unique('sectors', 'name')
            ->where('user_id', $this->user()->id)
            ->ignore($this->route('sector'));
    }
}
