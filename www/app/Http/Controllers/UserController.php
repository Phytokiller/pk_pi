<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function switch($id)
    {

        $this->settings->user_id = $id;
        $this->settings->update();

        return redirect()->back();

    }
}
