<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class passwordResetSendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $sendData;
    /**
     * Create a new message instance.
     *
     * @return void 
     */
    public function __construct($sendData)
    {
        $this->sendData = $sendData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        if($this->sendData['role_id'] == 3){
            return $this->from($address = config('mail.mailers.smtp.username'), $name = 'ボーダレスワーキング運営管理者')
                ->subject('【ボーダレスワーキング】ログイン用パスワード再設定のお知らせ')
                ->view(['Mail.jobseeker-passwordResetMail-tmp', 'Mail.jobseeker-passwordResetMail-tmp_plain'])
                ->with('data', $this->sendData);
        }
        else if($this->sendData['role_id'] == 2){
            return $this->from($address = config('mail.mailers.smtp.username'), $name = 'ボーダレスワーキング運営管理者')
                ->subject('【ボーダレスワーキング】ログイン用パスワード再設定のお知らせ')
                ->view(['Mail.recruiter-passwordResetMail-tmp', 'Mail.recruiter-passwordResetMail-tmp_plain'])
                ->with('data', $this->sendData);
        }
        //return $this->view(['Mail.passwordResetSendMail','Mail.passwordResetSendMail_plain']);
    }
}
