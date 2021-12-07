<?php
namespace App\Http\Controllers\API\V1\Universal;

use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Model\Job;
use App\Model\Jobseeker;
use App\Model\Recruiter;
use App\Model\ChatMessage;
use App\User;
use Auth;
use App\Model\Scout;
use App\Model\JobApply;
use DateTime;
use DateTimeZone;

use App\Helpers\CustomLog;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\API\V1\ClearChatAttachmentsTrait;

class ChatMessageController extends BaseController
{
	use ClearChatAttachmentsTrait;

	private $scout_chattable_status;
	private $jobapply_chattable_status;

	public function __construct()
	{
		$this->scout_chattable_status = [
			config('config.scouts.interested'),
			config('config.scouts.unclaimed'),
			config('config.scouts.billed'),
			config('config.scouts.payment_confirmed'),
		];
		$this->jobapply_chattable_status = [
			config('config.job_apply.under_review'),
			config('config.job_apply.unclaimed'),
			config('config.job_apply.billed'),
			config('config.job_apply.payment_confirmed'),
		];
	}

	/**
	 * @desc Get chattable scout/apply list for recruiter
	 * @author Myo Ko Ko @ 2020/10/05
	 * @last_maintained Myo Ko Ko @ 2020/11/05
	 * @param  string $type
	 * @return application/json
	 */
	public function getRecruiterChattables(string $type,Request $request)
	{
		$recruiter_id = $this->getLoginRecruiterID();
		$q = $request->input('q');
		$timezone = User::find(Auth::user()->id)->selectedTimeZone();

		switch ($type) {
			case config('config.payment_job_type.scout'):
				$qb = Scout::join('jobseekers','scouts.jobseeker_id','=','jobseekers.id')
						->join('jobs','scouts.job_id','=','jobs.id')
						->join('users','jobseekers.user_id','=','users.id')
						->whereIn('scout_status',$this->scout_chattable_status)
						->where('scouts.recruiter_id', $recruiter_id)
						->select('jobs.*','users.id AS user_id','scouts.id AS scoutid_or_applyid',
						// --attach a type indicator
						DB::raw(sprintf("'%s' AS type",config('config.payment_job_type.scout'))),
						'scouts.recruiter_id','scouts.jobseeker_id','scouts.management_number',
						'scouts.created_at AS o_created_at','scouts.scout_status AS status',
						'jobseekers.jobseeker_name')
						->orderBy('scouts.created_at', 'desc');	
			break;

			case config('config.payment_job_type.job_apply'):
				$qb = JobApply::join('jobseekers','job_applies.jobseeker_id','=','jobseekers.id')
						->join('jobs','job_applies.job_id','=','jobs.id')
						->join('users','jobseekers.user_id','=','users.id')
						->whereIn('job_apply_status',$this->jobapply_chattable_status)
						->where('jobs.recruiter_id', $recruiter_id)
						->select('jobs.*','users.id AS user_id','job_applies.id AS scoutid_or_applyid',
						// --attach a type indicator
						DB::raw(sprintf("'%s' AS type",config('config.payment_job_type.job_apply'))),
						'jobs.recruiter_id','job_applies.jobseeker_id','job_applies.management_number',
						'job_applies.created_at AS o_created_at','job_applies.job_apply_status AS status',
						'jobseekers.jobseeker_name')
						->orderBy('job_applies.created_at', 'desc');
			break;
		}
		// --filtering
		if (!empty($q)) {
			$qb->where(function($query) use ($q,$type) {
				// $query->where('jobs.job_number','LIKE',"%{$q}%");
				// --management numbers are on different tables
				if ($type == config('config.payment_job_type.scout')) {
					$query->where('scouts.management_number','LIKE',"%{$q}%");
				} else if ($type == config('config.payment_job_type.job_apply')) {
					$query->where('job_applies.management_number','LIKE',"%{$q}%");
				}
				$query->orWhere('jobs.title','LIKE',"%{$q}%");
				$query->orWhere('jobseekers.jobseeker_name','LIKE',"%{$q}%");
			});
		}
		$data = $qb->get();

		// --Attach unread count
		foreach	($data as $key => $datum) {
			$unread = DB::table('chat_message_receivers')
				->join('chat_messages','chat_message_receivers.chat_message_id','=','chat_messages.id')
				->where('chat_messages.scoutid_or_applyid', $datum->scoutid_or_applyid)
				->where('chat_messages.type', $type)
				->where('receiver_id', $recruiter_id)
				->where('receiver_role_id', 2)
				->where('status', 0)
				->count();
			$last_message = DB::table('chat_messages')
				->where('scoutid_or_applyid', $datum->scoutid_or_applyid)
				->where('chat_messages.type', $datum->type)
				->select('id','recruiter_id', 'jobseeker_id', 'speaker_id', 'speaker_role_id', 'scoutid_or_applyid', 'type', 'message', 'message_type', 'expired', 
				// DB::raw("convert_tz(created_at,'+00:00','$timezone') as created_at"), 
				'created_at',
				'updated_at')
				->orderBy('created_at','DESC')
				->first();
				$data[$key]->unread = $unread;
				$data[$key]->last_chat_time = $last_message ? $last_message->created_at : null;
		}

		return $this->createResponse($data);
	}

	/**
	 * @desc Get chattable scout/apply list for jobseeker
	 * @author Myo Ko Ko @ 2020/10/05
	 * @last_maintained Myo Ko Ko @ 2020/10/05
	 * @param  string $type
	 * @return application/json
	 */
	public function getJobseekerChattables(string $type,Request $request)
	{
		$jobseeker_id = $this->getLoginJobseekerID();
		$q = $request->input('q');
		$timezone = User::find(Auth::user()->id)->selectedTimeZone();

		switch ($type) {
			case config('config.payment_job_type.scout'):
				$qb = Scout::join('recruiters','scouts.recruiter_id','=','recruiters.id')
						->join('jobs','scouts.job_id','=','jobs.id')
						->join('users','recruiters.user_id','=','users.id')
						->whereIn('scout_status',$this->scout_chattable_status)
						->where('scouts.jobseeker_id', $jobseeker_id)
						->select('jobs.*','users.id AS user_id','scouts.id AS scoutid_or_applyid',
						// --attach a type indicator
						DB::raw(sprintf("'%s' AS type",config('config.payment_job_type.scout'))),
						'scouts.recruiter_id','scouts.jobseeker_id','scouts.management_number',
						'scouts.created_at AS o_created_at','scouts.scout_status AS status',
						'recruiters.recruiter_name','recruiters.incharge_name')
						->orderBy('scouts.created_at', 'desc');
			break;

			case config('config.payment_job_type.job_apply'):
				$qb = JobApply::join('jobs','job_applies.job_id','=','jobs.id')
						->join('recruiters','jobs.recruiter_id','=','recruiters.id')
						->join('users','recruiters.user_id','=','users.id')
						->whereIn('job_apply_status',$this->jobapply_chattable_status)
						->where('job_applies.jobseeker_id', $jobseeker_id)
						->select('jobs.*','users.id AS user_id','job_applies.id AS scoutid_or_applyid',
						// --attach a type indicator
						DB::raw(sprintf("'%s' AS type",config('config.payment_job_type.job_apply'))),
						'jobs.recruiter_id','job_applies.jobseeker_id','job_applies.management_number',
						'job_applies.created_at AS o_created_at','job_applies.job_apply_status AS status',
						'recruiters.recruiter_name','recruiters.incharge_name')
						->orderBy('job_applies.created_at', 'desc');
			break;
		}
		// --filtering
		if (!empty($q)) {
			$qb->where(function($query) use ($q,$type) {
				// $query->where('jobs.job_number','LIKE',"%{$q}%");
				// --management numbers are on different tables
				if ($type == config('config.payment_job_type.scout')) {
					$query->where('scouts.management_number','LIKE',"%{$q}%");
				} else if ($type == config('config.payment_job_type.job_apply')) {
					$query->where('job_applies.management_number','LIKE',"%{$q}%");
				}
				$query->orWhere('jobs.title','LIKE',"%{$q}%");
				$query->orWhere('recruiters.recruiter_name','LIKE',"%{$q}%");
				$query->orWhere('recruiters.incharge_name','LIKE',"%{$q}%");
			});
		}
		$data = $qb->get();
		// --Attach unread count
		foreach	($data as $key => $datum) {
			$unread = DB::table('chat_message_receivers')
				->join('chat_messages','chat_message_receivers.chat_message_id','=','chat_messages.id')
				->where('chat_messages.scoutid_or_applyid', $datum->scoutid_or_applyid)
				->where('chat_messages.type', $type)
				->where('receiver_id', $jobseeker_id)
				->where('receiver_role_id', 3)
				->where('status', 0)
				->count();
			$last_message = DB::table('chat_messages')
				->where('scoutid_or_applyid', $datum->scoutid_or_applyid)
				->where('chat_messages.type', $datum->type)
				->select('id','recruiter_id', 'jobseeker_id', 'speaker_id', 'speaker_role_id', 'scoutid_or_applyid', 'type', 'message', 'message_type', 'expired', 
				// DB::raw("convert_tz(created_at,'+00:00','$timezone') as created_at"), 
				'created_at',
				'updated_at')
				->orderBy('created_at','DESC')
				->first();
			$data[$key]->unread = $unread;
			$data[$key]->last_chat_time = $last_message ? $last_message->created_at : null;
		}
		return $this->createResponse($data);
	}

	/**
	 * @desc Get chattable scout/apply list for admin
	 * @author Myo Ko Ko @ 2020/10/05
	 * @last_maintained Myo Ko Ko @ 2020/10/05
	 * @param  string $type
	 * @return application/json
	 */
	public function getAdminChattables(string $type,Request $request)
	{
		$user = auth()->user();
		$q = $request->input('q');
		$timezone = '+09:00';

		switch ($type) {
			case config('config.payment_job_type.scout'):
				$qb = Scout::join('jobseekers','scouts.jobseeker_id','=','jobseekers.id')
						->join('jobs','scouts.job_id','=','jobs.id')
						->join('recruiters','scouts.recruiter_id','=','recruiters.id')
						->whereIn('scout_status',$this->scout_chattable_status)
						->select('jobs.*','scouts.id AS scoutid_or_applyid','scouts.recruiter_id','scouts.jobseeker_id',
						// --attach a type indicator
						DB::raw(sprintf("'%s' AS type",config('config.payment_job_type.scout'))),
						'jobseekers.user_id AS jobseeker_user_id','recruiters.user_id AS recruiter_user_id',
						'scouts.scout_status AS status',
						'scouts.management_number','scouts.created_at AS o_created_at')
						->orderBy('scouts.created_at', 'desc');
			break;

			case config('config.payment_job_type.job_apply'):
				$qb = JobApply::join('jobseekers','job_applies.jobseeker_id','=','jobseekers.id')
						->join('jobs','job_applies.job_id','=','jobs.id')
						->join('recruiters','jobs.recruiter_id','=','recruiters.id')
						->whereIn('job_apply_status',$this->jobapply_chattable_status)
						->select('jobs.*','jobs.id AS user_id','job_applies.id AS scoutid_or_applyid','jobs.recruiter_id','job_applies.jobseeker_id',
						// --attach a type indicator
						DB::raw(sprintf("'%s' AS type",config('config.payment_job_type.job_apply'))),
						'jobseekers.user_id AS jobseeker_user_id','recruiters.user_id AS recruiter_user_id',
						'job_applies.job_apply_status AS status',
						'job_applies.management_number','job_applies.created_at AS o_created_at')
						->orderBy('job_applies.created_at', 'desc');
			break;
		}
		// --filtering
		if (!empty($q)) {
			$qb->where(function($query) use ($q,$type) {
				// $query->where('jobs.job_number','LIKE',"%{$q}%");
				// --management numbers are on different tables
				if ($type == config('config.payment_job_type.scout')) {
					$query->where('scouts.management_number','LIKE',"%{$q}%");
				} else if ($type == config('config.payment_job_type.job_apply')) {
					$query->where('job_applies.management_number','LIKE',"%{$q}%");
				}
				$query->orWhere('jobs.title','LIKE',"%{$q}%");
				$query->orWhere('jobseekers.jobseeker_name','LIKE',"%{$q}%");
				$query->orWhere('recruiters.recruiter_name','LIKE',"%{$q}%");
				$query->orWhere('recruiters.incharge_name','LIKE',"%{$q}%");
			});
		}
		$data = $qb->get();
		// --Attach unread count
		foreach	($data as $key => $datum) {
			$unread = DB::table('chat_message_receivers')
				->join('chat_messages','chat_message_receivers.chat_message_id','=','chat_messages.id')
				->where('chat_messages.scoutid_or_applyid', $datum->scoutid_or_applyid)
				->where('chat_messages.type', $type)
				->where('receiver_id', $user->id)
				->where('receiver_role_id', 1)
				->where('status', 0)
				->count();
			$last_message = DB::table('chat_messages')
				->where('scoutid_or_applyid', $datum->scoutid_or_applyid)
				->where('chat_messages.type', $datum->type)
				->select('id','recruiter_id', 'jobseeker_id', 'speaker_id', 'speaker_role_id', 'scoutid_or_applyid', 'type', 'message', 'message_type', 'expired', 
				// DB::raw("convert_tz(created_at,'+00:00','$timezone') as created_at"), 
				'created_at',
				'updated_at')
				->orderBy('created_at','DESC')
				->first();
				$data[$key]->unread = $unread;
				$data[$key]->last_chat_time = $last_message ? $last_message->created_at : null;
		}
		return $this->createResponse($data);
	}

	/**
	 * @desc Save a new chat message
	 * @author Myo Ko Ko @ 2020/10/07
	 * @last_maintained Myo Ko Ko @ 2020-10-07
	 * @param  Request $request
	 * @return application/json
	 */
	public function saveMessage(Request $request)
	{
		// return $request;
		DB::beginTransaction();
		try {
			$message = new ChatMessage;
			$message->recruiter_id = $request->input('recruiter_id');
			$message->jobseeker_id = $request->input('jobseeker_id');
			$message->speaker_id = $request->input('speaker_id');
			$message->speaker_role_id = $request->input('speaker_role_id');
			$message->scoutid_or_applyid = $request->input('scoutid_or_applyid');
			$message->type = $request->input('type');
			$message->message = $request->input('message');
			$message->message_type = $request->input('message_type');

			if(Auth::user()->role_id == 1){
				$timezone = 'Asia/Tokyo';
			}
			else{
				if($message->speaker_role_id == 3) {
					$opp_user_id = Recruiter::where('id', $message->recruiter_id)->first()->user_id;
					$timezone = User::find($opp_user_id)->selectedTimeZoneVal();
				}
				else if ($message->speaker_role_id == 2) {
					$opp_user_id = Jobseeker::where('id', $message->jobseeker_id)->first()->user_id;
					$timezone = User::find($opp_user_id)->selectedTimeZoneVal();
				}
			} 
			
			// --Check for file input
			if ($request->file('file') != null) {

				// --Define file meta
				$file = $request->file('file');
				$ext = $file->getClientOriginalExtension();
			
				$filename = $file->getClientOriginalName();
				// --Modify filename
				if (strlen($filename) < 241) {
					$prefix = uniqid().'_';
					$filename = $prefix.$filename;
					$message->message = $filename;
				}
				// --Put file
				put_file($file, $filename, 'chat');
			}
			// --Save message model
			$message->save();

			// --Save receivers and status
			$receivers = [];
			switch ($message->speaker_role_id) {

				case 1:
					$receivers[] = [
						'chat_message_id' => $message->id,
						'receiver_id' => $message->recruiter_id,
						'receiver_role_id' => 2,
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now(),
					];
					$receivers[] = [
						'chat_message_id' => $message->id,
						'receiver_id' => $message->jobseeker_id,
						'receiver_role_id' => 3,
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now(),
					];
				break;

				case 2:
					$receivers[] = [
						'chat_message_id' => $message->id,
						'receiver_id' => User::where('role_id', 1)->first()->id,
						'receiver_role_id' => 1,
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now(),
					];
					$receivers[] = [
						'chat_message_id' => $message->id,
						'receiver_id' => $message->jobseeker_id,
						'receiver_role_id' => 3,
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now(),
					];
				break;

				case 3:
					$receivers[] = [
						'chat_message_id' => $message->id,
						'receiver_id' => User::where('role_id', 1)->first()->id,
						'receiver_role_id' => 1,
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now(),
					];
					$receivers[] = [
						'chat_message_id' => $message->id,
						'receiver_id' => $message->recruiter_id,
						'receiver_role_id' => 2,
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now(),
					];
				break;
			}		
			DB::table('chat_message_receivers')->insert($receivers);
			DB::commit();
		}
		catch (\Exception $e) {
			DB::rollback();
			return $this->createErrorResponse($e->getMessage());
		}

		$db_time = new DateTime($message->created_at);        
        $user_time_zone = new DateTimeZone($timezone);
        $db_time->setTimezone($user_time_zone);
        $newDate = $db_time->format('Y-m-d H:i:s');
		$message->last_chat_time = $newDate;
		
		// --Get speaker's last chat time
		if(Auth::user()->role_id == 1) {
			$tz = 'Asia/Tokyo';
		}
		else {
			$tz = Auth::user()->selectedTimeZoneVal();
		}
		
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at, 'UTC');
		$date->setTimezone($tz == null ? 'Asia/Tokyo' : $tz);
		$message->speaker_last_chat_time = $date->format('Y-m-d H:i:s');
		$message->created_at_utc = $message->created_at->format('Y-m-d H:i:s');

		return $this->createResponse($message);
	}

	/**
	 * @desc Get list of chat messages
	 * @author Myo Ko Ko @ 2020/10/07
	 * @last_maintained Myo Ko Ko @ 2020/10/07
	 * @param  string $type (scout, job-apply)
	 * @param  int $id (scout or jobapply id)
	 * @return application/json
	 */
	public function getMessage(string $type, int $id)
	{
		// $fields = [
		// 	'm.*',
		// 	'r.receiver_id',
		// 	'r.receiver_role_id',
		// 	'r.status',
		// 	'r.updated_at AS read_time',
		// ];
		if(Auth::user()->role_id == 1){
			$timezone = 'Asia/Tokyo';
			$timezoneVal = '+09:00';
		}
		else{
			$timezone = User::find(Auth::user()->id)->selectedTimeZoneVal();
			$timezoneVal = User::find(Auth::user()->id)->selectedTimeZone();
		} 

		$messages = DB::table('chat_messages AS m')
			->leftJoin('chat_message_receivers AS r','m.id','=','r.chat_message_id')
			->where([
				'scoutid_or_applyid' => $id,
				'type' => $type, 
			])
			->where('r.receiver_role_id','!=',1)
			// ->select($fields)
			
			->select('m.id','m.recruiter_id', 'm.jobseeker_id', 'm.speaker_id', 'm.speaker_role_id', 'm.scoutid_or_applyid', 'm.type', 'm.message', 'm.message_type', 'm.expired', 
			// DB::raw("convert_tz(m.created_at,'+00:00','$timezoneVal') as created_at"), 'm.updated_at',
			'm.created_at','m.updated_at',
			'r.receiver_id',
			'r.receiver_role_id',
			'r.status',
			// DB::raw("convert_tz(r.updated_at,'+00:00','$timezoneVal') as read_time"))
			'r.updated_at AS read_time')
			->groupBy('m.id')
			->distinct()
			->orderBy('m.created_at','desc')
			->paginate(15);

		return response()->json($messages);
	}
	public function fileDownload($id)
	{
		$message = ChatMessage::findOrFail($id);
		if ($message->message_type != 'file') {
			return $this->createErrorResponse('Undefine message type');
		}
		$file = './uploads/chat/'.$message->message;
		if (!file_exists($file)) {
			return $this->createErrorResponse('File no longer exists');
		}
		return response()->file($file);
	}
	public function downloadFile(int $id)
	{
		$message = ChatMessage::findOrFail($id);
		if ($message->message_type != 'file') {
			return $this->createErrorResponse('Undefine message type');
		}
		$file = './uploads/chat/'.$message->message;
		if (!file_exists($file)) {
			return $this->createErrorResponse('File no longer exists');
		}
		return response()->file($file);
	}

	public function deleteMessage($id)
	{
		$message = ChatMessage::findOrFail($id);
		$receivers = DB::table('chat_message_receivers')
			->where('chat_message_id', $message->id)
			->where('status',FALSE)
			->select('receiver_id','receiver_role_id')
			->get();
		$last_message = ChatMessage::where('id','<',$message->id)
			->where('scoutid_or_applyid',$message->scoutid_or_applyid)
			->where('type',$message->type)
			->orderBy('id','DESC')
			->first();
		$message->unread_receivers = $receivers;
		$message->last_chat_time = $message->created_at->format('Y-m-d H:i:s');
		$message->second_last_chat_time = $last_message ? $last_message->created_at->format('Y-m-d H:i:s') : null;
		
		// --Delete data
		DB::beginTransaction();
		try {
			if ($message->message_type == 'file') {
				// --Delete file using trait function
				$this->deleteFile($message);
			}
			ChatMessage::findOrFail($id)->delete();
			DB::table('chat_message_receivers')
				->where('chat_message_id', $message->id)
				->delete();
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			$this->createErrorResponose($e->getMessage());
		}
		return $this->createResponse($message);
	}

	/**
	 * @desc Get meta information for chat box
	 * @author Myo Ko Ko @ 2020/10/07
	 * @last_maintained Myo Ko Ko @ 2020/10/07
	 * @param  string $type (scout, job-apply)
	 * @param  int $id (scoutid or applyid)
	 * @return application/json
	 */
	public function getChatMetadata(string $type, int $id)
	{
		

		$job = null;
		$jobseeker = null; 
		$recruiter = null;
		$admin = null;
		if ($type == config('config.payment_job_type.scout'))
		{
			$job = DB::table('jobs')
					->join('scouts', 'jobs.id', 'scouts.job_id')
					->where('scouts.id', $id)
					->select('jobs.*','scouts.jobseeker_id','scouts.recruiter_id','scouts.management_number')
					->first();

		} 
		elseif ($type == config('config.payment_job_type.job_apply'))
		{
			$job = DB::table('jobs')
					->join('job_applies', 'jobs.id', 'job_applies.job_id')
					->where('job_applies.id', $id)
					->select('jobs.*','job_applies.jobseeker_id','jobs.recruiter_id','job_applies.management_number')
					->first();
		}
		$jobseeker = Jobseeker::where('id', $job->jobseeker_id)->first();
		$recruiter = Recruiter::where('id', $job->recruiter_id)->first();
		$admin = User::where('role_id', 1)->first();

		return $this->createResponse([
			'job' => $job,
			'jobseeker' => $jobseeker,
			'recruiter' => $recruiter,
			'admin' => $admin,
		]);
	}

	/**
	 * @desc Mark a message as read
	 * @author Myo Ko Ko @ 2020/10/07
	 * @last_maintained Myo Ko Ko @ 2020/10/07
	 * @param  string $type (scout, job-apply)
	 * @param  int $id (scoutid or applyid) 
	 * @param  string $role (1,2,3)
	 * @return application/json
	 */
	public function markMessageRead(string $type, int $id, string $role, int $opposite_id)
	{
		switch ($role) {
			case 1:
				$receiver_role_id = 1;
				$receiver_id = User::where('role_id', 1)->first()->id;
				$timezone = 'Asia/Tokyo';
			break;

			case 2:
				$receiver_role_id = 2;
				$receiver_id = $this->getLoginRecruiterID();
				$user_id = Jobseeker::where('id', $opposite_id)->first()->user_id;
				$timezone = User::find($user_id)->selectedTimeZoneVal();
			break;

			case 3:
				$receiver_role_id = 3;
				$receiver_id = $this->getLoginJobseekerID();
				$user_id = Recruiter::where('id', $opposite_id)->first()->user_id;
				$timezone = User::find($user_id)->selectedTimeZoneVal();
			break;
		}

		$ids = DB::table('chat_messages')
			->join('chat_message_receivers','chat_messages.id','=','chat_message_receivers.chat_message_id')
			->where([
				'scoutid_or_applyid' => $id,
				'type' => $type,
				'receiver_id' => $receiver_id,
				'receiver_role_id' => $receiver_role_id,
				'status' => 0,
			])
			->pluck('chat_message_receivers.id');

			$now = Carbon::now();			
			$utc_now = Carbon::now();
			DB::table('chat_message_receivers')
				->whereIn('id', $ids)
				->update([
					'status' => 1,
					'updated_at' => $now,
				]);
			
			// --Get affected message ids
			$ids = DB::table('chat_message_receivers')
					->whereIn('id', $ids)
					->pluck('chat_message_id');

			$user_time_zone = new DateTimeZone($timezone);
			$now->setTimezone($user_time_zone);
	
			return $this->createResponse([
				'message_ids' => $ids,
				'read_time' => $now->format('Y-m-d H:i:s'),
				'read_time_utc' => $utc_now->format('Y-m-d H:i:s'),
			]);
	}

	/**
	 * @desc Send unread notification
	 * @author Myo Ko Ko @ 2020/10/27
	 * @last_maintained Myo Ko Ko @ 2020/10/27
	 * @param  Request $request
	 * @return application/json
	 */
	public function sendMail(Request $request)
    {
		$data = [
			'from' => $request->from,
			'date' => $request->date,
			'number' => $request->number,
			'title' => $request->title,
			'to' => $request->to,
			'to_name' => $request->to_name,
			'jobseeker_email' => $request->jobseeker_email,
			'recruiter_email' => $request->recruiter_email,
			'datetime' => Carbon::now('Asia/Tokyo')->format('Y-m-d H:i:s'),
		];

		$messageId = $request->input('message_id');
		$receiver_role_id = $request->input('receiver_role_id');
		$chatMessage = ChatMessage::findOrFail($messageId);
		$receiver = DB::table('chat_message_receivers')->where([
			'chat_message_id' => $chatMessage->id,
			'receiver_role_id' => $receiver_role_id,			
		])->first();
		$timezone = null; // --Passed by reference to get value
		// $timezone = User::find(Auth::user()->id)->selectedTimeZoneVal();
		if (!$this->_allowMailing($receiver->receiver_id, $receiver_role_id, $timezone)) {
			CustomLog::info(sprintf('Receiver %s with role %s does not allow sending mail',$receiver->receiver_id,$receiver_role_id));
			return $this->createResponse([]);
		}

		// Convert chat message created_at to timezone (default Tokyo)
		$created_at = $chatMessage->created_at;
		$date = Carbon::createFromFormat('Y-m-d H:i:s', $created_at, 'UTC');
		$date->setTimezone($timezone == null ? 'Asia/Tokyo' : $timezone);
		$data['datetime'] = $date->format('Y-m-d H:i:s');

		// Find unread count of message	type 1	
		$type1Unread = DB::table('chat_message_receivers AS r')
			->join('chat_messages AS m','r.chat_message_id','m.id')
			->where([
				'scoutid_or_applyid' => $chatMessage->scoutid_or_applyid,
				'type' => $chatMessage->type,
				'r.status' => 0,
				'r.receiver_role_id' => $receiver_role_id,
			])
			->select('r.*')
			->count();
		if ($type1Unread > 0) {				
			$this->sendMailInternal($data, $chatMessage, $receiver_role_id);
		}

		return $this->createResponse([
			'status' => sprintf('Email sent to %s', $data['to']),
		]);
	}

	private function sendMailInternal($data, $m, $receiver_role_id) 
	{
		// Get last sent timestamp
		$last = DB::table('chat_message_receivers AS r')
			->join('chat_messages AS m','r.chat_message_id','m.id')
			->where([
				'scoutid_or_applyid' => $m->scoutid_or_applyid,
				'type' => $m->type,
				'receiver_role_id' => $receiver_role_id,
			])
			->whereNotNull('email_sent_at')
			->orderBy('r.id','desc')
			->first();

		// Sent email only after 1 hr
		if ($last == null || Carbon::now()->diffInHours($last->email_sent_at) >= 1) {
			Mail::to($data['to'])->send(new \App\Mail\MessageNotificationMail($data));
			// If receiver is admin, also send to extra mails
			if ($receiver_role_id == 1) {
				$admin = User::where('role_id', 1)->first();
				if ($admin && $admin->extra) {
					$extras = explode(',',$admin->extra);
					foreach ($extras as $e){
						if ($e){
							Mail::to($e)->send(new \App\Mail\MessageNotificationMail($data));
						}
					}
				}
			}
			$sentTime = Carbon::now();
		} else {
			$sentTime = $last->email_sent_at;
		}

		// Update last email sent timestamp on all unread message
		DB::table('chat_message_receivers AS r')
		->join('chat_messages AS m','r.chat_message_id','m.id')
		->where([
			'scoutid_or_applyid' => $m->scoutid_or_applyid,
			'type' => $m->type,
			'status' => 0,
			'receiver_role_id' => $receiver_role_id,
		])
		->update([ 'email_sent_at' => $sentTime, ]);
	}

	private function _allowMailing($js_r_id, $receiver_role_id, &$timezone) {

		if ($receiver_role_id == 2) {
			// --Check recruiter allow receive email
			$user = DB::table('recruiters')
				->join('users','recruiters.user_id','users.id')
				->where([
					'recruiters.id' => $js_r_id,
					'users.record_status' => 1,
				])
				->select('users.*')
				->first();
			if ($user != null && $user->extra) {
				$timezone_val = json_decode($user->extra);
				$timezone = $timezone_val->timezone;
			}
			return $user != null && $user->email_send;
		} else if ($receiver_role_id == 3) {
			// --Check jobseeker allow receive email
			$user = DB::table('jobseekers')
			->join('users','jobseekers.user_id','users.id')
			->where([
				'jobseekers.id' => $js_r_id,
				'users.record_status' => 1,
			])
			->select('users.*')
			->first();
			if ($user != null && $user->extra) {
				$timezone_val = json_decode($user->extra);
				$timezone = $timezone_val->timezone;
			}
			return $user != null && $user->email_send;
		} else {
			// --Check admin allow receive email
			$user = DB::table('users')
			->where([
				'users.id' => $js_r_id,
				'users.record_status' => 1,
				'users.role_id' => 1,
			])
			->select('users.*')
			->first();
			return $user != null && $user->email_send;
		}
	}	
}