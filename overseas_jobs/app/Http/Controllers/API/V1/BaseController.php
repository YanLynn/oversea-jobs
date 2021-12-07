<?php 
namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\Controller;
use Auth;
use App\Model\Recruiter;
use App\Model\Jobseeker;
use DB;
class BaseController extends Controller {

	public function createResponse($data, $statusCode = 200) {
		return response()->json([
			'data' => $data
		], $statusCode);
	}

	public function createErrorResponse($message, $statusCode = 500) {
		return response()->json([
			'error' => [
				'status' => $statusCode,
				'message' => $message,
			]
		], $statusCode);
    }

    public function authErrorResponse() {
		return response()->json([
			'error' => [
				'status' => 401,
				'error' => 'メールアドレスまたはパスワードが正しくありません',
			]
            ],401);
    }


    public function activateError() {
		return response()->json([
			'error' => [
				'status' => 401,
				'error' => 'メールアドレスまたはパスワードが正しくありません',
			]
            ],401);
    }
    
    public function credentialErrorResponse() {
		return response()->json([
			'error' => [
				'status' => 400,
				'error' => ' メールアドレスまたはパスワードが正しくありません',
			]
            ],400);
    }
    
    public function UserNotExit()
    {
        return response()->json(['error'=>'このユーザは存在しません'],404);
    }
    // public function StopUser()
    // {
    //     return response()->json(['error'=>'このユーザは存在しません'],404);
    // }
    public function tokenExpired()
    {
        return response()->json(['error'=>'Token expired!'],400);
    }
    public function tokenInvalided()
    {
        return response()->json(['error'=>'tokenInvalided!'],400);
    }

    public function checkEmail()
    {
        return response()->json(['success'=>'ご入力されたメールアドレス宛にメールを送信しましたので、ご確認ください。'],200);
    }
    
    public static function getLoginRecruiterID()
    {
       $getLoginUser = Auth::user()->id;
       $getLoignRecuriter = Recruiter::where('user_id',$getLoginUser)->value('id');
       return $getLoignRecuriter;
    }

    public static function getLoginJobseekerID()
    {
       $getLoginUser = Auth::user()->id;
       $getLoignJobseeker = Jobseeker::where('user_id',$getLoginUser)->value('id');
       return $getLoignJobseeker;
    }

    public function getRelationsCountsResult($relations, $result){
        foreach ($relations as $relation) {
            if($result->$relation->count() > 0)
                return true;
        }
        return false;
    }

}