<?php

namespace App\Http\Controllers\API\V1\Admin;
use App\Http\Controllers\API\V1\BaseController;
use App\Http\Controllers\API\V1\RemoveFavouriteTrait;
use Illuminate\Http\Request;
use App\Helpers\CustomLog;
use DB;
use Auth;
use Carbon\Carbon;
use PDF;
use App\Model\Job;
use App\User;
use App\Model\Scout;
use App\Model\Recruiter;
use App\Model\PaymentTransaction;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScoutInvoiceMail;
use HTTP_Request2;
use XML_Serializer;
use XML_Unserializer;
use App\Model\Taxes;
use App\services\CustomCryption;

class AdminScoutController extends BaseController
{
	use RemoveFavouriteTrait;
    public function index(Request $request)
    {        
        $tableData = $request->input('tableData');
        $filteredData = $request->input('filteredData');
		$columns = $request->input('columns');

		// $recruiter_status = $filteredData['recruiter_status'] == true ? [1,2,3] : [1,2];
		
        $query  = DB::table('scouts AS s')
                ->join ('jobs AS j', 'j.id', '=', 's.job_id')
                ->join('jobseekers AS js', 'js.id', '=', 's.jobseeker_id')
				->join('recruiters AS r', 'r.id', '=', 's.recruiter_id')
				->where('s.scout_status', '!=', config ('config.scouts.waiting'))				
				->where('s.record_status','1')
				->where('j.job_post_status','post')
				->select(DB::raw("convert_tz(s.scouted_date,'+00:00','+09:00') as scouted_date"),'r.id as recruiter_id','r.registered_place_type','s.id','s.management_number','r.recruiter_number as recruiter_number','r.recruiter_name', 'r.email AS recruiter_email',
				'j.id as job_id', 'j.job_number', 'j.title','js.jobseeker_number as jobseeker_number','js.jobseeker_name','js.id as jobseeker_id','s.scout_status','s.jobseeker_id','s.recruiter_id','s.job_id');

				// ->where('js.record_status','<>','3')
				// ->where('r.record_status','<>','3')

            $query->where(function($query) use ($filteredData) {
                $query->where('r.recruiter_number' ,'like', '%' . $filteredData['recruiter_id'] . '%')
                    ->where('r.recruiter_name' ,'like', '%' . $filteredData['recruiter_name'] . '%')
                    ->where('j.job_number' ,'like', '%' . $filteredData['job_number'] . '%')
                    ->where('j.title' ,'like', '%' . $filteredData['job_title' ] . '%')
					->where('js.jobseeker_name' ,'like', '%' . $filteredData['jobseeker_name'] . '%')
					->where('js.jobseeker_number' ,'like', "%{$filteredData['jobseeker_number']}%")
					->where('s.management_number' ,'like', "%{$filteredData['management_number']}%");

                    if(sizeof($filteredData['scout_status']) > 0) {
                        $query->wherein('s.scout_status', $filteredData['scout_status']);
                    }
                    if($filteredData['from_date'] != '' && $filteredData['to_date'] != '') {
						$query->whereBetween(DB::raw("DATE_FORMAT(convert_tz(s.scouted_date,'+00:00','+09:00'), '%Y-%m-%d')"), [$filteredData['from_date'], $filteredData['to_date']]);
					}
					if($filteredData['from_date'] != '' && $filteredData['to_date'] == '') {
						$query->where(DB::raw("DATE_FORMAT(convert_tz(s.scouted_date,'+00:00','+09:00'), '%Y-%m-%d')"), ">=",[$filteredData['from_date']]);   
					}
					if($filteredData['from_date'] == '' && $filteredData['to_date'] != '') {
						$query->where(DB::raw("DATE_FORMAT(convert_tz(s.scouted_date,'+00:00','+09:00'), '%Y-%m-%d')"), "<=",[$filteredData['to_date']]);
					}

					if($filteredData['recruiter_status_deactivate'] == true && $filteredData['recruiter_status_stop'] == true) {
						$query->whereIn('r.record_status', [1,2,3]);
						$query->whereIn('js.record_status', [1,2,3]);
					}
					else if($filteredData['recruiter_status_deactivate'] == false && $filteredData['recruiter_status_stop'] == false) {
						$query->whereIn('r.record_status', [1]);
						$query->whereIn('js.record_status', [1]); //orwhere
					}
					else if($filteredData['recruiter_status_deactivate'] == true && $filteredData['recruiter_status_stop'] == false) {
						$query->whereIn('r.record_status', [1,3]);
						$query->whereIn('js.record_status', [1,3]); //orwhere
					}
					else if($filteredData['recruiter_status_deactivate'] == false && $filteredData['recruiter_status_stop'] == true) {
						$query->whereIn('r.record_status', [1,2]);
						$query->whereIn('js.record_status', [1,2]); //orwhere
					}			
			});

		$projects = $query->orderBy('s.created_at',$tableData['dir'])->paginate($tableData['length']);
		// $projects = $query->orderBy($columns[$tableData['column']]['label'], $tableData['dir'])->paginate($tableData['length']);
        return response()->json($projects,200);
	}
	
	/**
	 * @desc Generate invoice preview for sending PDF mail
	 * @author Myo Ko Ko @ 2020/08/04
	 * @last_maintained Myo Ko Ko @ 2020/08/04
	 * @param  Request $request
	 * @return Response
	 */
	public function generateBill(Request $request)
	{
		$scout = Scout::where([
			'id' => $request->input('transaction_id'),
		])->firstOrFail();
		
		$scout  = DB::table('scouts AS s')
                ->join ('jobs AS j', 'j.id', '=', 's.job_id')
                ->join('jobseekers AS js', 'js.id', '=', 's.jobseeker_id')
				->join('recruiters AS r', 'r.id', '=', 's.recruiter_id')
				->where('s.id', $request->input('transaction_id'))
				->select('s.*', 'r.*', 'j.title')
				->first();
		if (!$scout) {
			return $this->createErrorResponse("Scout not found.", 404);
		}

		$payment = $this->findPayment($scout,$request->input('invoice_amount'));
		$payment->invoice_amount = $request->input('invoice_amount');
		$payment->remark = $request->input('remark');
		$payment->tax = $request->input('tax');
		$payment->default_amount = $request->input('default_amount');
		$payment->management_number = $scout->management_number;

		$scout->payment = $payment;

		$PDF = PDF::loadView('Mail.scout-invoice-pdf', [
            'data' => $scout,
            'sign' => get_sign_image_base64(),
		]);

		$pdf_filename = $payment->invoice_number . '.pdf';
		return response()->json(
			[
				'data' => [
					'pdf' => base64_encode($PDF->output()),
					'filename' => $pdf_filename,
				],
			]
			// Response::HTTP_OK
		);

		// return view('Mail.scout-invoice-pdf', [
		// 	'data' => $scout,
		// 	'sign' => get_sign_image_base64(),
		// ]);
	}

	/**
	 * @desc Generate and send PDF invoice
	 * @author Myo Ko Ko @ 2020/08/04
	 * @last_maintained Myo Ko Ko @ 2020/08/04
	 * @param  Request
	 * @return json
	 */
	public function sendInvoiceMail(Request $request)
	{
		$scout = Scout::where([
			'id' => $request->input('transaction_id'),
		])->firstOrFail();

		$data  = DB::table('scouts AS s')
                ->join ('jobs AS j', 'j.id', '=', 's.job_id')
                ->join('jobseekers AS js', 'js.id', '=', 's.jobseeker_id')
				->join('recruiters AS r', 'r.id', '=', 's.recruiter_id')
				->where('s.id', $request->input('transaction_id'))
				->select('s.*', 'r.*', 'j.title','s.management_number as s_management_number','js.*','j.application_address as job_email')
				->first();
		if (!$data) {
			return $this->createErrorResponse("Scout not found.", 404);
		}

		$payment = $this->findPayment($scout,$request->input('invoice_amount'));		
		$payment->invoice_amount = $request->input('invoice_amount');
		$payment->payment_job_type = config('config.payment_job_type.scout');
		$payment->payment_method = config('config.payment_method.invoice');
		// $payment->due_date = Carbon::now()->add(1, 'month');
		$payment->due_date = Carbon::now()->addMonth()->endOfMonth()->toDateString();
		$payment->generateInvoiceNumber($data->s_management_number);
		$payment->remark = $request->input('remark');
		$payment->tax_id = $request->input('tax_id');
		$payment->tax = $request->input('tax');
        $payment->default_amount = $request->input('default_amount');
        $payment->management_number = $data->s_management_number;
		$data->payment = $payment;
		//$admin_mail = Auth::user()->email;
		$admin = User::where('role_id',1)->select('email','extra')->first();
		
		if($admin->extra != null && $admin->extra != '') {
			$extra = array_merge(array($admin->email),\explode(',',$admin->extra));
		}
		else{
			$extra = array($admin->email);
		}
		$PDF = PDF::loadView('Mail.scout-invoice-pdf', [ 
			'data' => $data,
			'sign' => get_sign_image_base64(),
		]); 
		$mail_para = [
			'name' => $data->recruiter_name,
			'management_number' => $data->s_management_number,
			'job_title' => $data->title,
			'seeker_number' => $data->jobseeker_number,
			'seeker_name' => $data->jobseeker_name,
			'seeker_furigana' => $data->jobseeker_furigana_name, 
			'tax' => $request->input('tax'),
			'default_amount' => $request->input('default_amount'),
			'invoice_amount' => $request->input('invoice_amount'),
			'job_email' => $data->job_email,
			'admin_email' => $admin->email,
		];
		try {
			$today = Carbon::now();
			$pdf_filename = $payment->invoice_number . '.pdf';
			Mail::to($data->job_email)
				->cc($extra)
				->send(new ScoutInvoiceMail($mail_para, $PDF->output(), $pdf_filename));

        } catch (\Exception $exception) {
			CustomLog::error($exception->getMessage());
			return $this->createErrorResponse($exception->getMessage());
		}
		// --tax and default_amount are for display only. We don't save them in DB
		unset($payment->tax);
		unset($payment->default_amount);
		unset($payment->invoicedate);
		unset($payment->management_number);
		// return $payment;
		$payment->save();
		CustomLog::info("Create ".$payment->id);

		$scout->scout_status = config('config.scouts.billed');
		$scout->save();
		CustomLog::info("ChangeStatus ".$request->input('transaction_id'));

		return $this->createResponse($scout);
	}

	private function findPayment($scout,$invoice_amount)
	{
		$payment = PaymentTransaction::scout()
					->where('scoutid_or_applyid', $scout->id)
					->orderBy('id','DESC')
					->first();
		
		if (!$payment) {
			
			$payment = new PaymentTransaction([
				'scoutid_or_applyid' => $scout->id,
				'recruiter_id' => $scout->recruiter_id,
				'payment_job_type' => config('config.payment_job_type.scout'),
				'payment_method' => config('config.payment_method.invoice'),
				'invoice_amount' => $invoice_amount,
				// 'due_date' => Carbon::now()->add(1, 'month'),
				'due_date' => Carbon::now()->addMonth()->endOfMonth()->toDateString(),				
				'remark' => '',
				'payment_amount' => 0,
			]);
			// $payment->generateInvoiceNumber();
		}
		$payment->generateInvoiceNumber($scout->management_number);
		$payment->invoice_date = Carbon::now();
		$inv_date = explode('-', $payment->invoice_date->format('Y-m-d'));
		$payment->invoicedate = $inv_date[0].'年'.$inv_date[1].'月'.$inv_date[2].'日';
		$payment->due_date = Carbon::now()->addMonth()->endOfMonth()->toDateString();
		// $payment->due_date = Carbon::now()->add(1, 'month');
		return $payment;
	}

	/**
	 * @desc Change scout status
	 * @author Myo Ko Ko @ 2020/08/04
	 * @last_maintained Myo Ko Ko @ 2020/08/04
	 * @param  Request $request
	 * @return json
	 */
	public function changeStatus(Request $request)
	{
		$scout = Scout::where([
			'id' => $request->input('scout_id'),
		])->firstOrFail();

		DB::beginTransaction();
		try {
			$scout->scout_status = $request->input('status');
			$scout->save();
			CustomLog::info("Update ".$request->input('scout_id'));

			// --Create a payment_transaction on unclaimed status
			if($request->input('reg_place') == 'local') {
				$method = config('config.payment_method.invoice');
			}
			else{
				$method = config('config.payment_method.credit');
			}

			if ($scout->scout_status == config('config.scouts.unclaimed')) {
				$payment = new PaymentTransaction([
					'scoutid_or_applyid' => $scout->id,
					'recruiter_id' => $scout->recruiter_id,
					'payment_job_type' => config('config.payment_job_type.scout'),
					'payment_method' => $method,
				]);
				$payment->save();
				CustomLog::info("Create ".$payment->id);

				// --Remove any favourite jobseekers or jobs
				$this->removeFavourites($scout->recruiter_id, $scout->jobseeker_id, $scout->job_id);
			}
			// --Disable status change to 'billed' or 'payment_confirmed' without sending invoice to recruiter
			elseif ($scout->scout_status == config('config.scouts.billed') || 
					$scout->scout_status == config('config.scouts.payment_confirmed')) {
				$payment = PaymentTransaction::scout()
					->where('scoutid_or_applyid', $scout->id)
					->orderBy('id','DESC')
                    ->first();
                if($payment) {
                    if($payment->payment_method == 'クレカ'){
                        if($scout->scout_status == config('config.scouts.billed')){
                            if (!$payment->invoice_number || !$payment->invoice_date || !$payment->due_date) {
                                $payment_list = PaymentTransaction::scout()
                                ->where('scoutid_or_applyid', $scout->id)
                                ->orderBy('id','DESC')
                                ->get();
                                if(sizeof($payment_list) > 1 && $payment_list[1]['invoice_number'] != NULL) {
                                    $payment->delete();
                                }
                                else{
                                    throw new \Exception("これまで一度も請求したことがないため、請求済へは変更できません。");
                                }                                
                            }
                        }
                        else{
                            if($request->input('changeAble') == false){
                                if (!$payment->invoice_number || !$payment->invoice_date || !$payment->due_date) {
                                    throw new \Exception("これまで一度も請求したことがないため、請求済へは変更できません。");
                                }
                            }
                            else{
                                if (!$payment->invoice_number || !$payment->invoice_date || !$payment->due_date) {
                                    $payment->delete();
                                }                            
                            }
                        }                        
                    }
                    else {
                        if (!$payment->invoice_number || !$payment->invoice_date || !$payment->due_date) {
                            throw new \Exception("これまで一度も請求したことがないため、請求済へは変更できません。");
                        }
                    }                    
                }                

				if (!$payment) {
					throw new \Exception("これまで一度も請求したことがないため、請求済へは変更できません。");
				}
				
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			return $this->createErrorResponse($e->getMessage());
		}		
		return $this->createResponse($scout);
	}

	public function confirmPayment(Request $request)
	{
		$scout = Scout::where([
			'id' => $request->input('scout_id'),
		])->firstOrFail();
		$scout->scout_status = config('config.scouts.payment_confirmed');
		$scout->save();
        CustomLog::info("ChangeStatus ".$request->input('scout_id'));

		return $this->createResponse($scout);
	}

	
	public function taxes()
	{

		return response()->json(Taxes::all());
	}

} 