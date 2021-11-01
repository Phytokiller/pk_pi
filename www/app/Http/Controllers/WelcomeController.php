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
                        ->with('baths')
                        ->orderBy('id', 'desc')
                        ->paginate(1),
            'default' => $this->pk->currentAccount()
                        ->palettes()
                        ->whereDoesntHave('baths')
                        ->orderBy('id')
                        ->limit(2)
                        ->get(),
        ]);

    }

}
