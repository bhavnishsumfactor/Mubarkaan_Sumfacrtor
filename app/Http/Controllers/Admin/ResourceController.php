<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Resource;
use DB;
use App\Http\Controllers\YokartController;
use Spatie\Analytics\Exceptions\InvalidConfiguration;
use App\Models\Configuration;
use Illuminate\Support\Arr;
use Storage;

class ResourceController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    /***
    *  Get Resource Pages
    *
    */
    public function getListing(Request $request)
    {
        $resources = Resource::getResources($request->all());
        if (!empty($resources) && count($resources) > 0) {
            $resources =  Resource::buildTree($resources);
        }
        $records['listing'] = $resources;
        $records['showEmpty'] = 0;
        if (count($records['listing']) == 0) {
            $records['showEmpty'] = 1;
        }
        return jsonresponse(true, '', $records);
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        $records = Resource::searchRecords($request->input('search'));
        return jsonresponse(true, '', $records);
    }

    public function details(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $record = array();
        $details = Resource::details($id, $title);
        $record['description'] = str_replace("BASE_URL", url(''), $details->description);
        $record['showEmpty'] = 0;
        if (empty($details)) {
            $record['showEmpty'] = 1;
        }
        return jsonresponse(true, '', $record);
    }
}