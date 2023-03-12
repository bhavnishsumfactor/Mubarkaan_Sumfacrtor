<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogPostComment;
use App\Models\BlogPostToComment;
use App\Models\BlogReplyToComment;
use App\Models\Configuration;
use App\Models\Admin\AdminRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use DB;
use Carbon\Carbon;

class BlogPostCommentController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::BLOGS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListing(Request $request)
    {
        $data = [];
        $data['comments'] = BlogPostComment::getRecords($request->all());
        
        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['comments']->count() == 0) {
            $data['showEmpty'] = 1;
        }
        return jsonresponse(true, null, $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $status = $request->input('status') == 'true' ? 1:0;
        BlogPostComment::where('bpcomment_id', $id)->update(['bpcomment_approved' => $status, 'bpcomment_updated_by_id' => $this->admin->admin_id, 'bpcomment_updated_at' => Carbon::now() ]);
        return jsonresponse(true, __('NOTI_COMMENT_STATUS_UPDATED'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $ipAddress = \Request::ip();
        $userAgent = $request->header('User-Agent');
        $commentId = BlogPostComment::create([
            'bpcomment_user_id'  => 0,
            'bpcomment_content'  => $request->input('bpcomment_content'),
            'bpcomment_approved' => 1,
            'bpcomment_added_on' => Carbon::now(),
            'bpcomment_user_ip'  => $ipAddress,
            'bpcomment_user_agent' => $userAgent,
            'bpcomment_created_by_id' => $this->admin->admin_id,
            'bpcomment_updated_by_id' => $this->admin->admin_id,
            'bpcomment_created_at' => Carbon::now(),
            'bpcomment_updated_at' => Carbon::now()
       ])->bpcomment_id;
        BlogReplyToComment::create([
            'prtc_replied_bpcomment_id' => $request->input('comment_id'),
            'prtc_replyto_bpcomment_id' => $commentId
        ]);
        return jsonresponse(true, __('NOTI_COMMENT_SENT'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BlogPostComment  $blogPostComment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lastCommentedAdmin = [];
        $postDetail = BlogPostComment::getRecordById($id);
        $postComments = BlogReplyToComment::getReplies($id);
       
        if (!empty($postComments) && $postComments->count() > 0) {
            $lastComment = $postComments[$postComments->count() - 1];
            $lastCommentedAdmin = BlogPostComment::select('bpcomment_created_by_id', 'bpcomment_updated_by_id', 'bpcomment_created_at', 'bpcomment_updated_at')->with(['createdAt','updatedAt'])->where('bpcomment_id', $lastComment->bpcomment_id)->first();
        }
        $businessName = Configuration::getValue('BUSINESS_NAME');
        return jsonresponse(true, null, compact('postDetail', 'postComments', 'businessName', 'lastCommentedAdmin'));
    }



    public function destroy($id)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        DB::beginTransaction();
        try {
            BlogPostComment::where('bpcomment_id', $id)->delete();
            BlogPostToComment::where('bptc_bpcomment_id', $id)->delete();
            BlogReplyToComment::where('prtc_replied_bpcomment_id', $id)->delete();
            DB::commit();
            return jsonresponse(true, __('NOTI_COMMENT_DELETED'));
        } catch (\Exception $e) {
            DB::rollback();
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }
}
