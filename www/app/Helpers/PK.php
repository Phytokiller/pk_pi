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
                return abort(403);
            }
        }
    }

    public function currentUser()
    {
        if($this->settings->user_id) {

            return User::find($this->settings->user_id);

        } else {

            $user = $this->currentAccount()->users()->first();
            
            if($user) {
                $this->settings->user_id = $user->id;
                $this->settings->update();
            }
             
            return $user;

        }
    }

    // Get the next bath number
    public function nextBathNumber()
    {
        $latest = $this->currentAccount()->baths()->withTrashed()->orderBy('created_at', 'desc')->select('number')->first();

        if($latest) {
            $array = explode('-', $latest->number);
            $number = end($array);
            array_shift($array);
            array_unshift($array, $this->currentAccount()->bath_number_prefix);
            array_pop($array);
            array_push($array, ++$number);
            return implode('-', $array);
        } else {
            return $this->currentAccount()->bath_number_prefix . '-001';
        }

    }

}