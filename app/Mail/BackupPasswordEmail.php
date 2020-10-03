<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BackupPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public function __construct($data)
    {
        $this->mailData = $data;
    }

    public function build()
    {
        return $this->from('it2.0tools@list.ru')
            ->subject('Резервный пароль')
            ->view('mail.backup_password');
    }
}
