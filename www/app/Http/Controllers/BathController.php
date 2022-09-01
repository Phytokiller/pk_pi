<?php

namespace App\Http\Controllers;

use App\Models\Bath;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BathController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->pk->currentAccount()->baths()
                                    ->whereHas('measures')
                                    ->whereHas('palettes')
                                    ->with('measures', 'palettes')
                                    ->get()
                                );
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

        $counter = ++$this->pk->currentAccount()->bath_counter;

        $bath = Bath::create([
            'account_id' => $this->pk->currentAccount()->id,
            'user_id' => $this->pk->currentUser()->id,
            'number' => $this->pk->nextBathNumber(),
            'counter' => $counter,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $bath->palettes()->attach($ids);

        // Incrementing bath counter
        $this->pk->currentAccount()->update([
            'bath_counter' => $counter,
        ]);

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        return Bath::upsert($request->baths,
            ['id'], ['user_id', 'number', 'finished_at', 'created_at', 'updated_at', 'deleted_at']
        );
    }

    public function show()
    {
        return Inertia::render('Bath');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bath  $bath
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
