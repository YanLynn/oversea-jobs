<?php

namespace App\Http\Controllers;
use App\Http\Controllers\API\V1\BaseController;
use App\User;
use App\Model\Jobseeker;
use Illuminate\Http\Request;
use Carbon\Carbon;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\services\LoginAttempts;

class AuthController extends BaseController
{
    // set login attempts limitations
    private $maxAttempts    = 3; // Limit 3 times
    private $decayMinutes   = 1440; // 60 mins * 24

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','registerRecruiter','jobseekerLogin','recruiterLogin']]);
    }  
    
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = 2;      
        $user->password = bcrypt($request->password);

        $user->save();

        return response()->json(['user' => $user]);
    }

    /*jobseeker/login*/
    public function jobseeker_register(Request $request)
    {
      
        $jobseeker = new Jobseeker();
        $jobseeker->role_id = 3;
        $jobseeker->jobseeker_name = $request->jobseeker_name;
        $jobseeker->jobseeker_furigana_name = $request->jobseeker_furigana_name;
        $jobseeker->dob = $request->dob;
        $jobseeker->address = $request->address;
        $jobseeker->phone = $request->phone;
        $jobseeker->email = $request->email;
        $jobseeker->password = bcrypt($request->password);

        $jobseeker->save();

        return response()->json(['jobseeker' => $jobseeker]);
    }
    /*jobseeker/login*/

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function login()
    {
        $credentials = request(['email', 'password']);
        $user = User::where('email', $credentials['email'])->first();

        if (!$token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'メールアドレスまたはパスワードが正しくありません'
            ], 401);
        }

        if ($user['role_id'] != 1) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'メールアドレスまたはパスワードが正しくありません'
            ], 402);
        }

        return $this->respondWithToken($token);
    }

    public function jobseekerLogin()
    {   
        $req_data = request(['email', 'password']);
        $tmp_arr = array_merge($req_data, array('record_status' => '1'));        
        $credentials = array_merge($tmp_arr, array('role_id' => '3','email_verified' => 1));

        $user_info = User::where('email', $credentials['email'])
                        ->where('role_id','3')
                        ->where('record_status','1')
                        ->where('email_verified', '1')
                        ->first();

        if (!$token = JWTAuth::attempt($credentials)) {
            // return $this->credentialErrorResponse(); 
            $response_msg = 'メールアドレスまたはパスワードが正しくありません';
            $has_login_attempts_max = LoginAttempts::hasTooManyLoginAttempts($user_info, $this->maxAttempts, $this->decayMinutes);
            $response_msg = $has_login_attempts_max ? 'メールアドレスとパスワードの組み合わせを規定回数以上間違えたため、このアカウントはロックされました' : $response_msg;
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => $response_msg
            ], 401);           
        }

        if ($user_info['role_id'] != 3) {
            return $this->authErrorResponse();
        } 
     
        if ($user_info['record_status'] == 3 || $user_info['record_status'] == 2 || $user_info['email_verified'] == 0) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'メールアドレスまたはパスワードが正しくありません'
            ], 402);
        }

        $is_still_locked = LoginAttempts::stillLocked($user_info['email'].$user_info['role_id'].$user_info['id']);
        if($is_still_locked){
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'メールアドレスとパスワードの組み合わせを規定回数以上間違えたため、このアカウントはロックされました'
            ], 403);
        }

        return $this->respondWithToken($token);
        
    }

    public function recruiterLogin()
    {       
        $req_data = request(['email', 'password']);
        $tmp_arr = array_merge($req_data, array('record_status' => '1'));
        $credentials = array_merge($tmp_arr, array('role_id' => '2','email_verified' => 1));

        $user_info = User::where('email', $credentials['email'])
                        ->where('role_id','2')
                        ->where('record_status','1')
                        ->where('email_verified', '1')
                        ->first();

        if (!$token = JWTAuth::attempt($credentials)) {
            // return response([
            //     'status' => 'error',
            //     'error' => 'invalid.credentials',
            //     'msg' => 'メールアドレスまたはパスワードが正しくありません'
            // ], 401);

            $response_msg = 'メールアドレスまたはパスワードが正しくありません';
            $has_login_attempts_max = LoginAttempts::hasTooManyLoginAttempts($user_info, $this->maxAttempts, $this->decayMinutes);
            $response_msg = $has_login_attempts_max ? 'メールアドレスとパスワードの組み合わせを規定回数以上間違えたため、このアカウントはロックされました' : $response_msg;
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => $response_msg
            ], 401);
        }

        // if($user_info['role_id'] != 2)
        // {
        //     return $this->authErrorResponse();
        // }        

        // if($user_info['email_verified'] == 0)
        // {
        //     return $this->activateError();
        // }       
        
        if ($user_info['role_id'] != 2 || $user_info['record_status'] == 2 || $user_info['record_status'] == 3 || $user_info['email_verified'] == 0) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'メールアドレスまたはパスワードが正しくありません'
            ], 402);
        }
        
        // if($dif < 86400 && $user_info['password_locked'] == 3)
        // {
        //     return response([
        //         'status' => 'error',
        //         'error' => 'invalid.credentials',
        //         'msg' => '規定回数以上回答に誤りがありましたので、アカウントをロックしました。24時間後に自動的にロックが解除されます。'
        //     ], 402);
       
        // }else{
        //     User::where('id', $user_info['id'])->update(['password_locked' => 0,'locked_datetime' => '0000-00-00']);
        // }

        // testing
        // $block_date = strtotime($user_info->locked_datetime);
        // $c_date = Carbon::now()->timestamp;
        // $dif =   $c_date - $block_date;
        // if($dif > 86400 && $user_info['password_locked'] == 3)
        // {
        //     User::where('id', $user_info['id'])->update(['password_locked' => 0,'locked_datetime' => '0000-00-00']);
        // }

        if(!is_null($user_info['locked_datetime'])){
            $current_datetime = date('Y-m-d H:i:s', strtotime(Carbon::now()));
            $oneday_after_locked = date('Y-m-d H:i:s', strtotime($user_info['locked_datetime'] . ' +1 day'));
            if(strtotime($current_datetime) >= strtotime($oneday_after_locked)) {
                User::where('id', $user_info['id'])->update(['password_locked' => 0, 'locked_datetime' => NULL]);
            }
        }
        
        if ($user_info['password_locked'] == 1 || $user_info['password_locked'] == 2) {
            User::where('id', $user_info['id'])->update(['password_locked' => 0,'locked_datetime' => NULL]);
        }

        $is_still_locked = LoginAttempts::stillLocked($user_info['email'].$user_info['role_id'].$user_info['id']);
        if($is_still_locked){
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'メールアドレスとパスワードの組み合わせを規定回数以上間違えたため、このアカウントはロックされました'
            ], 403);
        }
        
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }


    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }


    protected function respondWithToken($token)
    {
        if(Auth::user()->role_id == 3){
            $jobseekerId = $this->getLoginJobseekerID();
            $getJobseeker = Jobseeker::where('id',$jobseekerId)->get();
        }
        else{
            $getJobseeker = null;
        }

        return response()->json([
            'access_token' => $token,
            'user' => $this->guard()->user(),
            'token_type' => 'bearer',
            'user_info' => $getJobseeker,
            'expires_in' => 60
        ]);
    }

    public function guard(){
        return \Auth::Guard('api');
    }
    
    
    public function getdata(Request $request)
    {       
        $columns = ['name', 'email', 'password'];
        $length = $request->input('length');
        $searchValue = $request->input('search');
        $query = User::select('id', 'name', 'email', 'password');

        if ($searchValue) {
          
            $query->where(function($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                ->orWhere('email', 'like', '%' . $searchValue . '%');
            });
        }
    
        $projects = $query->orderBy($columns[$request->input('column')], 'desc')->paginate($length);
      
        return response()->json(array("userlist"=>$projects));       
    }    

    //get time zone
    public function gettimezone()
    {
        $user_timezone =  User::where('id',Auth::user()->id)->select('extra')->value('extra');
        $decode_timezone = json_decode($user_timezone);
        return response()->json(['user_timezone'=>$decode_timezone]);     
    }
    //get time zone list
    public function timeZoneList()
    {
        return response()->json(['get_timezone' => config ('config.timezone')]);     
    }
    //insert time zone
    public function timezone(Request $request)
    {
        $timezone = [ 'timezone'=> $request->timezone, 'offset'=> $request->offset ];
        
        User::where('id',Auth::user()->id)->update(['extra' => json_encode($timezone)]);
        $get_timezone =  User::where('id',Auth::user()->id)->select('extra')->value('extra');
        $decode_timezone = json_decode($get_timezone);
        return response()->json($decode_timezone);  

    }

   
}