<?php

namespace App\Http\Controllers;

use App\Models\Palette;
use Illuminate\Http\Request;
use App\Helpers\PK;
use Inertia\Inertia;

class WelcomeController extends Controller
{

    protected $pk;

    public function __construct()
    {
        $this->pk = new PK;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $debug = [
            'Compte' => $this->pk->currentAccount(),
            'Settings' => $this->pk->settings,
            'Users' => $this->pk->currentAccount()->users,
            'Palettes' => $this->pk->currentAccount()->palettes,
        ];

        //dd($debug);

        return Inertia::render('Welcome', [
            'palettes' => $this->pk->currentAccount()->palettes,
        ]);

        //return view('welcome', ['pk' => $this->pk]);
    }

}
