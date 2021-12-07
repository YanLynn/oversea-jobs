<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminContentMail extends Mailable
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
                ->subject($this->data['subject'])
                ->view(['Mail.content-admin-tmp', 'Mail.content-admin-tmp_plain'])
                ->with('data', $this->data);
    }
}
