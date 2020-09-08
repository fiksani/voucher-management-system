<?php

namespace App\Transformers;

use App\VoucherName;
use League\Fractal;

class VoucherNameTransformer extends Fractal\TransformerAbstract
{

    public function transform(VoucherName $voucherName)
    {
        return [
            'id' => (int) $voucherName->id,
            'name' => $voucherName->name,
            'short_code' => $voucherName->short_code,
            'period_day' => $voucherName->period,
            'value' => $voucherName->value,
            'type' => $voucherName->type,
            'generate_voucher_qty' => $voucherName->generate_voucher_qty,
            'total_voucher_qty' => $voucherName->total_voucher_qty,
            'expired_date' => $voucherName->expired_date,
            'active' => $voucherName->active,
        ];
    }
}