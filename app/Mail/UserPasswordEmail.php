<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $createdPasswordData;
    public function __construct($data)
    {
        $this->createdPasswordData = $data;
    }

    public function build()
    {
        return $this->from('it2.0tools@list.ru')
            ->subject('Доступ для входа в систему IT 2.0 Tools')
            ->view('mail.createdPassword');
    }
}
