<?php

namespace App\Listeners;

use App\Event\TeacherAssignedToStudent;
use App\Notifications\AssignTeacherNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendEmailNotificationToTeacher
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
     * @param  \App\Event\TeacherAssignedToStudent  $event
     * @return void
     */
    public function handle(TeacherAssignedToStudent $event)
    {
        try {
            $mailData = [
                'name' => $event->teacher->name,
                'body' => 'Meet your new student : ' . $event->student->name,
                'thanks' => 'Thank you',
            ];
            Notification::send($event->teacher, new AssignTeacherNotification($mailData));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
