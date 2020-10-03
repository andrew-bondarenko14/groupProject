<?php


namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AvatarUpdateRequest extends FormRequest
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
            'file' => 'bail|file|mimes:bmp,jpg,jpeg|max:2097152',
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'Максимальный размер загружаемого файла 2мб',
            'file.file' => 'Отправляемые данный должны быть файлом',
            'file.mimes' => 'Поддерживаемые типы отправляемого файла: bmp,jpg,jpeg',
        ];
    }
}
