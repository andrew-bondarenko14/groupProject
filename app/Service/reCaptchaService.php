<?php


namespace App\Service;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class reCaptchaService
{
    public function handleRecaptchaCheck($token, $ip)
    {
        if (config('recaptcha.enabled') && !$this->reCaptchaCheck($token, $ip)) {
            return 'Ошибка капчи';
        }
    }

    private function reCaptchaCheck($token, $ip) {
        $response = (new Client)->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret'   => '6LcgV_EUAAAAAE-a-xX1DymKE33rwOCjmWsfmTKK',
                'response' => $token,
                'remoteip' => $ip,
            ],
        ]);
        $response = json_decode((string)$response->getBody(), true);

        return $response['success'];
    }
}
