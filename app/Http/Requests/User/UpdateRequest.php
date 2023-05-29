<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'manager_id' => ['nullable', 'integer', 'min:1', 'exists:users'],
            'login' => ['nullable', 'string', 'min:1', 'max:255'],
            'bitrix_link' => ['nullable', 'string', 'min:1', 'max:255'],
            'name' => ['nullable', 'string', 'min:1', 'max:255'],
            'subscribe_end' => ['nullable', 'string', 'min:1', 'max:255'],
            'status' => ['nullable', 'boolean']
        ];
    }
}
