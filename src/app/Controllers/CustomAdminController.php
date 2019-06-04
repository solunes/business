<?php

namespace Solunes\Business\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Asset;

class CustomAdminController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->middleware('auth');
	  $this->middleware('permission:dashboard');
	  $this->prev = $url->previous();
	  $this->module = 'admin';
	}

	public function getIndex() {
		$user = auth()->user();
		//$array['tasks'] = $user->active_business_tasks;
		$array['tasks'] = \Solunes\Business\App\BusinessTask::limit(2)->get();
		$array['active_issues_businesss'] = \Solunes\Business\App\Business::has('active_business_issues')->with('active_business_issues')->get();
      	return view('business::list.dashboard', $array);
	}

    public function searchProduct($id = NULL) {
    	$array = ['i'=>NULL, 'dt'=>'create'];
        $categories = \Solunes\Product\App\Category::has('product_bridges')->with('product_bridges')->orderBy('name', 'ASC')->get();
        $product_options = [''=>'-'];
        foreach($categories as $category){
            foreach($category->products as $product){
            	$name = $product->name;
            	if(config('business.product_barcode')){
            		$name .= ' ('.$product->barcode.')';
            	}
                $product_options[$category->name][$product->id] = $name;
            }
        }
		$array['products'] = $product_options;
    	if($id){
    		$array['product'] = \Solunes\Business\App\ProductBridge::find($id);
    	} else {
    		$array ['product'] = NULL;
    	}
      	return view('business::item.search-product', $array);
	}

    public function generateBarcodesPdf() {
    	$products = \Solunes\Product\App\Product::where('printed', 0)->get();
    	$array = [];
    	foreach($products as $product){
    		$code = \Asset::generate_barcode_image($product->barcode);
    		$array[] = ['image'=>'<img src="data:image/png;base64,'.$code.'" />', 'name' => $product->name];
    		$product->printed = 1;
    		$product->save();
    	}
        return \PDF::loadView('business::pdf.product-barcodes', ['products'=>$array])->setPaper('letter')->setOption('margin-top', 12)->setOption('margin-left', 3)->setOption('margin-right', 0)->setOption('margin-bottom', 0)->stream('bulk_barcode.pdf');
	}

	public function getCheckProduct($id, $currency_id = 1) {
        $item = \Solunes\Business\App\ProductBridge::find($id);
        $currency = \Solunes\Business\App\Currency::find($currency_id);
		if(config('solunes.inventory')){
			$quantity = $item->total_stock;
		} else {
			$quantity = 10000;
		}
        $price = $item->price;
        if($item->currency_id!=$currency->id){
            $price = $price/$currency->main_exchange;
        }
      	return ['name'=>$item->name, 'price'=>$price, 'no_invoice_price'=>$price, 'currency'=>$currency->name, 'quantity'=>$quantity];
	}

}