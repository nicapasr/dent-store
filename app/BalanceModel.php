<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BalanceModel extends Model
{
    use SoftDeletes;

    protected $table = 'balance';
    protected $fillable = ['material_id', 'b_value', 'b_exp_date'];

    public function materials()
    {
        return $this->belongsTo('App\MaterialModel', 'material_id', 'm_code');
    }
}
