<?php

namespace App\Http\Controllers;

use App\Models\BlogPostComment;
use App\Models\BlogPostToComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Str;
use App\Http\Controllers\YokartController;
use Illuminate\Support\Facades\Validator;
use App\Events\SystemNotification;
use App\Models\NotificationTemplate;
use App\Models\Notification;

class BlogPostCommentController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }
    protected function validateComments(array $data)
    {
        return Validator::make($data, [
             'comment' => ['required']
         ]);
    }
    public function store(Request $request)
    {
       
        $this->validateComments($request->all())->validate();
        $ipAddress = \Request::ip();
        $userAgent = $request->header('User-Agent');
        $commentId = BlogPostComment::create([
            'bpcomment_user_id' =>$this->user->user_id,
            'bpcomment_content' => $request->input('comment'),
            'bpcomment_approved' => 0,
            'bpcomment_added_on' => Carbon::now(),
            'bpcomment_user_ip' => $ipAddress,
            'bpcomment_user_agent' => $userAgent
        ])->bpcomment_id;
        BlogPostToComment::create([
            'bptc_post_id' => $request->input('post_id'),
            'bptc_bpcomment_id' => $commentId
        ]);

        /** trigger event for system notification */
        $blog = BlogPost::select('post_title')->where('post_id', $request->input('post_id'))->first();
        $data = NotificationTemplate::getBySlug('new_comment_on_blog');
        $message = str_replace('{blogTitle}', $blog->post_title, $data->ntpl_body);
        $message = str_replace('{userName}', Str::title($this->user->user_fname . ' ' . $this->user->user_lname), $message);
        event(new SystemNotification([
            'type' => Notification::BLOG_COMMENT,
            'record_id' => $commentId,
            'from_id' => $this->user->user_id,
            'message' => $message
         ]));

        return jsonresponse(true, __('NOTI_COMMENT_SUCCESSFULLY_SENT'));
    }
}
