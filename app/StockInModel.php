<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockInModel extends Model
{
    use SoftDeletes;
    //
    protected $table = 'stock_in';
    protected $fillable = ['ware_house', 'value_in', 'total_price_in', 'date_in'];
    protected $primaryKey = 'id_stock_in';

    // public function materials()
    // {
    //     return $this->belongsTo('App\MaterialModel', 'material_id', 'm_code');
    // }

    public function warehouses()
    {
        return $this->belongsTo('App\WareHouseModel', 'ware_house', 'id_warehouse');
    }

    public function balances()
    {
        return $this->hasOne('App\BalanceModel', 'id', 'balance_id');
    }
}
