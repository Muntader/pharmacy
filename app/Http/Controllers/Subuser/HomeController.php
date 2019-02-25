<?php

namespace App\Http\Controllers\Subuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        return view('users.home');
    }
}
