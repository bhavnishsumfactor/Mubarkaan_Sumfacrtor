<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\DiscountCouponRecord;
use DB;
use Carbon\Carbon;

class DiscountCoupon extends YokartModel
{
    public $timestamps = false;

    public const COUPON_PERCENT_TYPE = 1;
    public const COUPON_FLAT_TYPE = 0;

    public const OR_CONDITION = 0;
    public const AND_CONDITION = 1;

    const OPERATOR_CONDITIONS = [
        self::OR_CONDITION => 'Or',
        self::AND_CONDITION => 'And'
    ];

    public const AVAILABLE_DISCOUNT_FOR_LOGGED_IN_USERS = 1;
    public const AVAILABLE_DISCOUNT_FOR_ALL = 0;

    const AVAILABLE_DISCOUNT = [
        self::AVAILABLE_DISCOUNT_FOR_LOGGED_IN_USERS => 'No',
        self::AVAILABLE_DISCOUNT_FOR_ALL => 'Yes'
    ];
    protected $primaryKey = 'discountcpn_id';

    protected $fillable = [
        'discountcpn_id', 'discountcpn_code', 'discountcpn_in_percent', 'discountcpn_type', 'discountcpn_total_uses',
        'discountcpn_uses_per_user', 'discountcpn_maxamt_limit', 'discountcpn_minorderamt', 'discountcpn_amount', 'discountcpn_startdate',  'discountcpn_enddate', 'discountcpn_publish', 'discountcpn_operator', 'discountcpn_created_at', 'discountcpn_updated_at', 'discountcpn_for_guest', 'discountcpn_created_by_id', 'discountcpn_updated_by_id'
    ];
    public function couponConditions()
    {
        return $this->hasMany('App\Models\DiscountCouponRecord', 'dcr_discountcpn_id', 'discountcpn_id');
    }
    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'order_discount_coupon_code', 'discountcpn_code');
    }
    public static function getDiscountCoupons($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = DiscountCoupon::select(
            'discountcpn_id',
            'discountcpn_code',
            'discountcpn_in_percent',
            'discountcpn_type',
            'discountcpn_total_uses',
            'discountcpn_uses_per_user',
            'discountcpn_maxamt_limit',
            'discountcpn_minorderamt',
            'discountcpn_amount',
            'discountcpn_startdate',
            'discountcpn_enddate',
            'discountcpn_for_guest',
            'discountcpn_publish',
            'discountcpn_operator',
            'discountcpn_created_by_id',
            'discountcpn_updated_by_id',
            'discountcpn_created_at',
            'discountcpn_updated_at',
            'af.afile_id'
        );
        if (!empty($request['search'])) {
            $query->where('discountcpn_code', 'like', '%' . $request['search'] . '%');
        }
        $query->leftJoin('attached_files AS af', function ($join) {
            $join->on('discountcpn_id', '=', 'af.afile_record_id');
            $join->where('af.afile_type', AttachedFile::getConstantVal('discountCouponImage'));
        });
        if (!empty($request['type'])) {
            switch ($request['type']) {
                case 'all':
                    $query->latest('discountcpn_id');
                    break;
                case 'active':
                    $query->where('discountcpn_publish', 1)->whereDate('discountcpn_enddate', '>', Carbon::now()->toDateTimeString())
                        ->latest('discountcpn_id');
                    break;
                case 'scheduled':
                    $query->whereDate('discountcpn_startdate', '>', Carbon::now()->toDateTimeString())
                        ->oldest(DB::raw('TIMESTAMP(discountcpn_startdate)'));
                    break;
                case 'expired':
                    $query->whereDate('discountcpn_enddate', '<', Carbon::now()->toDateTimeString());
                    break;
            }
        } else {
            $query->latest('discountcpn_id');
        }
        $query->with(['createdAt', 'updatedAt']);
        if ($query->paginate((int) $per_page)->count() >= 1 && $query->paginate((int) $per_page)->currentPage() >= 1) {
            return $query->paginate((int) $per_page);
        } else {
            return $query->paginate((int) $per_page, ['*'], 'page', (int) $query->paginate((int) $per_page)->currentPage() - 1);
        }
    }

    public static function getRecordByCouponCode($couponCode, $userId = 0)
    {
        $currentDateTime = $dt = Carbon::now()->toDateTimeString();
        $query =  DiscountCoupon::with('couponConditions')
            ->where('discountcpn_code', $couponCode)
            ->where('discountcpn_startdate', '<=', $currentDateTime)
            ->where('discountcpn_enddate', '>=', $currentDateTime);
        if ($userId == 0) {
            $query->where('discountcpn_for_guest', self::AVAILABLE_DISCOUNT_FOR_ALL);
        }
        return $query->first();
    }
    public static function getActiveRecords($userId = 0)
    {
        $currentDateTime = $dt = Carbon::now()->toDateTimeString();
        $query = DiscountCoupon::with('couponConditions')->select(
            'discountcpn_id',
            'discountcpn_amount',
            'discountcpn_code',
            'discountcpn_maxamt_limit',
            'discountcpn_total_uses',
            'discountcpn_uses_per_user',
            'discountcpn_enddate',
            'discountcpn_operator',
            'discountcpn_in_percent',
            'discountcpn_type',
            'discountcpn_minorderamt'
        )->where('discountcpn_startdate', '<=', $currentDateTime)->where('discountcpn_enddate', '>=', $currentDateTime)
            ->where('discountcpn_publish', config('constants.YES'));
        if ($userId == 0) {
            $query->where('discountcpn_for_guest', self::AVAILABLE_DISCOUNT_FOR_ALL);
        }
        $query->withCount('orders')->havingRaw('orders_count < discountcpn_total_uses');
        if ($userId != 0) {
            $query->withCount(['orders as userCounpons' => function ($userCounpon) use ($userId) {
                $userCounpon->where('order_user_id', $userId);
            }])->havingRaw('userCounpons < discountcpn_uses_per_user');
        }

        //dd($query->limit(1)->get());
        return $query->get();
    }

    public static function getUserActiveRecords($userId, $usedOnly = false)
    {
        $currentDateTime = Carbon::now()->toDateTimeString();
        $query = DiscountCoupon::select(
            'discountcpn_id',
            'discountcpn_amount',
            'discountcpn_code',
            'discountcpn_maxamt_limit',
            'discountcpn_total_uses',
            'discountcpn_uses_per_user',
            'discountcpn_enddate',
            'discountcpn_in_percent',
            'discountcpn_type',
            'discountcpn_minorderamt',
            'af.afile_updated_at',
            'af.afile_id'
        )->where('discountcpn_startdate', '<=', $currentDateTime)->where('discountcpn_enddate', '>=', $currentDateTime)
            ->where('discountcpn_publish', config('constants.YES'))
            ->with(['orders' => function($q) use ($userId) {
                $q->where('order_user_id', $userId);
            }]);
        $query->leftJoin('attached_files AS af', function ($join) {
            $join->on('discountcpn_id', '=', 'af.afile_record_id');
            $join->where('af.afile_type', AttachedFile::getConstantVal('discountCouponImage'));
        });
        if (!empty($userId)) {
            $per_page = config('app.pagination');
            if (!empty($request['per_page'])) {
                $per_page = $request['per_page'];
            }
            if ($usedOnly) {
                $query->whereHas('orders', function ($q) use ($userId) {
                    $q->where('order_user_id', $userId);
                });
                $query->withCount(['orders' => function($q) use ($userId) {
                    $q->where('order_user_id', $userId);
                }])->has('orders', '>', 0);
            }
            if ($query->paginate((int) $per_page)->count() >= 1 && $query->paginate((int) $per_page)->currentPage() >= 1) {
                return $query->paginate((int) $per_page);
            } else {
                return $query->paginate((int) $per_page, ['*'], 'page', (int) $query->paginate((int) $per_page)->currentPage() - 1);
            }
        } else {
            return $query->get();
        }
    }

    public static function couponSummary($couponId, $userId)
    {
        $data = [];
        $data['usageByUser'] = DiscountCoupon::where('discountcpn_id', $couponId)
            ->join('orders', function ($join) use ($userId) {
                $join->on('discountcpn_code', '=', 'orders.order_discount_coupon_code');
                $join->where('orders.order_user_id', $userId);
            })
            ->count();
        return $data;
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'discountcpn_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'discountcpn_updated_by_id')->select(['admin_id', 'admin_username']);
    }
    public function attachedFile()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'discountcpn_id')->where('afile_type', AttachedFile::getConstantVal('discountCouponImage'));
    }
    public static function getCoupons($userId, $row = 0)
    {
        $currentDateTime = Carbon::now()->toDateTimeString();
        $query = DiscountCoupon::select(
            'discountcpn_id',
            'discountcpn_amount',
            'discountcpn_code',
            'discountcpn_maxamt_limit',
            'discountcpn_total_uses',
            'discountcpn_uses_per_user',
            'discountcpn_enddate',
            'discountcpn_in_percent',
            'discountcpn_type',
            'discountcpn_minorderamt'
        )
            ->where('discountcpn_startdate', '<=', $currentDateTime)->where('discountcpn_enddate', '>=', $currentDateTime)
            ->where('discountcpn_publish', config('constants.YES'))
            ->with(['orders' => function ($sql) use ($userId) {
                $sql->select('order_discount_coupon_code', 'order_discount_amount');
                $sql->where('order_user_id', $userId);
            }])
            ->with('attachedFile')
            ->withCount(['couponConditions as conCount' => function ($conSql) {
                $conSql->where('dcr_type', DiscountCouponRecord::DISCOUNT_RECORD_USER_TYPE);
            }])
            ->withCount(['couponConditions as userConCount' => function ($userConSql) use ($userId) {
                $userConSql->where('dcr_type', DiscountCouponRecord::DISCOUNT_RECORD_USER_TYPE)
                    ->where('dcr_record_id', $userId);
            }])
            ->groupBy('discountcpn_code')->latest('discountcpn_id')
            ->havingRaw('conCount = 0 or (conCount > 0 and userConCount > 0)');
        if ($row != 0) {
            return $query->skip($row)->take(config('app.pagination'))->get()->toArray();
        } else {
            return $query->paginate(config('app.pagination'));
        }
    }

    public static function getIncluded($couponId)
    {
        $data = [];
        $data['products'] = DiscountCouponRecord::select('prod_id as record_id', 'prod_name as record_title', 'pov_display_title', 'pov_code')
        ->where('dcr_type', 1)->where('dcr_discountcpn_id', $couponId)
        ->leftJoin('products', 'products.prod_id', 'dcr_record_id')
        ->leftJoin('product_option_varients as varients', 'varients.pov_code', 'dcr_subrecord_id')
        ->get();
        $data['categories'] = DiscountCouponRecord::select('cat_id as record_id', 'cat_name as record_title')
        ->where('dcr_type', 2)->where('dcr_discountcpn_id', $couponId)
        ->leftJoin('product_categories', 'product_categories.cat_id', 'dcr_record_id')->get();
        $data['brands'] = DiscountCouponRecord::select('brand_id as record_id', 'brand_name as record_title')
        ->where('dcr_type', 3)->where('dcr_discountcpn_id', $couponId)
        ->leftJoin('brands', 'brands.brand_id', 'dcr_record_id')->get();
        $data['users'] = DiscountCouponRecord::select('user_id as record_id', DB::raw('CONCAT(user_fname, " ", user_lname) as record_title'))
        ->where('dcr_type', 4)->where('dcr_discountcpn_id', $couponId)
        ->leftJoin('users', 'users.user_id', 'dcr_record_id')->get();
        return $data;
    }
    public static function getCouponLinkedData($couponId)
    {
        $data = [];
        $data['products'] = DiscountCouponRecord::where('dcr_type', 1)->where('dcr_discountcpn_id', $couponId)
        ->leftJoin('products', 'products.prod_id', 'dcr_record_id')
        ->leftJoin('product_option_varients as varients', 'varients.pov_code', 'dcr_subrecord_id')
        ->pluck('prod_name as record_title')->implode(', ');
        $data['categories'] = DiscountCouponRecord::select('cat_id as record_id', 'cat_name as record_title')
        ->where('dcr_type', 2)->where('dcr_discountcpn_id', $couponId)
        ->leftJoin('product_categories', 'product_categories.cat_id', 'dcr_record_id')->pluck('cat_name as record_title')->implode(', ');
        $data['brands'] = DiscountCouponRecord::where('dcr_discountcpn_id', $couponId)
        ->leftJoin('brands', 'brands.brand_id', 'dcr_record_id')->pluck('brand_name as record_title')->implode(', ');
        $data['users'] = DiscountCouponRecord::select(DB::raw('CONCAT(user_fname," ",user_lname) AS record_title'))->where('dcr_type', 4)
        ->where('dcr_discountcpn_id', $couponId)
        ->leftJoin('users', 'users.user_id', 'dcr_record_id')->pluck('record_title')->implode(', ');
        return $data;
    }
    public static function getCouponsForApp($userId, $page)
    {
        $currentDateTime = Carbon::now()->toDateTimeString();
        $query = DiscountCoupon::select(
            'discountcpn_id',
            'discountcpn_amount',
            'discountcpn_code',
            'discountcpn_maxamt_limit',
            'discountcpn_total_uses',
            'discountcpn_uses_per_user',
            'discountcpn_enddate',
            'discountcpn_in_percent',
            'discountcpn_type',
            'discountcpn_minorderamt'
        )
            ->where('discountcpn_startdate', '<=', $currentDateTime)->where('discountcpn_enddate', '>=', $currentDateTime)
            ->where('discountcpn_publish', config('constants.YES'))
            ->with(['orders' => function ($sql) use ($userId) {
                $sql->select('order_discount_coupon_code', 'order_discount_amount');
                $sql->where('order_user_id', $userId);
            }])
            ->with(array('attachedFile' => function ($query) {
                $query->select('afile_record_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id,'?t=', UNIX_TIMESTAMP(afile_updated_at)) as discount_image"));
            }))
            ->withCount(['couponConditions as conCount' => function ($conSql) {
                $conSql->where('dcr_type', DiscountCouponRecord::DISCOUNT_RECORD_USER_TYPE);
            }])
            ->withCount(['couponConditions as userConCount' => function ($userConSql) use ($userId) {
                $userConSql->where('dcr_type', DiscountCouponRecord::DISCOUNT_RECORD_USER_TYPE)
                    ->where('dcr_record_id', $userId);
            }])
            ->groupBy('discountcpn_code')->latest('discountcpn_id')
            ->havingRaw('conCount = 0 or (conCount > 0 and userConCount > 0)');
        return $query->paginate((int) config('app.app_pagination'), ['*'], 'page', $page);
    }
}
