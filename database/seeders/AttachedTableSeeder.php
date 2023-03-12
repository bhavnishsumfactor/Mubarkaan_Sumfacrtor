<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttachedFile;
use DB;

class AttachedTableSeeder extends Seeder
{

    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    

    public function run()
    {
        AttachedFile::truncate();
        $sql =  base_path('public/dummydata/yokart_attached_files.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
