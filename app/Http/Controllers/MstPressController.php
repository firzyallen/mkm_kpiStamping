<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MstPressController extends Controller
{
    public function shopview(){
        return view('press.index');
    }
}
