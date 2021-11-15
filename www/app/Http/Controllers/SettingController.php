<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{


    public function index()
    {

        $settings = $this->pk->settings;
        $account = $this->pk->currentAccount();

        return Inertia::render('Setting', [
            'settings' => $settings,
            'account' => $account,
        ]);

    }

    public function update()
    {

        $settings = $this->pk->settings;

        return redirect()->back();

    }

}
