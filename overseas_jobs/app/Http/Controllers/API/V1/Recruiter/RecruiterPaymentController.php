<?php

namespace App\Http\Controllers\API\V1\Recruiter;

use App\Model\Job;
use App\Model\JobApply;
use App\Model\Scout;
use App\Model\PaymentTransaction;

use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use PDF;
use HTTP_Request2;
use XML_Serializer;
use XML_Unserializer;
use App\Model\Recruiter;
use App\services\CustomCryption;
use App\Helpers\CustomLog;

class RecruiterPaymentController extends BaseController
{
    private function accessGMO($info)
    {
        $card_info = array();
        $contract_code = "70157960"; //testoverseas
        // $contract_code = "69071710"; //test.borderless
        $option = array(
            "timeout" => "20", // タイムアウトの秒数指定
        //    "allowRedirects" => true, // リダイレクトの許可設定(true/false)
        //    "maxRedirects" => 3, // リダイレクトの最大回数
        );
        $request = new HTTP_Request2($info->order_url, HTTP_Request2::METHOD_POST, $option);
        $request->setConfig(array(
            'ssl_verify_peer' => false,
        #     'ssl_verify_peer' => true,
        #     'ssl_cafile' => '/etc/ssl/certs/ca-bundle.crt', //ルートCA証明書ファイルを指定
        ));

        $request->addPostParameter('version', '2' );
        $request->addPostParameter('contract_code', $contract_code);
        $request->addPostParameter('user_id', $info->recruiter_number);        
        $request->addPostParameter('xml', '1');
        $request->addPostParameter('character_code', 'UTF8' );
        if($info->type == 'payment') {
            $rec_name = substr($info->recruiter_name,0,100);
            $request->addPostParameter('user_name', mb_convert_encoding($rec_name, "UTF-8", "auto"));
            $request->addPostParameter('user_mail_add', $info->email);
            $request->addPostParameter('item_code', $info->job_number);
            $request->addPostParameter('item_name', mb_convert_encoding($info->title, "UTF-8", "auto"));
            $request->addPostParameter('order_number', $info->invoice_number);
            $request->addPostParameter('item_price', $info->invoice_amount);
            $request->addPostParameter('process_code', $info->process_code);
            $request->addPostParameter('st_code', $info->st_code);
            $request->addPostParameter('memo1', $info->memo1);
            $request->addPostParameter('memo2', $info->memo2);
            $request->addPostParameter('mission_code', $info->mission_code);
            // $request->addPostParameter('lang_id', $info->lang_id);
            // $request->addPostParameter('currency_id', $info->currency_id);
            $request->addPostParameter('page_type', $info->page_type);
            // $request->addPostParameter('trans_code', $get_info->trans_code);
        }
        $response = $request->send();
        $res_content = $response->getBody();
        $temp_xml_res = str_replace("x-sjis-cp932", "UTF-8", $res_content);

        $unserializer = new XML_Unserializer();
        $unserializer->setOption('parseAttributes', TRUE);
        $unseriliz_st = $unserializer->unserialize($temp_xml_res);

        if ($unseriliz_st === true) {
            $res_array = $unserializer->getUnserializedData();

            $card_info["is_xml_error"] = false;            

            foreach($res_array['result'] as $uns_k => $uns_v){
                $result_atr_key = key($uns_v);
                $result_atr_val = current($uns_v);

                switch ($result_atr_key) {
                case 'redirect':
                    $card_info['xml_redirect_url'] = rawurldecode($result_atr_val);
                    break;
                case 'memo1':
                    $card_info["xml_memo1_msg"] = mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
                    break;
                case 'memo2':
                    $card_info["xml_memo2_msg"] = mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
                    break;
                case 'trans_code':
                    $card_info["trans_code"] = mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
                    break;

                case 'card_brand':
                    $card_info["card_brand"] = $result_atr_val;
                    break;
                case 'card_expire':
                    $card_info["card_expire"] = $result_atr_val;
                    break;
                case 'card_number_mask':
                    $card_info["card_number_mask"] = rawurldecode($result_atr_val);
                    break;
                case 'err_code':
                    $card_info["is_xml_error"] = true;
                    $card_info["xml_error_cd"] = $result_atr_val;
                    break;
                case 'err_detail':                    
                    if($info->type == 'payment') {
                        $card_info["xml_error_msg"] = mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
                    }
                    else{
                        $card_info["xml_error_msg"] = $result_atr_val;
                    }
                    // $card_info["xml_error_msg"] = $result_atr_val;
                    break;                      
                case 'result':
                    $card_info["result"] = mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
                    break;                      
                default:
                    break;
                }
            }                           
        }else{
            // $err_msg = "xml parser error";
            $card_info["status"] = "XML parser error";
            // return $err_msg;
        }
        return $card_info; 
    }

    public function cardInfo($userid)
    {
        // No need to get card info
        // if(isset($_GET["tokenid"])){
        //     $userid = CustomCryption::cryption($_GET["tokenid"],'d');
        // }

        // No need to get card info
        // $getRecriuter = Recruiter::where('user_id', $userid)->first();
        // $getRecriuter["order_url"] = "https://beta.epsilon.jp/cgi-bin/order/get_user_info.cgi";
        // $getRecriuter["order_url"] = "https://secure.epsilon.jp/cgi-bin/order/get_user_info.cgi ";
        // $getRecriuter["type"] = "info";
        // $card_info = $this->accessGMO($getRecriuter);   
        if(isset($_GET["key"])){
            $card_info["transaction_id"] = CustomCryption::cryption($_GET["key"],'d');
        }    

        return response()->json([ 'status' => 'success', "card_info" => $card_info ]);
    }

    public function transactionInfo(int $id, $type, Request $request)
    {
        if($type == 'scout'){
            $table = "scouts";
            $job_type = 'scout';
        }
        else{
            $table = "job_applies";
            $job_type = 'job-apply';
        }
        
        $info = PaymentTransaction::join('recruiters','recruiters.id','payment_transactions.recruiter_id')
                ->join("$table", "$table.id", 'payment_transactions.scoutid_or_applyid')
                ->join('jobs', 'jobs.id', "$table.job_id")
                ->select('jobs.title','payment_transactions.invoice_number',"$table.management_number",'recruiters.recruiter_number', 'recruiters.recruiter_name', 'recruiters.email', 'payment_transactions.invoice_amount', 'jobs.job_number')
                ->where('payment_job_type', "$job_type")->where('scoutid_or_applyid', $id)->orderBy('payment_transactions.id','DESC')->first();
     
        return response()->json(["info"=> $info]);       
    }

    public function creditCardPayment(Request $request)
    {
        // return $request;
        if($request["choice"] == 'old'){
            $request["process_code"] = 2;
        }
        else{
            $request["process_code"] = 1;
        }
        // Testing
        $request["order_url"] = "https://beta.epsilon.jp/cgi-bin/order/receive_order3.cgi"; 
        //Real
        // $request["order_url"] = "https://secure.epsilon.jp/cgi-bin/order/receive_order3.cgi"; 
        $request["memo1"] = '';
        $request["memo2"] = '';
        $request["st_code"] = '10000-0000-00000-00000-00000-00000-00000';
        $request["mission_code"] = "1";
        $request["lang_id"] = 'ja';
        $request["currency_id"] = 'JPY';
        $request["page_type"] = 12;
        $request["type"] = "payment";
        $card_info = $this->accessGMO($request);
        // return $card_info;die();

        if(isset($card_info["status"])){
            if($card_info["status"] == 'XML parser error') {
                $err_msg = "エラー : XML parser error";
                return response()->json([ 'status' => 'error', 'msg' => $err_msg, 'error_code' => '' ]);
            }
        }
        
        if($card_info["is_xml_error"]){
            // データ送信結果が失敗だった場合、オーダー入力画面に戻し、エラーメッセージを表示します。
            $err_msg = "エラー : " . $card_info["xml_error_cd"] . $card_info["xml_error_msg"];
            return response()->json([ 'status' => 'error', 'msg' => $err_msg, 'error_code' => $card_info["xml_error_cd"] ]);
        }else{
            if( !empty( $card_info["xml_redirect_url"] ) ){
                // データ送信に成功した場合、リダイレクト先URLへリダイレクトさせてください。               
                return response()->json([ 'status' => 'success', 'link' => $card_info["xml_redirect_url"] ]);
            }
        }
    }

    public function paymentComplete(Request $request)
    {
        $order_number = $request['order_number'];
        if(strpos($order_number, 'S') !== false) {
            $join_table = "scouts"; 
        }
        else{
            $join_table = "job_applies";
        } 

        $payment_transaction = PaymentTransaction::where("payment_transactions.invoice_number", '=', $order_number)->firstOrFail();
        $payment_transaction->payment_amount = $payment_transaction->invoice_amount;
        $payment_transaction->save();

        $payment = PaymentTransaction::select("$join_table.management_number", "payment_transactions.invoice_number as m_number", 'jobs.title', 'recruiters.recruiter_name', 'payment_transactions.invoice_amount',"payment_transactions.scoutid_or_applyid as join_id")
                                    ->join($join_table,"$join_table.id",'=','payment_transactions.scoutid_or_applyid')
                                    ->join('jobs', "jobs.id",'=', "$join_table.job_id")                 
                                    ->join('recruiters', 'recruiters.id', 'payment_transactions.recruiter_id')
                                    ->where("payment_transactions.invoice_number", '=', $order_number)
                                    ->get();
        if($join_table == 'scouts'){
            $row = Scout::where([ 'id' => $payment[0]["join_id"] ])->firstOrFail();
            $row->scout_status = '入金確認済';
        }
        else{
            $row = JobApply::where([ 'id' => $payment[0]["join_id"] ])->firstOrFail();
            $row->job_apply_status = '入金確認済';
        }
        $row->save();
        CustomLog::info("ChangeStatus Paymentconfirm ".$order_number);

        return $this->createResponse($payment, 200);
    }

    /**
	 * @desc Recruiter download a PDF invoice for jobapply
	 * @author Myo Ko Ko @ 2020/08/17
	 * @last_maintained Myo Ko Ko @ 2020/08/17
	 * @param  int $jobapply_id
	 * @return application/pdf
	 */
	public function downloadJobapplyInvoice(int $jobapply_id, Request $request)
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
        // $payment->invoice_date = Carbon::now();
		$inv_date = explode('-', $payment->invoice_date->format('Y-m-d'));
		$payment->invoicedate = $inv_date[0].'年'.$inv_date[1].'月'.$inv_date[2].'日';
        $payment->default_amount = floatval($payment->invoice_amount) / (1 + ($tax/100));
        $payment->tax = floatval($payment->invoice_amount) - $payment->default_amount;
        $payment->management_number = $jobapply->management_number;
        $jobapply->payment = $payment;

        $PDF = PDF::loadView('Mail.jobapply-invoice-pdf', [
            'data' => $jobapply,
            'sign' => get_sign_image_base64(),
        ]);
        $pdf_filename = $payment->invoice_number . '.pdf';
        return $PDF->stream($pdf_filename);
	}

	/**
	 * @desc Recruiter download PDF invoice for scout
	 * @author Myo Ko Ko @ 2020/08/25
	 * @last_maintained Myo Ko Ko @ 2020/09/23
	 * @param  int $scout_id
	 * @return application/pdf
	 */
	public function downloadScoutInvoice(int $scout_id, Request $request)
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
        // $inv_date = Carbon::now();
		// $inv_date = explode('-', $payment->invoice_date->format('Y-m-d'));
        // $payment->invoice_date = $inv_date[0].'年'.$inv_date[1].'月'.$inv_date[2].'日';
        // $payment->invoice_date = Carbon::now();
		$inv_date = explode('-', $payment->invoice_date->format('Y-m-d'));
		$payment->invoicedate = $inv_date[0].'年'.$inv_date[1].'月'.$inv_date[2].'日';
        $payment->default_amount = floatval($payment->invoice_amount) / (1 + ($tax/100));
        $payment->tax = floatval($payment->invoice_amount) - $payment->default_amount;
        $payment->management_number = $scout->management_number;
        $scout->payment = $payment;

        $PDF = PDF::loadView('Mail.scout-invoice-pdf', [
            'data' => $scout,
            'sign' => get_sign_image_base64(),
        ]);
        $pdf_filename = $payment->invoice_number . '.pdf';
        return $PDF->stream($pdf_filename);
	}
}
