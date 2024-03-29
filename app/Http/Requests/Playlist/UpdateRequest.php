<?php

namespace App\Http\Requests\Playlist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function rules(Request $request)
    {
        return [
            'styles' => ['required', 'array'],
            'styles.*.storage_style' => ['required', 'string', 'min:1', 'max:1024'],
            'styles.*.chance' => ['required', 'integer', 'min:1', 'max:100'],
            'styles.*.time' => ['required', 'date_format:H:i:s'],
            'ads' => [Rule::requiredIf(count($request->ads) > 0), 'array'],
            'ads.*.ad_id' => ['required', 'integer', 'exists:ads,id'],
            'ads.*.time' => ['required', 'date_format:H:i:s'],
            'ads.*.use_any' => ['required', 'boolean'],
            'name' => ['nullable', 'string', 'min:1', 'max:255'],
            'description' => ['nullable', 'string'],
            'time_start' => ['nullable', 'date_format:H:i:s'],
            'time_end' => ['nullable', 'date_format:H:i:s'],
        ];
    }
}
