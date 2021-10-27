<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use App\Models\Setting;

use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    private $settings;

    public function __construct()
    {
        $this->settings = Setting::find(1);
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
