<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TempPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $nickname, $tempPassword;

    public function __construct($nickname, $tempPassword)
    {
        $this->nickname = $nickname;
        $this->tempPassword = $tempPassword;
    }

    public function build()
    {
        return $this->view('emails.temp-password')
            ->with([
                'nickname' => $this->nickname,
                'tempPassword' => $this->tempPassword
            ])
            ->subject('[' . cache('config.basic')->basic->site_name . '] 임시 비밀번호 발급');
    }
}
