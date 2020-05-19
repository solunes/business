<?php

return [

	// GENERAL
	'after_seed' => true,
	'seed_currencies' => true,
	'countries' => false,
	'holidays' => false,
	'labor_days' => false,
	'seed_countries' => false,
	'seed_regions' => true,
	'seed_bolivia' => true,
	'seed_agencies' => true,
	'product_images' => true,
	'main_exchange' => 6.96,
	'product_barcode' => false,
	'products_page' => 'tienda',
	'product_page' => 'producto',
	'product_slug' => true,
	'product_images' => false,
	'product_variations' => false,
	'second_product_variations' => false,
	'third_product_variations' => false,
	'online_store_agency_id' => 1,
	'companies' => false,
	'contacts' => false,
	'deals' => false,
	'channels' => false,
	'pricing_rules' => false,
	'product_bridge_category' => true,
	'categories' => true,
	'category_image' => false,
	'agencies' => true,
	'variation_agencies' => false,
	'agency_payment_methods' => false,
	'agency_shippings' => false,

	// INTEGRATIONS
	'ipapi_key' => '8b587af1fe91d0a3f3ac3d9aaaf69cc5',
	'gitlab' => false,
	'gitlab_api_key' => env('GITLAB_API_KEY'),
	'hubspot' => false,
	'hubspot_api_key' => env('HUBSPOT_API_KEY'),

	// CUSTOM FORMS
    'item_get_after_vars' => ['purchase','product'], // array de nodos: 'node'
    'item_child_after_vars' => ['product'],
    'item_remove_scripts' => ['purchase'=>['leave-form']],
    'item_add_script' => ['purchase'=>['barcode-product'], 'product'=>['product']],

];