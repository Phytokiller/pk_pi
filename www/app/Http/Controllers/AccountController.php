<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bath;
use App\Models\Palette;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Update the resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function synchronize(Request $request)
    {
        if(Account::where('id', $request->account['id'])->exists()) {
            $account = Account::find($request->account['id']);
            $counter = ($request->account['bath_counter'] >= $account?->bath_counter) 
                ? $request->account['bath_counter'] 
                : $account->bath_counter;
        } else {
            $counter = $request->account['bath_counter'];
        }

        // Update account data from manager
        $account = Account::updateOrCreate([
            'id' => $request->account['id'],
        ], [
            'name' => $request->account['name'],
            'bath_duration' => $request->account['bath_duration'],
            'bath_temperature' => $request->account['bath_temperature'],
            'bath_number_prefix' => $request->account['bath_number_prefix'],
            'default_palettes_selected' => $request->account['default_palettes_selected'],
            'bath_counter' => $counter,
            'updated_at' => Carbon::now(),
        ]);

        // Update Users from manager
        $ids = [];

        foreach ($request->account['users'] as $user) {
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

        // Upsert palettes from manager
        Palette::upsert($request->palettes,
            ['id'], ['account_id', 'number', 'created_at', 'updated_at', 'deleted_at']
        );

        // Update Baths and Bath Palettes from manager (deleted, etc)
        // Since we can modify palettes on baths with the manage, we should sync bath_palette too
        if ($request->baths) {
            $baths = collect($request->baths);

            $baths->each(function ($item, $key) {
                $bath = Bath::updateOrCreate([
                    'id' => $item['id'],
                    'account_id' => $item['account_id'],
                ], [
                    'counter' => $item['counter'],
                    'number' => $item['number'],
                    'user_id' => $item['user_id'],
                    'finished_at' => $item['finished_at'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at'],
                    'deleted_at' => $item['deleted_at'],
                ]);

                // BathPalette
                if (isset($item['palettes'])) {
                    $palettes = collect($item['palettes'])->pluck('id');
                    $bath->palettes()->sync($palettes);
                }
            });
        }


        // RETURN THE LOCAL BATHS TO THE MANAGER
        return response()->json([
            'counter' => $this->pk->currentAccount()->bath_counter,
            'items' => $this->pk->currentAccount()->baths()
                                    ->where('created_at', '>', Carbon::now()->submonths(2))
                                    ->whereHas('measures')
                                    ->whereHas('palettes')
                                    ->with('measures', 'palettes')
                                    ->get(),
        ]);
    }

    /**
     * Update the resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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

        foreach ($request->users as $user) {
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
