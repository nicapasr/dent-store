<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitModel extends Model
{
    //
    protected $table = 'unit';
    protected $fillable = ['id_unit', 'unit_name'];
    protected $primaryKey = 'id_unit';
    public $timestamps = false;

    public function materials()
    {
        return $this->hasMany('App\MaterialModel');
    }
}
