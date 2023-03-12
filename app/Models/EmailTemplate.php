<?php

namespace App\Models;

use App\Models\YokartModel;

class EmailTemplate extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'etpl_id';

    protected $fillable = [
        'etpl_id', 'etpl_name', 'etpl_subject', 'etpl_body', 'etpl_updated_by_id', 'etpl_updated_at'
    ];

    public static function getAllEmailTemplates($request)
    {
        $per_page = !empty($request['per_page']) ? $request['per_page'] : '';
        $search = !empty($request['search']) ? $request['search'] : '';

        $sort = 'etpl_name';
        $sortBy = 'ASC';
        if (!empty($request['sort']) && !empty($request['sortBy'])) {
            $sort = $request['sort'];
            $sortBy = ($request['sortBy']) ? $request['sortBy'] : 'ASC';
        }
        if (empty($request['per_page'])) {
            $per_page = config('app.pagination');
        }
        $query = EmailTemplate::select('etpl_id', 'etpl_name', 'etpl_subject', 'etpl_updated_by_id', 'etpl_updated_at');
        if (!empty($search)) {
            $query->where('etpl_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->orderBy($sort, $sortBy)->paginate((int)$per_page);
        return $records;
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'etpl_updated_by_id')->select(['admin_id', 'admin_username']);
    }

    public static function graphicByOrderStatus($template, $isPickup)
    {
        $templateBody = $template->etpl_body;
        if ($isPickup) {
            switch ($template->etpl_slug) {
                case 'physical_ready_for_shipping':
                    $templateBody = str_replace('{statusImage}', self::imageTag('packing.png'), $templateBody);
                    break;
                case 'physical_order_shipped':
                    $templateBody = str_replace('{statusImage}', self::imageTag('ready-for-pickup.png'), $templateBody);
                    break;
                case 'physical_order_delivered':
                    $templateBody = str_replace('{statusImage}', self::imageTag('picked-up.png'), $templateBody);
                    break;
            }
        } else {
            switch ($template->etpl_slug) {
                case 'physical_ready_for_shipping':
                    $templateBody = str_replace('{statusImage}', self::imageTag('ready-for-shipping.png'), $templateBody);
                    break;
                case 'physical_order_shipped':
                    $templateBody = str_replace('{statusImage}', self::imageTag('shipped.png'), $templateBody);
                    break;
                case 'physical_order_delivered':
                    $templateBody = str_replace('{statusImage}', self::imageTag('delivered.png'), $templateBody);
                    break;
            }
        }
        return $templateBody;
    }

    public static function imageTag($src)
    {
        return '<img src="'.url('').'/emails/' . $src . '" alt="{statusImage}" />';
    }
}
