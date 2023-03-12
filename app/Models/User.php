<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use DB;
use App\Models\UserGroup;
use App\Models\UserGroupMember;
use App\Models\UserPasswordReset;
use App\Models\Notification;
use App\Models\Configuration;
use App\Models\AttachedFile;
use App\Models\Currency;
use App\Models\Country;
use App\Jobs\SendNotification;
use App\Helpers\EmailHelper;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use Arr;
use Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;

    const CREATED_AT = 'user_created_at';
    const UPDATED_AT = 'user_updated_at';

    const USER_EMAIL_REGISTER_TYPE = 0;
    const USER_PHONE_REGISTER_TYPE = 1;
    const USER_SOCIAL_REGISTER_TYPE = 2;
  
    
    const G_MALE = 1;
    const G_FEMALE = 2;

    const GENDER = [
        self::G_MALE => 'Male',
        self::G_FEMALE => 'Female',
    ];
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_facebook_id', 'user_google_id', 'user_instagram_id', 'user_fname', 'user_lname', 'user_dob', 'user_gender', 'user_email', 'user_password', 'user_publish', 'user_phone_country_id', 'user_phone', 'user_phone_verified', 'user_email_verified', 'user_register_type',
        'user_language_id', 'user_currency_id', 'user_country_id', 'user_birthday_points_expiry', 'user_is_guest', 'user_created_at', 'user_updated_at', 'user_created_by_id', 'user_updated_by_id', 'user_cookie_preferences'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password'
    ];

    public static function getUsers($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $dbprefix = DB::getTablePrefix();
        $query = User::select(
            'user_id',
            DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'),
            'user_email',
            'user_phone',
            'user_publish',
            'user_gdpr_approved',
            'user_email_verified',
            'user_phone_verified',
            DB::raw("(SELECT GROUP_CONCAT(user_groups.ugroup_name) FROM " . $dbprefix . "user_group_members as user_group_members INNER JOIN " . $dbprefix . "user_groups as user_groups ON user_groups.ugroup_id = user_group_members.ugm_ugroup_id WHERE user_group_members.ugm_user_id = " . $dbprefix . "users.user_id) as user_groups"),
            'country_phonecode'
        )->leftJoin('countries', 'users.user_phone_country_id', '=', 'countries.country_id');
        if (!empty($request['search'])) {
            $query->where(function ($whereQuery) use ($request) {
                $whereQuery->where(DB::raw('concat(user_fname," ",user_lname)'), 'like', '%' . $request['search'] . '%')->orWhere('user_email', 'like', '%' . $request['search'] . '%')->orWhere('user_phone', 'like', '%' . $request['search'] . '%');
            });
        }
        if ($query->paginate((int) $per_page)->count() >= 1 && $query->paginate((int) $per_page)->currentPage() >= 1) {
            return $query->latest('user_id')->paginate((int) $per_page);
        } else {
            //return $query->paginate((int)$per_page)->previousPage();
            return $query->latest('user_id')->paginate((int) $per_page, ['*'], 'page', (int) $query->paginate((int) $per_page)->currentPage() - 1);
        }
    }

    public static function getUsersList($request)
    {
        $query = User::select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'));
        if (!empty($request['search'])) {
            $query->where('user_fname', 'like', '%' . $request['search'] . '%');
        }
        return $query->oldest('user_fname')->offset($request['row'])->take(10)->get();
    }

    public static function searchUser($search, $oldSeached = [])
    {
        $query = User::select('user_id', 'user_email', 'user_phone', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'));
        $query->where(function ($sql) use ($search) {
            $sql->orWhere(DB::raw('concat(user_fname," ",user_lname)'), 'like', '%' . $search . '%')
                ->orWhere('user_email', 'like', '%' . $search . '%')
                ->orWhere('user_phone', 'like', '%' . $search . '%');
        });
        if (count($oldSeached) > 0) {
            $query->whereNotIn('user_id', $oldSeached);
        }

        return $query->where('user_gdpr_approved', 0)->oldest('user_id')->get();
    }

    public function getAuthPassword()
    {
        return $this->user_password;
    }
    public function getRememberToken()
    {
        return $this->user_remember_token;
    }
    public function setRememberToken($value)
    {
        $this->user_remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'user_remember_token';
    }

    public static function sendEmailVerification($userId, $slug, $link, $newEmail = null)
    {
        UserPasswordReset::where('upr_expiry', '<', Carbon::now()->subMinutes(config('app.expiredToken'))->toDateTimeString())->delete();
        $user = User::where('user_id', $userId)->first();
        if ($user) {
            $records =  UserPasswordReset::where('upr_user_id', $user->user_id)->first();
            $token = '';
            if (!empty($records)) {
                $token = $records->upr_token;
            } else {
                $token = Str::random(64);
                UserPasswordReset::insert([
                    'upr_user_id' => $user->user_id,
                    'upr_token' => $token,
                    'upr_expiry' => Carbon::now()
                ]);
            }
            if ($newEmail == null) {
                $newEmail = $user->user_email;
            }
            /*send email code*/
            $data = EmailHelper::getEmailData($slug);
            $priority = $data['body']->etpl_priority;
            $data['subject'] = $data['body']->etpl_subject;
            $data['body'] = str_replace('{name}', $user->user_fname, $data['body']->etpl_body);
            $data['body'] = str_replace('{email}', $newEmail, $data['body']);
            $data['body'] = str_replace('{resetlink}', url($link, $token), $data['body']);
            $data['to'] = $newEmail;
            dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
        }
    }

    public static function generateUserId($data)
    {
        $user = User::select('user_id', 'user_email', 'user_phone_country_id')->with('phoneCountry')->where('user_email', $data['addr_email'])->first();
        if (!empty($user)) {
            return $user;
        };
        return User::create([
            'user_fname' => $data['addr_first_name'],
            'user_lname' => $data['addr_last_name'],
            'user_email' => $data['addr_email'],
            'user_phone_country_id' => $data['addr_phone_country_id'],
            'user_phone' => $data['addr_phone'],
            'user_email_verified' => config("constants.NO"),
            'user_timezone' => getConfigValueByName('ADMIN_TIMEZONE'),
            'user_is_guest' => 1
        ]);
    }

    public function messageFrom()
    {
        return $this->morphOne('App\Model\ThreadMessage', 'messageFrom');
    }

    public static function getUserById($id, $type = 'user_id')
    {
        return User::select('user_id', 'user_fname', 'user_lname', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'), 'user_email', 'user_instagram_id', 'user_facebook_id', 'user_google_id', 'user_dob', 'user_gender', 'user_phone', 'user_publish', 'user_phone_country_id', 'country_code', 'country_phonecode', 'user_created_by_id', 'user_updated_by_id', 'user_created_at', 'user_updated_at')->leftJoin('countries', 'users.user_phone_country_id', '=', 'countries.country_id')
            ->with(['createdAt', 'updatedAt'])
            ->where($type, $id)
            ->first();
    }

    public function phoneCountry()
    {
        return $this->belongsTo('App\Models\Country', 'user_phone_country_id', 'country_id');
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'user_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'user_updated_by_id')->select(['admin_id', 'admin_username']);
    }

    public function totalOrderAmount()
    {
        return $this->hasMany('App\Models\Order', 'order_user_id', 'user_id');
    }

    public function newsLetterSubscription()
    {
        return $this->hasOne('App\Models\NewsletterSubscription', 'subscription_email', 'user_email');
    }

    public function image()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'user_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['userProfileImage']);
    }

    public static function getUser($fields, $conditions)
    {
        $query = User::select($fields);
        $query->where($conditions);
        $query->with(['phoneCountry:country_id,country_phonecode', 'image']);
        return $query->get();
    }
    public static function getDetailsForApp($userId)
    {
        $user = User::getUser(
            [
                'user_id',
                'user_fname',
                'user_lname',
                DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'),
                'user_email',
                'user_phone_country_id',
                'user_phone',
                'user_dob',
                'user_register_type',
                'user_gender',
            ],
            [
                'user_id' => $userId
            ]
        )->first();
        $response = $user;
        $response["total_unread_notification_count"] = 0;
        $response["cart_items_count"] = 0;
        $response["total_favourite_items"] = 0;
        $response["total_unread_message_count"] = 0;

        if (!empty(Auth::guard('api')->user()->user_currency_id)) {
            $currency = Currency::getRecordById(Auth::guard('api')->user()->user_currency_id);
        } else {
            $currency = Currency::getDefault();
        }
        $currency = !empty($currency) ? $currency->toArray() : [];
        $response['currency'] = Arr::only($currency, ['currency_id', 'currency_code', 'currency_symbol', 'currency_position', 'currency_value']);

        $response["user_phone_code"] = !empty($user->phoneCountry->country_phonecode) ? "+" . $user->phoneCountry->country_phonecode : "";
        $response["user_image"] = !empty($user->image->afile_id) ? url('/') . '/yokart/image/' . $user->image->afile_id . '?t=' . strtotime($user->image->afile_updated_at) : "";
        unset($response["user_phone_country_id"]);
        unset($response["phoneCountry"]);
        unset($response["image"]);
        return $response;
    }
    public static function updateProfileFromApp($request, $userId, $id = false)
    {
        $uId = '';
        if ($id) {
            $uId = 'required';
        }
        $validator = Validator::make($request->all(), [
            'user_fname' => 'required|string|max:191',
            'user_lname' => 'required|string|max:191',
            'user_dob' => 'required',
            'user_id' => $uId,
            'user_gender' => 'required'
        ]);
        $validator->setAttributeNames([
            'user_fname' => 'first name',
            'user_lname' => 'last name',
            'user_dob' => 'date of birth',
            'user_id' => 'user id',
            'user_gender' => 'gender'
        ]);
        if ($validator->fails()) {
            return  ['status' => false, 'message' => $validator->errors()->first()];
        }
        $updatedRecords = $request->only('user_fname', 'user_lname', 'user_dob', 'user_gender');
        if ($request['phone']) {
              $country = Country::getCountries(['country_id'], ['country_code' => strtoupper($request['phone_country_code'])])->first();
            if (!empty($country->country_id)) {
                $updatedRecords['user_phone_country_id'] = $country->country_id;
            }
            $updatedRecords['user_phone'] = $request['phone'];
        }
        User::where('user_id', $userId)->update($updatedRecords);
        return  ['status' => true];
    }
}
