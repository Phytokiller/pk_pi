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

        return Inertia::render('Setting', [
            'settings' => $settings,
        ]);

    }

    public function update()
    {

        $settings = $this->pk->settings;

        return redirect()->back();

    }

}
