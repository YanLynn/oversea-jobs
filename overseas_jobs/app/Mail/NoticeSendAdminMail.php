<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoticeSendAdminMail extends Mailable
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
        $subject = sprintf('【ボーダレスワーキング】採否の通知がありました(%s)', $this->data['management_number']);
		return $this->from($address = config('mail.mailers.smtp.username'), $name = 'ボーダレスワーキング運営管理者')
                ->subject($subject)
                ->view(['Mail.noticeAcceptanceAdmin','Mail.noticeAcceptanceAdmin_plain'])
                ->with('data', $this->data);
    }
}
