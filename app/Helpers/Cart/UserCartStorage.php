<?php

namespace App\Helpers\Cart;

use App\Helpers\Cart\CartCollection;
use App\Models\UserCart;
use Carbon\Carbon;
use Auth;

class UserCartStorage
{
    public function has($key)
    {
        return UserCart::where('ucart_last_session_id', $key)->first();
    }
    public function get($key)
    {
        if ($this->has($key)) {
            return new CartCollection(UserCart::where('ucart_last_session_id', $key)->first()->ucart_details);
        } else {
            return [];
        }
    }
    public function put($key, $value)
    {

        if ($row = UserCart::where('ucart_last_session_id', $key)->first()) {
            // update
            $row->ucart_details = $value;
            $row->save();
        } else {
            $userId =0;
            if (Auth::check()) {
                $userId = Auth::user()->user_id;
            }
            UserCart::create([
                'ucart_user_id' => $userId,
                'ucart_last_session_id' => $key,
                'ucart_added_date' => Carbon::now(),
                'ucart_details' => $value
            ]);
        }
    }
}
