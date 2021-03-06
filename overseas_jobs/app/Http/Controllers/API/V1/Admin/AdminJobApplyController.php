<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\V1\BaseController;
use App\Http\Controllers\API\V1\RemoveFavouriteTrait;
use App\Helpers\CustomLog;
use App\Model\JobApply;
use App\Model\Job;
use App\Model\Jobseeker;
use App\Model\Recruiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Model\PaymentTransaction;
use PDF;
use Auth;
use App\User;
use App\Mail\JobApplyInvoiceMail;
use Illuminate\Support\Facades\Mail;
class AdminJobApplyController extends BaseController
{
	use RemoveFavouriteTrait;
    //generated by Su Sandy 
    //admin job apply_list
    public function index(Request $request)
    {
        $tableData = $request->input('tableData'); 
        $filteredData = $request->input('filteredData');
        $columns = $request->input('columns');
		
		// $recruiter_status = $filteredData['recruiter_status'] ? [1,2,3] : [1, 2];

        $query = DB::table('job_applies as ja')
                ->join('jobs as j','j.id','=','ja.job_id')
                ->join('recruiters as r','r.id','=','j.recruiter_id')
                ->join('jobseekers as js','js.id','ja.jobseeker_id')
				->select(DB::raw("convert_tz(ja.job_apply_date,'+00:00','+09:00') as job_apply_date"),'r.id as recruiter_id','r.registered_place_type','ja.id','ja.id AS jobapply_id','ja.management_number','r.email AS recruiter_email',
				'r.recruiter_number','r.recruiter_name','j.id as job_id','j.job_number','j.title','js.jobseeker_number','js.jobseeker_name',
				'js.id as jobseeker_id','ja.job_apply_status');
		
        // $query->whereIn('r.record_status', $recruiter_status);
		// $query->whereIn('js.record_status', $recruiter_status); //orwhere

        if (sizeof($filteredData)>1) {
            $query->where(function($query) use ($filteredData) {
                $query->where('r.recruiter_number' ,'like', '%' . $filteredData['recruiter_no'] . '%')     
                    ->where('r.recruiter_name' ,'like', '%' . $filteredData['recruiter_name'] . '%')
                    ->where('js.jobseeker_number' ,'like', '%' . $filteredData['jobseeker_no'] . '%')
                    ->where('js.jobseeker_name' ,'like', '%' . $filteredData['jobseeker_name'] . '%')
					->where('j.title' ,'like', '%' . $filteredData['job_title'] . '%')
					->where('ja.management_number' ,'like', '%' . $filteredData['management_number'] . '%');

                    if(sizeof($filteredData['jobapply_status']) > 0) {
                        $query->wherein('ja.job_apply_status', $filteredData['jobapply_status']);
                    }

                    if($filteredData['from_date'] != '' && $filteredData['to_date'] != '') {
						$query->whereBetween(DB::raw("DATE_FORMAT(convert_tz(ja.job_apply_date,'+00:00','+09:00'), '%Y-%m-%d')"), [$filteredData['from_date'], $filteredData['to_date']]);
					}
					if($filteredData['from_date'] != '' && $filteredData['to_date'] == '') {
						$query->where(DB::raw("DATE_FORMAT(convert_tz(ja.job_apply_date,'+00:00','+09:00'), '%Y-%m-%d')"), ">=",[$filteredData['from_date']]);   
					}
					if($filteredData['from_date'] == '' && $filteredData['to_date'] != '') {
						$query->where(DB::raw("DATE_FORMAT(convert_tz(ja.job_apply_date,'+00:00','+09:00'), '%Y-%m-%d')"), "<=",[$filteredData['to_date']]);
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
					
					// if($filteredData['recruiter_status'] == true) {
					// 	$query->whereIn('r.record_status', [1,2,3]);
					// 	$query->whereIn('js.record_status', [1,2,3]); //orwhere
					// }
					// else{
					// 	$query->whereIn('r.record_status', [1,2]);
					// 	$query->whereIn('js.record_status', [1,2]); //orwhere
					// }
            });
        }
      
        $projects = $query->orderBy($columns[$tableData['column']]['label'],$tableData['dir'] )->paginate($tableData['length']);
        return response()->json($projects,200);
        
	}

 	public function changeStatus(Request $request)
	{		
		$apply = JobApply::where([
			'id' => $request->input('jobapply_id'),
		])->firstOrFail();
		$job = Job::where('id',$apply->job_id)->first();

		DB::beginTransaction();
		try {
			$apply->job_apply_status = $request->input('status');
			$apply->save();
			CustomLog::info("ChangeStatus ".$request->input('jobapply_id'));

			// --Create a payment_transaction on unclaimed status if no payment record exists (invoice)
			if($request->input('reg_place') == 'local') {
				$method = config('config.payment_method.invoice');
			}
			else{
				$method = config('config.payment_method.credit');
			}			

			if ($apply->job_apply_status == config('config.job_apply.unclaimed')) {
				$payment = new PaymentTransaction([
					'scoutid_or_applyid' => $apply->id,
					'recruiter_id' => $job->recruiter_id,
					'payment_job_type' => config('config.payment_job_type.job_apply'),
					'payment_method' => $method,
				]);
				// $payment->generateInvoiceNumber();
				$payment->save();
				CustomLog::info("Create ".$payment->id);
				
				// --Remove any favourite jobseekers or jobs
				$this->removeFavourites($job->recruiter_id, $apply->jobseeker_id, $apply->job_id);
			}
			// --Disable status change to 'billed' or 'payment_confirmed' without sending invoice to recruiter
			elseif ($apply->job_apply_status == config('config.job_apply.billed') || 
					$apply->job_apply_status == config('config.job_apply.payment_confirmed')) {
				$payment = PaymentTransaction::jobapply()
				->where('scoutid_or_applyid', $apply->id)
				->orderBy('id','DESC')
                ->first();
                
                if($payment) {
                    if($payment->payment_method == '?????????'){
                        if($apply->job_apply_status == config('config.job_apply.billed')){
                            if (!$payment->invoice_number || !$payment->invoice_date || !$payment->due_date) {
                                $payment_list = PaymentTransaction::jobapply()
                                ->where('scoutid_or_applyid', $apply->id)
                                ->orderBy('id','DESC')
                                ->get();
                                if(sizeof($payment_list) > 1 && $payment_list[1]['invoice_number'] != NULL) {
                                    $payment->delete();
                                }
                                else{
                                    throw new \Exception("????????????????????????????????????????????????????????????????????????????????????????????????");
                                }                                
                            }
                        }
                        else{
                            if($request->input('changeAble') == false){
                                if (!$payment->invoice_number || !$payment->invoice_date || !$payment->due_date) {
                                    throw new \Exception("????????????????????????????????????????????????????????????????????????????????????????????????");
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
                            throw new \Exception("????????????????????????????????????????????????????????????????????????????????????????????????");
                        }
                    }                    
                }

				if (!$payment) {
					throw new \Exception("????????????????????????????????????????????????????????????????????????????????????????????????");
				}
				// if (!$payment->invoice_date || !$payment->due_date) {
				// 	throw new \Exception("????????????????????????????????????????????????????????????????????????????????????????????????");
				// }
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			return $this->createErrorResponse($e->getMessage());
		}


		return $this->createResponse($apply);
	}

    //generated by Su Sandy 
    public function generateBill(Request $request)
	{	
		
		$JobApply = JobApply::where([
			'id' => $request->input('transaction_id'),
		])->firstOrFail();

		$data  = DB::table('job_applies AS ja')
                ->join ('jobs AS j', 'j.id', '=', 'ja.job_id')
				->join('recruiters AS r', 'r.id', '=', 'j.recruiter_id')
				->where('ja.id', $request->input('transaction_id'))
				->select('ja.*', 'r.*', 'j.title')
				->first();
		if (!$data) {
			return $this->createErrorResponse("jobApply not found.", 404);
		}

		$payment = $this->findPayment($JobApply,$request->input('invoice_amount'));
		$payment->invoice_amount = $request->input('invoice_amount');
		$payment->remark = $request->input('remark');
		$payment->tax = $request->input('tax');
		$payment->default_amount = $request->input('default_amount');
		$payment->management_number = $JobApply->management_number;
		// $payment->due_date = Carbon::now()->add(1, 'month');
		$payment->due_date = Carbon::now()->addMonth()->endOfMonth()->toDateString();

		$data->payment = $payment;

		$PDF = PDF::loadView('Mail.jobapply-invoice-pdf', [
            'data' => $data,
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

		// return view('Mail.jobapply-invoice-pdf', [
		// 	'data' => $data,
		// 	'sign' => get_sign_image_base64(),
		// ]);


		// $apply = JobApply::where([
		// 	'id' => $id,
		// ])->firstOrFail();
		// $apply->job_apply_status = config('config.job_apply.billed');
		// $apply->save();

		// return $this->createResponse($apply);
	}

    //generated by Su Sandy
    public function confirmPayment($id)
	{
    
		$apply = JobApply::where('id', $id)->firstOrFail();
		$apply->job_apply_status = config('config.job_apply.payment_confirmed');
		$apply->save();

		return $this->createResponse($apply);
    }  
    
	private function findPayment($JobApply, $invoice_amount)
	{		
		$payment = PaymentTransaction::JobApply()
					->where('scoutid_or_applyid', $JobApply->id)
					->orderBy('id','DESC')
					->first();
		if (!$payment) {
			$payment = new PaymentTransaction([
				'scoutid_or_applyid' => $JobApply->id,
				'recruiter_id' => $JobApply->recruiter_id,
				'payment_job_type' => config('config.payment_job_type.job_apply'),
				'payment_method' => config('config.payment_method.invoice'),
				'invoice_amount' => $invoice_amount,
				// 'due_date' => Carbon::now()->add(1, 'month'),
				'due_date' => Carbon::now()->addMonth()->endOfMonth()->toDateString(),
				'remark' => '',
				'payment_amount' => 0,
			]);
			
		}
		$payment->generateInvoiceNumber($JobApply->management_number);
		$payment->invoice_date = Carbon::now();
		$inv_date = explode('-', $payment->invoice_date->format('Y-m-d'));
		$payment->invoicedate = $inv_date[0].'???'.$inv_date[1].'???'.$inv_date[2].'???';
		return $payment;
	}

	public function sendInvoiceMail(Request $request)
	{
		$due_date = Carbon::now()->addMonth()->endOfMonth()->toDateString();		
		$JobApply = JobApply::where([
			'id' => $request->input('transaction_id'),
		])->firstOrFail();

		$data  = DB::table('job_applies AS ja')
                ->join ('jobs AS j', 'j.id', '=', 'ja.job_id')
				->join('recruiters AS r', 'r.id', '=', 'j.recruiter_id')
				->join('jobseekers AS js', 'js.id', '=', 'ja.jobseeker_id')
				->where('ja.id', $request->input('transaction_id'))
				->select('ja.*', 'r.*', 'j.title','r.id as recruiter_id','ja.id as jobapply_id','j.*','js.*','ja.management_number as management_number','j.application_address as job_email')
						->first();
		if (!$data) {
			return $this->createErrorResponse("jobApply not found.", 404);
		}

		$payment = $this->findPayment($JobApply,$request->input('invoice_amount'));
		$payment->invoice_amount = $request->input('invoice_amount');
		$payment->payment_job_type = config('config.payment_job_type.job_apply');
		$payment->remark = $request->input('remark');
		$payment->tax = $request->input('tax');
		$payment->tax_id = $request->input('tax_id');
		$payment->default_amount = $request->input('default_amount');
		$payment->payment_method  =config('config.payment_method.invoice');
		$payment->due_date = $due_date;
        $payment->generateInvoiceNumber($JobApply->management_number);
        $payment->management_number = $JobApply->management_number;
		$data->payment = $payment;
		// $admin_mail = Auth::user()->email; 
		$admin = User::where('role_id',1)->select('email','extra')->first();
		
		if($admin->extra != null && $admin->extra != '') {
			$extra = array_merge(array($admin->email),\explode(',',$admin->extra));
		}
		else{
			$extra = array($admin->email);
		}
		
		$PDF = PDF::loadView('Mail.jobapply-invoice-pdf', [ 
			'data' => $data,
			'sign' => get_sign_image_base64(),
		]);
		$mail_para = [
			'name' => $data->recruiter_name,
			'management_number' => $data->management_number,
			'job_title' => $data->title,
			'occupation_description' => $data->occupation_description ,
			'job_location' => $data->job_location,
			'job_apply_status' => $data->job_apply_status,
			'seeker_number' => $data->jobseeker_number,
			'seeker_name' => $data->jobseeker_name,
			'seeker_furigana' => $data->jobseeker_furigana_name,
			'job_email' => $data->job_email,
			'tax' => $request->input('tax'),
			'default_amount' => $request->input('default_amount'),
			'invoice_amount' => $request->input('invoice_amount'),
			'admin_email' => $admin->email,
		];

		try {
			$today = Carbon::now();
			$pdf_filename = $payment->invoice_number . '.pdf';
			Mail::to($data->job_email)
				->cc($extra)
				->send(new JobApplyInvoiceMail($mail_para, $PDF->output(), $pdf_filename));
			
        } catch (\Exception $exception) {
			CustomLog::error($exception->getMessage());
			return $this->createErrorResponse($exception->getMessage());
		}
		// --tax and default_amount are for display only. We don't save them in DB
		unset($payment->tax);
		unset($payment->default_amount);
		unset($payment->invoicedate);
		unset($payment->management_number);
		$payment->save();
		CustomLog::info("Create ".$payment->id);

		$JobApply->job_apply_status = config('config.job_apply.billed');
		$JobApply->save();
		CustomLog::info("ChangeStatus ".$request->input('transaction_id'));

		return $this->createResponse($JobApply);
	}
}
