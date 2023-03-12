<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\User;
use App\Models\Admin\AdminRole;
use App\Http\Requests\UserGroupRequest;
use App\Helpers\EmailHelper;
use App\Models\UserGroup;
use App\Models\Notification;
use App\Jobs\SendNotification;
use App\Models\UserGroupMember;
use Carbon\Carbon;
use DB;

class UserGroupController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::USERS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $usergroups = UserGroup::getGroups($request->all());
        $status = ($usergroups->count() > 0) ? true : false;
        $allusers = User::select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'))->oldest('user_name')->where('user_publish', 1)
                    ->take(config('app.pagination'))->get();

        $data['usergroups'] = $usergroups;
        $data['allusers'] = $allusers;

        $data['showEmpty'] = 0;
        if (empty($request['search']) && $usergroups->count() == 0) {
            $data['showEmpty'] = 1;
        }
        return jsonresponse($status, '', $data);
    }

    public function getRecords($ugroup_id = null)
    {
        $ugmUserIds = [];
        $userGroups = [];
        if (!empty($ugroup_id)) {
            $ugmUserIds = UserGroupMember::where('ugm_ugroup_id', $ugroup_id)->pluck('ugm_user_id')->toArray();
            $userGroups = UserGroup::where('ugroup_id', $ugroup_id)->with(['createdAt','updatedAt'])->first();
        }
        $sourceList = User::select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'))->where('user_publish', 1)->whereNotIn('user_id', $ugmUserIds)->having("user_name", '!=', " ")->take(config('app.pagination'))->get();
        $sourceAll = User::select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'))->where('user_publish', 1)->whereNotIn('user_id', $ugmUserIds)->count();
        $destinationList = User::select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'))->where('user_publish', 1)->whereIn('user_id', $ugmUserIds)->take(config('app.pagination'))->get();
        $destinationAll = User::select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'))->where('user_publish', 1)->whereIn('user_id', $ugmUserIds)->count();

        return jsonresponse(true, '', ['id' => $ugroup_id, 'sourceList' => $sourceList, 'destinationList' => $destinationList, 'sourceAll' => $sourceAll, 'destinationAll' => $destinationAll, 'pageLength' => config('app.pagination'), 'userGroups' => $userGroups ]);
    }

    public function updateMembers(Request $request)
    {
        if (!canWrite(AdminRole::USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        if (!empty($request->input('ugroup_id')) && $request->input('ugroup_id') != 'undefined') {
            $id = $request->input('ugroup_id');
            UserGroup::where('ugroup_id', $id)->update([
                'ugroup_name' => $request->input('ugroup_name'),
                'ugroup_updated_at' => Carbon::now(),
                'ugroup_updated_by_id' => $this->admin->admin_id
            ]);
        } else {
            $usergroup = UserGroup::create([
                'ugroup_name' => $request->input('ugroup_name'),
                'ugroup_created_by_id' => $this->admin->admin_id,
                'ugroup_updated_by_id' => $this->admin->admin_id,
                'ugroup_created_at' => Carbon::now(),
                'ugroup_updated_at' => Carbon::now()
            ]);
            $id = $usergroup->ugroup_id;
        }

        $destination = json_decode($request->input('destination'));
        $deleteDestinationUser = json_decode($request->input('deleted'));
       
        if (isset($deleteDestinationUser) && !empty($deleteDestinationUser)) {
            UserGroupMember::where('ugm_ugroup_id', $id)->whereIn('ugm_user_id', $deleteDestinationUser)->delete();
        }
        if (!empty($destination)) {
            foreach ($destination as $v) {
                if ($v == null) {
                    continue;
                }
                $check = UserGroupMember::where('ugm_ugroup_id', $id)->where('ugm_user_id', $v->user_id)->count();
                if ($check == 0) {
                    UserGroupMember::insert(['ugm_user_id' => $v->user_id, 'ugm_ugroup_id' => $id]);
                }
            }
        }
        return $this->getRecords($id);
    }

    public function getSourceListing(Request $request, $ugroup_id = null)
    {
        $ugmUserIds = UserGroupMember::where('ugm_ugroup_id', $ugroup_id)->pluck('ugm_user_id')->toArray();
        $query = User::select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'))->where('user_publish', 1)->whereNotIn('user_id', $ugmUserIds);
        if (!empty($request['search'])) {
            $query->where(DB::raw('concat(user_fname," ",user_lname)'), 'like', '%' . $request['search'] . '%');
        }
        $sourceList = $query->having("user_name", '!=', " ")->offset($request['row'])->take(10)->get();
        return jsonresponse(true, '', $sourceList);
    }

    public function getDestinationListing(Request $request, $ugroup_id = null)
    {
        $ugmUserIds = UserGroupMember::where('ugm_ugroup_id', $ugroup_id)->pluck('ugm_user_id')->toArray();
        $query = User::select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'))->where('user_publish', 1)->whereIn('user_id', $ugmUserIds);
        if (!empty($request['search'])) {
            $query->where(DB::raw('concat(user_fname," ",user_lname)'), 'like', '%' . $request['search'] . '%');
        }
        $destinationList = $query->offset($request['row'])->take(10)->get();
        return jsonresponse(true, '', $destinationList);
    }

    public function index(Request $request)
    {
        return view('admin.userGroups.index');
    }

    public function store(UserGroupRequest $request)
    {
        if (!canWrite(AdminRole::USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $usergroup = UserGroup::create([
            'ugroup_name' => $request->input('ugroup_name'),
            'ugroup_created_by_id' => $this->admin->admin_id,
            'ugroup_updated_by_id' => $this->admin->admin_id,
            'ugroup_created_at' => Carbon::now(),
            'ugroup_updated_at' => Carbon::now()
        ]);
        $destination = json_decode($request->input('destination'));
        if (!empty($destination)) {
            foreach ($destination as $v) {
                if ($v == null) {
                    continue;
                }
                $check = UserGroupMember::where('ugm_ugroup_id', $usergroup->ugroup_id)->where('ugm_user_id', $v->user_id)->count();
                if ($check == 0) {
                    UserGroupMember::insert(['ugm_user_id' => $v->user_id, 'ugm_ugroup_id' => $usergroup->ugroup_id]);
                }
            }
        }
        return jsonresponse(true, __('NOTI_GROUP_CREATED'));
    }

    public function update(UserGroupRequest $request, $id)
    {
        if (!canWrite(AdminRole::USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        UserGroup::where('ugroup_id', $id)->update([
            'ugroup_name' => $request->input('ugroup_name'),
            'ugroup_updated_at' => Carbon::now(),
            'ugroup_updated_by_id' => $this->admin->admin_id
        ]);
        return jsonresponse(true, __('NOTI_GROUP_UPDATED'));
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        UserGroup::where('ugroup_id', $id)->delete();
        UserGroupMember::where('ugm_ugroup_id', $id)->delete();
        return jsonresponse(true, __('NOTI_GROUP_DELETED'));
    }

    public function sendMail(Request $request, $group_id)
    {
        if (!canWrite(AdminRole::USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $ugmUserIds = UserGroupMember::where('ugm_ugroup_id', $group_id)->pluck('ugm_user_id')->toArray();
        $users = User::select('user_email', 'user_fname', 'user_lname')->whereIn('user_id', $ugmUserIds)->get();
        $message = $request->input('message');
        $subject = $request->input('subject');
        $usersCount = count($users);
        if ($usersCount > 0) {
            for ($i = 0; $i < $usersCount; $i++) {
                $data = EmailHelper::getEmailData('email_from_admin_to_buyer');
                $priority = $data['body']->etpl_priority;
                $data['subject'] = $this->replaceBuyerMailTags($data['body']->etpl_subject, $users[$i], $subject, $message);
                $data['body'] = $this->replaceBuyerMailTags($data['body']->etpl_body, $users[$i], $subject, $message);
                $data['to'] = $users[$i]->user_email;
                dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
            }
        }
        return jsonresponse(true, __('NOTI_EMAILS_SENT'));
    }

    private function replaceBuyerMailTags($content, $user, $subject, $message)
    {
        $content = str_replace('{subject}', $subject, $content);
        $content = str_replace('{name}', $user->user_fname . ' ' . $user->user_lname, $content);
        $content = str_replace('{messageContent}', $message, $content);
        $content = str_replace('{contactEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $content);
        $content = str_replace('{websiteName}', (getConfigValueByName('BUSINESS_NAME') ?? ''), $content);
        return $content;
    }

    public function moveUserSourceToDestination(Request $request, $ugroup_id = null)
    {
        $ugmUserIds = UserGroupMember::where('ugm_ugroup_id', $ugroup_id)->pluck('ugm_user_id')->toArray();
        $query = User::select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'))->whereNotIn('user_id', $ugmUserIds);
        if (!empty($request['search'])) {
            $query->where(DB::raw('concat(user_fname," ",user_lname)'), 'like', '%' . $request['search'] . '%');
        }
        $sourceList = $query->having("user_name", '!=', " ")->get();
        return jsonresponse(true, '', $sourceList);
    }

    public function moveUserDestinationToSource(Request $request, $ugroup_id = null)
    {
        if (UserGroupMember::where('ugm_ugroup_id', $ugroup_id)->delete()) {
            return jsonresponse(true, '');
        } else {
            return jsonresponse(false, '');
        }
    }
}
