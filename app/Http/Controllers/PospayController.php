<?php

namespace App\Http\Controllers;

use App\Models\Pospay;

class PospayController extends Controller
{
    public function show(){
        $Pay = Pospay::get();
        return response()->json([
            'data' => $Pay
        ]);
    }
}

