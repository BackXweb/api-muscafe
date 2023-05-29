<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'manager_id' => ['required', 'integer', 'min:1', 'exists:users,id'],
            'login' => ['required', 'string', 'min:1', 'max:255', 'unique:users'],
            'bitrix_link' => ['required', 'string', 'min:1', 'max:255'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'subscribe_end' => ['nullable', 'string', 'min:1', 'max:255'],
        ];
    }
}
