<?php 

namespace App\Http\Controllers\API\V1\Recruiter;

use App\Http\Controllers\API\V1\BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Model\Recruiter;
use DB;
use Carbon\Carbon;
use App\Helpers\CustomLog;

class RecruiterProfileController extends BaseController 
{
	public function getProfile($id=null,Request $request)
	{
        // return "hello";
        if($id == null){
            $id = $this->getLoginRecruiterID();
        }
		
		$recruiter = Recruiter::with('related_images')->findOrFail($id);
		return $this->createResponse($recruiter);
    }    

	public function updateProfile($id=null,Request $request)
	{
        if($id == null){
            $id = $this->getLoginRecruiterID();
        }
		$recruiter = Recruiter::with('related_images')->findOrFail($id);
		
		DB::beginTransaction();
		try {
            $user = User::findOrFail($recruiter->user_id);			
			$user->name = $request->input('recruiter_name');
			$user->update();
			
			$recruiter->recruiter_name = $request->input('recruiter_name');
			$recruiter->recruiter_nick_name = $request->input('recruiter_nick_name');
			$recruiter->establishment_date = $request->input('establishment_date');
			// $recruiter->establishment_date = Carbon::createFromDate($request->input('establishment_year'), $request->input('establishment_month'), 1);
			$recruiter->representative_name = $request->input('representative_name');
			$recruiter->num_of_employees = $request->input('num_of_employees');
			$recruiter->business_description = $request->input('business_description');
			$recruiter->address = $request->input('address');
			$recruiter->phone1 = $request->input('phone1');
			$recruiter->phone2 = $request->input('phone2');		
			$recruiter->website = $request->input('website');
			$recruiter->video = $request->input('video');
			$recruiter->incharge_name = $request->input('incharge_name');
			$recruiter->incharge_name_furigana = $request->input('incharge_name_furigana');
			$recruiter->account_id = $request->input('account_id');
			$recruiter->question = $request->input('question');
			$recruiter->answer = $request->input('answer');
			$recruiter->company_pr = $request->input('company_pr');

			/* Logo */
			if ($request->input('delete_logo') === 'true') {
				delete_file($recruiter->logo, 'recruiters/images');
				$recruiter->logo = '';
			}			
			if ($request->file('logo') != null) {
				// --Delete existing file
				delete_file($recruiter->logo, 'recruiters/images');

				// --Define file meta
				$file = $request->file('logo');
				$ext = $file->getClientOriginalExtension();
				$filename = $recruiter->recruiter_number.'_logo.'.$ext;

				// --Put file
				put_file($file, $filename, 'recruiters/images');
				$recruiter->logo = $filename;
			}

			/* Incharge Photo */
			if ($request->input('delete_incharge_photo') === 'true') {
				delete_file($recruiter->incharge_photo, 'recruiters/images');
				$recruiter->incharge_photo = '';
			}
			if ($request->file('incharge_photo') != null) {
				// --Delete existing file
				delete_file($recruiter->incharge_photo, 'recruiters/images');

				// --Define file meta
				$file = $request->file('incharge_photo');
				$ext = $file->getClientOriginalExtension();
				$filename = $recruiter->recruiter_number.'_incharge_photo.'.$ext;

				// --Put file
				put_file($file, $filename, 'recruiters/images');
				$recruiter->incharge_photo = $filename;
			}

			/* Related Images deletion */
			if ($request->input('delete_related_images') != null) {
				$to_delete = $request->input('delete_related_images');
				foreach	($to_delete as $filename) {
					$old = $recruiter->related_images()->where('url', 'LIKE', $filename.'%')->first();
					if ($old) {
						delete_file($old->url, 'recruiters/images');
						$old->delete();
					}
				}
			}			

			/* Related Images */
			if ($request->file('related_images') != null) {
				$files = ($request->all())['related_images'];
				foreach ($files as $fileInfo) {
					if (isset($fileInfo['file'])) {
						$file = $fileInfo['file'];
						$prefix = (explode('.', $fileInfo['url']))[0];
						// --delete old record & file
						$old = $recruiter->related_images()->where('url', 'LIKE', $prefix.'%')->first();
						if ($old) {
							delete_file($old->url, 'recruiters/images');
							$old->delete();
						}

						// --put new file & save in db
						$ext = $file->getClientOriginalExtension();
						$filename = $prefix.".".$ext;
						put_file($file, $filename, 'recruiters/images');
						$recruiter->related_images()->updateOrCreate([
							'url' => $filename,
							'file_type' => 'photo',
							'user_type' => 'recruiter',
						]);
					}
				}
			}

			$recruiter->save();
			DB::commit();
			CustomLog::info("Update ".$recruiter->id);
		} catch (\Exception $e) {
			DB::rollback();
			CustomLog::error($e->getMessage());			
			return $this->createErrorResponse($e->getMessage(), 500);
		}

		return $this->createResponse($recruiter, 200);
	}
}