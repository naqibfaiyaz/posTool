<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\catalog;
use App\catalogCategory;
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
        $ExistingCatalog=catalog::select('category_id')->where('status', 1)->where('show_as_product', 1)->get()->unique('category_id')->values()->toArray();
        $category= catalogCategory::whereIn('id', $ExistingCatalog)->get();
        $quantity= catalogQuantity::all();
        
        return view('home')->with(compact('catalog', $catalog))->with(compact('category', $category))->with(compact('quantity', $quantity));
    }
}
