<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Press;

class MstPressController extends Controller
{
    public function index(){
        $item = Press::all();
      return view('press.index',compact('item'));
    }
}
