<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\orderHistory;
use App\orderSumHistory;
use App\catalogQuantity;
use App\quantityRemark;
use App\catalog;
use Auth;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

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
            if(isset($_GET['StartDateFilter'])){
                $StartDateFilter=carbon::parse($_GET['StartDateFilter']);
            }else{
                $StartDateFilter=carbon::now();
            }
            
            if(isset($_GET['EndDateFilter'])){
                $EndDateFilter=carbon::parse($_GET['EndDateFilter']);
            }else{
                $EndDateFilter=carbon::now();
            }
            $orderData=orderSumHistory::whereBetween('created_at', [$StartDateFilter->format('Y-m-d 00:00:00'), $EndDateFilter->format('Y-m-d 23:59:59')])->orderBy('id', 'desc')->take(10000)->get();
            
            $page = Input::get('page', 1);
            $perPage = 50;

            $data = new LengthAwarePaginator(
                $orderData->forPage($page, $perPage), $orderData->count(), $perPage, $page
            );
            $data->setPath(url()->current());
            $EndDate=$EndDateFilter->toDateString(); 
            $StartDate=$StartDateFilter->toDateString();
            return view('OrderHistory.index')->with('orderData', $data)->with('exportData', $orderData)->with(compact('EndDate', $EndDate))->with(compact('StartDate', $StartDate));
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
    public function show($id, Request $request)
    {
        $managerRole=$request->user()->authorizeRoles(['manager']);

        if($managerRole){
            $orderSumHistory = orderSumHistory::where('order_id',$id)->get(); //Get user with specified id
            $orderDataDetails = orderHistory::where('order_id',$id)->get(); //Get user with specified id
    
            return view('OrderHistory.show')->with(compact('orderDataDetails', $orderDataDetails))->with(compact('orderSumHistory', $orderSumHistory));
        }else{
            abort(403, "Unauthorized User.");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quantityRemark=new quantityRemark;
        $orderId = orderSumHistory::select('id')->where('order_id',$id)->get()->toArray()[0]['id']; //Get user with specified id
        
        $orderFind=new orderSumHistory;
        $orderFind=$orderFind::find($orderId);
        
        $orderFind->order_status=0;

        $allOrders=orderHistory::where('order_id', $id)->get()->toArray();

        foreach($allOrders as $key=>$orderDetails){
            $catalog_id=catalog::select('id')->where('name',$orderDetails['item_name'])->get()->toArray()[0]['id'];
            $catalogQuantity=catalogQuantity::where('catalog_id', $catalog_id)->get()->toArray()[0];
            
            catalogQuantity::where('catalog_id', $catalog_id)->update(['quantity' => $catalogQuantity['quantity'] + $orderDetails['item_quantity']]);
            $quantityRemark->firstOrCreate(['quantity_id' => $catalogQuantity['id'], 'modified_quantity' => $orderDetails['item_quantity'], 'input_type' => 2, 'remarks' => auth::user()->name . " cancelled order no " . $id, 'user' => auth::user()->name]);
        }
        // dd($quantityRemark->all()->toArray());
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
