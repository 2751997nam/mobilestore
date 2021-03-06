<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller 
{
    const VALIDDATION_RULES = [
        'name' => 'required|max:255',
        'description' => 'max:255'
    ];
    const NICE_NAMES = [
        'name' => 'tên danh mục',
        'description' => 'mô tả'
    ];

    const VALIDATION_IMAGE_MESSAGES = [
        'required' => 'Vui lòng chọn ảnh để tải lên',
        'mimes' => 'Chỉ chấp nhận file có định dạng jpeg, bmp, png, webp, jpg',
        'max' => 'Kích thước ảnh tối đa là 5MB',
    ];

    public function index(Request $request)
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return $this->responseData($categories);
    }

    public function store(Request $request)
    {
        $retval = $this->responseSuccess('success');

        $validator = Validator::make($request->all(), self::VALIDDATION_RULES, [], self::NICE_NAMES);

        if ($validator->fails()) {
            return $this->responseFail($validator->errors()->first());
        }

        Category::create($request->all());

        return $retval;
    }

    public function uploadImage(Request $request)
    {
        $retval = [
            'status' => 'successful',
            'result' => []
        ];
        $validator = Validator::make($request->all(), [
            'images.*' => 'bail|required|file|mimes:jpeg,bmp,png,webp,jpg|max:5120'
        ], self::VALIDATION_IMAGE_MESSAGES);

        if ($validator->fails()) {
            $retval = $this->responseFail($validator->errors()->first());

            return $retval;
        }

        $result = [];
        if ($request->hasFile('images')) {
            $images = $request->file('images');
        }

        foreach ($images as $image) {
            $filename = $image->store('images');
            $result[] = Storage::url($filename);
        }

        $retval['result'] = $result;

        return $retval;
    }

    public function update(Request $request, $id)
    {
        $retval = $this->responseSuccess('success');

        $validator = Validator::make($request->all(), self::VALIDDATION_RULES, [], self::NICE_NAMES);

        if ($validator->fails()) {
            return $this->responseFail($validator->errors()->first());
        }

        $category = Category::find($id);

        if (!empty($category)) {
            $category->fill($request->all());
            $category->save();
        } else {
            return $this->responseFail('Không tìm thấy danh mục');
        }

        return $retval;
    }

    public function delete($id)
    {
        Category::destroy($id);

        return $this->responseSuccess('success');
    }
}
