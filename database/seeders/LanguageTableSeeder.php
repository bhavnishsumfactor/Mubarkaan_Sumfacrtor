<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageTableSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(string $languageCode = "")
    {
        Language::truncate();
        Language::insert(Language::LANGUAGES);
        if (!empty($languageCode)) {
            Language::where('lang_default', '=', config('constants.YES'))->orWhere('lang_view_default', '=', config('constants.YES'))->update([
                'lang_default' => config('constants.NO'),
                'lang_view_default' => config('constants.NO')
            ]);
            $language = Language::where('lang_code', $languageCode)->first();
            $language->lang_default = config('constants.YES');
            $language->lang_view_default = config('constants.YES');
            $language->save();
        }
    }
}
