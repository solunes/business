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
        if(config('business.pricing_rules')){
            \Solunes\Business\App\PricingRule::truncate();
        }
        \Solunes\Business\App\ProductBridgeTranslation::truncate();
        \Solunes\Business\App\ProductBridge::truncate();
        if(config('business.categories')){
            \Solunes\Business\App\CategoryTranslation::truncate();
            \Solunes\Business\App\Category::truncate();
        }
        if(config('business.contacts')){
            \Solunes\Business\App\Contact::truncate();
        }
        if(config('business.companies')){
            \Solunes\Business\App\Company::truncate();
        }
        \Solunes\Business\App\Agency::truncate();
        \Solunes\Business\App\CurrencyTranslation::truncate();
        \Solunes\Business\App\Currency::truncate();
        \Solunes\Business\App\CityTranslation::truncate();
        \Solunes\Business\App\City::truncate();
        \Solunes\Business\App\RegionTranslation::truncate();
        \Solunes\Business\App\Region::truncate();
        if(config('business.countries')){
            \Solunes\Business\App\Country::truncate();
        }
        if(config('business.holidays')||config('solunes.staff')){
            \Solunes\Business\App\LaborDay::truncate();
        }
        if(config('business.holidays')||config('solunes.staff')){
            \Solunes\Business\App\Holiday::truncate();
        }
    }
}