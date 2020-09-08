<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoucherName extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
    	'active' => 'boolean',
    	'total_voucher_qty' => 'integer',
    	'generate_voucher_qty' => 'integer',
    	'value' => 'integer'
    ];
}
