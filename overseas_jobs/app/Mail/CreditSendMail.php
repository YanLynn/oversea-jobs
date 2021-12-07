<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreditSendMail extends Mailable
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
        $subject = sprintf('【ボーダレスワーキング】仲介手数料の支払いをお願いいたします(%s)', $this->data['management_number']);
		return $this->from($address = config('mail.mailers.smtp.username'), $name = 'ボーダレスワーキング運営管理者')
                ->subject($subject)
                ->view(['Mail.credit-sendMail','Mail.credit-sendMail_plain'])
                ->with('data', $this->data);
    }
}
