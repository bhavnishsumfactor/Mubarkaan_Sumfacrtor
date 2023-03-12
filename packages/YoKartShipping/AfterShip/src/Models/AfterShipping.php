<?php

namespace YoKartShipping\AfterShip\Models;

use App\Models\PackageYokartModel;

class AfterShipping extends PackageYokartModel
{
    public const URL = "https://api.aftership.com";
    public const VERSION = 'v4';
}