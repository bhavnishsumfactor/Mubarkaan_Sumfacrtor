<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\UserRewardPoint;

class RewardController extends YokartController
{
    public function list(Request $request, $page)
    {
        $response = [];
        $usablePoints = UserRewardPoint::getRecordsByUserId(Auth::guard('api')->user()->user_id, true);
        
        $configVal = getConfigValues(['REWARD_POINTS_SPENDING_POINTS_EARNINGS', 'REWARD_POINTS_SPENDING_POINTS_AMOUNT']);
        $pointsWorth = $usablePoints * ($configVal['REWARD_POINTS_SPENDING_POINTS_AMOUNT'] / $configVal['REWARD_POINTS_SPENDING_POINTS_EARNINGS']);
        $response['usable_points'] = $usablePoints;
        $response['points_worth'] = (string) $pointsWorth;
        
        $rewards = UserRewardPoint::getRewardPointsListingForApp(Auth::guard('api')->user()->user_id, $request, $page)->toArray();
        if (!empty($rewards['data'])) {
            foreach ($rewards['data'] as $revKey => $revValue) {
                if (isset($revValue["refund_products"]["op_id"]) && $revValue["urp_type"] == UserRewardPoint::ORDER_REQUEST_REFUND_POINT) {
                    $rewards['data'][$revKey]['order_products'] = array_values(array_filter(
                        $revValue['order_products'],
                        fn ($val, $key) => $val['op_id'] == $revValue["refund_products"]["op_id"],
                        ARRAY_FILTER_USE_BOTH
                    ));
                }
                unset($rewards['data'][$revKey]['refund_products']);
                $rewards['data'][$revKey]['date'] = getConvertedDateTime($revValue['date']);
                $rewards['data'][$revKey]['image_url'] = $this->getImageUrl($revValue);
            }
        }
        $response['total'] = $rewards['total'];
        $response['pages'] = ceil($rewards['total'] / $rewards['per_page']);
        $response['rewards'] = $rewards['data'];
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    private function getImageUrl($reward)
    {
        $baseUrl = url('');
        $imageUrl = "";
        switch ($reward["urp_type"]) {
            case UserRewardPoint::ORDER_EARNED_POINT:
                $imageUrl = $baseUrl . "/dashboard/media/retina/points-earned-for-product.png";
                break;
            case UserRewardPoint::REGISTRATION_POINT:
                $imageUrl = $baseUrl . "/dashboard/media/retina/signup-points.png";
                break;
            case UserRewardPoint::BIRTHDAY_POINT:
                $imageUrl = $baseUrl . "/dashboard/media/retina/birthday-points.png";
                break;
            case UserRewardPoint::ORDER_REDEEMED_POINT:
                $imageUrl = $baseUrl . "/dashboard/media/retina/product-return-deduction-points.png";
                break;
            case UserRewardPoint::ORDER_REQUEST_REFUND_POINT:
                $imageUrl = $baseUrl . "/dashboard/media/retina/returned_points.png";
                break;
            case UserRewardPoint::EXPIRED_POINT:
                $imageUrl = $baseUrl . "/dashboard/media/retina/expired-points.png";
                break;
            case UserRewardPoint::ADMIN_UPDATED_POINT:
                if ($reward["points"] > 0) {
                    $imageUrl = $baseUrl . "/dashboard/media/retina/credited-points.png";
                } else {
                    $imageUrl = $baseUrl . "/dashboard/media/retina/dedited-points.png";
                }
                break;
            case UserRewardPoint::SIGNUP_BONUS:
                $imageUrl = $baseUrl . "/dashboard/media/retina/credited-points.png";
                break;
            
            default:
                $imageUrl = $baseUrl . "/dashboard/media/retina/credited-points.png";
                break;
        }
        return $imageUrl;
    }
}
