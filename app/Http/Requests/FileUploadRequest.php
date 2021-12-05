<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'files'   => ['required', 'array', 'max:30'],
            'files.*' => ['required', 'mimes:jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function messages()
    {
        return [
            'files.*.required' => trans('validation.file_required'),
            'files.*.max'      => trans('validation.file_max', ['max' => 5120]),
            'files.*.mimes'    => trans('validation.file_mimes', ['values' => 'jpg,jpeg,png']),
        ];
    }
}
