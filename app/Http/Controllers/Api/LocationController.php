<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function provinces()
    {
        $provinces = DB::table('provinces')->orderBy('name')->get();
        return response()->json($provinces);
    }

    public function cities($provinceId)
    {
        $cities = DB::table('cities')
            ->where('province_id', $provinceId)
            ->orderBy('name')
            ->get();
        return response()->json($cities);
    }
}
