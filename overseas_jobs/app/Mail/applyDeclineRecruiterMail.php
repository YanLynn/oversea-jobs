<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class applyDeclineRecruiterMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = config('mail.mailers.smtp.username'), $name = 'ボーダレスワーキング運営管理者')
        ->subject('【ボーダレスワーキング】求職者会員が求人への問合わせ/応募を辞退しました('.$this->data['management_number'].')')
        ->view(['Mail.applyDeclineRecruiter', 'Mail.applyDeclineRecruiter_plain'])
        ->with('data', $this->data);
    }
}
