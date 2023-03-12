<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\User;
use DB;

class UsersExport implements FromCollection, WithHeadings
{
    private $filter;

    public function __construct($search)
    {
        $this->filter = $search;
    }

    public function collection()
    {
        $filters = $this->filter;
        $userObj = User::select('user_id', 'user_fname', 'user_lname', 'user_email', DB::raw('concat(user_phone_country_id," ",user_phone)'), \DB::raw('(CASE 
        WHEN user_gender = "2" THEN "Female" 
        WHEN user_gender = "1" THEN "Male" END) AS user_gender'), 'user_dob')
        ->withCount(['newsLetterSubscription' => function($query){
            $query->select(DB::raw('(CASE  WHEN count(subscription_id) = "1" THEN "Yes"  WHEN count(subscription_id) = "0" THEN "No" END)'));
        }])
        ->withCount(['totalOrderAmount as total_order_amount' => function ($query) {
            $query->select(DB::raw("SUM(order_net_amount)"));
        }])->withCount(['totalOrderAmount as discount_order_amount' => function ($query) {
            $query->select(DB::raw("SUM(order_discount_amount)"));
        }]);
        if (!empty($filters['date'])) {
            $date = explode(',', $filters['date']);
            $userObj->whereBetween('user_created_at', [$date[0]." 00:00:00", $date[1]." 23:59:59"]);
        }
        if (isset($filters['userStatus']) && !empty($filters['userStatus']) && ($filters['userStatus'] == "Active" || $filters['userStatus'] == "InActive" )) {
            if ($filters['userStatus'] == "Active") {
                $userObj->where('user_publish', config('constants.YES'));
            } else {
                $userObj->where('user_publish', config('constants.NO'));
            }
        }

        if (isset($filters['userVerified']) && !empty($filters['userVerified']) && ($filters['userVerified'] == "Yes" || $filters['userVerified'] == "No" )) {
            if ($filters['userVerified'] == "Yes") {
                $userObj->where(function($sql){
                    $sql->where('user_phone_verified', config('constants.YES'))->orWhere('user_email_verified', config('constants.YES'));
                });
            } else {
                $userObj->where(function($sql){
                    $sql->where('user_phone_verified', config('constants.NO'))->orWhere('user_email_verified', config('constants.NO'));
                });
            }
        }
        return $userObj->get();
    }

    public function headings(): array
    {
        return [
            'Id',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Gender',
            'DOB',
            'News Letter Subscription',
            'Total Order Amount',
            'Total Saving'
        ];
    }
}
