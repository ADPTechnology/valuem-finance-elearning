<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebinarEventRequest extends FormRequest
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
            "description" => ['required', 'max:255'],
            "date" => ['required'],
            "time_start" => ['required'],
            "time_end" => ['required'],
            "user_id" => ['required'],
            "responsable_id" => ['required'],
            "room_id" => ['required'],
            "active" => ['nullable'],
        ];
    }
}
