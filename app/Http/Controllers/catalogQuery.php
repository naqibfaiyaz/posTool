<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\catalog;

class catalogQuery extends Controller
{
    // public function __construct() {
    //     $this->middleware(['auth']); //isAdmin middleware lets only users with a //specific permission to access these resources
    // }

    protected function index(){
        $catalog = catalog::find(1);

        return $catalog;
    }
}
