<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

//┌───────────────────────────────────────────────
//│ Class Name　： ScoutInterestedRecruiterMail
//│ Created By　：Myo Ko Ko
//│ Created Date　：2020/09/25
//│ Description : Success mail to recruiter if a jobseeker has interest in scout
//├───────────────────────────────────────────────
//│ Copyright notice　：Copyright (C) 2020 Management Partners Myanmar Co.,Ltd.
//├───────────────────────────────────────────────
//│ Repair history　　：2020/09/25　-> Created New
//└───────────────────────────────────────────────
class ScoutInterestedRecruiterMail extends Mailable
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
	 * @desc Build mail for recruiter scout interested by jobseeker
	 * @author Myo Ko Ko @ 2020/09/25
	 * @last_maintained Myo Ko Ko @ 2020/09/25
	 * @param  null
	 * @return void	
	 */
    public function build()
    {
		$subject = sprintf('【ボーダレスワーキング】求職者会員がスカウトに興味を示しました(%s)', $this->data['management_number']);
        return $this->from($address = config('mail.mailers.smtp.username'), $name = 'ボーダレスワーキング運営管理者')
				->subject($subject)
				->view(['Mail.scout-interested-recruiter','Mail.scout-interested-recruiter_plain'])
				->with('data', $this->data);
    }
}