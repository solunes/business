<?php

namespace Solunes\Business\App;


use Illuminate\Database\Eloquent\Model;


class CategoryTranslation extends Model {
    
    protected $table = 'category_translation';
    public $timestamps = false;
    protected $fillable = ['name','description'];

}