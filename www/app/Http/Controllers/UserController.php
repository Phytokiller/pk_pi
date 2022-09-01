<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function switch($id)
    {
        $this->settings->user_id = $id;
        $this->settings->update();

        return redirect()->back();
    }
}
