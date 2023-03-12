<?php

namespace App\Models;

use App\Models\YokartModel;

class UserGroupMember extends YokartModel
{
  public $timestamps = false;

  public function user()
  {
      return $this->belongsTo('App\Models\User', 'ugm_user_id', 'user_id');
  }
}
