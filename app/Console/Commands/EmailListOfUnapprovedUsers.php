<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\ApiResponseController;
use App\Models\User;
use App\Notifications\EmailUserUnapprovalListNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use PhpParser\Node\Stmt\TryCatch;

class EmailListOfUnapprovedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ListOfUnapprovedUsers:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will email unapproved user list to admin';

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

        try {
            $response[] = "";
            $adminEmail = User::select('email')
                ->where('user_type', 'admin')
                ->get();
            $users = User::where('user_type', '!=', 'admin')
                ->where('is_approved', 0)
                ->get();

            if (count($users) > 0) {
                Notification::route('mail', $adminEmail)->notify(
                    new EmailUserUnapprovalListNotification($users)
                );
                dd('Email Sent Successfully...');
            } else {
                dd('No Data to send...');
            }
        } catch (\Throwable $e) {
            dd(ApiResponseController::responseServerError($e->getMessage()));
        }
    }
}
