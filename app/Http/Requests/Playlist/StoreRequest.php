<?php

namespace App\Http\Requests\Playlist;

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
            'styles' => ['required', 'array'],
            'styles.*.storage_style' => ['required', 'string', 'min:1', 'max:255'],
            'styles.*.chance' => ['required', 'integer', 'min:1', 'max:100'],
            'styles.*.time' => ['required', 'date'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'time_start' => ['required', 'date'],
            'time_end' => ['required', 'date'],
        ];
    }
}
