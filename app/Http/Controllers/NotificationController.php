<?php

namespace App\Http\Controllers;

use App\Event\TeacherAssignedToStudent;
use App\Event\UserApproved;
use App\Http\Controllers\Api\ApiResponseController;
use Illuminate\Http\Request;
use App\Models\User;

class NotificationController extends Controller
{
    public function approval_notification($id)
    {
        try {
            $user = User::find($id);
            $response[] = "";
            $userApproved = event(new UserApproved($user));
            if ($userApproved) {
                $response = ApiResponseController::responseSuccess('Email Notification sent successfully');
            } else {
                $response = ApiResponseController::responseFailed('Email Notification failed');
            }
            return $response;
        } catch (\Throwable $e) {
            return ApiResponseController::responseServerError($e->getMessage());
        }
    }
    public function assign_teacher_notification(Request $req)
    {
        try {
            $student = User::where('id', $req->student_id)->where('user_type', 'Student')->get();
            $teacher = User::where('id', $req->teacher_id)->where('user_type', 'Teacher')->get();
            $teacherAssigned = event(new TeacherAssignedToStudent($teacher[0], $student[0]));

            $response[] = "";

            if ($teacherAssigned) {
                $response = ApiResponseController::responseSuccess('Email Notification sent successfully');
            } else {
                $response = ApiResponseController::responseFailed('Email Notification failed');
            }
            return $response;
        } catch (\Throwable $e) {
            return ApiResponseController::responseServerError($e->getMessage());
        }
    }
}
