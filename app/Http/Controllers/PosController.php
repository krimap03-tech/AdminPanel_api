<?php

namespace App\Http\Controllers;

use App\Models\Pos;

class PosController extends Controller
{
    public function show(){

        $Pos = Pos::get();
        return response()->json([
            'data'=>$Pos
        ]);

    }
}
