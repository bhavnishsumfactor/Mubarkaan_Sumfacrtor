<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Product;
use App\Jobs\CreateSiteMap;
use Carbon\Carbon;
use App\Helpers\FileHelper;
use App\Helpers\SitemapHelper;

class siteMapsController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function generate()
    {
        $this->dispatch(new CreateSiteMap());
        return jsonresponse(true, __('NOTI_SITEMAP_GENERATED'));
    }

    public function view()
    {
        $fileExists = FileHelper::fileExists('sitemap.xml');
        if ($fileExists === false) {
            $created = SitemapHelper::generateSitemap();
            if ($created) {
                return jsonresponse(true, '');
            }
        } else {
            return jsonresponse(true, '');
        }
    }
}
