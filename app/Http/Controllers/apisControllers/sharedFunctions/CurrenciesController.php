<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
    // only if they active (status = 1)
    public function getCurrencies()
    {
        $currencies = Currency::where('c_status', 1)->get();
        return response()->json([
            'currencies' => $currencies,
        ]);
    }
}
