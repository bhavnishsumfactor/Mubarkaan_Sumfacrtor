<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Admin\EmailArchive;

class EmailArchivesController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function getListing(Request $request)
    {
        $emails = EmailArchive::getEmails($request->all());
        return jsonresponse(true, null, $emails);
    }

    public function getEmailDetails(Request $request, $id)
    {
        return jsonresponse(true, null, EmailArchive::getRecordsById($id));
    }
}
