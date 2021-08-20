<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WareHouseModel extends Model
{
    //
    protected $table = 'warehouse';
    protected $fillable = ['id_warehouse','warehouse_name'];
    protected $primaryKey = 'id_warehouse';
    public $timestamps = false;

    public function StockIn(){
        return $this->hasMany('App\StockIn', 'id_warehouse');
    }
}
