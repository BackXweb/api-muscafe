<?php

namespace App\Http\Requests\Playlist;

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
            'styles.*.storage_style' => ['required', 'integer', 'min:1'],
            'styles.*.chance' => ['required', 'integer', 'min:1', 'max:100'],
            'styles.*.time' => ['required', 'string', 'min:1', 'max:255'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'time_start' => ['required', 'string', 'min:1', 'max:255'],
            'time_end' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }
}
