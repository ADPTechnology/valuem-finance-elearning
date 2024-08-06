<?php

namespace App\Http\Requests\PrincipalBanner;

use Illuminate\Foundation\Http\FormRequest;

class PBannerRequestStore extends FormRequest
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
            "title" => ['nullable', 'max:100'],
            "content" => ['nullable', 'max:6000'],
            "status" => ['nullable']
        ];
    }
}
