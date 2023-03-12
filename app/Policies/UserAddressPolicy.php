<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserAddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserAddress  $userAddress
     * @return mixed
     */
    public function update(User $user, UserAddress $userAddress)
    {
        return $user->user_id === $userAddress->addr_user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserAddress  $userAddress
     * @return mixed
     */
    public function delete(User $user, UserAddress $userAddress)
    {
        return $user->user_id === $userAddress->addr_user_id;
    }
}
