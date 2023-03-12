<?php

namespace App\Models;

use App\Models\YokartModel;

class Theme extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'theme_id';

    public static function getActiveTheme()
    {
        return Theme::select('theme_slug')->where('theme_publish', 1)->where('theme_default', 1)->first();
    }

    public static function getAll()
    {
        return Theme::select('theme_id', 'theme_name', 'theme_slug')->get();
    }
}
