<?php

namespace App\Http\Controllers;

use App\Models\Palette;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;

class WelcomeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->page == '') {

            $lastPage = $this->pk->currentAccount()
                        ->palettes()
                        ->with('bath')
                        ->latest()
                        ->paginate(1)
                        ->lastPage();

            Paginator::currentPageResolver(function() use ($lastPage) {
                return $lastPage;
            });
            
        }

        return Inertia::render('Welcome', [
            'palettes' => $this->pk->currentAccount()
                        ->palettes()
                        ->with('bath')
                        ->latest()
                        ->paginate(1),
            'default' => $this->pk->currentAccount()
                        ->palettes()
                        ->whereDoesntHave('bath')
                        ->orderBy('created_at', 'ASC')
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
