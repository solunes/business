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
        $node_region = \Solunes\Master\App\Node::create(['name'=>'region', 'location'=>'business', 'folder'=>'parameters']);
        $node_city = \Solunes\Master\App\Node::create(['name'=>'city', 'table_name'=>'cities', 'location'=>'business', 'folder'=>'parameters']);
        $node_currency = \Solunes\Master\App\Node::create(['name'=>'currency', 'table_name'=>'currencies', 'location'=>'business', 'folder'=>'parameters']);
        $node_agency = \Solunes\Master\App\Node::create(['name'=>'agency', 'table_name'=>'agencies', 'location'=>'business', 'folder'=>'parameters']);
        $node_company = \Solunes\Master\App\Node::create(['name'=>'company', 'table_name'=>'companies', 'location'=>'business', 'folder'=>'business']);
        $node_contact = \Solunes\Master\App\Node::create(['name'=>'contact', 'location'=>'business', 'folder'=>'business']);
        $node_deal = \Solunes\Master\App\Node::create(['name'=>'deal', 'location'=>'business', 'folder'=>'business']);
        $node_deal_company = \Solunes\Master\App\Node::create(['name'=>'deal-company', 'location'=>'business', 'type'=>'field', 'parent_id'=>$node_deal->id, 'model'=>'Solunes\Business\App\Company']);
        $node_deal_contact = \Solunes\Master\App\Node::create(['name'=>'deal-contact', 'location'=>'business', 'type'=>'field', 'parent_id'=>$node_deal->id, 'model'=>'Solunes\Business\App\Contact']);

        // Usuarios
        $admin = \Solunes\Master\App\Role::where('name', 'admin')->first();
        $member = \Solunes\Master\App\Role::where('name', 'member')->first();
        $parameters_perm = \Solunes\Master\App\Permission::create(['name'=>'parameters', 'display_name'=>'Parámetros']);
        $business_perm = \Solunes\Master\App\Permission::create(['name'=>'business', 'display_name'=>'Negocio']);
        $admin->permission_role()->attach([$parameters_perm->id, $business_perm->id]);

    }
}