<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FreightType;
use App\Models\PackagingType;
use App\Models\Unit;

class LookupController extends Controller
{
    public function packagingTypes()
    {
        return response()->json(PackagingType::where('is_active', true)->get());
    }

    public function units()
    {
        return response()->json(Unit::where('is_active', true)->get());
    }

    public function freightTypes()
    {
        return response()->json(FreightType::where('is_active', true)->get());
    }
}
