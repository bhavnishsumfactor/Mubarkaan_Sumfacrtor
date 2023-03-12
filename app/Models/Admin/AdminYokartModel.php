<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminYokartModel extends Model
{
    public static function getDisplayOrder($model, $orderfield, $conditions = [])
    {
        $modelObj = new $model;
        $latestDisplayOrder = $modelObj::select($orderfield)->where($conditions)->latest($orderfield)->first();
        return !empty($latestDisplayOrder->$orderfield)?$latestDisplayOrder->$orderfield+1:1;
    }
}
