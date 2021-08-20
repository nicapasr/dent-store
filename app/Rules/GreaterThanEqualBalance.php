<?php

namespace App\Rules;

use App\BalanceModel;
use App\MaterialModel;
use Illuminate\Contracts\Validation\Rule;

class GreaterThanEqualBalance implements Rule
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
        $balance = BalanceModel::where('id', $this->id)->first();
        return $value <= $balance->b_value;
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
