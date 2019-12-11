<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    const ORDER_VALIDATION_RULES = [
        'delivery_address' => 'nullable|string|max:255',
        'note' => 'nullable|string|max:255',
        'delivery_note' => 'nullable|string|max:500',
        'province_id' => 'bail|required|exists:province,id',
        'district_id' => 'bail|required_with:province_id|exists:district,id',
        'commune_id' => 'bail|required_with:district_id|exists:commune,id'
    ];

    const ORDER_VALIDATION_MESSAGES = [
        'amount.regex' => 'Trường :attribute không được có quá 15 chữ số trước dấu phẩy',
        'customer_id.required' => 'Không tìm thấy khách hàng',
        'customer_id.exists' => 'Không tìm thấy khách hàng',
        'items.required' => 'Vui lòng chọn sản phẩm',
        'items.array' => 'Sản phẩm không đúng định dạng',
        'items.*.product_id.required' => 'Vui lòng chọn sản phẩm',
        'items.*.product_id.exists' => 'Sản phẩm không tồn tại',
        'items.*.price.required' => 'Sản phẩm không có giá',
        'items.*.price.numeric' => 'Giá sản phẩm không đúng định dạng',
        'district_id.required' => 'Vui lòng chọn quận/huyện',
        'district_id.exists' => 'Vui lòng chọn quận/huyện',
        'province_id.required' => 'Vui lòng chọn tỉnh/thành phố',
        'province_id.exists' => 'Vui lòng chọn tỉnh/thành phố',
        'commune_id.required' => 'Vui lòng chọn xã/phường',
        'commune_id.exists' => 'Vui lòng chọn xã/phường',
    ];

    const ORDER_NICE_NAMES = [
        'amount' => 'thành tiền',
        'discount' => 'giảm giá',
        'shipping_fee' => 'phí giao hàng',
        'delivery_address' => 'địa chỉ giao hàng',
        'note' => 'ghi chú',
        'delivery_note' => 'ghi chú giao hàng'
    ];

    const CUSTOMER_VALIDATION_RULES = [
        'phone' => 'bail|required|regex:/(0)[0-9]{9}/|digits_between:10,11',
        'full_name' => 'bail|required|max:50|regex:/^[a-zA-Z \-àảãáạăằẳẵắặâầẩẫấậđèẻẽéẹêềểễếệìỉĩíịòỏõóọôồổỗốộơờởỡớợùủũúụưừửữứựỳỷỹýỵÀẢÃÁẠĂẰẲẴẮẶÂẦẨẪẤẬĐÈẺẼÉẸÊỀỂỄẾỆÌỈĨÍỊÒỎÕÓỌÔỒỔỖỐỘƠỜỞỠỚỢÙỦŨÚỤƯỪỬỮỨỰỲỶỸÝỴ]+$/',
        'email' => 'bail|nullable|email|max:100',
        'address' => 'bail|max:200',
        'note' => 'bail|max:200',
        'province_id' => 'bail|required|exists:province,id',
        'district_id' => 'bail|required_with:province_id|exists:district,id',
        'commune_id' => 'bail|required_with:district_id|exists:commune,id'
    ];

    const CUSTOMER_VALIDATION_MESSAGES = [
        'phone.regex' => 'Trường :attribute không hợp lệ',
        'district_id.required_with' => 'Vui lòng chọn quận/huyện',
        'district_id.exists' => 'Vui lòng chọn quận/huyện',
        'province_id.required' => 'Vui lòng chọn tỉnh/thành phố',
        'province_id.exists' => 'Vui lòng chọn tỉnh/thành phố',
        'commune_id.required_with' => 'Vui lòng chọn xã/phường',
        'commune_id.exists' => 'Vui lòng chọn xã/phường',
        'full_name.regex' => 'Trường :attribute chỉ bao gồm các chữ cái, dấu cách và dấu -'
    ];

    const CUSTOMER_NICE_NAME = [
        'full_name' => 'họ tên',
        'phone' => 'số điện thoại',
        'email' => 'email',
    ];

    public function validateCustomer(Request $request)
    {
        $retVal = ['status' => 'success', 'message' => 'Thông tin khách hàng hợp lệ'];

        $validator = Validator::make(
            $request->all(),
            self::CUSTOMER_VALIDATION_RULES,
            self::CUSTOMER_VALIDATION_MESSAGES,
            self::CUSTOMER_NICE_NAME
        );
        if ($validator->fails()) {
            $retVal = ['status' => 'failed', 'message' => $validator->errors()];
        }
        $code = $retVal['status'] === 'success' ? 200 : 422;

        return response()->json($retVal, $code);
    }

    public function validateOrder(Request $request, $hasCustomer = true)
    {
        $retVal = ['status' => 'successful', 'message' => 'ok'];
        if (empty($request->district_id)) {
            $retVal = ['status' => 'failed', 'message' => 'Vui lòng nhập địa chỉ giao hàng'];
        } else {
            $validator = Validator::make(
                $request->all(),
                self::ORDER_VALIDATION_RULES,
                self::ORDER_VALIDATION_MESSAGES,
                self::ORDER_NICE_NAMES
            );
            if ($validator->fails()) {
                $retVal = ['status' => 'failed', 'message' => $validator->errors()->first()];
            }
            if ($hasCustomer) {
                if ($request->has('customer')) {
                    $validator = Validator::make(
                        $request->customer,
                        self::CUSTOMER_VALIDATION_RULES,
                        self::CUSTOMER_VALIDATION_MESSAGES,
                        self::CUSTOMER_NICE_NAME
                    );
                    if ($validator->fails()) {
                        $retVal = ['status' => 'failed', 'message' => $validator->errors()->first()];
                    }
                } else {
                    $retVal = ['status' => 'failed', 'message' => 'Vui lòng nhập khách hàng'];
                }
            }
        }

        return $retVal;
    }

    public function store(Request $request)
    {
        $retval = $this->validateOrder($request);

        if ($retval['status'] !== 'successful') {
            return $retval;
        } else {
            $sessionId = session()->getId();
            $data = $request->all();
            DB::beginTransaction();
            try {
                $customer = $this->createOrUpdateCustomer($data['customer']);
        
                $cart = Cart::where('token', $sessionId)->where('status', 'pending')->first();
                if (!empty($cart)) {
                    $cartItems = CartItem::where('cart_id', $cart->id)->get();
                    if (!empty($cartItems)) {
                        $amount = 0;
                        foreach ($cartItems as $item) {
                            $amount += $item->price * $item->quantity;
                        }
                        $order = Order::create([
                            'code' => $this->generateCode(),
                            'amount' => $amount,
                            'delivery_address' => $data['delivery_address'],
                            'province_id' => $data['province_id'],
                            'district_id' => $data['district_id'],
                            'commune_id' => $data['commune_id'],
                            'delivery_note' => $data['delivery_note'],
                            'customer_id' => $customer->id
                        ]);
        
                        if (!empty($order)) {
                            foreach ($cartItems as $item) {
                                OrderItem::create([
                                    'order_id' => $order->id,
                                    'product_id' => $item->product_id,
                                    'price' => $item->price,
                                    'quantity' => $item->quantity,
                                    'product_name' => $item->product_name,
                                    'image_url' => $item->image_url
                                ]);
                            }

                            $cart->status = 'ordered';
                            $cart->save();
                        }
                    }
                }
                DB::commit();
    
                return $this->responseSuccess('create order success');
            } catch (\Exception $ex) {
                DB::rollback();
                return $this->responseFail($ex->getMessage() . ' File:' . $ex->getFile() . ' Line: ' . $ex->getLine());
            }
        }
    }

    public function createOrUpdateCustomer($data)
    {
        $customer = Customer::where('phone', $data['phone'])->first();
        if (empty($customer)) {
            $customer = Customer::create($data);
        } else {
            $customer->update($data);
        }

        return $customer;
    }

    public function generateCode()
    {
        $code = Cache::get('order::code');
        if (empty($code)) {
            $code = 100001;
        } else {
            $code = $code + 1;
        }
        Cache::forever('order::code', $code);

        return $code;
    }

}
