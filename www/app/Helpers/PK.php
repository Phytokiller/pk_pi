<?php

namespace App\Helpers;

use App\Models\Setting;
use App\Models\Account;
use App\Models\User;

class PK {


	protected $apiUrl;

    public $settings;

	public function __construct()
	{
        $this->settings = Setting::find(1);
        $this->apiUrl = env('PK_API_URL', 'manager.phytokiller.com');
	}


    public function ping()
    {
        if ( $connected = @fsockopen($this->apiUrl, 80) ) {
            fclose($connected);
            return true;
        }

        return false;

    }

    public function currentAccount()
    {
        if($this->settings->account_id) {

            return Account::find($this->settings->account_id);

        } else {

            $account = Account::first();

            if($account) {
                $this->settings->account_id = $account->id;
                $this->settings->user_id = $account->users()->first()->id;
                $this->settings->update();
                return $account;
            } else {
                abort(403, "La station n'a pas de compte enregistré. Vous devez synchroniser votre compte depuis le manager.");
            }
        }
    }

    public function currentUser()
    {
        if($this->settings->user_id) {

            return User::find($this->settings->user_id);

        } else {

            $user = $this->currentAccount()->users()->first();
            $this->settings->user_id = $user->id;
            $this->settings->update();

            dd($user);

            return $user;

        }
    }


}