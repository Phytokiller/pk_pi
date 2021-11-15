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

    public function update(Request $request)
    {

        $settings = $this->pk->settings;
        $account = $this->pk->currentAccount();

        $settings->offset_t1 = $request->T1offset;
        $settings->offset_t2 = $request->T2offset;
        $settings->update();

        $account->bath_temperature = $request->Tboiler;
        $account->update();

        return redirect()->back();

    }

}
