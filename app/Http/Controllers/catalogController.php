<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\orderHistory;
use App\catalog;
use App\catalogCategory;
use App\catalogQuantity;
use App\quantityRemark;
use App\orderToken;
use App\orderSumHistory;
use Carbon\Carbon;
use Auth;

class catalogController extends Controller
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

    public function index()
    {
        $catalog=catalog::all()->toArray();
        $catalogQuantity=catalogQuantity::all();
        $catalogCategory=catalogCategory::all();
        foreach($catalog as $key=>$catalogData){
            $inventory[$key]=$catalogData;
            $inventory[$key]['category']=$catalogCategory->where('id', $catalogData['category_id'])->pluck('name')[0];
            $inventory[$key]['quantity']=$catalogQuantity->where('catalog_id', $catalogData['id'])->pluck('quantity')[0];
            $inventory[$key]['remarks']=$catalogQuantity->where('catalog_id', $catalogData['id'])->pluck('remarks')[0];
        }

        return view('inventory.index')->with(compact('inventory', $inventory));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $catalogCategory=catalogCategory::all();

        return view('inventory.create')->with(compact('catalogCategory', $catalogCategory));
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
            $remaining_quantity=$orderHistoryData['remaining_quantity'];
            unset($orderHistoryData['subtotal']);
            unset($orderHistoryData['discount']);
            unset($orderHistoryData['total_price']);
            unset($orderHistoryData['catalog_id']);
            unset($orderHistoryData['current_quantity']);
            unset($orderHistoryData['seller_name']);
            unset($orderHistoryData['customer_type']);
            unset($orderHistoryData['order_time']);
            unset($orderHistoryData['cash_tendered']);
            unset($orderHistoryData['change_due']);
            unset($orderHistoryData['remaining_quantity']);

            orderHistory::insert([$orderHistoryData]);
            $catalog_id=$data["catalog_id"];
            catalogQuantity::where('catalog_id', $catalog_id)->update(['quantity' => $remaining_quantity]);
        }

        $orderToken->curr_token_no=$request->all()['data'][0]["token_no"];
        $orderToken->token_date=Carbon::now('Asia/Dhaka')->format('Y-m-d');
        $orderToken->save();

        $orderSumHistory->order_id=$request->all()['data'][0]["order_id"];
        $orderSumHistory->token_no=$request->all()['data'][0]["token_no"];
        $orderSumHistory->subtotal=$request->all()['data'][0]["subtotal"];
        $orderSumHistory->order_time=$request->all()['data'][0]["order_time"];
        $orderSumHistory->seller_name=$request->all()['data'][0]["seller_name"];
        $orderSumHistory->customer_type=$request->all()['data'][0]["customer_type"];
        $orderSumHistory->Total_discount=$request->all()['data'][0]["discount"];
        $orderSumHistory->total_price=$request->all()['data'][0]["total_price"];
        $orderSumHistory->cash_tendered=$request->all()['data'][0]["cash_tendered"];
        $orderSumHistory->change_due=$request->all()['data'][0]["change_due"];
        $orderSumHistory->order_status=1;
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
        $catalog=catalog::where('id', $id)->get()->toArray();
        $catalogQuantity=catalogQuantity::all();
        $catalogCategory=catalogCategory::all();
        
        $inventory=$catalog[0];
        $inventory['category_name']=$catalogCategory->where('id', $catalog[0]['category_id'])->pluck('name')[0];
        $inventory['quantity']=$catalogQuantity->where('catalog_id', $catalog[0]['id'])->pluck('quantity')[0];
        $inventory['remarks']=$catalogQuantity->where('catalog_id', $catalog[0]['id'])->pluck('remarks')[0];

        return view('inventory.edit')->with(compact('inventory', $inventory))->with(compact('catalogCategory', $catalogCategory));
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
        $catalog=new catalog;
        $catalogQuantity= new catalogQuantity;
        $quantityRemark=new quantityRemark;
        $catalog=$catalog::find($id);
        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'price' => 'required|numeric',
            'discount_status' => 'required|numeric',
        ]);
        
        if ($request->hasFile('image')) {
            $path = $request->image->move(public_path('/images/catalog'), $request->image->getClientOriginalName());
            $catalog->image=$request->image->getClientOriginalName();
        }

        $catalog->category_id=$request->all()['category_id'];
        $catalog->name=$request->all()['name'];
        $catalog->price=$request->all()['price'];
        $catalog->discount_status=$request->all()['discount_status'];
        $catalog->status=1;
        
        $catalog->save();
        
        $catalogQuantityData=$catalogQuantity::where('catalog_id', $id)->get()->toArray();
        
        if($catalogQuantityData){
            $catalogQuantityid=$catalogQuantityData[0]['id'];
            $catalogQuantity=$catalogQuantity::find($catalogQuantityid);
        }
        $newQuantity=$catalogQuantityData[0]['quantity']-(int)$request->all()['quantity'];

        if($newQuantity>=0){
            $catalogQuantity->quantity=$newQuantity;
            $catalogQuantity->save();
        }else{
            return back()->withInput()->with('error_message','Inventory quantity cannot be less than zero');
        }

        if((int)$request->all()['quantity']>0){
            $quantityRemark->quantity_id=$catalogQuantityid;
            $quantityRemark->quantity_reduced=(int)$request->all()['quantity'];
            $quantityRemark->remarks=$request->all()['remarks'];
            $quantityRemark->user=auth::user()->name;
            $quantityRemark->save();
        }

        return redirect('catalog');
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

    public function getCurrentToken(){
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

    public function changeStatus($id){
        $catalogDetails=new catalog;

        $catalogDetails=$catalogDetails::find($id);
        if($catalogDetails->status){
            $catalogDetails->status=0;
        }else{
            $catalogDetails->status=1;
        }

        $catalogDetails->save();

        return redirect('catalog');
    }

    public function changeDiscount($id){
        $catalogDetails=new catalog;

        $catalogDetails=$catalogDetails::find($id);
        if($catalogDetails->discount_status){
            $catalogDetails->discount_status=0;
        }else{
            $catalogDetails->discount_status=1;
        }

        $catalogDetails->save();

        return redirect('catalog');
    }

    public function updateQuantity(Request $request, $id){
        $catalogQuantity=new catalogQuantity;
        $quantityId=$catalogQuantity::select('id')->where('catalog_id', $id)->get('id')->toArray()[0]['id'];
        
        $catalogQuantity=$catalogQuantity::find($quantityId);
        if($catalogQuantity->quantity>=0){
            $catalogQuantity->quantity=(int)$request->only('currentQuantity')['currentQuantity'];
        }else{
            $catalogQuantity->quantity=0;
        }

        $catalogQuantity->save();

        return redirect('catalog');
    }

    public function newInventory(Request $request){
        $catalog=new catalog;
        $catalogQuantity= new catalogQuantity;
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'price' => 'required|numeric',
        ]);
        
        if ($request->hasFile('image')) {
            $path = $request->image->move(public_path('/images/catalog'), $request->image->getClientOriginalName());
            $catalog->image=$request->image->getClientOriginalName();
        }

        $catalog->category_id=$request->all()['category_id'];
        $catalog->name=$request->all()['name'];
        $catalog->price=$request->all()['price'];
        $catalog->discount_status=$request->all()['discount_status'];
        $catalog->status=1;
        
        $catalog->save();
        
        $catalogQuantityData=$catalogQuantity::select('id')->where('catalog_id', $catalog->id)->get('id')->toArray();
        $catalogQuantity->quantity=$request->all()['quantity'];
        $catalogQuantity->catalog_id=$catalog->id;

        if($catalogQuantityData){
            $catalogQuantityid=$catalogQuantityData[0]['id'];
            $catalogQuantity=$catalogQuantity::find($catalogQuantityid);
        }
        $catalogQuantity->save();

        return redirect('catalog');
    }
}
