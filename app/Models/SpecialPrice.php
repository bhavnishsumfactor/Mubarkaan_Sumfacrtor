<?php

namespace App\Models;

use App\Models\YokartModel;
use DB;
use App\Models\Timezone;
use Carbon\Carbon;

class SpecialPrice extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'splprice_id';

    protected $fillable = [
      'splprice_id', 'splprice_name', 'splprice_type', 'splprice_amount', 'splprice_startdate',
      'splprice_enddate', 'splprice_include', 'splprice_publish','splprice_created_by_id', 'splprice_updated_by_id',
      'splprice_created_at', 'splprice_updated_at'
    ];
    public const FLAT_TYPE = 1;
    public const PERCENTAGE_TYPE = 2;

    const SPECIALPRICE_TYPE = [
        self::FLAT_TYPE => 'flat',
        self::PERCENTAGE_TYPE => 'percentage'
    ];
    public const PRODUCT_INCLUDE = 1;
    public const CATEGORY_INCLUDE = 2;
    public const BRAND_INCLUDE = 3;
   

    const SPECIALPRICE_INCLUDE = [
       self::PRODUCT_INCLUDE => 'products',
       self::CATEGORY_INCLUDE => 'categories',
       self::BRAND_INCLUDE => 'brands'
    ];

    public static function getSpecialpriceTypes()
    {
        return self::SPECIALPRICE_TYPE;
    }

    public static function getSpecialpriceIncludes()
    {
        return self::SPECIALPRICE_INCLUDE;
    }

    public static function getSpecialprices($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = Specialprice::select(
            'splprice_id',
            'splprice_name',
            'splprice_type',
            'splprice_amount',
            'splprice_startdate',
            'splprice_enddate',
            'splprice_include',
            'splprice_publish',
            'splprice_created_by_id',
            'splprice_updated_by_id',
            'splprice_created_at',
            'splprice_updated_at'
        );
        if (!empty($request['search'])) {
            $query->where('splprice_name', 'like', '%'.$request['search'].'%');
        }
        if (!empty($request['type'])) {
            switch ($request['type']) {
                case 'all':
                    $query->latest('splprice_id');
                    break;
                case 'active':
                    $query->where('splprice_publish', config('constants.YES'))->where('splprice_startdate', '<=', Carbon::now()->format('Y-m-d H:i'))->where('splprice_enddate', '>=', Carbon::now()->format('Y-m-d H:i'))->latest('splprice_id');
                    break;
                case 'scheduled':
                    $query->where('splprice_startdate', '>', Carbon::now()->format('Y-m-d H:i'))->oldest(DB::raw('TIMESTAMP(splprice_startdate)'));
                    break;
                case 'expired':
                    $query->where('splprice_enddate', '<', Carbon::now()->format('Y-m-d H:i'))->latest(DB::raw('TIMESTAMP(splprice_enddate)'));
                    break;
            }
        } else {
            $query->latest('splprice_id');
        }
        $query->with('included');
        $query->with('excluded:spe_splprice_id,prod_id  as record_id,prod_name as record_title,spe_subrecord_id', 'excluded.varients');
        $query->with(['createdAt', 'updatedAt']);
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->paginate((int)$per_page);
        } else {
            return $query->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }

    public function included()
    {
        return $this->hasMany('App\Models\SpecialPriceInclude', 'spi_splprice_id', 'splprice_id');
    }
    

    public function excluded()
    {
        return $this->hasMany('App\Models\SpecialPriceExclude', 'spe_splprice_id', 'splprice_id')
                ->join('products', 'products.prod_id', 'spe_record_id')
                ->leftJoin('product_option_varients as varients', 'varients.pov_code', 'spe_subrecord_id');
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'splprice_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'splprice_updated_by_id')->select(['admin_id', 'admin_username']);
    }
}
