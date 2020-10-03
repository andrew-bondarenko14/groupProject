<?php


namespace App\Http\Requests\departments;

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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:departments,name,' . $this->id,
            'director_user_id' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'director_user_id.required' => 'Поле обязательно к заполнению',
            'name.required' => 'Поле обязательно к заполнению',
            'name.unique' => 'Отдел с таким названием уже существует',
        ];
    }
}
