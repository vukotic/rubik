<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\Interfaces\RubikCubeInterface;

class CubeController extends Controller{

    public function rotate(RubikCubeInterface $r, $axis, $ring, $direction){
        return response()->json($r->rotate($axis, $ring, $direction));
    }

    //----------------

    public function recreate(RubikCubeInterface $r){
        return response()->json($r->recreate());
    }

    //----------------

    public function display(RubikCubeInterface $r){
        return response()->json($r->display());
    }

    //----------------

    public function displaySide(RubikCubeInterface $r, string $side){
        return response()->json($r->displaySide($side));
    }
}
