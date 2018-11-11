<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\orderHistory;
use App\catalog;
use App\orderToken;
use App\orderSumHistory;
use App\catalogQuantity;
use Carbon\Carbon;

class catalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today=Carbon::now('Asia/Dhaka')->format('Y-m-d');
        
        $token = orderToken::select('curr_token_no')->where('token_date', $today)->orderBy('curr_token_no', 'desc')->first();
        if($token==null){
            $token=1;
        }else{
            $token=$token->curr_token_no;
            
            $token++;
        }

        return collect(["token"=> $token]);
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
        $orderToken=new orderToken;
        $orderSumHistory=new orderSumHistory;
        $allRequest=$request->all()['data'];

        foreach($allRequest as $key=>$data){
            $orderHistoryData = $data;
            unset($orderHistoryData['subtotal']);
            unset($orderHistoryData['discount']);
            unset($orderHistoryData['total_price']);
            unset($orderHistoryData['catalog_id']);
            unset($orderHistoryData['current_quantity']);
            unset($orderHistoryData['seller_name']);
            unset($orderHistoryData['customer_type']);
            unset($orderHistoryData['order_time']);

            orderHistory::insert([$orderHistoryData]);

            $catalog_id=$data["catalog_id"];
            $remaining_quantity=$data["current_quantity"]-$data["item_quantity"];
         
            catalogQuantity::where('catalog_id', $catalog_id)->update(['quantity' => $remaining_quantity]);
        }

        $orderToken->curr_token_no=$request->all()['data'][0]["token_no"];
        $orderToken->token_date=$today=Carbon::now('Asia/Dhaka')->format('Y-m-d');
        $orderToken->save();

        $orderSumHistory->order_id=$request->all()['data'][0]["order_id"];
        $orderSumHistory->token_no=$request->all()['data'][0]["token_no"];
        $orderSumHistory->subtotal=$request->all()['data'][0]["subtotal"];
        $orderSumHistory->order_time=$request->all()['data'][0]["order_time"];
        $orderSumHistory->seller_name=$request->all()['data'][0]["seller_name"];
        $orderSumHistory->customer_type=$request->all()['data'][0]["customer_type"];
        $orderSumHistory->Total_discount=$request->all()['data'][0]["discount"];
        $orderSumHistory->total_price=$request->all()['data'][0]["total_price"];
        $orderSumHistory->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
