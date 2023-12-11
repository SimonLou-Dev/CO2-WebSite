<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function getIndex(string $a =null){
        return view("home");
    }

}
