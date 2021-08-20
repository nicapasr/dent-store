<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOutModel extends Model
{
    use SoftDeletes;
    //
    protected $table = 'stock_out';
    protected $primaryKey = 'id_stock_out';
    protected $fillable = ['member_id', 'material_id', 'room', 'value_out', 'total_price_out', 'date_out', 'status'];
    // public $timestamps = false;

    public function materials()
    {
        return $this->belongsTo('App\MaterialModel', 'material_id', 'id');
    }

    // public function userTable()
    // {
    //     return $this->belongsTo('App\User', 'user_id', 'username');
    // }

    // public function importants()
    // {
    //     return $this->belongsTo(ImportantModel::class, 'important', 'id_imp');
    // }

    public function members()
    {
        return $this->belongsTo('App\MemberModel', 'member_id', 'id');
    }

    public function balances()
    {
        return $this->hasOne('App\BalanceModel', 'id', 'balance_id');
    }
}
