<?php

namespace App\Models;

use App\Models\YokartModel;
use DB;

class Reason extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'reason_id';

    protected $fillable = [
        'reason_id', 'reason_type', 'reason_title', 'reason_publish'
    ];

    public const RETURN = 1;
    public const CANCELLATION = 2;

    public const TYPE = [
        self::RETURN => 'Return',
        self::CANCELLATION => 'Cancellation'
    ];

    //for backend
    public static function getAllReasons($request)
    {
        $per_page = !empty($request['per_page']) ? $request['per_page'] : '';
        $search = !empty($request['search']) ? $request['search'] : '';
        if (empty($request['per_page'])) {
            $per_page = config('app.pagination');
        }
        $query = Reason::select(
            'reason_id',
            'reason_type',
            'reason_title',
            'reason_publish',
            DB::raw('(CASE WHEN reason_type = ' . self::RETURN . ' THEN "' . self::TYPE[self::RETURN] . '" WHEN reason_type = ' . self::CANCELLATION . ' THEN "' . self::TYPE[self::CANCELLATION] . '" END) as reason_type_label')
        );
        if (!empty($request['search'])) {
            $query->where('reason_title', 'like', '%' . $search . '%');
        }
        if (!empty($request['reason_type'])) {
            $query->where('reason_type', $request['reason_type']);
        }
        if ($query->paginate((int) $per_page)->count() >= 1 && $query->paginate((int) $per_page)->currentPage() >= 1) {
            $records = $query->latest('reason_id')->paginate((int) $per_page);
        } else {
            $records = $query->latest('reason_id')->paginate((int) $per_page, ['*'], 'page', (int) $query->paginate((int) $per_page)->currentPage() - 1);
        }
        return $records;
    }
}
