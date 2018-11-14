<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\orderHistory;
use App\orderSumHistory;

class orderHistoryViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $managerRole=$request->user()->authorizeRoles(['manager']);
        
        if($managerRole){
            $orderData=orderSumHistory::all()->sortByDesc('id');
            
            return view('OrderHistory.index')->with(compact('orderData', $orderData));
        }else{
            abort(403, "Unauthorized User.");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orderSumHistory = orderSumHistory::where('order_id',$id)->get(); //Get user with specified id
        $orderDataDetails = orderHistory::where('order_id',$id)->get(); //Get user with specified id

        return view('OrderHistory.show')->with(compact('orderDataDetails', $orderDataDetails))->with(compact('orderSumHistory', $orderSumHistory));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $orderId = orderSumHistory::select('id')->where('order_id',$id)->get()->toArray()[0]['id']; //Get user with specified id
        
        $orderFind=new orderSumHistory;
        $orderFind=$orderFind::find($orderId);
        
        $orderFind->order_status=0;

        $orderFind->save();
        
        return redirect('orderHistory');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
