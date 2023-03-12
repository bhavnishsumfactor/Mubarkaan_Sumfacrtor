<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\AttachedFile;
use Redirect;
use Storage;

class AttachedFileController extends AdminYokartController
{
    public function deleteFile(Request $request, $id)
    {
        if (isset($id) && !empty($id)) {
            if (AttachedFile::deleteAttachedFileById($id)) {
                return jsonresponse(true, __('NOTI_IMAGE_DELETED_SUCCESSFULLY'));
            } else {
                return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
            }
        }
    }
}
