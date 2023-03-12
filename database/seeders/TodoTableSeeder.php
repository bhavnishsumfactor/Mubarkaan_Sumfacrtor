<?php

namespace Database\Seeders;

use App\Models\Admin\Todo;
use Illuminate\Database\Seeder;

class TodoTableSeeder extends Seeder
{
    
  public $records = [
      [
        'todo_id' => 1,
        'todo_admin_id' => 1,
        'todo_description' => "Speak with shoes company for new order",
        'todo_status' => 0,
        'todo_reminder_at' => 0,
        'todo_created_at' => 0
      ],[
        'todo_id' => 2,
        'todo_admin_id' => 1,
        'todo_description' => "Review all in-progress orders",
        'todo_status' => 0,
        'todo_reminder_at' => 0,
        'todo_created_at' => 0
      ],[
        'todo_id' => 3,
        'todo_admin_id' => 1,
        'todo_description' => "Discuss revenue with the team over the weekend",
        'todo_status' => 0,
        'todo_reminder_at' => 0,
        'todo_created_at' => 0
      ]
    ];

  public function run()
  {
    Todo::truncate();
    Todo::insert($this->records);
  }
}
