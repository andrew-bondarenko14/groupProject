<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecurityChangePassEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $warningData;
    public function __construct($data)
    {
        $this->warningData = $data;
    }

    public function build()
    {
        return $this->from('it2.0tools@list.ru')
            ->subject('Смена пароля с нового IP')
            ->view('mail.security.changePassword');
    }
}
