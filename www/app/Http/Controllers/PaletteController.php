<?php

namespace App\Http\Controllers;

use App\Models\Palette;
use Illuminate\Http\Request;

class PaletteController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        return Palette::upsert($request->palettes, 
            ['id'], ['account_id', 'number', 'created_at', 'updated_at', 'deleted_at']
        );

    }

    public function start()
    {
        return $this->pk->currentAccount()
                        ->palettes()
                        ->whereDoesntHave('bath')
                        ->orderBy('id')
                        ->first();
    }

    public function next(Palette $palette)
    {
        return $this->pk->currentAccount()->palettes()->where('id', '>', $palette->id)->orderBy('id')->first();
    }

    public function prev(Palette $palette)
    {
        return $this->pk->currentAccount()->palettes()->where('id', '<', $palette->id)->orderBy('id', 'desc')->first();
    }

}
