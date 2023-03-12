<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\YokartModel;

class AppPushNotificaton extends YokartModel
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'apn_id';
    protected $fillable = [
        'apn_user_id', 'apn_type', 'apn_title', 'apn_body', 'apn_extra', 'apn_status', 'apn_created_on'
    ];
    public static function getRecords($userId, $pageId)
    {
        return AppPushNotificaton::where('apn_user_id', $userId)->latest('apn_id')
        ->paginate((int) config('app.pagination'), ['*'], 'page', $pageId)->toArray();
    }
    public function getApnExtraAttribute($value)
    {
        return $this->apn_extra = json_decode($value, true);
    }
    public function getApnCreatedOnAttribute($value)
    {
        return $this->apn_extra = getConvertedDateTime($value);
    }
}


