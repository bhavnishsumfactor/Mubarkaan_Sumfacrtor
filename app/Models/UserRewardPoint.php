<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\UserRewardPointBreakup;
use App\Models\Configuration;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Stripe\Discount;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;

class UserRewardPoint extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'urp_id';

    protected $fillable = [
        'urp_user_id', 'urp_type', 'urp_referral_user_id', 'urp_comments', 'urp_points', 'urp_order_id', 'urp_date_added', 'urp_date_expiry', 'urp_used', 'urp_orrequest_id'
    ];

    public const ORDER_EARNED_POINT = 1;
    public const REGISTRATION_POINT = 2;
    public const BIRTHDAY_POINT = 3;
    public const ORDER_REDEEMED_POINT = 4;
    public const ORDER_REQUEST_REFUND_POINT = 5;
    public const EXPIRED_POINT = 6;
    public const ADMIN_UPDATED_POINT = 7;
    public const SIGNUP_BONUS = 8;

    public const TYPE = [
        self::ORDER_EARNED_POINT => 'Order Earned Point',
        self::REGISTRATION_POINT => 'Registration Point',
        self::BIRTHDAY_POINT => 'Birthday Point',
        self::ORDER_REDEEMED_POINT => 'Order Redeemed Point',
        self::ORDER_REQUEST_REFUND_POINT => 'Returned Points',
        self::EXPIRED_POINT => 'Expired Point',
        self::ADMIN_UPDATED_POINT => 'Admin Updated Point',
        self::SIGNUP_BONUS => 'SignUp Bonus'
    ];

    public function refundProducts()
    {
        return $this->hasOne('App\Models\OrderReturnRequest', 'orrequest_id', 'urp_orrequest_id')
        ->join('order_products', 'op_id', 'orrequest_op_id');
    }
    public function orderProducts()
    {
        return $this->hasMany('App\Models\OrderProduct', 'op_order_id', 'urp_order_id');
    }

    public static function getRecordsByUserId($userId, $sum = false)
    {
        $obj = UserRewardPoint::join('user_reward_point_breakup as pointbackup', 'pointbackup.urpbreakup_urp_id', 'user_reward_points.urp_id')
            ->where('urp_user_id', $userId)->where('urpbreakup_expiry', '>=', Carbon::now())->select('urpbreakup_points', 'urpbreakup_id', 'urp_id')
            ->where('urp_used', 0)->where('pointbackup.urpbreakup_used', 0)->where('urp_type', '!=', self::EXPIRED_POINT);
        if ($sum == true) {
            return $obj->sum('pointbackup.urpbreakup_points');
        }
        return $obj->get();
    }
    public static function calculateRewardPoints($price, $userId)
    {
        $config = getConfigValues([
            'REWARD_POINTS_SPENDING_POINTS_MINIMUM_ORDER_TOTAL',
            'REWARD_POINTS_MINIMUM_POINTS_TO_USE',
            'REWARD_POINTS_PURCHASE_POINTS_AMOUNT',
            'REWARD_POINTS_ROUNDING_MODE',
            'REWARD_POINTS_PURCHASE_POINTS_EARNINGS',
            'REWARD_POINTS_SPENDING_POINTS_AMOUNT',
            'REWARD_POINTS_SPENDING_POINTS_EARNINGS',
            'REWARD_POINTS_MAXIMUM_POINTS_TO_USE_PER_ORDER',
            'REWARD_POINTS_ENABLE'
        ]);
        if ($config['REWARD_POINTS_ENABLE'] != 1) {
            return [];
        }
        $usablePoints = UserRewardPoint::getRecordsByUserId($userId, true);
        $totalAmount = 0;
        $totalPoints = $usablePoints;
        if ($config['REWARD_POINTS_SPENDING_POINTS_MINIMUM_ORDER_TOTAL'] < $price && $config['REWARD_POINTS_MINIMUM_POINTS_TO_USE'] <= $usablePoints) {
            if ($config['REWARD_POINTS_MINIMUM_POINTS_TO_USE'] < $usablePoints) {
                $usablePoints = $config['REWARD_POINTS_MINIMUM_POINTS_TO_USE'];
            }
        }

        $totalRewardPoint = 0;
        if ($config['REWARD_POINTS_SPENDING_POINTS_MINIMUM_ORDER_TOTAL'] < $price) {
            $totalRewardPoint = $price /  $config['REWARD_POINTS_PURCHASE_POINTS_AMOUNT'];

            if (($config['REWARD_POINTS_ROUNDING_MODE']) == 1) {
                if (strpos($totalRewardPoint, ".") !== false) {
                    $totalRewardPoint =  ceil($totalRewardPoint);
                }
            } else {
                $totalRewardPoint =  floor($totalRewardPoint);
            }
            $totalRewardPoint =   $totalRewardPoint *  $config['REWARD_POINTS_PURCHASE_POINTS_EARNINGS'];
        }
        $totalAmount = (($config['REWARD_POINTS_SPENDING_POINTS_AMOUNT'] / $config['REWARD_POINTS_SPENDING_POINTS_EARNINGS']) * $totalPoints);
        $result['totalEarnPoints'] = (string) $totalRewardPoint;
        $result['totalPoints'] = (string) $totalPoints;
        $result['usablePoints'] = (string) $usablePoints;
        $result['minUsePoints'] = (string) $config['REWARD_POINTS_MINIMUM_POINTS_TO_USE'];
        $result['minOrderTotal'] = (string) $config['REWARD_POINTS_SPENDING_POINTS_MINIMUM_ORDER_TOTAL'];
        $result['maxUsablePoints'] = (string)  $config['REWARD_POINTS_MAXIMUM_POINTS_TO_USE_PER_ORDER'];
        $result['totalPointsAmount'] = (string) $totalAmount;
        return $result;
    }
    public static function calculateEarnRewardPoints($price, $discount = 0)
    {
        $totalRewardPoint = 0;
        $config = Configuration::getRecordsByPrefix('REWARD_POINTS');
        if ($config['REWARD_POINTS_ENABLE'] != 1) {
            return $totalRewardPoint;
        }

        if ($config['REWARD_POINTS_PURCHASE_POINTS_MINIMUM_ORDER_TOTAL'] <= $price) {
            $totalRewardPoint = ($price - $discount) /  $config['REWARD_POINTS_PURCHASE_POINTS_AMOUNT'];

            if (($config['REWARD_POINTS_ROUNDING_MODE']) == 1) {
                if (strpos($totalRewardPoint, ".") !== false) {
                    $totalRewardPoint =  ceil($totalRewardPoint);
                }
            } else {
                $totalRewardPoint =  floor($totalRewardPoint);
            }
            $totalRewardPoint =   $totalRewardPoint *  $config['REWARD_POINTS_PURCHASE_POINTS_EARNINGS'];
        }

        return $totalRewardPoint;
    }
    public static function redeemRewardPoints($userId, $orderId, $totalPoints, $type = '', $requestId = 0)
    {
        $redeemType = UserRewardPoint::ORDER_REDEEMED_POINT;
        $comment =  'Redeemed points on order #' . $orderId;
        if (!empty($type) && $type == UserRewardPoint::ORDER_REQUEST_REFUND_POINT) {
            $redeemType = $type;
            $comment = 'Returned points for Order #' . $orderId;
        }
        $records = static::getRecordsByUserId($userId);
        if ($records->count() > 0) {
            UserRewardPoint::create([
                'urp_type' => $redeemType,
                'urp_user_id' => $userId,
                'urp_referral_user_id' => 0,
                'urp_comments' => $comment,
                'urp_points' => '-' . $totalPoints,
                'urp_order_id' => $orderId,
                'urp_date_added' => Carbon::now(),
                'urp_date_expiry' => 'null',
                'urp_used' => 1,
                'urp_orrequest_id' => $requestId,
            ]);

            $totalPointsTemp = $totalPoints;

            foreach ($records as $record) {
                if ($totalPoints == 0) {
                    break;
                }
                if ($record->urpbreakup_points <= $totalPoints) {
                    $totalPoints = $totalPoints - $record->urpbreakup_points;
                    UserRewardPointBreakup::where('urpbreakup_id', $record->urpbreakup_id)->update([
                        'urpbreakup_used_order_id' => $orderId,
                        'urpbreakup_used_date' => Carbon::now(),
                        'urpbreakup_used' => 1,
                    ]);
                } else {
                    $balancePoints = $record->urpbreakup_points - $totalPoints;
                    $rewordBackoup  = UserRewardPointBreakup::find($record->urpbreakup_id);
                    $replicateRewordBackoup = $rewordBackoup->replicate();
                    $replicateRewordBackoup->urpbreakup_points = $balancePoints;
                    $replicateRewordBackoup->save();
                    UserRewardPointBreakup::where('urpbreakup_id', $record->urpbreakup_id)->update([
                        'urpbreakup_points' => $totalPoints,
                        'urpbreakup_used_order_id' => $orderId,
                        'urpbreakup_used_date' => Carbon::now(),
                        'urpbreakup_used' => 1,
                    ]);
                    $totalPoints = 0;
                }
            }
            if (empty($type)) {
                $user = User::getUserById($userId);
                $data = EmailHelper::getEmailData('rewards_spent_on_order');
                $priority = $data['body']->etpl_priority;
                $data['subject'] = str_replace('{rewardPoints}', $totalPointsTemp, $data['body']->etpl_subject);
                $data['subject'] = str_replace('{orderNumber}', $orderId, $data['subject']);
                $data['body'] = str_replace('{name}', $user->user_name, $data['body']->etpl_body);
                $data['body'] = str_replace('{rewardPoints}', $totalPointsTemp, $data['body']);
                $data['body'] = str_replace('{orderNumber}', $orderId, $data['body']);
                $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                $data['to'] = $user->user_email;
                sendPushNotification($userId, 'rewards_spent_on_order', ['reward_points' => $totalPointsTemp, 'order_id' => $orderId]);
                dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
            }
        }
    }

    public static function updateRewardPoints($userId, $type, $points, $comments, $validity = 0)
    {
        if ($validity == 0) {
            $rewardPointsValidity = Configuration::getValue('REWARD_POINTS_PURCHASE_POINTS_VALIDITY');
        } else {
            $rewardPointsValidity = $validity;
        }
        if (empty($rewardPointsValidity)) {
            return ['status' => false, 'message' => 'Please configured the rewards points in system!'];
        }
        if ($type == 'credit') {
            $insertID = UserRewardPoint::create([
                'urp_type' => UserRewardPoint::ADMIN_UPDATED_POINT,
                'urp_user_id' => $userId,
                'urp_referral_user_id' => 0,
                'urp_comments' => $comments,
                'urp_points' => $points,
                'urp_order_id' => 0,
                'urp_date_added' => Carbon::now(),
                'urp_date_expiry' => Carbon::now()->addDays($rewardPointsValidity),
                'urp_used' => 0,
            ])->urp_id;
            UserRewardPointBreakup::create([
                'urpbreakup_urp_id' => $insertID,
                'urpbreakup_referral_user_id' => 0,
                'urpbreakup_used_order_id' => 0,
                'urpbreakup_points' => $points,
                'urpbreakup_used' => 0,
                'urp_date_added' => Carbon::now(),
                'urpbreakup_expiry' => Carbon::now()->addDays($rewardPointsValidity),
            ]);
        } elseif ($type == 'debit') {
            $records = static::getRecordsByUserId($userId);
            if ($records->count() > 0) {
                UserRewardPoint::create([
                    'urp_type' => UserRewardPoint::ADMIN_UPDATED_POINT,
                    'urp_user_id' => $userId,
                    'urp_referral_user_id' => 0,
                    'urp_comments' => $comments,
                    'urp_points' => '-' . $points,
                    'urp_order_id' => 0,
                    'urp_date_added' => Carbon::now(),
                    'urp_date_expiry' => 'null',
                    'urp_used' => 1
                ]);

                foreach ($records as $record) {
                    if ($points == 0) {
                        break;
                    }
                    if ($record->urpbreakup_points <= $points) {
                        $points = $points - $record->urpbreakup_points;
                        UserRewardPointBreakup::where('urpbreakup_id', $record->urpbreakup_id)->update([
                            'urpbreakup_used_order_id' => 0,
                            'urpbreakup_used_date' => Carbon::now(),
                            'urpbreakup_used' => 1,
                        ]);
                    } else {
                        $balancePoints = $record->urpbreakup_points - $points;
                        $rewordBackoup  = UserRewardPointBreakup::find($record->urpbreakup_id);
                        $replicateRewordBackoup = $rewordBackoup->replicate();
                        $replicateRewordBackoup->urpbreakup_points = $balancePoints;
                        $replicateRewordBackoup->save();
                        UserRewardPointBreakup::where('urpbreakup_id', $record->urpbreakup_id)->update([
                            'urpbreakup_points' => $points,
                            'urpbreakup_used_order_id' => 0,
                            'urpbreakup_used_date' => Carbon::now(),
                            'urpbreakup_used' => 1,
                        ]);
                        $points = 0;
                    }
                }
            }
        }
    }
    public static function getRewardPointsListing($userId, $request = [], $row = 0)
    {
        $filterBy = !empty($request['filters']) ? $request['filters'] : '';
        $query = UserRewardPoint::select(
            'urp_id',
            'urp_type',
            'urp_user_id',
            'urp_referral_user_id',
            'urp_points as points',
            'urp_comments as comments',
            'urp_date_added as date',
            'urp_order_id',
            'users.user_email as referral_email',
            'users.user_phone as referral_phone',
            'users.user_phone_country_id',
            'urp_orrequest_id',
            'countries.country_phonecode'
        )
            ->where('urp_user_id', $userId);
        $query->leftJoin('users', function ($sql) {
            $sql->on('users.user_id', 'urp_referral_user_id');
        });
        $query->leftJoin('countries', 'countries.country_id', 'user_phone_country_id');
        $query->with('orderProducts.rewardPoints')
        ->with('orderProducts.images:afile_record_id,afile_updated_at')
        ->with('refundProducts:op_id,orrequest_id')
        ->with('orderProducts.refundPoints:orrequest_op_id');
        switch ($filterBy) {
            case 'earned':
                $query->where(function ($sql) {
                    $sql->where('urp_type', self::ORDER_EARNED_POINT);
                    $sql->orWhere('urp_type', self::REGISTRATION_POINT);
                    $sql->orWhere('urp_type', self::BIRTHDAY_POINT);
                });
                break;
            case 'redeemed':
                $query->where('urp_type', self::ORDER_REDEEMED_POINT);
                break;
            case 'expired':
                $query->where('urp_type', self::EXPIRED_POINT);
                break;

            default:
                break;
        }
        $query->latest('urp_id');
        if ($row != 0) {
            return $query->skip($row)->take($request['paginate'])->get()->toArray();
        } else {
            return $query->paginate($request['paginate']);
        }
    }

    public static function calculateRegistrationRewardPoint($userId, $sum = false)
    {
        $rewardPoint = UserRewardPoint::join('user_reward_point_breakup as pointbackup', 'pointbackup.urpbreakup_urp_id', 'user_reward_points.urp_id')
            ->where('urp_user_id', $userId)->where('urpbreakup_expiry', '>=', Carbon::now())->select('urpbreakup_points', 'urpbreakup_id', 'urp_id')
            ->where('urp_used', 0)->where('pointbackup.urpbreakup_used', 0)->where('urp_type', UserRewardPoint::REGISTRATION_POINT);
        if ($sum == true) {
            return $rewardPoint->sum('pointbackup.urpbreakup_points');
        }
        return $rewardPoint->get();
    }

    public static function referralRewardPoints($userId)
    {
        return UserRewardPoint::where('urp_user_id', $userId)->select('urp_points', 'urp_id')
            ->where('urp_type', UserRewardPoint::REGISTRATION_POINT)->sum('urp_points');
    }
    public static function getRewardPointsListingForApp($userId, $request = [], $page = 0)
    {
        $filterBy = !empty($request['type']) ? $request['type'] : '';
        $query = UserRewardPoint::select(
            'urp_id',
            'urp_type',
            'urp_user_id',
            'urp_referral_user_id',
            'urp_points as points',
            'urp_comments as comments',
            'urp_date_added as date',
            'urp_order_id',
            'urp_orrequest_id',
            'users.user_email as referral_email',
            'users.user_phone as referral_phone',
            'users.user_phone_country_id',
            'countries.country_phonecode',
            DB::Raw("IF(user_email IS NULL, '', user_email) as referral_email"),
            DB::Raw("IF(user_phone IS NULL, '', user_phone) as referral_phone"),
            DB::Raw("IF(user_phone_country_id IS NULL, '', user_phone_country_id) as user_phone_country_id"),
            DB::Raw("IF(country_phonecode IS NULL, '', country_phonecode) as country_phonecode")
        )
            ->where('urp_user_id', $userId);
        $query->leftJoin('users', function ($sql) {
            $sql->on('users.user_id', 'urp_referral_user_id');
        });
        $query->leftJoin('countries', 'countries.country_id', 'user_phone_country_id');
        $query->with(array('orderProducts.rewardPoints' => function ($query) {
            $query->select('opc_op_id', 'opc_value');
        }))
        ->with(array('orderProducts.images' => function ($query) {
            $query->select('afile_record_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id,'?t=', UNIX_TIMESTAMP(afile_updated_at)) as product_image"));
        }))
        ->with(array('orderProducts' => function ($query) {
            $query->select('op_id', 'op_order_id', 'op_product_id', 'op_product_name');
        }))
        ->with('refundProducts:op_id,orrequest_id')
        ->with('orderProducts.refundPoints:orrequest_op_id');
        
        switch ($filterBy) {
            case 'earned':
                $query->where(function ($sql) {
                    $sql->where('urp_type', self::ORDER_EARNED_POINT);
                    $sql->orWhere('urp_type', self::REGISTRATION_POINT);
                    $sql->orWhere('urp_type', self::BIRTHDAY_POINT);
                });
                break;
            case 'redeemed':
                $query->where('urp_type', self::ORDER_REDEEMED_POINT);
                break;
            case 'expired':
                $query->where('urp_type', self::EXPIRED_POINT);
                break;

            default:
                break;
        }
        $query->latest('urp_id');
        return $query->paginate((int) config('app.app_pagination'), ['*'], 'page', $page);
    }
}
