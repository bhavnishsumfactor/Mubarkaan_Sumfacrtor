<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\Product;
use App\Http\Controllers\YokartController;
use Inertia\Inertia;
use Illuminate\Support\Collection;

class MessagesController extends YokartController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $messages = Thread::getAllMessageLoggedInUser($this->user->user_id);
        
        return Inertia::render('Activity/Messages/Index', ['allMessages' => $messages]);
    }
    public function getAllThreadMessages(Request $request, $threadId)
    {
        $messageThread = Thread::getAllThreadMessages($threadId);
        $fields =  'prod_id, ' . Product::getPrice();
        $product = Product::productById($messageThread->thread_product_id, $fields, [], $messageThread->thread_product_variant, true);
        $records['messageThread'] = $messageThread;
        $available = true;
        if (empty($product)) {
            $available = false;
        }
     
        $messageThread['available'] = $available;
         Thread::readAdminMessages($threadId);
        return jsonresponse(true, '', $messageThread);
    }
    public function sendMessages(Request $request)
    {
        $message = ThreadMessage::saveMessage($request->all(), $this->user->user_id);
        return jsonresponse(true, '', $message);
    }

    public function searchThread(Request $request)
    {
        $messages = Thread::getAllMessageLoggedInUser($this->user->user_id, $request->input('search'));
        return jsonresponse(true, '', $messages);
    }

    public function getUnreadMessageCount(Request $request)
    {
        $messageCount = 0;
        $messageCount = ThreadMessage::getUserUnreadMessageCount($this->user->user_id);
        return jsonresponse(true, '', $messageCount);
    }

    public function uploadImage(Request $request)
    {
        $message  = ThreadMessage::saveMessage($request->all(), $this->user->user_id);
        $response =  view('themes.' . config('theme') . '.dashboard.messages.message', compact('message'))->render();
        return jsonresponse(true, '', $response);
    }
}
