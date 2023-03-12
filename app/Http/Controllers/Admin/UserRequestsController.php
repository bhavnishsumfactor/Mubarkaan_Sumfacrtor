<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\UsersRequestHistory;

class UserRequestsController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function getListing(Request $request)
    {
        $data = [];
        $data['requests'] = UsersRequestHistory::getAllUserRequests($request->all());
        
        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['requests']->count() == 0) {
            $data['showEmpty'] = 1;
        }
        return jsonresponse(true, '', $data);
    }
}
