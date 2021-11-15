<?php

namespace App\Http\Controllers;

use App\Models\Palette;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Inertia::render('Welcome', [
            'palettes' => $this->pk->currentAccount()
                        ->palettes()
                        ->with('bath')
                        ->orderBy('id')
                        ->paginate(1),
            'default' => $this->pk->currentAccount()
                        ->palettes()
                        ->whereDoesntHave('bath')
                        ->orderBy('id')
                        ->limit(2)
                        ->get(),
        ]);

    }

    /**
     * Display a page if the pi doesn't have default account configured
     *
     */
    public function noaccount()
    {

        return Inertia::render('NoAccount');

    }

}
