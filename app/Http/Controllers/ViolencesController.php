<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViolencesController extends Controller
{
       public function viewviolences()
    {
        return view('violences');
    }
       public function viewvaddviolences()
    {
        return view('addviolences');
    }
}
