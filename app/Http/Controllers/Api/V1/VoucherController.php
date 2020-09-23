<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Contracts\Response;

use App\Http\Controllers\Controller;
use App\Customer;
use App\Voucher;
use App\VoucherName;

/**
 * @group  Voucher
 *
 * APIs for register and redeem voucher
 */
class VoucherController extends Controller
{

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Register voucher
     *
     * @urlParam id required The id of the campaign.
     * @bodyParam name string required The name of the customer. Example: Suherman
     * @bodyParam email string required The email of the customer. Example: suherman@site.domain
     *
     * @response {
     *   "data": {
     *     "code": "Y-2081600950810",
     *     "expired_date": "2020-09-24T12:33:30.734968Z"
     *   }
     * }
     * 
     * @response 404 {
     *  "message": "Voucher Not Found"
     * }
     */
    public function register(Request $request, $id)
    {
        $voucherName= VoucherName::where('id', $id)->first();
        if (!$voucherName) {
            return $this->response->errorNotFound('Voucher Not Found');
        }

        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);
        $name = $request->get('name');
        $email = $request->get('email');

        // - cek dulu apakah email sudah ada di daftar customer
        if ($customer = Customer::where('email', $email)->first()){
            // - jika sudah ada ambil id customernya
            $customer_id= $customer->id;
        }else{
            // - jika belum ada, insert dulu ke customer lalu ambil id customernya
            $customer = Customer::create([
                'name' => $name,
                'email' => $email,
            ]);
            $customer_id= $customer->id;
        }

        // - selanjutnya cek di daftar voucher, jika customer masih mempunyai voucher yang masih berlaku untuk jenis voucher yang sama yang direquest,customer tidak boleh generate lagi.
        $alreadyActive = Voucher::where('customer_id',$customer_id )
                ->where('expired_date','>',now()) //date belum expired
                ->where('status_id',1) //statusnya masih create
                ->exists();
        if ($alreadyActive){
            return $this->response->errorForbidden("You still have active voucher");
        }else{
            // - Jika customer tidak mempunyaivoucher yang sama dan berlaku maka voucher dibuatkan
            $expire_date = $voucherName->expired_date;
            $period = $voucherName->period;
            $short_code=$voucherName->short_code;
            $total_voucher = $voucherName->total_voucher_qty;
            $current_voucher_qty = $voucherName->generate_voucher_qty;

            //cek jika voucher masih tersedia (generate_voucher_qty < total_voucher_qty)
            if($current_voucher_qty<$total_voucher){

                $dt = Carbon::now();

                $expire_date = $voucherName->expired_date;
                $period = $voucherName->period;
                $short_code=$voucherName->short_code;

                $length_days = ($dt->diffInDays($expire_date))+1;

                //cek jika antara date now + period melebihi expire date, maka expired date disesuasikan sisa harinya.
                if($length_days >= $period){
                    $expire_date = $dt->addDays($length_days);
                }

                //kode generator( kode di voucher name + id + timestamp tanggalsekarang)
                $code=($short_code.$customer_id.$dt->timestamp);

                $voucher = Voucher::create([
                    'code' => $code,
                    'customer_id' => $customer_id,
                    'voucher_name_id' => $id,
                    'expired_date' => ($expire_date),
                    'status_id' => 1 //for first status: create voucher
                ]);
                // setelah voucher dibuat, voucher dikirim ke email;
                if($voucher){
                    $voucherName->generate_voucher_qty = $current_voucher_qty+1;
                    $voucherName->save();

                    return $this->response->withArray([
                        'data' => [
                            'code' => $voucher->code,
                            'expired_date' => $voucher->expired_date
                        ]
                    ]);
                }
            }
            else {
                return $this->response->errorUnprocessable("Voucher out of stock");
            }
        }
        return $this->response->errorInternalError("Something went wrong");
    }

    /**
     * Redeem voucher
     *
     * @bodyParam code string required The code of the customer voucher. Example: Y-2081600950810
     *
     * @response {
     *    "data": {
     *      "message": "You've successfully redeemed your code",
     *      "voucher": {
     *        "code": "Y-2081600950810",
     *        "voucher": "YELLOWFIT-20",
     *        "type": "percentage",
     *        "value": 20
     *      }
     *    }
     * }
     * 
     * @response 404 {
     *  "message": "Voucher Not Found"
     * }
     */
    public function redeem(Request $request)
    {
        $code=($request->code);
        $voucher=Voucher::where('code',$code)->first();
        if(!$voucher){
            return $this->response->errorNotFound('Voucher Not Found');
        }

        if($voucher->expired_date > now()->addDays(-1)){
            switch ($voucher->status_id) {
                case '1':
                    $voucher->status_id = 3;
                    $voucher->save();
                    $alert="success";
                    $message = "You've successfully redeemed your code";
                    return $this->response->withArray([
                        'data' => [
                            'message' => $message,
                            'voucher' => [
                                'code' => $voucher->code,
                                'voucher' => $voucher->VoucherName->name,
                                'type' => $voucher->VoucherName->type,
                                'value' => $voucher->VoucherName->value,
                            ]
                        ]
                    ]);
                    break;
                case '2':
                    $message = $code." expired at ".date("jS F, Y", strtotime($voucher->expired_date));
                    break;
                case '3':
                    $message = $code." already used";
                    break;
            }

        } else {
            switch ($voucher->status_id) {
                case '1':
                    $voucher->status_id = 2;
                    $voucher->save();

                    $message = $code." expired at ".date("jS F, Y", strtotime($voucher->expired_date));
                    break;
                case '2':
                    $message = $code." expired at ".date("jS F, Y", strtotime($voucher->expired_date));
                    break;
                case '3':
                    $message = $code." already used";
                    break;
            }
        }

        return $this->response->errorUnprocessable($message);
    }
    
    /**
     * Check voucher
     *
     * @bodyParam code string required The code of the customer voucher. Example: Y-2081600950810
     *
     * @response {
     *    "data": {
     *      "voucher": {
     *        "code": "Y-2081600950810",
     *        "voucher": "YELLOWFIT-20",
     *        "type": "percentage",
     *        "value": 20,
     *		  "status": "used"
     *      }
     *    }
     * }
     * 
     * @response 404 {
     *  "message": "Voucher Not Found"
     * }
     */
    public function check(Request $request)
    {
        $code=($request->code);
        $voucher=Voucher::where('code',$code)->first();
        if(!$voucher){
            return $this->response->errorNotFound('Voucher Not Found');
        }

        if($voucher->expired_date > now()->addDays(-1)){
            switch ($voucher->status_id) {
                case '1':
                    return $this->response->withArray([
                        'data' => [
                            'voucher' => [
                                'code' => $voucher->code,
                                'voucher' => $voucher->VoucherName->name,
                                'type' => $voucher->VoucherName->type,
                                'value' => $voucher->VoucherName->value,
                                'status' => $voucher->status->status,
                            ]
                        ]
                    ]);
                    break;
                case '2':
                    $message = $code." expired at ".date("jS F, Y", strtotime($voucher->expired_date));
                    break;
                case '3':
                    $message = $code." already used";
                    break;
            }

        } else {
            switch ($voucher->status_id) {
                case '1':
                    $message = $code." expired at ".date("jS F, Y", strtotime($voucher->expired_date));
                    break;
                case '2':
                    $message = $code." expired at ".date("jS F, Y", strtotime($voucher->expired_date));
                    break;
                case '3':
                    $message = $code." already used";
                    break;
            }
        }

        return $this->response->errorUnprocessable($message);
    }
}
