<?php

namespace App\Http\Controllers;

use App\Service\Authorization\Create2FAEmailService;
use Illuminate\Http\Request;

class TwoFAController extends Controller
{
    protected $service;
    public function __construct(Create2FAEmailService $service)
    {
        $this->service = $service;
    }

    public function sendEmail(Request $request)
    {
        return $this->service->handleTwoFAEmail($request['email']);
    }
}
