<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\District;
use App\Models\Province;

class LocationController extends Controller 
{
    public function getProvinces()
    {
        $provinces = Province::select('id', 'name')->orderBy('name', 'asc')->get();

        return $this->responseData($provinces);
    }

    public function getDistricts(Request $request)
    {
        $districts = District::select('id', 'name', 'province_id')
            ->where('province_id', $request->province_id)
            ->orderBy('name', 'asc')
            ->get();

        return $this->responseData($districts);
    }

    public function getCommunes(Request $request)
    {
        $communes = Commune::select('id', 'name', 'district_id')
            ->where('district_id', $request->district_id)
            ->orderBy('name', 'asc')
            ->get();

        return $this->responseData($communes);
    }
}
