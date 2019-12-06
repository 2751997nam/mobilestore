<?php

namespace App\Http\Controllers\Api;

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
        'commune_id' => 'bail|required_with:district_id|exists:commune,id',
        'items.*' => 'bail|required|array',
        'items.*.product_id' => 'required|exists:product,id',
        'items.*.price' => 'required|numeric'
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

    public function index(Request $request) {
        $query = Order::with('items');
        if ($request->has('status')) {
            $status = explode(';', $request->status);
            $query->whereIn('status', $status);
        }

        if ($request->has('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $query->orderBy('code', 'desc');

        $meta = $this->getMetaData($query, $request);

        $orders = $this->paginate($query, $meta['page_size'], $meta['page_id']);

        return $this->responseWithMeta($orders, $meta);
    }

    public function show($id)
    {
        return $this->responseData(Order::with(['customer', 'items'])->where('id', $id)->first());
    }

    public function updateStatus(Request $request, $id)
    {
        $order =Order::find($id);

        if (!empty($order)) {
            $order->fill($request->all());

            $order->save();
        } else {
            return $this->responseFail('Không tìm thấy đơn hàng');
        }
        return $this->responseSuccess('');
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
                }
            }
        }

        return $retVal;
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

    public function store(Request $request)
    {
        $retval = ['status' => 'successful', 'message' => 'Tạo đơn hàng thành công'];

        $retval = $this->validateOrder($request, true);
        try {
            if ($retval['status'] === 'successful') {
                $data = $request->all();
                $code = $this->generateCode();
                $data['code'] = $code;
                $customer = $this->createOrUpdateCustomer($data['customer']);
                if (!empty($customer)) {
                    $data['customer_id'] = $customer->id;
                }
                $data['status'] = 'PROCESSING';
                $order = Order::create($data);
                $result = $this->syncOrderItem($order, $data);
                if ($result['status'] !== 'successful') {
                    Order::destroy($order->id);
                    $retval = ['status' => 'failed', 'message' => 'Không tìm thấy sản phẩm'];
                }
            }
        } catch (\Exception $ex) {
            $retval = [
                'status' => 'failed',
                'message' => $ex->getMessage() . '. Line: ' . $ex->getLine() . ' . File: ' . $ex->getFile()
            ];
        }

        $code = $retval['status'] === 'successful' ? 200 : 422;

        return response()->json($retval, $code);
    }

    public function syncOrderItem($order, $data)
    {
        $retval = ['status' => 'successful'];
        $count = count($data['items']);
        for ($i = 0; $i < $count; $i++) {
            if (empty($data['items'][$i]['price'])) {
                $data['items'][$i]['price'] = 0;
            }
        }
        $order->products()->sync($data['items']);

        return $retval;
    }

    public function update(Request $request, $id)
    {
        $retval = $this->validateOrder($request);

        if ($retval['status'] !== 'successful') {
            return $retval;
        } else {
            $data = $request->all();
            DB::beginTransaction();
            try {
                $order = Order::find($id);
                if (!empty($order)) {
                    $order->fill($data);
                    $order->save();
                    if (!empty($data['items'])) {
                        OrderItem::where('order_id', $order->id)->delete();
                        foreach ($data['items'] as $item) {
                            OrderItem::create(
                                [
                                    'order_id' => $order->id,
                                    'product_id' => $item['product_id'],
                                    'price' => $item['price'],
                                    'quantity' => $item['quantity'],
                                    'product_name' => $item['product_name'],
                                    'image_url' => $item['image_url']
                                ]
                            );
                        }
                    }
                }
                DB::commit();
    
                return $this->responseSuccess('update order success');
            } catch (\Exception $ex) {
                DB::rollback();
                return $this->responseFail($ex->getMessage() . ' File:' . $ex->getFile() . ' Line: ' . $ex->getLine());
            }
        }
    }

}
