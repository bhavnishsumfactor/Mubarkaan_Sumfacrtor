<?php

namespace App\Models\Admin;

use App\Models\Admin\AdminYokartModel;
use Auth;
use DB;

class Todo extends AdminYokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'todo_id';
    protected $fillable = ['todo_admin_id', 'todo_description', 'todo_status', 'todo_reminder_at', 'todo_created_at'];

    public static function getTodos($row = 0)
    {
        return Todo::select(
            'todo_id',
            'todo_description',
            'todo_status',
            'todo_reminder_at',
            'todo_created_at',
            DB::raw("(CASE WHEN todo_reminder_at IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, NOW(), todo_reminder_at) ELSE 0 END) as todo_timediff")
        )
            ->where('todo_admin_id', Auth::guard('admin')->user()->admin_id)
            ->latest('todo_id')
            ->offset($row)
            ->take(15)
            ->get();
    }

    public static function getTodoCount()
    {
        return Todo::where('todo_admin_id', Auth::guard('admin')->user()->admin_id)
            ->count();
    }

    public static function getReminders($date)
    {
        return Todo::select('todo_id', 'todo_admin_id', 'todo_description', 'todo_status', 'todo_reminder_at', 'todo_created_at')
            ->where('todo_reminder_at', '<=', $date)
            ->where('todo_reminder_at', '<>', null)
            ->get();
    }

    public static function getTodoNotificationCount()
    {
        return Todo::where('todo_admin_id', Auth::guard('admin')->user()->admin_id)
            ->where('todo_status', '=', config('constants.NO'))->count();
    }
}
