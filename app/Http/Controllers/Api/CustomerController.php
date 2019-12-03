<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller 
{
    const VALIDATION_RULES = [
        'note' => 'nullable|string|max:255',
    ];

    const NICE_NAMES = [
        'note' => 'ghi chú',
    ];

    public function index(Request $request)
    {
        $data = $request->all();
        $search = $request->get('search', '');

        $query = Customer::orderBy('full_name', 'asc');
        if ($search) {
            $query->where('phone', 'like',  '%'. $search . '%')
                ->orWhere('full_name', 'like', '%' . $search . '%');
        }
        
        $meta = $this->getMetaData($query, $request);
        $customers = $this->paginate($query, $request);

        return $this->responseWithMeta($customers, $meta);
    }

    public function show($id)
    {
        $customer = Customer::with(['province', 'district', 'commune'])->where('id', $id)->first();

        if (!empty($customer)) {
            return $this->responseData($customer);
        } 
        return $this->responseFail('Không tìm thấy khách hàng');
    }

    public function update(Request $request, $id)
    {
        $retVal = $this->responseSuccess('Lưu ghi chú thành công');
        $validator = Validator::make(
            $request->all(),
            self::VALIDATION_RULES,
            [],
            self::NICE_NAMES
        );
        if ($validator->fails()) {
            $retVal = ['status' => 'failed', 'message' => $validator->errors()->first()];
        } else {
            $customer = Customer::find($id);

            if (!empty($customer)) {
                $customer->note = $request->get('note', '');
                $customer->save();
            } else {
                $this->responseFail('Không tìm thấy khách hàng');
            }
        }

        return $retVal;
    }
}
