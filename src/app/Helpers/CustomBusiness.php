<?php 

namespace Solunes\Business\App\Helpers;

use Form;

class CustomBusiness {
   
    public static function after_seed_actions() {
        /*$node_array['currency'] = ['action_field'=>['edit']];
        foreach($node_array as $node_name => $node_detail){
            $node = \Solunes\Master\App\Node::where('name', $node_name)->first();
            foreach($node_detail as $extra_type => $extra_value) {
                $node_extra = new \Solunes\Master\App\NodeExtra;
                $node_extra->parent_id = $node->id;
                $node_extra->type = $extra_type;
                $node_extra->value_array = json_encode($extra_value);
                $node_extra->save();
            }
        }*/
        // Borrar opciones del menú
        //\Solunes\Master\App\Menu::where('menu_type', 'admin')->where('level', 1)->whereTranslation('name', 'Global')->delete();
        // Menú
        //$pm = \Solunes\Master\App\Menu::where('menu_type', 'admin')->whereTranslation('name', 'Contabilidad')->first();
        //$menu_array[] = ['parent_id'=>$pm->id,'level'=>2,'icon'=>'pencil','name'=>'Crear Ingreso','link'=>'admin/model/income/create'];
        //$menu_array[] = ['parent_id'=>$pm->id,'level'=>2,'icon'=>'th-list','name'=>'Reporte de Ingresos','link'=>'admin/account-cash-flow/credit'];
        $pm = new \Solunes\Master\App\Menu;
        $pm->level = 1;
        $pm->type = 'blank';
        $pm->menu_type = 'admin';
        $pm->icon = 'area-chart';
        $pm->name = 'Gestión de Proyectos';
        $pm->save();
        $menu_array[] = ['parent_id'=>$pm->id,'level'=>2,'icon'=>'bar-chart','name'=>'Dashboard de Proyectos','link'=>'admin/business-dashboard'];
        $menu_array[] = ['parent_id'=>$pm->id,'level'=>2,'icon'=>'bar-chart','name'=>'Proyectos','link'=>'admin/businesss'];
        $menu_array[] = ['parent_id'=>$pm->id,'level'=>2,'icon'=>'bar-chart','name'=>'Wikis','link'=>'admin/wikis'];
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
        return 'After seed realizado correctamente.';
    }
       
    public static function get_custom_field($name, $parameters, $array, $label, $col, $i, $value, $data_type) {
        // Type = list, item
        $return = NULL;
        /*if($name=='parcial_cost'){
            $return .= \Field::form_input($i, $data_type, ['name'=>'quantity', 'required'=>true, 'type'=>'string'], ['value'=>1, 'label'=>'Cantidad Comprada', 'cols'=>4]);
            //$return .= \Field::form_input($i, $data_type, ['name'=>'total_cost', 'required'=>true, 'type'=>'string'], ['value'=>0, 'label'=>'Costo Total de Lote', 'cols'=>6], ['readonly'=>true]);
            if(request()->has('purchase_id')){
                $return .= '<input type="hidden" name="purchase_id" value="'.request()->input('purchase_id').'" />';
            }
        }*/
        return $return;
    }

    public static function after_login($user, $last_session, $redirect) {
        return true;
    }
    
    public static function check_permission($type, $module, $node, $action, $id = NULL) {
        // Type = list, item
        $return = 'none';
        /*if($node->name=='accounts-payable'||$node->name=='accounts-receivable'){
            if($type=='item'&&$action=='edit'){
                if($node->name=='accounts-payable'){
                    $pending = \App\AccountsPayable::find($id);
                } else if($node->name=='accounts-receivable'){
                    $pending = \App\AccountsReceivable::find($id);
                }
                if($pending->status=='paid'){
                    $return = 'false';
                }
            }
        }*/
        return $return;
    }

    public static function get_options_relation($submodel, $field, $subnode, $id = NULL) {
        /*if($field->relation_cond=='account_concepts'){
            $node_name = request()->segment(3);
            if($id){
                $node = \Solunes\Master\App\Node::where('name', request()->segment(3))->first();
                $model = \FuncNode::node_check_model($node);
                $model = $model->find($id);
                $submodel = $submodel->where('id', $model->account_id);
            } else {
                if(auth()->check()&&auth()->user()->hasRole('admin')){
                    if($node_name=='income'||$node_name=='accounts-receivable'){
                        $submodel = $submodel->where('code', 'income_other');
                    } else if($node_name=='expense'||$node_name=='accounts-payable'){
                        $submodel = $submodel->whereIn('code', ['expense_operating_com','expense_operating_adm','expense_operating_dep','expense_operating_int','expense_other']);
                    }
                } else {
                    if($node_name=='income'){
                        $submodel = $submodel->where('code', 'income_other');
                    } else if($node_name=='expense'){
                        $submodel = $submodel->where('code', 'expense_other');
                    }
                }
            }
        }*/
        return $submodel;
    }

}