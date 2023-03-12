<?php

namespace App\Models\Admin;

use App\Models\Admin\AdminYokartModel;

class EmailArchive extends AdminYokartModel
{
    public $timestamps = false;

    protected $fillable = [
        'emailarchive_to_email', 'emailarchive_tpl_name','emailarchive_body', 'emailarchive_subject', 'emailarchive_headers', 'emailarchive_created_at'
    ];

    public static function getEmails($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = EmailArchive::select('emailarchive_id', 'emailarchive_to_email', 'emailarchive_tpl_name', 'emailarchive_subject', 'emailarchive_body', 'emailarchive_headers', 'emailarchive_created_at');
        if (!empty($request['search'])) {
            $query->where('emailarchive_to_email', 'like', '%' . $request['search'] . '%')
            ->orWhere('emailarchive_tpl_name', 'like', '%' . $request['search'] . '%')
            ->orWhere('emailarchive_subject', 'like', '%' . $request['search'] . '%');
        }
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->latest('emailarchive_id')->paginate((int)$per_page);
        } else {
            return $query->latest('emailarchive_id')->paginate((int)$per_page, ['*'], 'page', (int) $query->paginate((int)$per_page)->currentPage() - 1);
        }
    }

    public static function getRecordsById($id)
    {
        $emailDetails = [];
        $emailDetails = EmailArchive::select('emailarchive_id', 'emailarchive_to_email', 'emailarchive_tpl_name', 'emailarchive_subject', 'emailarchive_body', 'emailarchive_headers', 'emailarchive_created_at')->where('emailarchive_id', $id)->first();
        return $emailDetails;
    }
}
