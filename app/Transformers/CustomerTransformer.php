<?php

namespace App\Transformers;

use App\Customer;
use League\Fractal;

class CustomerTransformer extends Fractal\TransformerAbstract
{

    public function transform(Customer $customer)
    {
        return [
            'id' => (int) $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
        ];
    }
}