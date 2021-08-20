<?php

namespace App\Rules;

use App\BalanceModel;
use App\MaterialModel;
use App\StockOutModel;
use Illuminate\Contracts\Validation\Rule;

class GreaterThanEqualMaterial implements Rule
{
    public $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $stockOuts = StockOutModel::where('id_stock_out', $this->id)->first(['balance_id', 'value_out']);
        $balance = BalanceModel::where('id', $stockOuts->balance_id)->first(['b_value']);
        $oldBalance = $stockOuts->value_out + $balance->b_value;
        return $value <= $oldBalance;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'กรุณากรอกจำนวนให้ น้อยกว่า หรือ เท่ากับ จำนวนคงเหลือ';
    }
}
