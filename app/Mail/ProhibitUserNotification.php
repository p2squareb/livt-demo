<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProhibitUserNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $nickname, $period_type, $until_date, $cause;

    public function __construct($nickname, $period_type, $until_date, $cause)
    {
        $this->nickname = $nickname;
        $this->period_type = $period_type;
        $this->until_date = $until_date;
        $this->cause = $cause;
    }

    public function build()
    {
        return $this->view('emails.prohibit-user')
            ->with([
                'nickname' => $this->nickname,
                'period_type' => $this->period_type,
                'until_date' => $this->until_date,
                'cause' => $this->cause,
            ])
            ->subject('[' . cache('config.basic')->basic->site_name . '] 사이트 이용 제한');
    }
}
