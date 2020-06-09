<?php

namespace Solunes\Business\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;

class MasterSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Módulo General de Empresa ERP
        if(config('business.holidays')||config('solunes.staff')){
            $node_holiday = \Solunes\Master\App\Node::create(['name'=>'holiday', 'location'=>'business', 'folder'=>'parameters']);
        }
        if(config('business.labor_days')||config('solunes.staff')){
            $node_labor_day = \Solunes\Master\App\Node::create(['name'=>'labor-day', 'location'=>'business', 'folder'=>'parameters']);
        }
        if(config('business.countries')){
            $node_countries = \Solunes\Master\App\Node::create(['name'=>'country', 'table_name'=>'countries', 'location'=>'business', 'folder'=>'parameters']);
        }
        $node_region = \Solunes\Master\App\Node::create(['name'=>'region', 'location'=>'business', 'folder'=>'parameters']);
        $node_city = \Solunes\Master\App\Node::create(['name'=>'city', 'table_name'=>'cities', 'location'=>'business', 'folder'=>'parameters']);
        $node_currency = \Solunes\Master\App\Node::create(['name'=>'currency', 'table_name'=>'currencies', 'location'=>'business', 'folder'=>'parameters']);
        $node_agency = \Solunes\Master\App\Node::create(['name'=>'agency', 'table_name'=>'agencies', 'multilevel'=>true, 'location'=>'business', 'folder'=>'parameters']);
        if(config('business.agency_payment_methods')){
            $node_agency_payment_method = \Solunes\Master\App\Node::create(['name'=>'agency-payment-method', 'table_name'=>'agency_payment_method', 'location'=>'business', 'type'=>'field', 'parent_id'=>$node_agency->id, 'model'=>'Solunes\Payments\App\PaymentMethod']);
        }
        if(config('business.agency_shippings')){
            $node_agency_shipping = \Solunes\Master\App\Node::create(['name'=>'agency-shipping', 'table_name'=>'agency_shipping', 'location'=>'business', 'type'=>'field', 'parent_id'=>$node_agency->id, 'model'=>'Solunes\Sales\App\Shipping']);
        }
        if(config('business.companies')){
            $node_company = \Solunes\Master\App\Node::create(['name'=>'company', 'table_name'=>'companies', 'location'=>'business', 'folder'=>'business']);
        }
        if(config('business.contacts')){
            $node_contact = \Solunes\Master\App\Node::create(['name'=>'contact', 'location'=>'business', 'folder'=>'business']);
        }
        if(config('business.deals')){
            $node_deal = \Solunes\Master\App\Node::create(['name'=>'deal', 'location'=>'business', 'folder'=>'business']);
            if(config('business.companies')){
                $node_deal_company = \Solunes\Master\App\Node::create(['name'=>'deal-company', 'location'=>'business', 'type'=>'field', 'parent_id'=>$node_deal->id, 'model'=>'Solunes\Business\App\Company']);
            }
            if(config('business.contacts')){
                $node_deal_contact = \Solunes\Master\App\Node::create(['name'=>'deal-contact', 'location'=>'business', 'type'=>'field', 'parent_id'=>$node_deal->id, 'model'=>'Solunes\Business\App\Contact']);
            }
        }
        if(config('business.categories')){
            $node_category = \Solunes\Master\App\Node::create(['name'=>'category', 'table_name'=>'categories', 'model'=>'\Solunes\Business\App\Category', 'multilevel'=>true, 'location'=>'business', 'folder'=>'products']);
        }
        if(config('business.brands')){
            $node_brand = \Solunes\Master\App\Node::create(['name'=>'brand', 'table_name'=>'brands', 'location'=>'business', 'folder'=>'parameters']);
        }
        if(config('business.channels')){
            $node_channel = \Solunes\Master\App\Node::create(['name'=>'channel', 'table_name'=>'channels', 'location'=>'business', 'folder'=>'parameters']);
        }
        $node_product_bridge = \Solunes\Master\App\Node::create(['name'=>'product-bridge', 'location'=>'business']);
        if(config('business.product_variations')){
            $node_variation = \Solunes\Master\App\Node::create(['name'=>'variation', 'location'=>'business', 'folder'=>'products']);
            \Solunes\Master\App\Node::create(['name'=>'variation-option', 'type'=>'child', 'location'=>'business', 'folder'=>'products', 'parent_id'=>$node_variation->id]);
            if(config('business.categories')){
                \Solunes\Master\App\Node::create(['name'=>'category-variation', 'table_name'=>'category_variation', 'location'=>'business', 'translation'=>1, 'model'=>'\Solunes\Business\App\Variation', 'type'=>'field', 'parent_id'=>$node_category->id]);
            }
            if(config('business.channels')){
                \Solunes\Master\App\Node::create(['name'=>'product-bridge-channel', 'table_name'=>'product_bridge_channel', 'location'=>'business', 'translation'=>0, 'model'=>'\Solunes\Business\App\Channel', 'type'=>'field', 'parent_id'=>$node_product_bridge->id]);
                $channel = \Solunes\Business\App\Channel::create(['name'=>'Tienda','type'=>'public']);
            }
            $image_folder = \Solunes\Master\App\ImageFolder::create(['site_id'=>1,'name'=>'variation-option-image','extension'=>'jpg']);
            \Solunes\Master\App\ImageSize::create(['parent_id'=>$image_folder->id,'code'=>'normal','type'=>'resize','width'=>800,'height'=>NULL]);
            \Solunes\Master\App\ImageSize::create(['parent_id'=>$image_folder->id,'code'=>'thumb','type'=>'fit','width'=>300,'height'=>300]);
            \Solunes\Master\App\ImageSize::create(['parent_id'=>$image_folder->id,'code'=>'tiny','type'=>'fit','width'=>50,'height'=>50]);

            \Solunes\Master\App\Node::create(['name'=>'product-bridge-variation', 'table_name'=>'product_bridge_variation', 'location'=>'business', 'translation'=>1, 'model'=>'\Solunes\Business\App\Variation', 'type'=>'field', 'parent_id'=>$node_product_bridge->id]);
            \Solunes\Master\App\Node::create(['name'=>'product-bridge-variation-option', 'table_name'=>'product_bridge_variation_option', 'location'=>'business',  'translation'=>1, 'model'=>'\Solunes\Business\App\VariationOption', 'type'=>'field', 'parent_id'=>$node_product_bridge->id]);
        }
        if(config('business.pricing_rules')){
            $node_pricing_rule = \Solunes\Master\App\Node::create(['name'=>'pricing-rule', 'location'=>'business', 'folder'=>'parameters']);
        }

        $image_folder = \Solunes\Master\App\ImageFolder::create(['site_id'=>1,'name'=>'product-bridge-image','extension'=>'jpg']);
        \Solunes\Master\App\ImageSize::create(['parent_id'=>$image_folder->id,'code'=>'normal','type'=>'resize','width'=>800,'height'=>NULL]);
        \Solunes\Master\App\ImageSize::create(['parent_id'=>$image_folder->id,'code'=>'thumb','type'=>'fit','width'=>370,'height'=>370]);
        \Solunes\Master\App\ImageSize::create(['parent_id'=>$image_folder->id,'code'=>'cart','type'=>'fit','width'=>80,'height'=>100]);
        \Solunes\Master\App\ImageSize::create(['parent_id'=>$image_folder->id,'code'=>'detail','type'=>'fit','width'=>570,'height'=>570]);
        \Solunes\Master\App\ImageSize::create(['parent_id'=>$image_folder->id,'code'=>'subdetail','type'=>'fit','width'=>120,'height'=>120]);

        if(config('business.seed_currencies')){
            $currency_1 = \Solunes\Business\App\Currency::create(['name'=>'Bs.','type'=>'main','plural'=>'bolivianos','code'=>'BOB','main_exchange'=>1]);
            $currency_2 = \Solunes\Business\App\Currency::create(['name'=>'US$','type'=>'secondary','plural'=>'dolares','code'=>'USD','main_exchange'=>config('business.main_exchange')]);
        }

        if(config('business.seed_regions')){
            if(config('business.countries')){
                if(config('business.seed_countries')){
                    $countries = file_get_contents(base_path(config('solunes.solunes_path').'/business/src/json/countries-'.config('solunes.main_lang').'.json'));
                    foreach(json_decode($countries) as $country_code => $country){
                        $languages = '';
                        foreach($country->languages as $key => $language){
                            if($key>0){
                                $languages .= ',';
                            }
                            $languages .= $language;
                        }
                        $created_country = \Solunes\Business\App\Country::create(['name'=>$country->name,'code'=>$country_code,'continent'=>$country->continent,'phone'=>$country->phone,'currency_code'=>$country->currency,'languages'=>$languages]);
                        if($country_code=='BO'){
                            $country_bolivia = $created_country;
                        }
                    }
                } else {
                    $country_bolivia = \Solunes\Business\App\Country::create(['name'=>'Bolivia','code'=>'BO','continent'=>'SA','phone'=>'591','currency_code'=>'BO','languages'=>'es,ay,qu']);
                }
                $other_country = \Solunes\Business\App\Country::create(['name'=>'No Reconozido','code'=>'other','continent'=>'other']);
                if(config('business.seed_bolivia')){
                    $region_1 = \Solunes\Business\App\Region::create(['country_id'=>$country_bolivia->id,'name'=>'La Paz','code'=>'L']);
                    $region_2 = \Solunes\Business\App\Region::create(['country_id'=>$country_bolivia->id,'name'=>'Santa Cruz','code'=>'S']);
                    $region_3 = \Solunes\Business\App\Region::create(['country_id'=>$country_bolivia->id,'name'=>'Cochabamba','code'=>'C']);
                    $region_4 = \Solunes\Business\App\Region::create(['country_id'=>$country_bolivia->id,'name'=>'Chuquisaca','code'=>'H']);
                    $region_5 = \Solunes\Business\App\Region::create(['country_id'=>$country_bolivia->id,'name'=>'Oruro','code'=>'O']);
                    $region_6 = \Solunes\Business\App\Region::create(['country_id'=>$country_bolivia->id,'name'=>'Tarija','code'=>'T']);
                    $region_7 = \Solunes\Business\App\Region::create(['country_id'=>$country_bolivia->id,'name'=>'Potosi','code'=>'P']);
                    $region_8 = \Solunes\Business\App\Region::create(['country_id'=>$country_bolivia->id,'name'=>'Beni','code'=>'B']);
                    $region_9 = \Solunes\Business\App\Region::create(['country_id'=>$country_bolivia->id,'name'=>'Pando','code'=>'N']);
                }
            } else {
                if(config('business.seed_bolivia')){
                    $region_1 = \Solunes\Business\App\Region::create(['name'=>'La Paz','code'=>'L']);
                    $region_2 = \Solunes\Business\App\Region::create(['name'=>'Santa Cruz','code'=>'S']);
                    $region_3 = \Solunes\Business\App\Region::create(['name'=>'Cochabamba','code'=>'C']);
                    $region_4 = \Solunes\Business\App\Region::create(['name'=>'Chuquisaca','code'=>'H']);
                    $region_5 = \Solunes\Business\App\Region::create(['name'=>'Oruro','code'=>'O']);
                    $region_6 = \Solunes\Business\App\Region::create(['name'=>'Tarija','code'=>'T']);
                    $region_7 = \Solunes\Business\App\Region::create(['name'=>'Potosi','code'=>'P']);
                    $region_8 = \Solunes\Business\App\Region::create(['name'=>'Beni','code'=>'B']);
                    $region_9 = \Solunes\Business\App\Region::create(['name'=>'Pando','code'=>'N']);
                }
            }
            $region_0 = \Solunes\Business\App\Region::create(['name'=>'Otro','code'=>'other']);
            if(config('business.seed_bolivia')){
                $city_1_1 = \Solunes\Business\App\City::create(['name'=>'La Paz', 'region_id'=>$region_1->id, 'active'=>1, 'latitude'=>'-16.4955455', 'longitude'=>'-68.1336229']);
                $city_1_2 = \Solunes\Business\App\City::create(['name'=>'El Alto', 'region_id'=>$region_1->id, 'active'=>1, 'latitude'=>'-16.4955593', 'longitude'=>'-68.1934964']);
                $city_2_1 = \Solunes\Business\App\City::create(['name'=>'Santa Cruz de la Sierra', 'region_id'=>$region_2->id, 'active'=>1, 'latitude'=>'-17.7834936', 'longitude'=>'-63.1820853']);
                $city_2_2 = \Solunes\Business\App\City::create(['name'=>'Montero', 'region_id'=>$region_1->id, 'active'=>1, 'latitude'=>'-17.3420202', 'longitude'=>'-63.2557687']);
                $city_3_1 = \Solunes\Business\App\City::create(['name'=>'Cochabamba', 'region_id'=>$region_3->id, 'active'=>1, 'latitude'=>'-17.3935853', 'longitude'=>'-66.1569588']);
                $city_3_2 = \Solunes\Business\App\City::create(['name'=>'Quillacollo', 'region_id'=>$region_3->id, 'active'=>1, 'latitude'=>'-17.3974006', 'longitude'=>'-66.2816828']);
                $city_4_1 = \Solunes\Business\App\City::create(['name'=>'Sucre', 'region_id'=>$region_4->id, 'active'=>1, 'latitude'=>'-19.0477251', 'longitude'=>'-65.2594306']);
                $city_5_1 = \Solunes\Business\App\City::create(['name'=>'Oruro', 'region_id'=>$region_5->id, 'active'=>1, 'latitude'=>'-17.9698192', 'longitude'=>'-67.1148635']);
                $city_6_1 = \Solunes\Business\App\City::create(['name'=>'Tarija', 'region_id'=>$region_6->id, 'active'=>1, 'latitude'=>'-21.5340695', 'longitude'=>'-64.7344181']);
                $city_7_1 = \Solunes\Business\App\City::create(['name'=>'Potosi', 'region_id'=>$region_7->id, 'active'=>1, 'latitude'=>'-19.5893051', 'longitude'=>'-65.7534808']);
                $city_8_1 = \Solunes\Business\App\City::create(['name'=>'Trinidad', 'region_id'=>$region_8->id, 'active'=>1, 'latitude'=>'-14.8346068', 'longitude'=>'-64.9042477']);
                $city_9_1 = \Solunes\Business\App\City::create(['name'=>'Cobija', 'region_id'=>$region_9->id, 'active'=>1, 'latitude'=>'-11.0182955', 'longitude'=>'-68.7537452']);
                $city_10_1 = \Solunes\Business\App\City::create(['name'=>'Otra Ciudad', 'active'=>1, 'other_city'=>1]);
                if(config('business.seed_agencies')){
                    $place_1 = \Solunes\Business\App\Agency::create(['name'=>'Central','type'=>'central','address'=>'Dirección de muestra', 'region_id'=>$region_1->id, 'city_id'=>$city_1_1->id]);
                }
            } else {
                if(config('business.seed_agencies')){
                    $place_1 = \Solunes\Business\App\Agency::create(['level'=>1,'name'=>'Central','type'=>'central','address'=>'Dirección de muestra']);
                }
            }
            //\Business::testIpData();
        }

        // Usuarios
        $admin = \Solunes\Master\App\Role::where('name', 'admin')->first();
        $member = \Solunes\Master\App\Role::where('name', 'member')->first();
        $parameters_perm = \Solunes\Master\App\Permission::create(['name'=>'parameters', 'display_name'=>'Parámetros']);
        $business_perm = \Solunes\Master\App\Permission::create(['name'=>'business', 'display_name'=>'Negocio']);
        $admin->permission_role()->attach([$parameters_perm->id, $business_perm->id]);

    }
}