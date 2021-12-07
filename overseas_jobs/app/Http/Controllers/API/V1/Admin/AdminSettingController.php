<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use App\Model\Taxes;
use App\User;
use DB;
class AdminSettingController extends BaseController
{
    public function taxes()
    {
        $get_taxes = Taxes::where('current',1)->get();
        return response()->json($get_taxes);
    }
    public function updateTaxes(Request $request)
    {
        $get_taxes = Taxes::where('current',1)->get();
        $data = $get_taxes->reduce(function($k, $v) {
            return $v;
        });
        if($data->percent != $request->percent){

            Taxes::where('current',1)->update(['current' => 0]);
            $taxes = new Taxes();
            $taxes->percent = $request->percent;
            $taxes->current = 1;
            $taxes->save();
            $this->taxes();
        }
       
    }
    public function getExtraEmail()
    {
        $data = User::where('role_id',1)->select('extra')->value('extra');
        $extra = \explode(',',$data);
        return response()->json($extra);
    }
    public function updateEmail(Request $request,$status)
    {
       $old_email = $request->oldEmail;
       $new_email = $request->c_email;
       $data = User::where('role_id',1)->select('extra')->value('extra');
       $extra = \explode(',',$data);
      
       if($old_email == null && $data != null){
           
            $update_email = array_push($extra,$new_email);
            $str = \implode(',',$extra);
            User::where('role_id',1)->update(['extra' => $str]);
            return response()->json(['success' => 'insert successful!']);
       }
       else if($status == 'update' && $old_email != null && $data != null){
            $rep = \str_replace($old_email,$new_email,$extra);
            $str = \implode(',',$rep);
            User::where('role_id',1)->update(['extra' => $str]);
            return response()->json(['success' => 'update successful!']);
        }
        else if($status == 'delete' && $old_email != null && $data != null){
            $del = array_search($old_email, $extra);
            unset($extra[$del]);
            $update_email = implode(',',$extra);
            User::where('role_id',1)->update(['extra' => $update_email]);
            return response()->json(['success' => 'delete successful!']);
        }
        else if(!$data){
            User::where('role_id',1)->update(['extra' => $new_email]);
            return response()->json(['success' => 'insert successful!']);
        }
        
    }

    public function emailSend()
    {
       $get_status = User::where('role_id',1)->select('email_send')->value('email_send');
       return response()->json($get_status);
    }
    public function updateEmailSend(Request $request)
    {   

        User::query()->update(['email_send' => $request->updateEmailSend]);
        // User::query()->update(['email_send' => $request->updateEmailSend]);
        DB::table('chat_message_receivers AS r')->update(['email_sent_at' => NULL]);
        $get_status = User::where('role_id',1)->select('email_send')->value('email_send');
        return response()->json($get_status);
       
        
    }
}

