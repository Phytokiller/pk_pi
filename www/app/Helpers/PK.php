<?php

namespace App\Helpers;

use App\Models\Setting;
use App\Models\Account;

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
        return Account::findOrFail($this->settings->current_account_id);
    }


}