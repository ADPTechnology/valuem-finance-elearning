<?php

namespace App\Http\Requests\SpecCourse;

use Illuminate\Foundation\Http\FormRequest;

class SpecCourseRequest extends FormRequest
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
            'title'         => ['required', 'max:100'],
            'subtitle'      => ['nullable', 'max:100'],
            'date'          => ['required'],
            'time_start'    => ['required'],
            'time_end'      => ['required'],
            'image'         => ['nullable'],
            'active'        => ['nullable'],
            'course_type_id'=> ['nullable'],
        ];
    }
}
