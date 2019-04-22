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
        $node_countries = \Solunes\Master\App\Node::create(['name'=>'country', 'table_name'=>'countries', 'location'=>'business', 'folder'=>'parameters']);
        $node_region = \Solunes\Master\App\Node::create(['name'=>'region', 'location'=>'business', 'folder'=>'parameters']);
        $node_city = \Solunes\Master\App\Node::create(['name'=>'city', 'table_name'=>'cities', 'location'=>'business', 'folder'=>'parameters']);
        $node_currency = \Solunes\Master\App\Node::create(['name'=>'currency', 'table_name'=>'currencies', 'location'=>'business', 'folder'=>'parameters']);
        $node_agency = \Solunes\Master\App\Node::create(['name'=>'agency', 'table_name'=>'agencies', 'location'=>'business', 'folder'=>'parameters']);
        $node_company = \Solunes\Master\App\Node::create(['name'=>'company', 'table_name'=>'companies', 'location'=>'business', 'folder'=>'business']);
        $node_contact = \Solunes\Master\App\Node::create(['name'=>'contact', 'location'=>'business', 'folder'=>'business']);
        $node_deal = \Solunes\Master\App\Node::create(['name'=>'deal', 'location'=>'business', 'folder'=>'business']);
        $node_deal_company = \Solunes\Master\App\Node::create(['name'=>'deal-company', 'location'=>'business', 'type'=>'field', 'parent_id'=>$node_deal->id, 'model'=>'Solunes\Business\App\Company']);
        $node_deal_contact = \Solunes\Master\App\Node::create(['name'=>'deal-contact', 'location'=>'business', 'type'=>'field', 'parent_id'=>$node_deal->id, 'model'=>'Solunes\Business\App\Contact']);
        $node_product_bridge = \Solunes\Master\App\Node::create(['name'=>'product-bridge', 'location'=>'business']);
        if(config('business.product_variations')){
            $node_variation = \Solunes\Master\App\Node::create(['name'=>'variation', 'location'=>'business', 'folder'=>'products']);
            \Solunes\Master\App\Node::create(['name'=>'variation-option', 'type'=>'child', 'location'=>'business', 'folder'=>'products', 'parent_id'=>$node_variation->id]);
            \Solunes\Master\App\Node::create(['name'=>'product-bridge-variation-option', 'type'=>'child', 'location'=>'business', 'parent_id'=>$node_product_bridge->id]);
            \Solunes\Master\App\Node::create(['name'=>'product-variation', 'table_name'=>'product_variation', 'location'=>'product', 'model'=>'\Solunes\Business\App\Variation', 'type'=>'field', 'parent_id'=>$node_product_bridge->id]);
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
                $country_1 = \Solunes\Business\App\Country::create(['name'=>'Bolivia']);
            }
            $region_1 = \Solunes\Business\App\Region::create(['name'=>'La Paz']);
            $city_1_1 = \Solunes\Business\App\City::create(['name'=>'La Paz', 'region_id'=>$region_1->id, 'active'=>1]);
            $city_1_2 = \Solunes\Business\App\City::create(['name'=>'El Alto', 'region_id'=>$region_1->id, 'active'=>1]);
            $region_2 = \Solunes\Business\App\Region::create(['name'=>'Santa Cruz']);
            $city_2_1 = \Solunes\Business\App\City::create(['name'=>'Santa Cruz de la Sierra', 'region_id'=>$region_2->id, 'active'=>1]);
            $city_2_2 = \Solunes\Business\App\City::create(['name'=>'Montero', 'region_id'=>$region_1->id, 'active'=>1]);
            $region_3 = \Solunes\Business\App\Region::create(['name'=>'Cochabamba']);
            $city_3_1 = \Solunes\Business\App\City::create(['name'=>'Cochabamba', 'region_id'=>$region_3->id, 'active'=>1]);
            $city_3_2 = \Solunes\Business\App\City::create(['name'=>'Quillacollo', 'region_id'=>$region_3->id, 'active'=>1]);
            $region_4 = \Solunes\Business\App\Region::create(['name'=>'Chuquisaca']);
            $city_4_1 = \Solunes\Business\App\City::create(['name'=>'Sucre', 'region_id'=>$region_4->id, 'active'=>1]);
            $region_5 = \Solunes\Business\App\Region::create(['name'=>'Oruro']);
            $city_5_1 = \Solunes\Business\App\City::create(['name'=>'Oruro', 'region_id'=>$region_5->id, 'active'=>1]);
            $region_6 = \Solunes\Business\App\Region::create(['name'=>'Tarija']);
            $city_6_1 = \Solunes\Business\App\City::create(['name'=>'Tarija', 'region_id'=>$region_6->id, 'active'=>1]);
            $region_7 = \Solunes\Business\App\Region::create(['name'=>'Potosi']);
            $city_7_1 = \Solunes\Business\App\City::create(['name'=>'Potosi', 'region_id'=>$region_7->id, 'active'=>1]);
            $region_8 = \Solunes\Business\App\Region::create(['name'=>'Beni']);
            $city_8_1 = \Solunes\Business\App\City::create(['name'=>'Trinidad', 'region_id'=>$region_8->id, 'active'=>1]);
            $region_9 = \Solunes\Business\App\Region::create(['name'=>'Pando']);
            $city_9_1 = \Solunes\Business\App\City::create(['name'=>'Cobija', 'region_id'=>$region_9->id, 'active'=>1]);
            $region_10 = \Solunes\Business\App\Region::create(['name'=>'Otro']);
            $city_10_1 = \Solunes\Business\App\City::create(['name'=>'Otra Ciudad', 'region_id'=>$region_10->id, 'active'=>1]);
        
            if(config('business.seed_agencies')){
                $place_1 = \Solunes\Business\App\Agency::create(['name'=>'Central','type'=>'central','address'=>'Dirección de muestra', 'region_id'=>$region_1->id, 'city_id'=>$city_1_1->id]);
            }
        }

        // Usuarios
        $admin = \Solunes\Master\App\Role::where('name', 'admin')->first();
        $member = \Solunes\Master\App\Role::where('name', 'member')->first();
        $parameters_perm = \Solunes\Master\App\Permission::create(['name'=>'parameters', 'display_name'=>'Parámetros']);
        $business_perm = \Solunes\Master\App\Permission::create(['name'=>'business', 'display_name'=>'Negocio']);
        $admin->permission_role()->attach([$parameters_perm->id, $business_perm->id]);

    }
}