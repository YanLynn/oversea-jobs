<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\API\V1\ClearChatAttachmentsTrait;
use Carbon\Carbon;
use App\Helpers\CustomLog;

class ClearChatAttachments extends Command
{
	use ClearChatAttachmentsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attachments:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear chat file upload attachements older than 10 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$count = $this->clearExpireFile();
		$now = Carbon::now()->format('Y-m-d H:i:s');
		$message = "Clear chat attachements schedule task has run on ".$now."\r\n Total "
			.$count." file(s) cleared.";
    Mail::raw($message, function ($mail) {
			$mail->from('admin@borderless-working.jp');
			$mail->to('thuzar@management-partners.co.jp')
				->subject('Schedule Task run');
		});
		// CustomLog::info($message);
		$this->info($message);
    }
}
