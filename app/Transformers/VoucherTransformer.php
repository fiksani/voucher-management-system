<?php

namespace App\Transformers;

use App\Voucher;
use League\Fractal;

class VoucherTransformer extends Fractal\TransformerAbstract
{

    public function transform(Voucher $voucher)
    {
        return [
            'id' => (int) $voucher->id,
            'code' => $voucher->code,
            'voucher' => $voucher->VoucherName->name,
            'expired' => $voucher->expired_date,
            'status' => $voucher->status->status,
        ];
    }
}