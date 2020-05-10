<?php 

namespace Solunes\Business\App\Helpers;

class Business {

    public static function getAgencyByToken($agency_token) {
        $agency = NULL;
        if($agency_token){
            $agency = \Solunes\Business\App\Agency::where('token', $agency_token)->first();
        }
        return $agency;
    }

    public static function changeNodeActionFields($node_array) {
        foreach($node_array as $node_name => $node_detail){
            $node = \Solunes\Master\App\Node::where('name', $node_name)->first();
            foreach($node_detail as $extra_type => $extra_value) {
                $node_extra = new \Solunes\Master\App\NodeExtra;
                $node_extra->parent_id = $node->id;
                $node_extra->type = $extra_type;
                $node_extra->value_array = json_encode($extra_value);
                $node_extra->save();
            }
        }
        return true;
    }

    public static function createBulkAdminMenu($menu_array) {
        foreach($menu_array as $new_menu){
            $menu = new \Solunes\Master\App\Menu;
            if(isset($new_menu['parent_id'])){
                $menu->parent_id = $new_menu['parent_id'];
            }
            $menu->level = $new_menu['level'];
            $menu->menu_type = 'admin';
            $menu->icon = $new_menu['icon'];
            $menu->name = $new_menu['name'];
            $menu->link = $new_menu['link'];
            if(isset($new_menu['order'])){
                $menu->order = $new_menu['order'];
            }
            $menu->save();
        }
        return true;
    }

    public static function calculate_currency($item_amount, $main_currency, $item_currency, $exchange = NULL) {
        if($main_currency->id!=$item_currency->id){
            if(!$exchange){
                $exchange = $item_currency->main_exchange;
            }
            if($main_currency->type!='main'){
                $item_amount = $item_amount / $exchange;
            } else {
                $item_amount = $item_amount * $exchange;
            }
        }
        return round($item_amount, 2);
    }

    public static function check_report_header($model, $places = [], $extra = NULL) {
        $date = date('Y-m-d');
        if(request()->has('currency_id')){
          $currency_id = request()->input('currency_id');
        } else {
          $currency_id = 1;
        }
        $currency = \Solunes\Store\App\Currency::find($currency_id);
        $currencies = \Solunes\Store\App\Currency::where('in_accounts', 1)->get()->lists('real_name', 'id');
        $array = ['i'=>NULL, 'dt'=>'create', 'currencies'=>$currencies, 'currency'=>$currency, 'datepicker_initial'=> $date, 'datepicker_end'=>$date];
        $path = request()->segment(2);
        if(request()->segment(3)){
            $path .= '/'.request()->segment(3);
        }
        $array['path'] = $path;
        if($date_item_i = $model->orderBy('created_at', 'ASC')->first()){
          $array['datepicker_initial'] = $date_item_i->created_at;
        }
        if($date_item_e = $model->orderBy('created_at', 'DESC')->first()){
          $array['datepicker_end'] = $date_item_e->created_at;
        }
        if(request()->has('period')&&request()->input('period')=='custom'){
            if(request()->has('initial_date')){
              $initial_date = request()->input('initial_date');
            } else {
              $initial_date = $date_item_i->created_at->format('Y-m-d');
            }
            if(request()->has('end_date')){
              $end_date = request()->input('end_date');
            } else {
              $end_date = $date_item_e->created_at->format('Y-m-d');
            }
        } else {
            if(!request()->has('period')||request()->input('period')=='month'){
                $i_date = 'first day of this month';
                $e_date = 'last day of this month';
            } else if(request()->input('period')=='year') {
                $i_date = date('Y-01-01');
                $e_date = date('Y-12-31');
            } else if(request()->input('period')=='day') {
                $i_date = $date;
                $e_date = $date;
            } else if(request()->input('period')=='week') {
                $i_date = 'monday this week';
                $e_date = 'sunday this week';
            }
            $initial_date = date("Y-m-d", strtotime($i_date));
            $end_date = date("Y-m-d", strtotime($e_date));
        }
        $array['initial_date'] = $initial_date;
        $array['end_date'] = $end_date;
        $array['i_date'] = $initial_date.' 00:00:00';
        $array['e_date'] = $end_date.' 23:59:59';
        $array['show_place'] = false;
        $array['show_account_id'] = false;
        if($extra&&$extra=='account_id'){
            $array['show_account_id'] = true;
            $array['accounts'] = \Solunes\Store\App\Account::lists('name','id');
            $array['current_account_id'] = 1;
            if(request()->has('account_id')){
                $array['current_account_id'] = request()->input('account_id');
            }
        }
        $array['places'] = ['all'=>'Consolidado']  + \Solunes\Store\App\Place::lists('name', 'id')->toArray() + $places;
        if(request()->has('place_id')){
            $array['place'] = request()->input('place_id');
        } else {
            $array['place'] = 'all';
        }
        if(isset($array['places'][$array['place']])){
            $array['place_name'] = $array['places'][$array['place']];
        }
        $array['periods'] = ['day'=>'Hoy', 'week'=>'Esta Semana', 'month'=>'Este Mes', 'year'=>'Este Año', 'custom'=>'Personalizado'];
        // URL
        $url = request()->fullUrl();
        if(strpos($url, '?') !== false){
            $url .= '&download-pdf=true';
        } else {
            $url .= '?download-pdf=true';
        }
        $array['url'] = $url;
        return $array;
    }
    
    public static function check_report_view($view, $array) {
        if(request()->has('download-pdf')){
            $array['pdf'] = true;
            $array['dt'] = 'view';
            $array['header_title'] = 'Reporte generado';
            $array['title'] = 'Reporte generado';
            $array['site'] = \Solunes\Master\App\Site::find(1);
            $pdf = \PDF::loadView($view, $array);
            $header = \View::make('pdf.header', $array);
            return $pdf->setPaper('letter')->setOption('header-html', $header->render())->stream('reporte_'.date('Y-m-d').'.pdf');
        } else {
            return view($view, $array);
        } 
    }
    
    public static function createProductBridge($product_type, $product_id, $array, $lang_array) {
        $item = new \Solunes\Business\App\ProductBridge;
        $item->product_type = $product_type;
        $item->product_id = $product_id;
        foreach($array as $name => $value){
            if($name=='image'){
                $item->$name = \Asset::upload_image($value, 'product-bridge-image');
            } else {
                $item->$name = $value;
            }
        }
        foreach($lang_array as $lang => $lang_subarray){
          foreach($lang_subarray as $key => $value){
            $item->translateOrNew($lang)->$key = $value;
          }
        }
        $item->save();
        return $item;
    }
            
    public static function getProductBridgeVariable($product_bridge, $variation_option_ids) {
        $new_product_bridge = \Solunes\Business\App\ProductBridge::where('product_type', $product_bridge->product_type)->where('product_id', $product_bridge->product_id)->first();
        if($new_product_bridge){
            $product_bridge = $new_product_bridge;
        }
        return $product_bridge;
    }
   
    public static function getProductBridgeStockItem($product_bridge, $agency_id) {
        $stock = $product_bridge->last_product_bridge_stocks()->where('agency_id', $agency_id)->first();
        if($stock){
            return $stock;
        } else {
            return NULL;
        }
    }

    public static function getProductBridgeStock($product_bridge, $agency_id) {
        $stock = $product_bridge->last_product_bridge_stocks()->where('agency_id', $agency_id)->first();
        if($stock){
            return $stock->quantity;
        } else {
            return 0;
        }
    }

   public static function getCustomerIp(){
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function getIpData($ip) {
        $key = config('business.ipapi_key');
        $url = 'http://api.ipstack.com/'.$ip.'?access_key='.$key.'&format=1'; // asmx URL of WSDL
        $headers = [];
        $client = new \GuzzleHttp\Client(['header' => $headers]);
        $response  = $client->get($url);
        $content = $response->getBody()->getContents();
        if ($response->getStatusCode() == 200) {
            return json_decode($content, true);
        } else {
            return [];
        }
    }
   
    public static function processIpData($ip) {
        $array = \Business::getIpData($ip);
        if($array&&isset($array['ip'])){
            \Log::info('IP Encontrado: '.json_encode($array));
            $region = NULL;
            if($array['region_code']==NULL){
                $region = \Solunes\Business\App\Region::where('code','other')->first();
            }
            $city = NULL;
            if($array['city']==NULL){
                $city = \Solunes\Business\App\City::where('other_city',1)->first();
            }
            if(config('business.countries')){
                if($array['country_code']){
                    if(!$country = \Solunes\Business\App\Country::where('code',$array['country_code'])->first()){
                        $languages = '';
                        if($array['location']['languages']){
                            foreach($array['location']['languages'] as $key => $language){
                                if($key>0){
                                    $languages .= ',';
                                }
                                $languages .= $language['code'];
                            }
                        }
                        $country = \Solunes\Business\App\Country::create(['name'=>$array['country_name'],'code'=>$array['country_code'],'continent'=>$array['continent_code'],'phone'=>$array['location']['calling_code'],'currency_code'=>NULL,'languages'=>$languages]);
                    }
                } else {
                    $country = \Solunes\Business\App\Country::where('code','other')->first();
                }
                if(!$region&&!$region = \Solunes\Business\App\Region::where('country_id',$country->id)->where('code',$array['region_code'])->first()){
                    $region = \Solunes\Business\App\Region::create(['country_id'=>$country->id,'name'=>$array['region_name'],'code'=>$array['region_code']]);
                }
            } else {
                if(!$region&&!$region = \Solunes\Business\App\Region::where('code',$array['region_code'])->first()){
                    $region = \Solunes\Business\App\Region::create(['name'=>$array['region_name'],'code'=>$array['region_code']]);
                }
            }
            if(!$city&&!$city = \Solunes\Business\App\City::where('region_id',$region->id)->whereTranslation('name',$array['city'])->first()){
                $city = \Solunes\Business\App\City::create(['region_id'=>$region->id,'name'=>$array['city'],'latitude'=>$array['latitude'],'longitude'=>$array['longitude']]);
            }
            if($country&&$region&&$city){
                \Log::info('IP Encontrado: CountryID '.$country->id.' - RegionID '.$region->id.' -  CityID '.$city->id);
            } else {
                \Log::info('IP Encontrado: Sin datos de region');
            }
            return ['ip'=>$array['ip'], 'country'=>$country, 'region'=>$region, 'city'=>$city];
        } else {
            \Log::info('IP NO Encontrado: '.json_encode($array));
            return ['ip'=>NULL, 'country'=>NULL, 'region'=>NULL, 'city'=>NULL];
        }
    }
     
    public static function getProductPrice($product_bridge, $quantity) {
        $price = $product_bridge->real_price;
        if(config('business.pricing_rules')){
            $range_price = \Solunes\Business\App\PricingRule::where('product','product')->where('product_bridge_id', $product_bridge->id)->where('min_quantity', '>=', $quantity)->where('max_quantity', '<=', $quantity)->first();
            if(!$range_price){
                $range_price = \Solunes\Business\App\PricingRule::where('product','category')->where('category_id', $product_bridge->category_id)->where('min_quantity', '>=', $quantity)->where('max_quantity', '<=', $quantity)->first();
                if(!$range_price){
                    $range_price = \Solunes\Business\App\PricingRule::where('product','general')->where('min_quantity', '>=', $quantity)->where('max_quantity', '<=', $quantity)->first();
                }
            }
            if($range_price){
                if($range_price->type=='normal'){
                    $price -= $range_price->value;
                } else {
                    $price = $price*$range_price->value;
                }
            }
        }
        // TODO: Custom pricing rules
        return $price;
    }

    public static function testIpData() {
        $ips = ['200.105.221.91','2.17.35.255','134.201.250.155'];
        foreach($ips as $ip){
            \Business::processIpData($ip);
        }
    }

}