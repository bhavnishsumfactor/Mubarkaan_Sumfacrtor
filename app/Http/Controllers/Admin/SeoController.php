<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use File;
use App\Models\Configuration;

class SeoController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
		$this->middleware('admin');
    }

    public function getBingWebmasterXml(Request $request)
    {
        $url = '';
        $size = '';
        if (File::exists(public_path() . '/BingSiteAuth.xml')) {
            $url = url('/') . '/BingSiteAuth.xml';
            $size = File::size(public_path() . '/BingSiteAuth.xml');
        }
        return jsonresponse( true, null, ['url' => $url,'size' => $size] );
    }

    public function deleteBingWebmasterXml(Request $request)
    {
        File::delete(public_path() . '/BingSiteAuth.xml');
        return jsonresponse( true, __('NOTI_XML_FILE_REMOVED_SUCCESSFULLY'));
    }
    
    public function updateBingWebmasterXml(Request $request)
    {
        $file = $request->file('file');
        $file->move(public_path(), 'BingSiteAuth.xml');
        return jsonresponse( true, __('NOTI_XML_FILE_UPLOADED_SUCCESSFULLY'));
    }
    
    public function getGoogleWebmasterHtml(Request $request)
    {
        $url = '';
        $size = '';
        $name = '';
        $files = preg_grep('~^google.*\.html$~', scandir(public_path()));
        $file = current($files);
        if ($file != '') {
            $url = url('/') . '/' . $file;
            $name = $file;
            //$size = filesize(url('/') . '/' . $file);
        }
        return jsonresponse( true, null, ['url' => $url, 'name'=> $name, 'size'=> ''] );
    }

    public function deleteGoogleWebmasterHtml(Request $request)
    {
        $files = preg_grep('~^google.*\.html$~', scandir(public_path()));
        $file = current($files);
        File::delete(public_path() . '/' . $file);
        return jsonresponse( true, __('NOTI_HTML_FILE_REMOVED_SUCCESSFULLY'));
    }
    
    public function updateGoogleWebmasterHtml(Request $request)
    {
        $file = $request->file('file');
        $file->move(public_path(), $file->getClientOriginalName());
        return jsonresponse( true, __('NOTI_HTML_FILE_UPLOADED_SUCCESSFULLY'), ['url' => $file->getClientOriginalName()]);
    }
}
