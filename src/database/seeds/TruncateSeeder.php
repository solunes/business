<?php

namespace Solunes\Business\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;

class TruncateSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Solunes\Business\App\ProductBridgeTranslation::truncate();
        \Solunes\Business\App\ProductBridge::truncate();
        \Solunes\Business\App\Contact::truncate();
        \Solunes\Business\App\Company::truncate();
        \Solunes\Business\App\Agency::truncate();
        \Solunes\Business\App\CurrencyTranslation::truncate();
        \Solunes\Business\App\Currency::truncate();
        \Solunes\Business\App\CityTranslation::truncate();
        \Solunes\Business\App\City::truncate();
        \Solunes\Business\App\RegionTranslation::truncate();
        \Solunes\Business\App\Region::truncate();
    }
}