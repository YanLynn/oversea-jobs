<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

//┌───────────────────────────────────────────────
//│ Class Name　： JobApplyInvoiceMail
//│ Created By　：Chhuani
//│ Created Date　：2020/08/17
//│ Description : Invoice mail for job apply
//├───────────────────────────────────────────────
//│ Copyright notice　：Copyright (C) 2020 Management Partners Myanmar Co.,Ltd.
//├───────────────────────────────────────────────
//│ Repair history　　：2020/08/17　-> Created New
//└───────────────────────────────────────────────
class JobApplyInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

	public $data;

	public $pdf;

	public $pdf_filename;

	/**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $pdf, $pdf_filename)
    {
		$this->data = $data;
		$this->pdf = $pdf;
		$this->pdf_filename = $pdf_filename;
	}
	
	/**
	 * @desc Build mail for pdf invoice
	 * @author Chhuani @ 2020/08/04
	 * @last_maintained Chhuani @ 2020/08/17
	 * @param  null
	 * @return void	
	 */
    public function build()
    {
        return $this->from($address = config('mail.mailers.smtp.username'), $name = 'ボーダレスワーキング運営管理者')
				->subject('【ボーダレスワーキング】仲介手数料の支払いをお願いいたします('.$this->data['management_number'].')')
				->view(['Mail.jobapply-invoice', 'Mail.jobapply-invoice_plain'])
				->with('data', $this->data) 
				->attachData($this->pdf, $this->pdf_filename, [
					'mime' => 'application/pdf',
				]);
    }
}