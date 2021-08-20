<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialModel extends Model
{
    //
    protected $table = 'material';
    protected $fillable = ['m_code', 'm_name', 'm_unit', 'm_price_unit', 'm_exp_date'];

    protected $hiden = [
        'id'
    ];

    // public function materialType()
    // {
    //     return $this->belongsTo('App\MaterialTypeModel', 'm_type', 'id_type');
    // }

    public function materialUnit()
    {
        return $this->belongsTo('App\UnitModel', 'm_unit', 'id_unit');
    }

    public function stockIn()
    {
        return $this->hasMany('App\StockInModel');
    }

    public function balances()
    {
        return $this->hasMany('App\BalanceModel', 'material_id', 'm_code');
    }
}
