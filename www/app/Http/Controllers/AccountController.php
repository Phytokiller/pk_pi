<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use App\Models\Bath;

use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    /**
     * Update the resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function synchronize(Request $request)
    {

        // Update account data from manager
        $account = Account::updateOrCreate([
            'id' => $request->account['id'],
        ], [
            'name' => $request->account['name'],
            'bath_duration' => $request->account['bath_duration'],
            'bath_temperature' => $request->account['bath_temperature'],
            'bath_number_prefix' => $request->account['bath_number_prefix'],
            'updated_at' => Carbon::now(),
        ]);

        // Update Users from manager
        $ids = [];

        foreach($request->account['users'] as $user) {

            User::updateOrCreate([
                'id' => $user['id'],
            ], [
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
            ]);

            $ids[] = $user['id'];

        }

        $account->users()->sync($ids);

        $this->settings->account_id = $account->id;
        $this->settings->user_id = $account->users()->first()->id;
        $this->settings->update();

        // Update Baths from manager (deleted, etc)
        Bath::upsert($request->baths, 
            ['id'], ['user_id', 'number', 'finished_at', 'created_at', 'updated_at', 'deleted_at']
        );

        // Return the local baths
        return response()->json($this->pk->currentAccount()->baths()
                                    ->whereHas('measures')
                                    ->whereHas('palettes')
                                    ->with('measures', 'palettes')
                                    ->get()
                                );
    }

    /**
     * Update the resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $account = Account::updateOrCreate([
            'id' => $request->id,
        ], [
            'name' => $request->name,
            'bath_duration' => $request->bath_duration,
            'bath_temperature' => $request->bath_temperature,
            'bath_number_prefix' => $request->bath_number_prefix,
            'updated_at' => Carbon::now(),
        ]);

        $ids = [];

        foreach($request->users as $user) {

            User::updateOrCreate([
                'id' => $user['id'],
            ], [
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
            ]);

            $ids[] = $user['id'];

        }

        $account->users()->sync($ids);

        $this->settings->account_id = $account->id;
        $this->settings->user_id = $account->users()->first()->id;
        $this->settings->update();


        return $account;
    }

    public function switch($id)
    {

        $account = Account::find($id);
        $this->settings->account_id = $account->id;
        $this->settings->user_id = $account->users()->first()->id;
        $this->settings->update();
        return redirect()->back();

    }
}
