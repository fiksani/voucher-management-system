<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use EllipseSynergie\ApiResponse\Contracts\Response;

use App\Http\Controllers\Controller;
use App\Transformers\CustomerTransformer;
use App\Transformers\VoucherTransformer;
use App\Customer;
use App\Voucher;

/**
 * @group  Customer
 *
 * APIs for show list of customer and customer vouchers
 */
class CustomerController extends Controller
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * List Customers
     */
    public function index()
    {
        $customers = Customer::paginate(10);
        return $this->response->withPaginator($customers, new CustomerTransformer);
    }

    /**
     * List Customer Vouchers
     */
    public function vouchers($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return $this->response->errorNotFound('Customer Not Found');
        }

        $vouchers = Voucher::with(['Customer:id,name,email', 'VoucherName:id,name', 'Status:id,status'])->where('customer_id', $id)->paginate(10);
        return $this->response->withPaginator($vouchers, new VoucherTransformer);
    }
}
