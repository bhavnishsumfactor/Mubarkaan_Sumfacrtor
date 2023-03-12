<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\OrderProduct;
use DB;

class ProductReview extends YokartModel
{
    public const CREATED_AT = 'preview_created_at';
    public const UPDATED_AT = 'preview_updated_at';

    protected $primaryKey = 'preview_id';

    protected $fillable = [
      'preview_id', 'preview_order_id', 'preview_user_id', 'preview_prod_id', 'preview_rating',
      'preview_title', 'preview_description', 'preview_publish', 'preview_status', 'preview_approved_at',
      'preview_created_at', 'preview_updated_at'
    ];

    public const PENDING = 1;
    public const APPROVED = 2;
    public const REJECTED = 3;

    public const STATUSES = [
        self::PENDING => 'Pending',
        self::APPROVED => 'Approved',
        self::REJECTED => 'Rejected'
    ];
    public function getUpdatedImage()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'preview_prod_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['productImages'])->orderBy('attached_files.afile_updated_at', 'DESC');
    }
    public static function getRecords($request)
    {
        $per_page = !empty($request['per_page']) ? $request['per_page'] : '';
        $search = !empty($request['search']) ? $request['search'] : '';
        if (empty($request['per_page'])) {
            $per_page = config('app.pagination');
        }
        $dbprefix = DB::getTablePrefix();
        $query = ProductReview::select(
            'preview_id',
            'preview_prod_id',
            'preview_order_id',
            'preview_user_id',
            'preview_rating',
            'preview_publish',
            'preview_status',
            DB::raw("(preview_rating*20) as preview_rating_percent"),
            'preview_title',
            'preview_description',
            'preview_approved_at',
            'preview_created_at',
            DB::raw("CONCAT(user_fname, ' ', user_lname) as username"),
            'products.prod_name',
            'pov.pov_code',
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "attached_files as af WHERE af.afile_record_id = preview_id AND af.afile_type = '" . AttachedFile::SECTIONS['productReviewImage'] . "') as image_attached"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 1) as helped_count"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 0) as nothelped_count")
        );

        $query->join('users', 'users.user_id', 'product_reviews.preview_user_id');
        $query->join('products', 'products.prod_id', 'product_reviews.preview_prod_id');
        $query->leftJoin('product_option_varients as pov', function ($join) {
            $join->on('pov.pov_prod_id', 'products.prod_id')
            ->where('pov.pov_default', config('constants.YES'));
        });
        $query->with('productReviewLog');
        if (!empty($request['prod_id'])) {
            $query->where('preview_prod_id', $request['prod_id']);
        }
        if (!empty($request['user_id'])) {
            $query->where('preview_user_id', $request['user_id']);
        }
        
        if (!empty($search)) {
            $search = ltrim($search,'#');
            $query->where(function ($query) use ($search) {
                $query->where('preview_title', 'like', '%' . $search . '%')
                    ->orWhere('preview_order_id', 'like', '%' . $search . '%')
                    ->orWhere('preview_description', 'like', '%' . $search . '%')
                    ->orWhere(DB::raw("CONCAT(user_fname, ' ', user_lname)"), 'like', '%' . $search . '%')
                    ->orWhere('prod_name', 'like', '%' . $search . '%');
            });
        }
        if (isset($request['sorting-by']) && !empty($request['sorting-by']) && isset($request['sorting-type']) && !empty($request['sorting-type'])) {
            $query->orderBy($request['sorting-by'], $request['sorting-type']);
        }
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->latest('preview_id')->paginate((int)$per_page);
        } else {
            return $query->latest('preview_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }
    public static function getReviewById($previewId)
    { 
        $dbprefix = DB::getTablePrefix();
        $query = ProductReview::select(
            'preview_id',
            'preview_prod_id',
            'preview_order_id',
            'preview_user_id',
            'preview_rating',
            'preview_status',
            DB::raw("(preview_rating*20) as preview_rating_percent"),
            'preview_title',
            'preview_description',
            'preview_approved_at',
            'preview_created_at',
            'orders.order_date_added',
            'preview_approved_at',
            DB::raw("CONCAT(user_fname, ' ', user_lname) as username"),
            'op.op_product_name as prod_name',
            'op.op_pov_code',
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "attached_files as af WHERE af.afile_record_id = preview_id AND af.afile_type = '" . AttachedFile::SECTIONS['productReviewImage'] . "') as image_attached"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 1) as helped_count"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 0) as nothelped_count")
        );
        $query->leftJoin('orders', 'orders.order_id', 'product_reviews.preview_order_id');
        $query->join('users', 'users.user_id', 'product_reviews.preview_user_id');
        $query->leftJoin('order_products as op', function ($sql) {
            $sql->on('op.op_product_id', 'product_reviews.preview_prod_id');
            $sql->on('op.op_order_id', 'product_reviews.preview_order_id');
        });
        $query->with('productReviewLog')
        ->with('getUpdatedImage:afile_record_id,afile_updated_at');
        $query->with('productReviewLog.attachedFiles:afile_id,afile_record_id');
        $query->where('preview_id', $previewId);
        $query->with('reply');
        $query->with('attachedFiles:afile_id,afile_record_id,afile_updated_at');
        return $query->first();
    }

    public static function getReviews($request)
    {
        $dbprefix = DB::getTablePrefix();
        $query = ProductReview::select(
            'preview_id',
            'preview_user_id',
            'preview_rating',
            DB::raw("(preview_rating*20) as preview_rating_percent"),
            'preview_title',
            'preview_description',
            'preview_created_at',
            DB::raw("CONCAT(user_fname, ' ', user_lname) as username"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_user_id = '" . $request['userId'] . "' AND prhh.prh_helpful = 1) as helped"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_user_id = '" . $request['userId'] . "' AND prhh.prh_helpful = 0) as nothelped"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 1) as helped_count"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 0) as nothelped_count")
        );

        $query->join('users', 'users.user_id', 'product_reviews.preview_user_id');
        $query->where('preview_prod_id', $request['id']);
        $query->where('preview_publish', config('constants.YES'));
        $query->where('preview_status', self::APPROVED);
        $query->with('reply');
        $query->with('attachedFiles:afile_id,afile_record_id,afile_updated_at');
        
        if (!empty($request['sort']) && $request['sort'] == 'Oldest') {
            $query->oldest('preview_updated_at');
        } elseif (!empty($request['sort']) && $request['sort'] == 'MostHelpful') {
            $query->latest('helped_count');
        } else {
            $query->latest('preview_updated_at');
        }
                
        return $query->paginate(5);
    }
    public static function getAverageRatingByProductId($prodId)
    {
        $rating = ProductReview::where('preview_prod_id', $prodId)->where('preview_publish', config('constants.YES'))->where('preview_status', self::APPROVED)
        ->average('preview_rating');
        return ($rating) ? round($rating) : 0;
    }
    public static function getRatingByProductId($prodId)
    {
        return ProductReview::where('preview_prod_id', $prodId)->where('preview_publish', config('constants.YES'))->where('preview_status', self::APPROVED)->get();
    }

    public static function getReviewCount($prodId)
    {
        $count = ProductReview::where('preview_prod_id', $prodId)->where('preview_publish', config('constants.YES'))->where('preview_status', self::APPROVED)
        ->count('preview_id');
        return ($count) ? $count : 0;
    }

    public static function getRatingSummaryByProductId($prodId)
    {
        $query = ProductReview::selectRaw("coalesce(FORMAT(AVG(preview_rating), 1), 0) as rating, COUNT(*) as noOfReviews, coalesce(AVG(preview_rating)*20, 0) as ratingPercent");
        if (!empty($prodId)) {
            $query->where('preview_prod_id', $prodId);
        }
        $review = $query->where('preview_publish', config('constants.YES'))->where('preview_status', self::APPROVED)->first();
       
        return $review;
    }

    public static function authorizedToPost($userId, $prodId)
    {
        $count = ProductReview::where('preview_prod_id', $prodId)
        ->where('preview_user_id', $userId)->count();
        $order = OrderProduct::userOrderedProduct($userId, $prodId)->first();
        
        if(!empty($order) && ($order['order_shipping_status'] == 1 || $order['order_shipping_status'] == 2) ){
            return ['status' => false, 'message' => 'Purchased product is in progress', 'purchase' => false, 'data' => $order];
        }
        if ($count == 0 && !empty($order)) {
            return ['status' => true, 'message' => '', 'redirect' => false, 'purchase' => false, 'data' => $order];
        }
        if ($count != 0) {
            return ['status' => true, 'message' => '',  'redirect' => true, 'purchase' => false, 'data' => $order];
        }

        if (empty($order)) {
            return ['status' => false, 'message' => 'Please purchase the product before sharing a Review', 'purchase' => true, 'data' => $order];
        }
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'preview_prod_id', 'prod_id');
    }

    public function reply()
    {
        return $this->belongsTo('App\Models\ProductReviewComment', 'preview_id', 'prc_preview_id')
            ->with('admin:admin_id,admin_name');
    }

    public function productReviewLog()
    {
        return $this->hasOne('App\Models\ProductReviewLog', 'prl_preview_id', 'preview_id');
    }

    public function helpful()
    {
        return $this->hasMany('App\Models\ProductReviewHelpful', 'prh_preview_id', 'preview_id');
    }

    public function attachedFiles()
    {
        return $this->hasMany('App\Models\AttachedFile', 'afile_record_id', 'preview_id')
        ->where('afile_type', AttachedFile::SECTIONS['productReviewImage']);
    }

    public static function getReviewByProduct($productId, $userId, $orderId = '')
    {
        $dbprefix = DB::getTablePrefix();
        $query = ProductReview::select(
            'preview_id',
            'preview_prod_id',
            'preview_rating',
            'preview_title',
            'preview_description',
            'preview_status',
            'preview_created_at',
            'op_id',
            'op_order_id',
            'op_product_variants',
            'preview_order_id',
            'op_product_name as prod_name',
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "attached_files as af WHERE af.afile_record_id = preview_id AND af.afile_type = '" . AttachedFile::SECTIONS['productReviewImage'] . "') as image_attached")
        );
        $query->addSelect(DB::Raw("CONCAT('" . url('') . "', '/yokart/app/product/image/', preview_prod_id, '/', op_pov_code, '?t=', UNIX_TIMESTAMP(preview_updated_at)) as prod_image"));
        
        $query->join('order_products', 'op_product_id', 'product_reviews.preview_prod_id');
        $query->with('productReviewLog');
        $query->where('op_product_id', $productId);
        if (!empty($orderId)) {
            $query->where('preview_order_id', $orderId);
        }
        $query->where('product_reviews.preview_user_id', $userId);
        $query->with('attachedFiles:afile_id,afile_record_id,afile_updated_at');
        return $query->get();
    }

    public static function getFeedbackSummaryById($previewId, $userId)
    {
        $dbprefix = DB::getTablePrefix();
        $query = ProductReview::select(
            'preview_id',
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_user_id = '" . $userId . "' AND prhh.prh_helpful = 1) as helped"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_user_id = '" . $userId . "' AND prhh.prh_helpful = 0) as nothelped"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 1) as helped_count"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 0) as nothelped_count")
        );
        $query->where('preview_id', $previewId);
        return $query->first();
    }
    public static function getReviewByProductIdForApp($request, $page, $limit = null)
    {
        $dbprefix = DB::getTablePrefix();
        $query = ProductReview::select(
            'preview_id',
            'preview_user_id',
            'preview_rating',
            DB::raw("(preview_rating*20) as preview_rating_percent"),
            'preview_title',
            'preview_description',
            'preview_created_at',
            DB::Raw("CONCAT('" . url('') . "', '/yokart/image/userProfileImage/', user_id, '/0', '?t=', '" . time() . "') as user_image"),
            DB::raw("CONCAT(user_fname, ' ', user_lname) as username"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_user_id = '" . $request["user_id"] . "' AND prhh.prh_helpful = 1) as helped"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_user_id = '" . $request["user_id"] . "' AND prhh.prh_helpful = 0) as nothelped"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 1) as helped_count"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 0) as nothelped_count")
        );

        $query->join('users', 'users.user_id', 'product_reviews.preview_user_id');
        $query->where('preview_prod_id', $request["prod_id"])
        ->where('preview_publish', config('constants.YES'))
        ->where('preview_status', self::APPROVED);
        $query->with('reply');
        $query->with('attachedFiles:afile_id,afile_record_id,afile_updated_at');
        $query->with(array('attachedFiles' => function ($query) {
            $query->select(
                'afile_record_id',
                DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '/thumb', '?t=', UNIX_TIMESTAMP(afile_updated_at)) as thumb_image"),
                DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as actual_image")
            );
        }));
        if (!empty($request['sort']) && $request['sort'] == 'oldest') {
            $query->oldest('preview_updated_at');
        } elseif (!empty($request['sort']) && $request['sort'] == 'mosthelpful') {
            $query->latest('helped_count');
        } else {
            $query->latest('preview_updated_at');
        }
        if ($limit !== null) {
            return $query->limit($limit)->get();
        }
        return $query->paginate((int) config('app.app_pagination'), ['*'], 'page', $page);
    }

    public static function getReviewByIdForApp($reviewId, $userId)
    {
        $dbprefix = DB::getTablePrefix();
        $query = ProductReview::select(
            'preview_id',
            'preview_user_id',
            'preview_rating',
            DB::raw("(preview_rating*20) as preview_rating_percent"),
            'preview_title',
            'preview_description',
            'preview_created_at',
            DB::Raw("CONCAT('" . url('') . "', '/yokart/image/userProfileImage/', user_id, '/0', '?t=', '" . time() . "') as user_image"),
            DB::raw("CONCAT(user_fname, ' ', user_lname) as username"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_user_id = '" . $userId . "' AND prhh.prh_helpful = 1) as helped"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_user_id = '" . $userId . "' AND prhh.prh_helpful = 0) as nothelped"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 1) as helped_count"),
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "product_review_helpful as prhh WHERE prhh.prh_preview_id = preview_id AND prhh.prh_helpful = 0) as nothelped_count")
        );
        $query->join('users', 'users.user_id', 'product_reviews.preview_user_id');
        $query->where('preview_id', $reviewId)
        ->where('preview_status', self::APPROVED);
        $query->with('reply');
        $query->with('attachedFiles:afile_id,afile_record_id,afile_updated_at');
        $query->with(array('attachedFiles' => function ($query) {
            $query->select(
                'afile_record_id',
                DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '/thumb', '?t=', UNIX_TIMESTAMP(afile_updated_at)) as thumb_image"),
                DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as actual_image")
            );
        }));
        return $query->first();
    }
}
