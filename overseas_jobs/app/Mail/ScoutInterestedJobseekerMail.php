<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

//┌───────────────────────────────────────────────
//│ Class Name　： ScoutInterestedJobseekerMail
//│ Created By　：Myo Ko Ko
//│ Created Date　：2020/10/15
//│ Description : Success mail to jobseeker if a jobseeker has interest in scout
//├───────────────────────────────────────────────
//│ Copyright notice　：Copyright (C) 2020 Management Partners Myanmar Co.,Ltd.
//├───────────────────────────────────────────────
//│ Repair history　　：2020/10/15　-> Created New
//└───────────────────────────────────────────────
class ScoutInterestedJobseekerMail extends Mailable
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
	 * @desc Build mail for jobseeker scout interested mail
	 * @author Myo Ko Ko @ 2020/10/15
	 * @last_maintained Myo Ko Ko @ 2020/10/15
	 * @param  null
	 * @return void	
	 */
    public function build()
    {
		$subject = sprintf('【ボーダレスワーキング】スカウトが成立しました(%s)', $this->data['management_number']);
        return $this->from($address = config('mail.mailers.smtp.username'), $name = 'ボーダレスワーキング運営管理者')
				->subject($subject)
				->view('Mail.scout-interested-jobseeker')
				->text('Mail.scout-interested-jobseeker_plain')
				->with('data', $this->data);
    }
}