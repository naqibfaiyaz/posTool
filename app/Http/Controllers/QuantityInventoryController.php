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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

class QuantityInventoryController extends Controller
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
        $catalog=catalog::all();
        $catalogQuantity=catalogQuantity::all();
        $catalogCategory=catalogCategory::all();
        $inventory=collect([]);
        $inventory=$catalog->map(function($catalogData, $key) use($catalogCategory, $catalogQuantity){
            return [
                'id'=>$catalogData['id'],
                'name'=>$catalogData['name'],
                'category_id'=>$catalogData['category_id'],
                'image'=>$catalogData['image'],
                'price'=>$catalogData['price'],
                'status'=>$catalogData['status'],
                'discount_status'=>$catalogData['discount_status'],
                'category'=>$catalogCategory->where('id', $catalogData['category_id'])->pluck('name')[0],
                'quantity'=>$catalogQuantity->where('catalog_id', $catalogData['id'])->pluck('quantity')[0],
            ];
        });

        $page = Input::get('page', 1);
        $perPage = 20;

        $data = new LengthAwarePaginator(
            $inventory->forPage($page, $perPage), $inventory->count(), $perPage, $page
        );
        $data->setPath(url()->current());

        return view('inventory.index')->with('inventory', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $catalogCategory=catalogQuantity::all();

        return view('QuantityInventory.create')->with(compact('catalogCategory', $catalogCategory));
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
        $quantityRemark=new quantityRemark;
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
            unset($orderHistoryData['token_no']);

            orderHistory::insert([$orderHistoryData]);

            $catalog_id=$data["catalog_id"];
            catalogQuantity::where('catalog_id', $catalog_id)->update(['quantity' => $remaining_quantity]);

            $catalogQuantityId=catalogQuantity::select('id')->where('catalog_id', $catalog_id)->get()->toArray()[0]['id'];
            $modifiedQuantity=$data['current_quantity']-$data['remaining_quantity'];
            $CurrentOrderid=$data['order_id'];
            $quantityRemark->firstOrCreate(['quantity_id' => $catalogQuantityId, 'modified_quantity' => $modifiedQuantity, 'input_type' => 3, 'remarks' => auth::user()->name . " created an order. Order no " . $CurrentOrderid, 'user' => auth::user()->name]);
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


        return 1;
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
    public function edit($id, Request $request)
    {
        $managerRole=$request->user()->authorizeRoles(['manager']);

        if($managerRole){
            $catalog=catalog::where('id', $id)->get()->toArray();
            $catalogQuantity=catalogQuantity::all();
            $catalogCategory=catalogCategory::all();
            
            $inventory=$catalog[0];
            $inventory['category_name']=$catalogCategory->where('id', $catalog[0]['category_id'])->pluck('name')[0];
            $inventory['quantity']=$catalogQuantity->where('catalog_id', $catalog[0]['id'])->pluck('quantity')[0];
            $inventory['remarks']=$catalogQuantity->where('catalog_id', $catalog[0]['id'])->pluck('remarks')[0];

            return view('inventory.edit')->with(compact('inventory', $inventory))->with(compact('catalogCategory', $catalogCategory));
        }else{
            abort(403, "Unauthorized");
        }
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
            'removeQuantity' => 'required',
            'remarks' => 'required_unless:removeQuantity,0'
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
        $newQuantity=$catalogQuantityData[0]['quantity']-(int)$request->all()['removeQuantity'];

        if($newQuantity>=0){
            $catalogQuantity->quantity=$newQuantity;
            $catalogQuantity->save();
        }else{
            return back()->withInput()->with('error_message','Inventory quantity cannot be less than zero');
        }

        if((int)$request->all()['removeQuantity']>0){
            $quantityRemark->quantity_id=$catalogQuantityid;
            $quantityRemark->modified_quantity=(int)$request->all()['removeQuantity'];
            $quantityRemark->input_type=0;
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
}
