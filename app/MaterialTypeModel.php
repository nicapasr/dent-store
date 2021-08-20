<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialTypeModel extends Model
{
    //
    protected $table = 'material_type';
    protected $fillable = ['type_name'];
    protected $primaryKey = 'id_type';
    public $timestamps = false;

    public function materials()
    {
        return $this->hasMany('App\MaterialModel');
    }
}
