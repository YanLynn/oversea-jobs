<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Http\TraitTest;
use Illuminate\Http\Request;
use App\services\CustomCryption;
use App\Mail\passwordResetSendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\V1\BaseController;
Use App\User;
use App\Model\Recruiter;
use App\Model\Jobseeker;
use App\services\LoginAttempts;
class ResetPasswordController extends BaseController
{
    use TraitTest;
    public function callResetPassword(Request $request)
    {

      $request = json_decode($_GET['jsonData']);
      $currentDate =  date('Y/m/d', strtotime(Carbon::now()));
      $userDecodeEmail =  CustomCryption::cryption($request->token,'d');
	  $userDecodeDate = CustomCryption::cryption($request->key,'d');
	  $roleIdDecode = CustomCryption::cryption($request->role_id,'d');
      $getExitUser = User::where('email',$userDecodeEmail)->where('role_id','=',$roleIdDecode)->where('record_status','<>',3)->where('email_verified',1)->first();
      $getQues = Recruiter::where('user_id',$getExitUser->id)->select('question')->first();
// 2020/07/21
      if(!$getExitUser){
       return $this->UserNotExit();
      }
      if($currentDate > $userDecodeDate){
 		return response()->json(['error' =>'Token expired!', 'role_id'=>$roleIdDecode],400);
       //return $this->tokenExpired($roleIdDecode);
      }
      if($getExitUser->remember_token != null){
		   return response()->json(['error' =>'tokenInvalided!', 'role_id'=>$roleIdDecode],400);
        // return $this->tokenInvalided($roleIdDecode);
      }
      else{
          return $this->createResponse(['getExitUser'=>$getExitUser,'getQues'=>$getQues]);
      }
    }
    public function sendPasswordResetLink(Request $request)
    {
  
        $this->validate( $request, [ 'email'=>  ['required', 'email'] ]);
		if($request->routeName == 'jobseeker-forgot-password'){
            $roleid = 3;
        }
        else{
            $roleid = 2;
        }
		$getExitUser = User::where('email',$request->email)->where('role_id','=',$roleid)->where('record_status','<>',3)->where('email_verified',1)->first();

        if(!$getExitUser){

			return $this->UserNotExit();
		
		}

		if($getExitUser){

		  if($request->routeName == 'jobseeker-forgot-password' && $getExitUser->role_id != 3){
				return $this->UserNotExit();
			}
		  else if($request->routeName == 'recruiter-forgot-password' && $getExitUser->role_id != 2){
				return $this->UserNotExit();
            }
          else if($getExitUser->record_status != 1){
                return $this->UserNotExit();
            }
            // else if($getExitUser->record_status == 2){
            //     return $this->StopUser();
            // }
            $is_still_locked = LoginAttempts::stillLocked($getExitUser['email'].$getExitUser['role_id'].$getExitUser['id']);
            if($is_still_locked){
                return response()->json(['error'=>'メールアドレスとパスワードの組み合わせを規定回数以上間違えたため、このアカウントはロックされました'],403);
            }

            $block_date = strtotime($getExitUser->locked_datetime);
            $c_date = Carbon::now()->timestamp;
            $dif =   $c_date - $block_date;

            if($dif < 86400 && $getExitUser['password_locked'] == 3){
                return response()->json(['error'=>'このメールアドレスは過去に秘密の回答を規定回数以上間違えたため、現在パスワードの変更ができません'],404);
            }

            if($getExitUser->role_id == 3){
                $jobseeker_furigana_name = Jobseeker::where('user_id','=',$getExitUser->id)->value('jobseeker_furigana_name');
                $jobseeker_name = Jobseeker::where('user_id','=',$getExitUser->id)->value('jobseeker_name');
                $getExitUser->name = $jobseeker_name."(".$jobseeker_furigana_name.")";
            }
            if($getExitUser->role_id == 2){
                $getExitUser->name = Recruiter::where('user_id','=',$getExitUser->id)->value('recruiter_name');
            }
        }
        
        $encryUserEmail = CustomCryption::cryption($request->email,'e');
		$currentDate =  date('Y/m/d', strtotime(Carbon::now()));
		$encryptRoleID = CustomCryption::cryption($getExitUser->role_id,'e');
        $encryCrurrentDate = CustomCryption::cryption(date('Y/m/d', strtotime(Carbon::now())),'e');
        $expireDate = date('Y/m/d', strtotime($currentDate . ' +1 day'));
        $key = rand();
        $urlCreate = url('reset-password?token='.$encryUserEmail.'&key='.$encryCrurrentDate.'&RID_key='.$encryptRoleID);
        
        $sendData = array(
            'email' =>$request->email,
            'name'=>$getExitUser->name,
            'url' =>$urlCreate,
            'today'=>$currentDate,
            'expireDate'=>$expireDate,
            'role_id' => $getExitUser->role_id
        );
        $getExitUser->remember_token = null;
        $getExitUser->update();
        Mail::to($request->email)->send(new passwordResetSendMail($sendData));
       return $this->checkEmail();

    }
    

     public function updateResetPassword(Request $request)
     {

        $userDecodeEmail =  CustomCryption::cryption($request->token,'d');
        $userDecodeDate = CustomCryption::cryption($request->key,'d');
        $roleIdDecode = CustomCryption::cryption($request->role_id,'d');
        $getExitUser = User::where('email',$userDecodeEmail)->where('role_id', $roleIdDecode)->where('record_status','<>',3)->first();
        $encryUserEmail = CustomCryption::cryption($getExitUser->email,'e');
        if($getExitUser->remember_token !=null){
            return $this->tokenExpired();
        }

        $getExitUser->password = Hash::make($request->password);
        $getExitUser->remember_token = $encryUserEmail;
        $getExitUser->password_locked = 0;
        $getExitUser->update();
        return $this->createResponse($getExitUser);
     }

    public function checkAns(Request $request)
    {
        $getExitUser = User::where('email',$request->locakedCount['email'])->where('role_id','=',2)->where('record_status','<>',3)->first();
        $getQuesAndAns = Recruiter::where('user_id',$getExitUser->id)->select('question','answer')->first();

        
        if($request->ans == $getQuesAndAns->answer){
               $getExitUser->password_locked = 0;
               $getExitUser->locked_datetime = '0000-00-00';
               $getExitUser->update();
         return response()->json($getQuesAndAns,200);
        }
        if($request->ans != $getQuesAndAns->answer){
            if($getExitUser->password_locked != 3){
               $getExitUser->password_locked += 1;
               $getExitUser->locked_datetime = Carbon::now();
               $getExitUser->update();
               
            }
            return response()->json(['error'=>'Answer does not match'],500);

        }
        
    }

}
