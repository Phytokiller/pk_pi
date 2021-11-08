<?php

namespace App\Http\Controllers;

use App\Models\Bath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Carbon\Carbon;
use Inertia\Inertia;

class BathController extends Controller
{

    public function ping()
    {
        
        return response()->json($this->pk->ping());

    }

    public function getNextNumber()
    {
        if($this->ping()) {
            $max = Bath::max('number') ?? $this->pk->currentAccount()->palette_number_prefix . '-000';
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
    public function index()
    {
        return response()->json($this->pk->currentAccount()->baths()->with('measures')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $ids = collect($request->palettes)->pluck('id');

        $bath = Bath::create([
            'account_id' => $this->pk->currentAccount()->id,
            'user_id' => $this->pk->currentUser()->id,
            'number' => $this->getNextNumber(),
        ]);

        $bath->palettes()->attach($ids);

        return Inertia::render('Bath', [
            'bath' => $bath,
            'palettes' => $bath->palettes,
        ]);

    }

    public function measure(Request $request, Bath $bath)
    {
        $bath->measures()->create([
            'sensor_1' => $request->sensor_1,
            'sensor_2' => $request->sensor_2,
            'door' => $request->door,
            'boiler' => $request->boiler,
            'elapsed_time' => $request->elapsed_time,
        ]);
    }


    public function show()
    {
        return Inertia::render('Bath');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bath $bath
     * @return Inertia\Inertia
     */
    public function finish(Request $request, Bath $bath)
    {
        $bath->finished_at = Carbon::now();
        $bath->update();

        return redirect()->route('welcome');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bath  $bath
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bath $bath)
    {
        $bath->delete();
        return redirect()->route('welcome');

    }
}
