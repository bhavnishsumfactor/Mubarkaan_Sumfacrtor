<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\ThreadMessage;
use App\Models\AttachedFile;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminRole;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use Carbon\Carbon;
use Auth;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class Thread extends YokartModel
{
    protected $primaryKey = 'thread_id';
    public $timestamps = false;

    protected $fillable = [
        'thread_started_by', 'thread_subject', 'thread_product_name', 'thread_product_id','thread_product_variant','thread_created_at'
    ];
    public static function createMessageThread($request, $userId)
    {
        DB::beginTransaction();
        try {
            $variantCode = '';
            if (!empty($request['thread_product_variant'])) {
                $variantCode = $request['thread_product_variant'];
            }
            $thread = Thread::create([
                    'thread_started_by' => $userId,
                    'thread_subject' => $request['subject'],
                    'thread_product_id' => $request['thread_product_id'],
                    'thread_product_variant' => $variantCode,
                    'thread_product_name' => $request['thread_product_name'],
                    'thread_created_at' => Carbon::now()
            ]);
            $superAdminId = Admin::getSuperAdminId();
            ThreadMessage::create([
                    'message_thread_id' => $thread->thread_id,
                    'message_from_id' => $userId,
                    'message_from_type' => 'App\Models\User',
                    'message_to' => $superAdminId->admin_id,
                    'message_text' => $request['message'],
                    'message_is_unread' => \Config::get('constants.YES'),
                    'message_deleted' => \Config::get('constants.NO'),
                    'message_created_at' => Carbon::now()
            ]);
            $admins = Admin::getAdminsByModule(AdminRole::MESSAGES);
            $thread->with('product:prod_id,prod_name')->first();
            if (!empty($admins)) {
                foreach ($admins as $key => $admin) {
                    $notificationData = [];
                    $sendSms = false;
                    $data = EmailHelper::getEmailData("message_admin");
                    $priority = $data['body']->etpl_priority;
                    $data['subject'] = str_replace('{buyerName}', Auth::user()->user_fname.' '. Auth::user()->user_lname, $data['body']->etpl_subject);
                    $data['subject'] = str_replace('{productName}', $thread->product->prod_name, $data['subject']);
                    $data['body'] = str_replace('{name}', $admin['admin_name'], $data['body']->etpl_body);
                    $data['body'] = str_replace('{buyerName}', Auth::user()->user_fname.' '. Auth::user()->user_lname, $data['body']);
                    $data['body'] = str_replace('{productName}', $request['thread_product_name'], $data['body']);
                    $data['body'] = str_replace('{productImage}', productImageUrl(
                        $thread->product->prod_id,
                        !empty($thread->thread_product_variant) ? str_replace("_", "|", $thread->thread_product_variant) : "0"
                    ), $data['body']);
                    $data['body'] = str_replace('{productUrl}', getRewriteUrl("products", $thread->product->prod_id), $data['body']);
                    $data['body'] = str_replace('{inboxUrl}', url("/admin#/thread/" . $thread->thread_id . "/messages"), $data['body']);
                    $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                    $data['to'] = $admin['admin_email'];
                    $notificationData['email'] = $data;
                    if (!empty($admin['admin_phone']) && !empty($admin['admin_phone_country_id'])) {
                        $country = Country::select('country_phonecode')->where('country_id', $admin['admin_phone_country_id'])->first();
                        if (!empty($country->country_phonecode) && !empty($admin['admin_phone'])) {
                            $data = SmsTemplate::getSMSTemplate('message_admin');
                            $priority = $data['body']->stpl_priority;
                            $data['body'] = str_replace('{name}', $admin['admin_name'], $data['body']->stpl_body);
                            $data['body'] = str_replace('{productName}', $request['thread_product_name'], $data['body']);
                            $notificationData['sms'] = [
                                'message' => $data['body'],
                                'recipients' => array('+' . $country->country_phonecode . $admin['admin_phone'])
                            ];
                            $sendSms = true;
                        }
                    }
                    dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public function getLastMessage()
    {
        return $this->hasOne('App\Models\ThreadMessage', 'message_thread_id', 'thread_id')->latest();
    }
    public function urlRewrite()
    {
        return $this->belongsTo('App\Models\UrlRewrite', 'thread_product_id', 'urlrewrite_record_id')->where('urlrewrite_type', UrlRewrite::PRODUCT_TYPE);
    }
    public function threadMessages()
    {
        return $this->hasMany('App\Models\ThreadMessage', 'message_thread_id', 'thread_id');
    }
    public static function getAllMessageLoggedInUser($userId, $search = '')
    {
        $obj = Thread::with('getLastMessage:message_thread_id,message_text,message_created_at')->withCount('getUnreadMessage')->where('thread_started_by', $userId)->with('images:afile_record_id,afile_updated_at');
        if ($search) {
            $obj->where('thread_product_name', 'LIKE', '%' . $search . '%');
        }
        return $obj->get()->sortByDesc(function ($query) {
            return $query->getLastMessage->message_created_at;
        });
    }
    public static function getAllThreadMessages($threadId)
    {
        return Thread::with('threadMessages.uploadImages')->with('product:prod_id,prod_name')->with('productVariant:pov_display_title,pov_code')->with('urlRewrite')->with('images:afile_record_id,afile_updated_at')->where('thread_id', $threadId)->get()->first();
    }

    public static function getThreadMessages($threadId)
    {
        return ThreadMessage::with(['thread:thread_id,thread_product_id,thread_subject', 'thread.product:prod_id,prod_name', 'messageFrom', 'uploadImages'])
        ->where('message_thread_id', $threadId)->get();
    }

    public static function getAllMessageThreads($request)
    {
        $dbprefix = \DB::getTablePrefix();
        if (!empty($request['search'])) {
            $search = $request['search'];
            $data['message'] =  Thread::with(['getLastMessage:message_thread_id,message_text,message_created_at'])
                ->select('thread_id', 'thread_started_by', 'thread_product_id', 'thread_product_variant', 'thread_created_at', 'thread_subject', 'user_fname', 'user_lname', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'), 'message_id', 'thread_product_name', 'message_text', 'message_created_at', 'message_thread_id') //, 'prod_id', 'prod_name'
                ->withcount('getAdminUnreadMessage')
                ->join('users', 'users.user_id', 'threads.thread_started_by')
                // ->join('products', 'products.prod_id', 'threads.thread_product_id')
                ->join('thread_messages', function ($join) use ($dbprefix) {
                    $join->on('message_thread_id', '=', 'thread_id')
                    ->whereRaw($dbprefix.'thread_messages.message_id IN (select MAX(tm.message_id) from '.$dbprefix.'thread_messages as tm join '.$dbprefix.'threads as thrd on thrd.thread_id = tm.message_thread_id group by thrd.thread_id)');
                })
                ->where(DB::raw('CONCAT(user_fname, " ", user_lname)'), 'LIKE', '%' . $search . '%')
                ->orderby('message_created_at', 'desc')->take(config('app.pagination'))->get()->toArray();
        } else {
            $data['message'] = Thread::select('thread_id', 'thread_started_by', 'thread_product_id', 'thread_product_variant', 'thread_created_at', 'thread_subject', 'user_fname', 'user_lname', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'), 'message_id', 'thread_product_name', 'message_text', 'message_created_at', 'message_thread_id') //, 'prod_id', 'prod_name'
            ->join('users', 'users.user_id', 'threads.thread_started_by')
            // ->join('products', 'products.prod_id', 'threads.thread_product_id')
            ->join('thread_messages', function ($join) use ($dbprefix) {
                $join->on('message_thread_id', '=', 'thread_id')
                ->whereRaw($dbprefix.'thread_messages.message_id IN (select MAX(tm.message_id) from '.$dbprefix.'thread_messages as tm join '.$dbprefix.'threads as thrd on thrd.thread_id = tm.message_thread_id group by thrd.thread_id)');
            })
            ->withCount('getAdminUnreadMessage')
            ->orderby('message_created_at', 'desc')->skip($request['total'])->take(config('app.pagination'))->get()->toArray();
        }
        $data['messageCount'] = ThreadMessage::getMessageCount();
        $data['pageCount'] = config('app.pagination');
        $data['threadCount'] = Thread::count();
        $data['threadUnreadCount'] = Thread::getThreadUnreadMessageCount();
       
        return $data;
    }
    
    public function product()
    {
        return $this->hasOne('App\Models\Product', 'prod_id', 'thread_product_id');
    }
    public function favProduct()
    {
        return $this->hasOne('App\Models\UserFavouriteProduct', 'ufp_prod_id', 'thread_product_id');
    }

    public function getAdminUnreadMessage()
    {
        return $this->hasMany('App\Models\ThreadMessage', 'message_thread_id', 'thread_id')
                ->where('message_is_unread', config('constants.YES'))->where('message_from_admin', config('constants.NO'));
    }

    public static function getAdminUnreadMessageCount()
    {
        return ThreadMessage::where('message_is_unread', config('constants.YES'))->where('message_from_admin', config('constants.YES'))
        ->distinct('message_thread_id')->count();
    }
    public static function getThreadUnreadMessageCount()
    {
        return ThreadMessage::where('message_is_unread', config('constants.YES'))->where('message_from_admin', config('constants.NO'))
        ->distinct('message_thread_id')->count();
    }

    public static function readAdminMessages($threadId)
    {
        return ThreadMessage::where('message_thread_id', $threadId)->where('message_is_unread', config('constants.YES'))->where('message_from_admin', config('constants.YES'))->update(['message_is_unread'=> config('constants.NO') ]);
    }

    public static function searchThread($value, $userId)
    {
        return Thread::join('products', 'prod_id', 'thread_product_id')
                ->where('prod_name', 'LIKE', '%'.$value.'%')->where('thread_started_by', $userId)->orderby('thread_id', 'desc')->get();
    }

    public function productVariant()
    {
        return $this->hasOne('App\Models\ProductOptionVarient', 'pov_code', 'thread_product_variant');
    }

    public function getUnreadMessage()
    {
        return $this->hasMany('App\Models\ThreadMessage', 'message_thread_id', 'thread_id')
            ->where('message_is_unread', config('constants.YES'))->where('message_from_admin', config('constants.YES'));
    }
    public function images()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'thread_product_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['productImages'])->orderBy('attached_files.afile_updated_at', 'DESC');
    }

    public static function getMessageListingForApp($userId, $page, $search = '')
    {
        $collection = Thread::select('thread_id', 'thread_product_id', 'thread_product_variant', 'thread_subject', 'thread_created_at', 'thread_product_name', 'ufp_id as favId', 'ufp_pov_code')
        ->with('getLastMessage:message_thread_id,message_text,message_created_at,message_from_admin')->withCount('getUnreadMessage')->where('thread_started_by', $userId)
        ->with(array('images' => function ($query) {
            $query->select('afile_record_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id,'?t=', UNIX_TIMESTAMP(afile_updated_at)) as message_image"));
        }))->leftjoin('user_favourite_products as fav', function ($join) use ($userId) {
            $join->on('fav.ufp_prod_id', '=', 'thread_product_id')
                ->where('ufp_user_id', $userId)
                ->whereRaw('(ufp_pov_code = thread_product_variant or  ufp_pov_code IS NULL)');
        });
        if ($search) {
            $collection->where('thread_product_name', 'LIKE', '%' . $search . '%');
        }
        $collection = $collection->get()->sortByDesc(function ($query) {
            return $query->getLastMessage->message_created_at;
        })->values();
        return self::getPaginator($collection, (int) config('app.app_pagination'), $page);
    }

    public static function getMessageDetailsForApp($threadId, $userId)
    {
    
        return Thread::select('thread_id', 'thread_product_id', 'thread_product_variant', 'thread_subject', 'thread_created_at', 'ufp_id as favId', 'thread_product_name')
        ->with(array('threadMessages.uploadImages' => function ($query) {
            $query->select('afile_record_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id,'?t=', UNIX_TIMESTAMP(afile_updated_at)) as message_image"));
        }))
        ->with('threadMessages:message_id,message_thread_id,message_text,message_created_at,message_from_admin')
        ->with('productVariant:pov_display_title,pov_code')
        ->leftjoin('user_favourite_products as fav', function ($join) use ($userId) {
            $join->on('fav.ufp_prod_id', '=', 'thread_product_id')
                ->where('ufp_user_id', $userId)
                ->whereRaw('(ufp_pov_code = thread_product_variant or  ufp_pov_code IS NULL)');
        })
        ->with(array('images' => function ($query) {
            $query->select('afile_record_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id,'?t=', UNIX_TIMESTAMP(afile_updated_at)) as message_image"));
        }))
        ->where('thread_id', $threadId)->get()->first();
    }

    private static function getPaginator($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
