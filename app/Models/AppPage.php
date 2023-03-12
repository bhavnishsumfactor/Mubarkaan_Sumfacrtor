<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\AdminYokartModel;

class AppPage extends AdminYokartModel
{
    use HasFactory;

    const PAGE_TYPE_HOME = 1;
    const PAGE_TYPE_STATIC = 2;
    const PAGE_TYPE_LINK = 3;
    const PAGE_TYPE_CMS = 4;

    public $timestamps = false;

    protected $primaryKey = 'page_id';
    protected $fillable = [
        'page_title', 'page_slug', 'page_type'
    ];

    public static function appPages()
    {
        return AppPage::where('page_type', '<>', self::PAGE_TYPE_HOME)->get()->toArray();
    }
}
