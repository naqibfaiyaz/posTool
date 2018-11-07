<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\catalog;
use App\catalogcategory;
use App\catalogQuantity;

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
        $quantity= catalogQuantity::all();
        // dd($catalog, $category, $quantity);
        return view('home')->with(compact('catalog', $catalog))->with(compact('category', $category))->with(compact('quantity', $quantity));
    }
}
