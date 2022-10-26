<?php

namespace App\Listeners;

use App\Event\UserApproved;
use App\Notifications\UserApprovalNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendEmailNotificationToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Event\UserApproved  $event
     * @return void
     */
    public function handle(UserApproved $event)
    {
        try {

            $mailData = [
                'name' => $event->user->name,
                'body' => 'Your profile has been approved. ',
                'thanks' => 'Thank you',
            ];

            // Notification::route('mail', $event->user->email)->notify(
            //     new UserApprovalNotification($mailData)
            // );
            Notification::send($event->user, new UserApprovalNotification($mailData));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
