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
                        ->orderBy('counter', 'DESC')
                        ->orderBy('created_at', 'DESC')
                        ->paginate(1)
                        ->lastPage();

            Paginator::currentPageResolver(function() use ($lastPage) {
                return $lastPage;
            });
            
        }

        return Inertia::render('Welcome', [
            'palettes' => $this->pk->currentAccount()
                        ->palettes()
                        ->with('bath', function($query) {
                            $query->whereNull('deleted_at');
                        })
                        ->orderBy('counter', 'ASC')
                        ->orderBy('created_at', 'ASC')
                        ->paginate(1),
            'default' => $this->pk->currentAccount()
                        ->palettes()
                        ->whereDoesntHave('bath')
                        ->orderBy('counter', 'ASC')
                        ->orderBy('created_at', 'ASC')
                        ->limit($this->pk->currentAccount()->default_palettes_selected)
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
