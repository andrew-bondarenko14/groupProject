<?php

namespace App\Http\Requests\userSettings;

use Illuminate\Foundation\Http\FormRequest;

class ChangeSettings extends FormRequest
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
            'two_step_authentication' => 'boolean',
            'security_notifications' => 'boolean',
            'backup_password' => 'boolean',
            'codeword' => 'nullable|string|min:3'

        ];
    }
}
