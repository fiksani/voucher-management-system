<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use EllipseSynergie\ApiResponse\Contracts\Response;

use App\Http\Controllers\Controller;
use App\VoucherName;
use App\Transformers\VoucherNameTransformer;
use Validator;

/**
 * @group  Campaign
 *
 * APIs for managing campaign
 */
class VoucherNameController extends Controller
{

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * List of campaign
     */
    public function index()
    {
        $voucherNames = VoucherName::paginate(10);
        return $this->response->withPaginator($voucherNames, new VoucherNameTransformer);
    }



    /**
     * Create a campaign
     *
     * @bodyParam name string required The name of the campaign. Example: Yellofit 20% Discount
     * @bodyParam short_code string required The code of the campaign. Example: YFK20P
     * @bodyParam period number required The period of the campaign in days. Example: 1
     * @bodyParam expired_date date required The expired date of the campaign. Example: 2020-09-06
     * @bodyParam total_voucher_qty number required The maximum voucher quantity. Example: 100
     * @bodyParam value number required The value of the campaign based on type. Example: 20
     * @bodyParam type string required The type of the campaign. Should be: percentage,virtual_currency. Example: percentage    
     *
     * @response {
     *   "data": {
     *      "message": "New Campaign is created.",
     *      "campaign": {
     *        "name": "Yellofit 20% Dicount",
     *        "short_code": "YF-20sDdd2s",
     *        "period": "1",
     *        "expired_date": "2020-09-06",
     *        "total_voucher_qty": 1,
     *        "value": 20,
     *        "type": "percentage",
     *        "id": 1
     *      }
     *    }
     *  }
     */
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
                'campaign' => collect($voucherName->toArray())
                    ->only(['id', 'name', 'short_code', 'period', 'expired_date', 'total_voucher_qty', 'value', 'type', 'active'])
                    ->all()
            ]
        ]);
    }

    /**
     * Update campaign
     *
     * @urlParam id required The id of the campaign. Example: 52
     * @bodyParam name string required The name of the campaign. Example: Yellofit 20% Discount
     * @bodyParam short_code string required The code of the campaign. Example: YFK20P
     * @bodyParam period number required The period of the campaign in days. Example: 1
     * @bodyParam expired_date date required The expired date of the campaign. Example: 2020-09-06
     * @bodyParam total_voucher_qty number required The maximum voucher quantity. Example: 100
     * @bodyParam active boolean required The status of the campaign. Example: false
     * @bodyParam value number required The value of the campaign based on type. Example: 20
     * @bodyParam type string required The type of the campaign. Should be: percentage,virtual_currency. Example: percentage    
     *
     * @response {
     *   "data": {
     *      "message": "Campaign is already updated.",
     *      "campaign": {
     *        "name": "Yellofit 20% Dicount",
     *        "short_code": "YF-20sDdd2s",
     *        "period": "1",
     *        "expired_date": "2020-09-06",
     *        "total_voucher_qty": 1,
     *        "value": 20,
     *        "type": "percentage",
     *        "active": false,
     *        "id": 52
     *      }
     *    }
     *  }
     * 
     * @response 404 {
     *  "message": "Campaign Not Found"
     * }
     */
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
        $voucherName->save();

        return $this->response->withArray([
            'data' => [
                'message' => 'Campaign is already updated.',
                'campaign' => collect($voucherName->toArray())
                    ->only(['id', 'name', 'short_code', 'period', 'expired_date', 'total_voucher_qty', 'value', 'type', 'active'])
                    ->all()
            ]
        ]);
    }
}
