<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecoveryEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $recoveryData;
    public function __construct($data)
    {
        $this->recoveryData = $data;
    }

    public function build()
    {
        return $this->from('it2.0tools@list.ru')
            ->subject('Подтверждение смены пароля в системе IT 2.0 Tools')
            ->view('mail.recovery');
    }
}
