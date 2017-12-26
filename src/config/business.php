<?php

return [

	// GENERAL
	'after_seed' => true,

	// INTEGRATIONS
	'gitlab' => false,
	'gitlab_api_key' => NULL,
	'hubspot' => false,
	'hubspot_api_key' => NULL,

	// CUSTOM FORMS
    'item_get_after_vars' => ['purchase','product'], // array de nodos: 'node'
    'item_child_after_vars' => ['product'],
    'item_remove_scripts' => ['purchase'=>['leave-form']],
    'item_add_script' => ['purchase'=>['barcode-product'], 'product'=>['product']],

];