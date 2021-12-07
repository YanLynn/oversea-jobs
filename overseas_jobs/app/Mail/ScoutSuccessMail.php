<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

//┌───────────────────────────────────────────────
//│ Class Name　： ScoutSuccessMail
//│ Created By　：Myo Ko Ko
//│ Created Date　：2020/09/24
//│ Description : Success mail to jobseeker if a recruiter has scouted
//├───────────────────────────────────────────────
//│ Copyright notice　：Copyright (C) 2020 Management Partners Myanmar Co.,Ltd.
//├───────────────────────────────────────────────
//│ Repair history　　：2020/09/24　-> Created New
//└───────────────────────────────────────────────
class ScoutSuccessMail extends Mailable
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
	 * @desc Build mail for scout success
	 * @author Myo Ko Ko @ 2020/09/24
	 * @last_maintained Myo Ko Ko @ 2020/09/24
	 * @param  null
	 * @return void	
	 */
    public function build()
    {
		$subject = sprintf('【ボーダレスワーキング】スカウトがありました(%s)', $this->data['management_number']);
        return $this->from($address = config('mail.mailers.smtp.username'), $name = 'ボーダレスワーキング運営管理者')
				->subject($subject)
				->view(['Mail.scout-success', 'Mail.scout-success_plain'])
				->with('data', $this->data);
    }
}