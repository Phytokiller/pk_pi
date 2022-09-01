<?php

namespace App\Http\Controllers;

use App\Helpers\PK;
use App\Models\Setting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $settings;

    public $pk;

    public function __construct()
    {
        $this->settings = Setting::find(1);
        $this->pk = new PK();
    }
}
