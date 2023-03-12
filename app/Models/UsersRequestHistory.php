<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderAddress;
use Carbon\Carbon;

use DB;

class UsersRequestHistory extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'ureq_id';

    protected $fillable = [
        'ureq_user_id', 'ureq_type', 'ureq_purpose', 'ureq_created_at'
    ];
   
    public const REQUEST_TYPE = [
        'datarequest' => 1,
        'deletionrequest' => 2
    ];
    
    public function users()
    {
        return $this->belongsTo('App\Models\User', 'ureq_user_id', 'user_id');
    }

    public static function getAllUserRequests($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = UsersRequestHistory::select(['user_id','user_email', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'),'ureq_created_at','ureq_id','ureq_purpose','ureq_type','ureq_user_id'])
        ->join('users', 'ureq_user_id', 'user_id');
        if (!empty($request['search'])) {
            $query->where('ureq_purpose', 'like', '%'.$request['search'].'%');
            $query->orWhere(DB::raw('CONCAT(user_fname, " ", user_lname)'), 'like', '%'.$request['search'].'%');
        }
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->latest('ureq_created_at')->paginate((int)$per_page);
        } else {
            return $query->latest('ureq_created_at')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }

    public static function saveUserGdprRequest($request, $userId)
    {
        DB::beginTransaction();
        try {
            UsersRequestHistory::create([
                'ureq_user_id' => $userId,
                'ureq_type'    => self::REQUEST_TYPE[$request['type']],
                'ureq_purpose' => $request['ureq_purpose'],
                'ureq_created_at'    => Carbon::now()
            ]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
 
    public static function removeUserData($user_id)
    {
        try {
            $user = User::where('user_id', $user_id)->first();
            $user->user_facebook_id = '';
            $user->user_google_id = '';
            $user->user_instagram_id = '';
            $user->user_email = obfuscate($user->user_email);
            $user->user_phone = obfuscate($user->user_phone);
            $user->user_phone_verified = config("constants.NO");
            $user->user_email_verified = config("constants.NO");
            $user->user_publish = config("constants.NO");
            $user->user_gdpr_approved = config("constants.YES");
            $user->save();
            UserAddress::where('addr_user_id', $user_id)->delete();
            $orders = Order::getOrderAddressByUserId($user_id);
            UsersRequestHistory::removeOrderAddress($orders);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public static function getUserData($user_id)
    {
        $content = "<h2>Your GDPR requested data is as listed below:</h2>";
        $user = User::where('user_id', $user_id)->first();
        
        $content .= "<strong>Email</strong>: " . (!empty($user->user_email) ? $user->user_email : "NA") . "<br>";
        
        $content .= "<strong>Phone</strong>: ";
        if (!empty($user->user_phone)) {
            $phoneDialCode = Country::getRecordById($user->user_phone_country_id)->country_phonecode;
            $content .= "+" . $phoneDialCode . ' ' . $user->user_phone . "<br>";
        } else {
            $content .= "NA" . "<br>";
        }
        $content .= "<strong>DOB</strong>: " . $user->user_dob . "<br><br>";
        $content .= "<strong>Addresses</strong>: " . "<br>";
        $userAddress = UserAddress::where('addr_user_id', $user_id)->get();
        $i = 0;
        if (isset($userAddress) && !empty($userAddress)) {
            foreach ($userAddress as $key => $address) {
                $content .= '<strong>' . ($key + 1) . '</strong>. ' . $address->addr_title;
                $content .= ", " . $address->addr_first_name . ' ' . $address->addr_last_name;
                $content .= ", " . $address->addr_address1;
                $content .= ", " . $address->addr_address2;
                $content .= ", " . $address->addr_city;
                if (!empty($address->addr_state_id)) {
                    $content .= ", " . State::getRecordById($address->addr_state_id)->state_name;
                }
                if (!empty($address->addr_country_id)) {
                    $content .= ", " . Country::getRecordById($address->addr_country_id)->country_name;
                }
                if (!empty($address->addr_postal_code)) {
                    $content .= ", " . $address->addr_postal_code;
                }
                if (!empty($address->addr_email)) {
                    $content .= ", " . $address->addr_email;
                }
                if (!empty($address->addr_phone)) {
                    $phoneDialCode = Country::getRecordById($address->addr_phone_country_id)->country_phonecode;
                    $content .= ", +" . $phoneDialCode . ' ' . $address->addr_phone;
                }
                $content .= " <br>";
            }
        }
        $content .= " <br>";
        return $content;
    }

    public static function removeOrderAddress($orders)
    {
        if ($orders > 0) {
            foreach ($orders as $key=>$order) {
                $orderAddress = OrderAddress::where('oaddr_order_id', $order)->get()->toArray();
                if (count($orderAddress) > 0) {
                    foreach ($orderAddress as $address) {
                        OrderAddress::where('oaddr_order_id', $address['oaddr_order_id'])->where('oaddr_type', $address['oaddr_type'])
                        ->update([
                            'oaddr_email' => obfuscate($address['oaddr_email']),'oaddr_phone' => obfuscate($address['oaddr_phone']), 'oaddr_address1' => obfuscate($address['oaddr_address1'])  ]);
                    }
                }
            }
        }
    }
}
