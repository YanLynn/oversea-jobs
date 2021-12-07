<?php

namespace App\Http\Controllers\API\V1\Admin;

use DB;
use App\Model\Job;
use App\Model\JobApply;
use App\Model\Recruiter;
use App\Model\Scout;
use App\User;
use App\Http\Controllers\API\V1\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\CustomLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
//┌───────────────────────────────────────────────
//│ Controller Name　： AdminRecruiterController
//│ Created Zayar Phone Naing
//│ Created Date　：2020/06/30
//│ Description : Actions for admin related tasks
//├───────────────────────────────────────────────
//│ Copyright notice　：Copyright (C) 2020 Management Partners Myanmar Co.,Ltd.
//├───────────────────────────────────────────────
//│ Repair history　　：2020/06/30　-> Created New
//└───────────────────────────────────────────────
class AdminRecruiterController extends BaseController
{
    /**
     * @recruiter job list with pagination
     * @author Zayar Phone Naing @ 2020/06/30
     * @last_maintained @ 2020/07/10
     * @param request
     * @return response
     */
    public function jobList(Request $request)
    {
        $time_now = Carbon::now()->subDays(29)->format('Y-m-d')." 00:00:00";
        Job::where('job_post_date','<',$time_now)->update(['record_status' => 3]);
       
        $tableData      = $request->input('tableData');
        $filteredData   = $request->input('filteredData');
        $selected       = $request->input('selected');
        $columns        = $request->input('columns');

        $company_name   = $filteredData['company_name'] ?? null;
        $company_number = $filteredData['company_number'] ?? null;
        $job_number     = $filteredData['job_number'] ?? null;
        $job_title      = $filteredData['job_title'] ?? null;
        $recruiter_status = $filteredData['recruiter_status'] ? [1,2,3] : [1, 2];
        $timezone = "+09:00";

        $query = Job::where('jobs.job_post_status', 'post')->where('jobs.record_status', '<>', 0)
            ->leftJoin('recruiters', 'jobs.recruiter_id', '=', 'recruiters.id')
            ->where(function ($q) use ($company_name, $company_number) {
                if (!is_null($company_name))
                    $q->where('recruiters.recruiter_name', 'LIKE', "%{$company_name}%");
                if (!is_null($company_number))
                    $q->where('recruiters.recruiter_number', 'LIKE', "%{$company_number}%");
            })
            ->leftJoin('scouts', 'jobs.id', '=', 'scouts.job_id')
            ->leftJoin('job_applies', 'jobs.id', '=', 'job_applies.job_id')
            ->select(
                'jobs.id',
                'jobs.job_number',
                'jobs.title',
                'jobs.record_status',
                
                'recruiters.recruiter_name',
                'recruiters.recruiter_number',
                'recruiters.id as recruiter_id',
                DB::raw("convert_tz(jobs.job_post_date,'+00:00','$timezone') as job_post_date,(SELECT COUNT(job_applies.jobseeker_id)FROM job_applies WHERE job_applies.job_apply_status = '検討中' AND jobs.id = job_applies.job_id) as jobapplied_count, (SELECT COUNT(scouts.jobseeker_id )FROM scouts WHERE scouts.scout_status = '興味あり' AND jobs.id = scouts.job_id)as scout_count"),
                // DB::raw("COUNT(DISTINCT scouts.id) as scout_count"),
                // DB::raw("COUNT(DISTINCT job_applies.id) as jobapplied_count")
            )
            ->groupBy('jobs.id');
            
        $query->whereIn('recruiters.record_status', $recruiter_status);

        if (!is_null($job_number))
            $query->where('jobs.job_number', 'LIKE','%'. $job_number . '%');

        if (!is_null($job_title))
            $query->where('jobs.title', 'LIKE', '%'. $job_title . '%');

        if (count($filteredData['status']) > 0)
            $query->whereIn('jobs.record_status', $filteredData['status']);

        /* $query = Job::where('job_post_status', 'post')->where('record_status', '<>', 0)
            ->with('recruiter')->whereHas('recruiter', function ($q) use ($company_name, $company_number) {
                if (!is_null($company_name))
                    $q->where('recruiter_name', 'LIKE', "{$company_name}%");
                if (!is_null($company_number))
                    $q->where('recruiter_number', 'LIKE', "{$company_number}%");
            })
            ->with('jobApply')
            ->with('scout');

        if (!is_null($job_number))
            $query->where('job_number', 'LIKE', $job_number . '%');

        if (!is_null($job_title))
            $query->where('title', 'LIKE', $job_title . '%');

        if (count($filteredData['status']) > 0)
            $query->whereIn('record_status', $filteredData['status']); */

        $projects = $query->orderBy($tableData['column'] == 9 ? 'jobs.created_at' : $columns[$tableData['column']]['label'], $tableData['dir'])->paginate($tableData['length']);
        return response()->json($projects, 200);
    }

    /**
     * @delete job of recruiter by updating status 1 -> 0
     * @author Zayar Phone Naing @2020/06/30
     * @last_maintained 
     * @param  
     * @return response
     */
    public function deleteJob(Request $request)
    {
        $relate = array('jobApply','scout');
     
        $get_jobApply_Scout_Count =  Job::with($relate)->whereHas('jobApply',function($q) use ($request){
             $q->whereIn('job_id',$request->checked_data);
        })->orWhere(function($q) use ($request){
            $q->whereHas('scout',function ($q) use ($request){
              $q->whereIn('job_id',$request->checked_data);
            });
        })->count();
        
        if($get_jobApply_Scout_Count > 0){
            $get_relate_job =  Job::with($relate)->whereHas('jobApply',function($q) use ($request){
                $q->whereIn('job_id',$request->checked_data);
           })->orWhere(function($q) use ($request){
               $q->whereHas('scout',function ($q) use ($request){
                 $q->whereIn('job_id',$request->checked_data);
               });
           })->get();
           
            $ids_in_transactions = [];
            foreach ($get_relate_job as $k => $v) {
                array_push($ids_in_transactions, $v->id);
            } 
            $diff_favjob_id =  array_diff($request->checked_data,$ids_in_transactions);
            
            //delete job
            Job::whereIn('id', $diff_favjob_id)->delete();
            CustomLog::info("Delete" . json_encode($diff_favjob_id));
            return $this->createResponse($ids_in_transactions, 200);

        
        }else{
            Job::whereIn('id', $request->checked_data)->delete();
            CustomLog::info("Delete" . json_encode($request->checked_data));
            return $this->createResponse("Successfully Deleted", 200);
        }
        
    }

    /**
     * @change status of the job of recruiter
     * @author Zayar Phone Naing @ 2020/07/07
     * @last_maintained 
     * @param  id(job), status - private (1) 非公開, public (2) 公開, stop (3) 停止, delete (0)
     * @return response
     */
    public function changeJobStatus(Request $request)
    {
        $jobId = $request->id ?? null;
        $changeRecordStatus = $request->status ?? null;
        Job::where('id', $jobId)->update(['record_status' => $changeRecordStatus]);
        CustomLog::info("Update" . json_encode($request->id));
        return $this->createResponse("Successfully Changed", 200);
    }

    // public function changeStatus($id)
    // {
    //     $changeStatus = Recruiter::find($id);
    //     if( $changeStatus->record_status == 2 ) {
    //         $changeStatus->record_status =1;
    //     }
    //     else if( $changeStatus->record_status == 1) {
    //         $changeStatus->record_status =2;
    //     }

    //    $changeStatus->save();
    //    return $this->createResponse(null, 200);
    // }

    public function changeStatus(Request $request)
	{
        try {
            DB::beginTransaction();
            $recruiterData = Recruiter::find($request->id);
            $recruiterData->record_status = $request->status;
            if($request->status === 3){
                $recruiterData->recruiter_number   .= 'T';
                $recruiterData->favourite_jobseeker_ids   = NULL;
                Scout::where('recruiter_id', $request->id)
                    ->whereIn('scout_status', ['回答待ち', '興味あり', '期限切れ'])
                    ->delete();
            }
            $recruiterData->save();
            // Recruiter::where('id', $request->id)->update(['record_status' => $request->status]);
            User::where('id', $recruiterData->user_id)->update(['record_status' => $request->status]);
            CustomLog::info("ChangeStatus " . $request->input('id'));
            DB::commit();
            return $this->createResponse("Successfully changed", 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->createErrorResponse($e->getMessage(), 500);
        }
	}

    /**
     * @recruiter  list with pagination
     * @author waizin @ 2020/07/06
     * @last_maintained 
     * @param  
     * @return Object
     */
    public function index(Request $request)
    {
        $tableData = $request->input('tableData');
        $filteredData = $request->input('filteredData');
        $selected = $request->input('selected');
        $columns = $request->input('columns');
        $query = Recruiter::select('recruiters.*','users.password_locked', 'users.email as user_email')
                            ->leftJoin('users','users.id','=','recruiters.user_id')
                            ->where('users.email_verified',1);
        
        if (sizeof($filteredData) > 1) {

            if (sizeof($filteredData['recruiter_recordstatus']) < 1) {
                $filteredData['recruiter_recordstatus'] = [0,1,2,3];
                // $filteredData['recruiter_recordstatus'] = [1, 2];
            }
            $query->where(function ($query) use ($filteredData) {
                $query->where('recruiters.recruiter_name', 'like', '%' . $filteredData['freeword'] . '%')
                      ->orWhere('recruiters.recruiter_number', 'like', '%' . $filteredData['freeword'] . '%')
                      ->orWhere('recruiters.email', 'like', '%' . $filteredData['freeword'] . '%');
            });
            $query->whereIn('recruiters.record_status', $filteredData['recruiter_recordstatus']);
        }
        $projects = $query->orderBy($columns[$tableData['column']]['label'], $tableData['dir'])->paginate($tableData['length']);

        // check account login was locked
        $projects->map(function($item) {
            $item['login_locked'] = (Cache::get($item['user_email'].'2'.$item['user_id'], null) === 3) && ($item['record_status'] == 1) ? true : false;
            return $item;
        });

        return response()->json($projects, 200);
    }

    /**
     * @ delete record in recruiter list
     * @author waizin @ 2020/07/06
     * @last_maintained @ 2020/09/17 Zayar Phone Naing
     * @param request
     * @return response
     */
    public function delete(Request $request)
    {
        $ids_in_transactions = [];
        $relations = array('job', 'scout', 'payment_transaction');
        $implode_relations = "'" . implode ( "', '", $relations ) . "'";
         
        foreach ($request->checked_data as $delete_id){
            $result = Recruiter::where('id', $delete_id)->with($relations)->first();
                        // ->with('job')
                        // ->with('scout')
                        // ->with('payment_transaction')
                        // ->first();
            if ($this->getRelationsCountsResult($relations, $result))
                array_push($ids_in_transactions, $delete_id);
        }        

        $delete_recruiters = Recruiter::whereIn('id', $request->checked_data)
            ->doesntHave('job')
            ->doesntHave('scout')
            ->doesntHave('payment_transaction')
            ->get();
        // delete in both user table and recruiter table
        foreach ($delete_recruiters as $delete_recruiter) {
            User::where('id', $delete_recruiter->user_id)->delete();
            Recruiter::where('id', $delete_recruiter->id)->delete();
        }
        CustomLog::info("Delete" . json_encode($request->checked_data));

        if (count($ids_in_transactions) > 0)
            return $this->createResponse($ids_in_transactions, 200);

        return $this->createResponse("Successfully deleted", 200);
    }
    
}
