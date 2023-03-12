<?php

namespace App\Models;

use App\Models\YokartModel;

class BlogReplyToComment extends YokartModel
{
    public $timestamps = false;


    protected $fillable = [
        'prtc_replied_bpcomment_id', 'prtc_replyto_bpcomment_id'
    ];


    public static function getReplies($id)
    {
        return BlogReplyToComment::leftJoin('blog_post_comments as comment', 'comment.bpcomment_id', 'blog_reply_to_comments.prtc_replyto_bpcomment_id')
        ->select('bpcomment_id','bpcomment_content', 'bpcomment_added_on', 'bpcomment_created_by_id', 'bpcomment_updated_by_id', 'bpcomment_created_at', 'bpcomment_updated_at')
        ->where('prtc_replied_bpcomment_id', $id)->get();
    }
}
