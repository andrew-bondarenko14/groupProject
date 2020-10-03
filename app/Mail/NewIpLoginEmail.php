<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewIpLoginEmail extends Mailable
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
            ->subject('Вход с нового IP адреса в систему IT 2.0 Tools')
            ->view('mail.security.newIpLogin');
    }
}
