<?php 

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;

use App\Model\ChatMessage;
use App\Helpers\CustomLog;
use Carbon\Carbon;
use DB;

trait ClearChatAttachmentsTrait 
{
	public function clearExpireFile()
	{
		// $upper_limit = Carbon::now()->subDays(10);
		$upper_limit = Carbon::now()->subMinutes(5);
		// --Fetch all expired records
		$expired = ChatMessage::active()
			->where('created_at','<',$upper_limit)
			->where('message_type','file')
			->get();
		$ids = [];
		foreach ($expired as $message) {
			// --Todo: check for server pathing
			// $file = public_path('uploads/chat/'.$message->message);
			// $file = './uploads/chat/'.$message->message;
			$file = '/home/sites/testoverseas-jobs.management-partners.co.jp/web/uploads/chat/'.$message->message;
			if (file_exists($file)) {
				unlink($file);
				CustomLog::info("Chat attachment with ID {".$message->id."} file name {".$message->message."} was removed.");
			}
			$ids[] = $message->id;
		}
		if (count($ids) > 0) {
			// --Update database
			DB::table('chat_messages')
				->whereIn('id', $ids)
				->update(['expired' => 1]);
		}
		return \count($ids);
	}

	public function deleteFile($message)
	{
		if ($message->message_type != 'file') return;
		$filename = $message->message;
		delete_file($filename, 'chat');
		CustomLog::info("Chat attachment with ID {".$message->id."} file name {".$message->message."} was removed.");
	}
}