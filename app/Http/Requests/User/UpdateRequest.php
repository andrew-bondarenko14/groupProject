<?php

namespace App\Http\Requests\User;

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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'bail|required|string|max:255',
            'surname' => 'bail|required|string|max:255',
            'middle_name' => 'max:255',
            'birthday' => 'bail|date|required',
            'hired_at' => 'date',
            'skype_login' => 'bail|max:255|unique:users,skype_login,' . $this->id,
            'email' => 'bail|required|email:filter|unique:users,email,' . $this->id,
            'mobile_phone' => 'bail|regex:/^[0-9]+$/|max:12|unique:users,mobile_phone,'
                . $this->id . '|unique:users,work_phone,' . $this->id,
            'work_phone' => 'bail|regex:/^[0-9]+$/|max:12|unique:users,work_phone,'
                . $this->id . '|unique:users,mobile_phone,' . $this->id,
            'position' => 'bail|required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле обязательно к заполнению',
            'name.string' => 'Поле обязано быть строкой',
            'name.max' => 'Максимальное количество символов - :max',
            'surname.required' => 'Поле обязательно к заполнению',
            'surname.string' => 'Поле обязано быть строкой',
            'surname.max' => 'Максимальное количество символов - :max',
            'middle_name.max' => 'Максимальное количество символов - :max',
            'birthday.required' => 'Поле обязательно к заполнению',
            'skype_login.max' => 'Максимальное количество символов - :max',
            'skype_login.unique' => 'Данный skype логин уже зарегистрирован в системе',
            'email.required' => 'Поле обязательно к заполнению',
            'email.email' => 'Поле должно содержать корректный электронный адрес',
            'email.unique' => 'Данный Email уже используется',
            'birthday.between' => 'Данные должны быть в диапазоне 1901 - 2155',
            'hired_at.between' => 'Данные должны быть в диапазоне 1901 - 2155',
            'mobile_phone.regex' => 'Телефон должен содержать только цифры',
            'work_phone.regex' => 'Телефон должен содержать только цифры',
            'mobile_phone.max' => 'Телефон должен содержать не более 12 цифр',
            'mobile_phone.unique' => 'Данный телефон уже зарегистрирован в системе',
            'work_phone.max' => 'Телефон должен содержать не более 12 цифр',
            'work_phone.unique' => 'Данный телефон уже зарегистрирован в системе',
            'position.required' => 'Поле обязательно к заполнению',
            'position.max' => 'Максимальное количество символов - :max',
        ];
    }
}
