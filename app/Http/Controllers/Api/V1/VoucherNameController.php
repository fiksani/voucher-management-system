<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use EllipseSynergie\ApiResponse\Contracts\Response;

use App\Http\Controllers\Controller;
use App\VoucherName;
use App\Transformers\VoucherNameTransformer;
use Validator;

class VoucherNameController extends Controller
{

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index()
    {
        $voucherNames = VoucherName::paginate(10);
        return $this->response->withPaginator($voucherNames, new VoucherNameTransformer);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'short_code' => 'required|unique:voucher_names',
            'period' => 'required',
            'expired_date' => 'required',
            'total_voucher_qty' => 'required',
            'value' => 'required|numeric',
            'type' => 'required|in:percentage,virtual_currency'
        ], [
            'type.in' => 'The selected type is invalid. Valid type: percentage,virtual_currency.'
        ]);

        if ($validator->fails()) {
            return $this->response->errorWrongArgs($validator->errors());
        }

        $voucherName = VoucherName::create($request->all());

        return $this->response->withArray([
            'data' => [
                'message' => 'New Campaign is created.',
                'campaign' => $voucherName
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $voucherName = VoucherName::find($id);
        if (!$voucherName) {
            return $this->response->errorNotFound('Campaign Not Found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'short_code' => 'required',
            'period' => 'required',
            'expired_date' => 'required',
            'total_voucher_qty' => 'required',
            'active' => 'required|boolean',
            'value' => 'required|numeric',
            'type' => 'required|in:percentage,virtual_currency'
        ], [
            'type.in' => 'The selected type is invalid. Valid type: percentage,virtual_currency.'
        ]);

        if ($validator->fails()) {
            return $this->response->errorWrongArgs($validator->errors());
        }

        $voucherName = $voucherName->fill($request->all());

        return $this->response->withArray([
            'data' => [
                'message' => 'Campaign is already updated.',
                'campaign' => $voucherName
            ]
        ]);
    }
}
