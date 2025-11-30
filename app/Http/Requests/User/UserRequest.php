<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'action' => ['required', 'in:update,reset_password'],

            // Validation rule untuk ubah profile
            'name' => [
                'required_if:action,update',
                'string',
                'max:50',
                $this->uniqueNamePerUserRule()
            ],

            // Validation rule untuk reset password
            'password' => [
                'required_if:action,reset_password',
                'string',
                'min:8'
            ],

            'confirm_password' => [
                'required_if:action,reset_password',
                'string',
                'min:8',
                'same:password'
            ],

        ];
    }

    protected function uniqueNamePerUserRule(): Unique
    {
        return Rule::unique('users', 'name')
            ->where('user_id', $this->user()->id)
            ->ignore($this->route('user'));
    }
}
