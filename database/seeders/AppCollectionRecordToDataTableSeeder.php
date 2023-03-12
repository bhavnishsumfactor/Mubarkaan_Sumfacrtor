<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use App\Models\AppExplore;
use App\Models\AppCollectionToRecord;
use App\Models\AppCollectionRecordToData;
use App\Models\AppCollectionSlider;
use App\Models\AppCollectionSliderLink;

class AppCollectionRecordToDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppCollectionToRecord::truncate();
        AppCollectionRecordToData::truncate();
        AppExplore::truncate();
        AppCollectionSlider::truncate();
        AppCollectionSliderLink::truncate();
    }
}
