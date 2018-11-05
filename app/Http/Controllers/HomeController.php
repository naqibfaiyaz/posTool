<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\catalog;
use App\catalogcategory;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catalog = catalog::where('status', 1)->get();
        $category= catalogcategory::all();
        return view('home')->with(compact('catalog', $catalog))->with(compact('category', $category));
    }
}
