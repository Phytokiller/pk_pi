<?php

namespace App\Http\Controllers;

use App\Models\Bath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use App\Helpers\PK;
use Auth;

class BathController extends Controller
{

    protected $pk;

    public function __construct()
    {
        $this->pk = new PK;
    }

    public function ping()
    {
        
        return response()->json($this->pk->ping());

    }

    public function getNextNumber()
    {
        if($this->ping()) {
            $max = Bath::max('number') ?? Auth::user()->account->palette_number_prefix . '-000';
            return ++$max;
        } else {
            return "Pas ok";
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Account $account)
    {
        return response()->json($account->baths);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bath = Bath::create([
            'account_id' => $this->pk->account()->id,
            'number' => $this->getNextNumber(),
        ]);

        $bath->palettes->attach($request->palette_ids);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bath  $bath
     * @return \Illuminate\Http\Response
     */
    public function show(Bath $bath)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bath  $bath
     * @return \Illuminate\Http\Response
     */
    public function edit(Bath $bath)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bath  $bath
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bath $bath)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bath  $bath
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bath $bath)
    {
        //
    }
}
