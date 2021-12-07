<?php

namespace App\Http\Controllers\API\V1\Admin;
use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use App\Helpers\CustomLog;
use DB;
use Auth;
use Carbon\Carbon;
use App\services\CustomCryption;
use App\Model\Scout;
use App\Model\JobApply;
use App\Model\Job;
use App\Model\PaymentTransaction;
use App\Model\Recruiter;
use App\Model\Jobseeker;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreditSendMail;
use HTTP_Request2;
use XML_Serializer;
use XML_Unserializer;
use App\User;
use PDF;

//┌───────────────────────────────────────────────
//│ Controller Name　： AdminPaymentController
//│ Created By　： Myo Ko Ko
//│ Created Date　：2020/08/11
//│ Description : Payment transaction management for Admin
//├───────────────────────────────────────────────
//│ Copyright notice　：Copyright (C) 2020 Management Partners Myanmar Co.,Ltd.
//├───────────────────────────────────────────────
//│ Repair history　　：2020/08/11　-> Created New
//└───────────────────────────────────────────────
class AdminPaymentController extends BaseController
{
	/**
	 * @desc Get payment list for admin
	 * @author Myo Ko Ko @ 2020/08/11
	 * @last_maintained Myo Ko Ko @ 2020/08/11
	 * @param  Request $request
	 * @return json
	 */
	public function index(Request $request)
	{
		$tableData = $request->input('tableData');
		$query = $this->buildPaymentTransactionQuery($request);
		$data = $query->paginate($tableData['length']);
        return response()->json($data, 200);
	}

	public function update(int $id, Request $request)
	{
		$payment = PaymentTransaction::findOrFail($id);
		
		$payment->payment_amount = $request->input('payment_amount');
		$payment->actual_payment_date = $request->input('actual_payment_date');
		$payment->remark = $request->input('remark');
		$payment->save();
		return $this->createResponse($payment);
	}

	public function exportCsv(Request $request)
	{
		$fileName = sprintf('payment_transactions_%s.csv', date('Y_m_d_His'));
		$query = $this->buildPaymentTransactionQuery($request);
		/*
		return (new PaymentExport($query))->download($fileName, \Maatwebsite\Excel\Excel::CSV, [
			'Access-Control-Allow-Origin' => '*',
			'Access-Control-Allow-Methods' => '*',
			"Access-Control-Expose-Headers" => "*",
			'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With, Application',
			'Access-Control-Allow-Credentials' => 'true',
		]);
		*/		
		$rows = $query->get();
		$headers = [
			"Content-type"        => "text/csv",
			"Content-Disposition" => "attachment; filename=$fileName",
			"Pragma"              => "no-cache",
			"Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
			"Expires"             => "0",
			"Access-Control-Expose-Headers" => "Content-Disposition",
		];

		$columns = [ "管理番号","請求方法","請求書番号","ステータス","請求金額","入金期日","入金金額", "入金日", "企業名", "電話番号", "メールアドレス","担当者名","備考",];

		$callback = function() use($rows, $columns) {
			$file = fopen('php://output', 'w');
			// --add BOM to fix UTF-8 in Excel 
			fputs($file, chr(0xEF).chr(0xBB).chr(0xBF));
			fputcsv($file, $columns);

			foreach ($rows as $row) {
				fputcsv($file, [ 
					$row->management_number, 
					$row->payment_method,
					$row->invoice_number,
					$row->status,
					number_format($row->invoice_amount),
					$row->invoice_date,
					number_format($row->payment_amount),
					$row->actual_payment_date,
					$row->recruiter_name,
					$row->phone1,
					$row->email,
					$row->incharge_name,
					$row->remark,
				]);
			}

			fclose($file);
		};

		return response()->stream($callback, 200, $headers);
	}

	private function buildPaymentTransactionQuery(Request $request)
	{
		$discarded_scout_status = [
			config('config.scouts.waiting'),
			config('config.scouts.interested'),
			config('config.scouts.declined'),
			config('config.scouts.expired'),
		];
		$discarded_jobapply_status = [
			config('config.job_apply.under_review'),
			config('config.job_apply.declined'),
		];
		$tableData = $request->input('tableData'); 
        $filteredData = $request->input('filteredData');
		$columns = $request->input('columns');
		
		$latestPaymentQuery = DB::table('payment_transactions')
								->select('scoutid_or_applyid','payment_job_type',DB::raw('MAX(id) AS id'))
								->groupBy('scoutid_or_applyid','payment_job_type');

        $query  = DB::table('payment_transactions AS p')
                ->leftJoin('scouts AS s', function ($join) {
					$join->on('p.scoutid_or_applyid', '=', 's.id')
						->where('p.payment_job_type', config('config.payment_job_type.scout'));
				})
				->leftJoin('job_applies AS a', function ($join) {
					$join->on('p.scoutid_or_applyid', '=', 'a.id')
						->where('p.payment_job_type', config('config.payment_job_type.job_apply'));
				})
				->join('recruiters AS r', 'r.id', '=', 'p.recruiter_id')
				->joinSub($latestPaymentQuery, 't2', function($join) {
					$join->on('p.id','=','t2.id');
				})
				->where(function($query) use ($discarded_jobapply_status, $discarded_scout_status) {
					$query->whereNotIn('s.scout_status', $discarded_scout_status);
					$query->orWhereNotIn('a.job_apply_status', $discarded_jobapply_status);
				})
				->select('p.*', DB::raw('IFNULL(s.management_number, a.management_number) AS management_number'),
					DB::raw('IFNULL(s.scout_status, a.job_apply_status) AS status'), 'r.recruiter_name', 'r.phone1', 'r.email', 'r.incharge_name');

        if (sizeof($filteredData) > 1) {
            $query->where(function($query) use ($filteredData) {

				if (sizeof($filteredData['record_status']) > 0) {
					$query->where(function ($sub) use ($filteredData) {
						$sub->whereIn('s.scout_status', $filteredData['record_status']);
						$sub->orWhereIn('a.job_apply_status', $filteredData['record_status']);
					});					
				}

				if (sizeof($filteredData['payment_status']) > 0) {
					$query->whereIn('p.payment_method', $filteredData['payment_status']);
				}

				if ($filteredData['management_number'] != '') {
					$query->where(function ($sub) use ($filteredData) {
						$sub->where('s.management_number', 'LIKE', '%'.$filteredData['management_number'].'%');
						$sub->orWhere('a.management_number', 'LIKE', '%'.$filteredData['management_number'].'%');
					});					
				}

				if($filteredData['invoice_from_date'] != '' && $filteredData['invoice_to_date'] != '') {
					$query->whereBetween(DB::raw("DATE_FORMAT(convert_tz(p.due_date,'+00:00','+09:00'), '%Y-%m-%d')"), [$filteredData['invoice_from_date'], $filteredData['invoice_to_date']]);
				}
				if($filteredData['invoice_from_date'] != '' && $filteredData['invoice_to_date'] == '') {
					$query->where(DB::raw("DATE_FORMAT(convert_tz(p.due_date,'+00:00','+09:00'), '%Y-%m-%d')"), ">=",[$filteredData['invoice_from_date']]);   
				}
				if($filteredData['invoice_from_date'] == '' && $filteredData['invoice_to_date'] != '') {
					$query->where(DB::raw("DATE_FORMAT(convert_tz(p.due_date,'+00:00','+09:00'), '%Y-%m-%d')"), "<=",[$filteredData['invoice_to_date']]);
				}
				
				// if ($filteredData['invoice_from_date'] != '' && $filteredData['invoice_to_date'] != '') {
				// 	$query->whereBetween('p.due_date', [$filteredData['invoice_from_date'], $filteredData['invoice_to_date']]);
				// }

				if($filteredData['payment_from_date'] != '' && $filteredData['payment_to_date'] != '') {
					$query->whereBetween(DB::raw("DATE_FORMAT(convert_tz(p.actual_payment_date,'+00:00','+09:00'), '%Y-%m-%d')"), [$filteredData['payment_from_date'], $filteredData['payment_to_date']]);
				}
				if($filteredData['payment_from_date'] != '' && $filteredData['payment_to_date'] == '') {
					$query->where(DB::raw("DATE_FORMAT(convert_tz(p.actual_payment_date,'+00:00','+09:00'), '%Y-%m-%d')"), ">=",[$filteredData['payment_from_date']]);   
				}
				if($filteredData['payment_from_date'] == '' && $filteredData['payment_to_date'] != '') {
					$query->where(DB::raw("DATE_FORMAT(convert_tz(p.actual_payment_date,'+00:00','+09:00'), '%Y-%m-%d')"), "<=",[$filteredData['payment_to_date']]);
				}

				// if ($filteredData['payment_from_date'] != '' && $filteredData['payment_to_date'] != '') {
				// 	$query->whereBetween('p.actual_payment_date', [$filteredData['payment_from_date'], $filteredData['payment_to_date']]);
				// }
            });
		}
		$outerQuery = DB::table(DB::raw("({$query->toSql()}) as t"))
						->mergeBindings($query)
						->orderBy('created_at',$tableData['dir']);
		return $outerQuery;
    }
    
    public function checkPay(Request $request, $type)
    {
        $getRecriuter = Recruiter::where('recruiter_number',$request->recruiter_number)->first();        
        $payment = PaymentTransaction::where('scoutid_or_applyid',$request->transaction_id)->where('payment_job_type',"$type")->whereNotNull('invoice_number')->orderBy('id','DESC')->first();
        if($payment){

			// testoverseas
            $contract_code = "70157960";
			$password = 'vxgEDZfH';

			// test.borderless
            // $contract_code = "69071710";
            // $password = 'wjNLAUD4';
            
            // $hrequest = new HTTP_Request2('https://beta.epsilon.jp/client/getsales.cgi', HTTP_Request2::METHOD_POST, $option);
            // $hrequest = new HTTP_Request2('https://beta.epsilon.jp/client/getsales.cgi', HTTP_Request2::METHOD_POST);
            $hrequest = new HTTP_Request2('https://secure.epsilon.jp/client/getsales.cgi', HTTP_Request2::METHOD_POST); // real site
            $hrequest->setConfig(array(
            'ssl_verify_peer' => false,
            #  'ssl_verify_peer' => true,
            'ssl_cafile' => '/etc/ssl/certs/ca-bundle.crt', //ルートCA証明書ファイルを指定
            ));
            $hrequest->setHeader("Content-Type","application/x-www-form-urlencoded");
            $hrequest->setBody("order_number=" . $payment->invoice_number);
            // $hrequest->setBody("trans_code=" . 'HwAGENa%2BTC829' . "&contract_code=" . $contract_code);
            // $hrequest->setBody("trans_code=" . '1581259' . "&order_number=" . "S20201126000101");
            
            $hrequest->setAuth($contract_code, $password,HTTP_Request2::AUTH_BASIC);
            $response = $hrequest->send();

            $res_content = $response->getBody();
            $temp_xml_res = str_replace("x-sjis-cp932", "UTF-8", $res_content);

            $unserializer = new XML_Unserializer();
            $unserializer->setOption('parseAttributes', TRUE);
            $unseriliz_st = $unserializer->unserialize($temp_xml_res);
            if ($unseriliz_st === true) {  
                       

                $res_array = $unserializer->getUnserializedData();
                // var_dump($res_array['result']['result']);return;      
                if(isset($res_array['result']['result'])){
                    // echo "処理に失敗しました<br><br>";
                    // exit(1);
                    // return 0;
                    $data = array("state"=>0, "invoice_number"=>$payment->invoice_number);
                    return $data;
                }
            
                $res_param_array = array();
                //pram setting
                foreach($res_array['result'] as $uns_k => $uns_v){
                    $result_atr_key = key($uns_v);
                    $result_atr_val = current($uns_v);

                    $res_param_array[$result_atr_key] =  mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
                }
                
                $data = array("state"=>$res_param_array["state"], "invoice_number"=>$payment->invoice_number);
                return $data;
            }
            else{
                // return "XML parse error";
                $data = array("state"=>"XML parse error", "invoice_number"=>$payment->invoice_number);
                return $data;
            }
            // $payment->invoice_number
            // return $this->createResponse($payment);    
        }
        else {
            // return "No payment";
            $data = array("state"=>"No payment");
            return $data;
        }
    }

	public function billMail(Request $request, $type)
	{
		$getRecriuter = Recruiter::where('recruiter_number',$request->recruiter_number)->first();
		$due_date = date('Y-m-d', strtotime('+14 day',strtotime(Carbon::now())));

		$payment = PaymentTransaction::where('scoutid_or_applyid',$request->transaction_id)->where('payment_job_type',"$type")->orderBy('id','DESC')->first();

		if($payment){
			$payment->scoutid_or_applyid = $request->transaction_id;
			$payment->recruiter_id = $getRecriuter->id;
			$payment->payment_job_type = "$type";
			$payment->payment_method = 'クレカ';
			$payment->invoice_amount = $request->invoice_amount;
			$payment->invoice_date = Carbon::now()->toDateString();
			$payment->due_date = $due_date;			
			
			$payment->generateOrderNumber($request->management_number);
			$payment->update();

			CustomLog::info("payment Bill".$request->transaction_id);
		}		

		if($type == 'scout') {
			$query = Scout::findOrFail($request->transaction_id);
			$query->scout_status = '請求済';
			$query->update();
			CustomLog::info("update scout".$request->transaction_id);	
		}
		else{
			$query = JobApply::findOrFail($request->transaction_id);
			$query->job_apply_status = '請求済';
			$query->update();
			CustomLog::info("update apply".$request->transaction_id);	
		}
		
        $jobseeker = Jobseeker::findOrFail($query->jobseeker_id);
        $id_encrypt = CustomCryption::cryption($query->id,'e');
        $user_id_encrypt = CustomCryption::cryption($getRecriuter->user_id,'e');
		$generate_url = url('recruiter/credit-payment?tokenid='.$user_id_encrypt.'&key='.$id_encrypt."&type=$type");
		$amount = $payment->invoice_amount - $request->tax;

        $data = array(
            // 'email' => $getRecriuter->email,
            'name' => $getRecriuter->recruiter_name,
            // 'recruiter_number' => $getRecriuter->recruiter_number,
            // 'start_date' => Carbon::now()->toDateString(),
            // 'due_date' => $due_date,
            'url' => $generate_url,
			'invoice_amount' => $payment->invoice_amount,
			'amount' => $amount,
			'tax' => $request->tax,
			'title' => $request->title,
			'management_number' => $request->management_number,
			'jobseeker_number' => $jobseeker->jobseeker_number,
			'jobseeker_name' => $jobseeker->jobseeker_name,
			'jobseeker_furigana_name' => $jobseeker->jobseeker_furigana_name
		);
		// $admin_mail = Auth::user()->email;
		$job_email = Job::select('application_address as job_email')->where('id','=',$query->job_id)->first();		
		$admin = User::where('role_id',1)->select('email','extra')->first();
		
		if($admin->extra != null && $admin->extra != '') {
			$extra = array_merge(array($admin->email),\explode(',',$admin->extra));
		}
		else{
			$extra = array($admin->email);
		}
		
		Mail::to($job_email->job_email)
				    ->cc($extra)
					->send(new CreditSendMail($data));
		
        return $this->createResponse($query);        
    }
    
    public function viewScoutInvoice(int $scout_id, Request $request)
	{

		$scout = DB::table('scouts AS s')
                ->join ('jobs AS j', 'j.id', '=', 's.job_id')
				->join('recruiters AS r', 'r.id', '=', 'j.recruiter_id')
				->where('s.id', $scout_id)
				->select('s.*', 'r.*', 'j.title')
				->first();

		if (!$scout) {
			return $this->createErrorResponse("Scout not found.", 404);
		}

		$payment = PaymentTransaction::scout()
					->where('scoutid_or_applyid', $scout_id)
					->orderBy('id','DESC')
					->first();
		if (!$payment) {
			return $this->createErrorResponse("Payment not found", 404);
		}
		// --reverse calculate tax and default amount
		$t = DB::table('taxes')->where('id', $payment->tax_id)->first();
		$tax = $t ? $t->percent : 0;
        $payment->default_amount = floatval($payment->invoice_amount) / (1 + ($tax/100));
        $payment->tax = floatval($payment->invoice_amount) - $payment->default_amount;
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
		);
	}

	public function viewJobapplyInvoice(int $jobapply_id, Request $request)
	{

		$jobapply = DB::table('job_applies AS a')
                ->join ('jobs AS j', 'j.id', '=', 'a.job_id')
				->join('recruiters AS r', 'r.id', '=', 'j.recruiter_id')
				->where('a.id', $jobapply_id)
				->select('a.*', 'r.*', 'j.title')
				->first();

		if (!$jobapply) {
			return $this->createErrorResponse("Jobapply not found.", 404);
		}

		$payment = PaymentTransaction::jobapply()
					->where('scoutid_or_applyid', $jobapply_id)
					->orderBy('id','DESC')
					->first();
		if (!$payment) {
			return $this->createErrorResponse("Payment not found", 404);
		}

		// --reverse calculate tax and default amount
		$t = DB::table('taxes')->where('id', $payment->tax_id)->first();
		$tax = $t ? $t->percent : 0;
        $payment->default_amount = floatval($payment->invoice_amount) / (1 + ($tax/100));
        $payment->tax = floatval($payment->invoice_amount) - $payment->default_amount;
        $payment->management_number = $jobapply->management_number;
        $jobapply->payment = $payment;

        $PDF = PDF::loadView('Mail.jobapply-invoice-pdf', [
            'data' => $jobapply,
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
		);
	}
    
}