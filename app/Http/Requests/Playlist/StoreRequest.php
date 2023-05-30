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
            'styles.*.storage_style' => ['required', 'string', 'min:1', 'max:255'],
            'styles.*.chance' => ['required', 'integer', 'min:1', 'max:100'],
            'styles.*.datetime' => ['required', 'string', 'min:1', 'max:255'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'datetime_start' => ['required', 'string', 'min:1', 'max:255'],
            'datetime_end' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }
}
