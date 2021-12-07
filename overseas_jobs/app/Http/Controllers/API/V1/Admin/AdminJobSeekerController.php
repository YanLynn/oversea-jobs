<?php

namespace App\Http\Controllers\API\V1\Admin;
use App\Model\Jobseeker;
use App\Model\Scout;
use App\Model\JobApply;
use App\User;
use DB;
use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use App\Helpers\CustomLog;
use Illuminate\Support\Facades\Cache;

class AdminJobSeekerController extends BaseController
{
    public function index(Request $request)
    {        
        $tableData = $request->input('tableData');
        $filteredData = $request->input('filteredData');
        $selected = $request->input('selected');
        $columns = $request->input('columns');
        $query = Jobseeker::select('jobseekers.*', 'users.password_locked', 'users.email as user_email')
                ->leftJoin('users','users.id','=','jobseekers.user_id')
                ->where('users.email_verified',1);

        if (sizeof($filteredData) > 1) {
            if (sizeof($filteredData['jobseeker_recordstatus']) < 1) {
                $filteredData['jobseeker_recordstatus'] = [1, 2, 3];
            }
            $query->where(function ($query) use ($filteredData) {
                $query->where('jobseekers.jobseeker_name', 'like', '%' . $filteredData['freeword'] . '%')
                      ->orWhere('jobseekers.jobseeker_number', 'like', '%' . $filteredData['freeword'] . '%')
                      ->orWhere('jobseekers.email', 'like', '%' . $filteredData['freeword'] . '%');
            });
            $query->whereIn('jobseekers.record_status', $filteredData['jobseeker_recordstatus']);
        }

        $projects = $query->orderBy($columns[$tableData['column']]['label'], $tableData['dir'])->paginate($tableData['length']);

        // check account login was locked
        $projects->map(function($item) {
            $item['login_locked'] = (Cache::get($item['user_email'].'3'.$item['user_id'], null) === 3) && ($item['record_status'] == 1) ? true : false;
            return $item;
        });

        return response()->json($projects, 200);
        
    }

    public function changeStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            $jobseekerData = Jobseeker::find($request->id);
            $jobseekerData->record_status = $request->status;
            if($request->status === 3){
                $jobseekerData->jobseeker_number   .= 'T';
                $jobseekerData->favourite_job_ids   = NULL;
                $jobseekerData->recently_job_ids    = NULL;
                Scout::where('jobseeker_id', $request->id)
                    ->whereIn('scout_status', ['回答待ち', '興味あり', '期限切れ'])
                    ->delete();
                JobApply::where(['jobseeker_id' => $request->id, 'job_apply_status' => '検討中'])->delete();
            }
            $jobseekerData->save();
            // Jobseeker::where('id', $request->id)->update(['record_status' => $request->status]);
            User::where('id', $jobseekerData->user_id)->update(['record_status' => $request->status]);
            CustomLog::info("Change Status" . $jobseekerData->user_id);
            DB::commit();

            return $this->createResponse("Successfully Changed", 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->createErrorResponse($e->getMessage(), 500);
        }
    }

    public function delete(Request $request)
    {
        /* $jobseekerData = Jobseeker::whereIn('id', $request->checked_data)->where('record_status', '<>', 0)->get('user_id');
        foreach ($jobseekerData as $jobseeker) {
            User::where('id', $jobseeker->user_id)->delete();
        }
        Jobseeker::where('record_status', '<>', 0)->whereIn('id', $request->checked_data)->delete();
        CustomLog::info("Delete" . $jobseeker->user_id);
        return $this->createResponse("Successfully Deleted", 200); */
        $ids_in_transactions = [];
        $relations = array('desiredOccupations', 'desiredIndustries', 'education_histories', 'education_overseas', 'experience_histories', 'experience_overseas', 'industry_history', 'jobseeker_languages', 'job_apply', 'scout');
        foreach ($request->checked_data as $delete_id){
            $result = Jobseeker::where('id', $delete_id)->with($relations)->first();
                        // ->with('desiredOccupations')
                        // ->with('desiredIndustries')
                        // ->with('education_histories')
                        // ->with('education_overseas')
                        // ->with('experience_histories')
                        // ->with('experience_overseas')
                        // ->with('industry_history')
                        // ->with('jobseeker_languages')
                        // ->with('job_apply')
                        // ->with('scout')
                        // ->first();
            if ($this->getRelationsCountsResult($relations, $result))
                array_push($ids_in_transactions, $delete_id);
        }
        if (count($ids_in_transactions) > 0)
            return $this->createResponse($ids_in_transactions, 200);

        $delete_jobseekers = Jobseeker::whereIn('id', $request->checked_data)->get();
        // delete in both user table and jobseeker table
        foreach ($delete_jobseekers as $delete_jobseeker) {
            User::where('id', $delete_jobseeker->user_id)->delete();
            Jobseeker::where('id', $delete_jobseeker->id)->delete();
        }
        CustomLog::info("Delete" . json_encode($request->checked_data));
        return $this->createResponse("Successfully deleted", 200);
    }
    
}
