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
        $inventory=[];
        foreach($catalog as $key=>$catalogData){
            $inventory[$key]=$catalogData;
            $inventory[$key]['category']=$catalogCategory->where('id', $catalogData['category_id'])->pluck('name')[0];
            $inventory[$key]['quantity']=$catalogQuantity->where('catalog_id', $catalogData['id'])->pluck('quantity')[0];
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
        $quantityRemark=new quantityRemark;
        $quantityId=$catalogQuantity::select('id')->where('catalog_id', $id)->get('id')->toArray()[0]['id'];
        
        $catalogQuantity=$catalogQuantity::find($quantityId);
        if($catalogQuantity->quantity>=0){
            $catalogQuantity->quantity=(int)$request->all()['currentQuantity'] + (int)$request->all()['addQuantity'];
        }else{
            $catalogQuantity->quantity=0;
        }

        $catalogQuantity->save();

        if((int)$request->all()['addQuantity']>0){
            $quantityRemark->quantity_id=$quantityId;
            $quantityRemark->modified_quantity=(int)$request->all()['addQuantity'];
            $quantityRemark->input_type=1;
            $quantityRemark->remarks=auth::user()->name . " added " . (int)$request->all()['addQuantity'] . " items";
            $quantityRemark->user=auth::user()->name;
            $quantityRemark->save();
        }

        return redirect('catalog');
    }

    public function newInventory(Request $request){
        $catalog=new catalog;
        $catalogQuantity= new catalogQuantity;
        
        request()->validate([
            'newCategory' => 'required if:category_id, AddNew',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'price' => 'required|numeric',
        ]);
        
        $category_id=$request->all()['category_id'];
        if($request->all()['category_id']=='AddNew'){
            $catalogCategory= new catalogCategory;
            $catalogCategory->name=$request->all()['newCategory'];

            $catalogCategory->save();

            $category_id=$catalogCategory->id;
        }
        
        if ($request->hasFile('image')) {
            $path = $request->image->move(public_path('/images/catalog'), $request->image->getClientOriginalName());
            $catalog->image=$request->image->getClientOriginalName();
        }

        $catalog->category_id=$category_id;
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

    public function InvRemarks(Request $request){
        $managerRole=$request->user()->authorizeRoles(['manager']);

        if($managerRole){
            if(isset($_GET['category_filter'])){
                $categoryFilter=$_GET['category_filter'];
            }else{
                $categoryFilter='all';
            }
            
            if(isset($_GET['item_filter'])){
                $itemFilter=$_GET['item_filter'];
            }else{
                $itemFilter='all';
            }
            
            if(isset($_GET['modify_Type'])){
                $modifyFilter=$_GET['modify_Type'];
            }else{
                $modifyFilter='all';
            }

            // dump($categoryFilter, $itemFilter);
            $catalog=new catalog;
            $catalogQuantity= new catalogQuantity;
            $catalogCategory=new catalogCategory;
            $quantityRemark=quantityRemark::all()->toArray();
            
            $allRemarks=collect();
            foreach($quantityRemark as $key=>$remarksData){
                $inventory=collect();
                if($remarksData['input_type']==1 || $remarksData['input_type']==2){
                    $remarksData['modified_quantity']='+' . $remarksData['modified_quantity'];
                }else{
                    $remarksData['modified_quantity']='-' . $remarksData['modified_quantity'];
                }
                $catalogQuantityRemarks=$catalogQuantity->where('id', $remarksData['quantity_id'])->get()->toArray()[0];
                $catalogRemarks=$catalog->where('id', $catalogQuantityRemarks['catalog_id'])->get()->toArray()[0];
                $catalogCategoryRemarks=$catalogCategory->where('id', $catalogRemarks['category_id'])->get()->toArray()[0];

                collect($remarksData)->each(function ($item, $key) use ($inventory){
                    $inventory->put($key,$item);
                });
                collect($catalogQuantityRemarks)->each(function ($item, $key) use ($inventory){
                    if($key!='id'){
                        $inventory->put($key,$item);
                    }
                });
                collect($catalogRemarks)->each(function ($item, $key) use ($inventory){
                    if($key=='name'){
                        $key='item_name';
                    }
                    if($key!='id'){
                        $inventory->put($key,$item);
                    }
                });
                collect($catalogCategoryRemarks)->each(function ($item, $key) use ($inventory){
                    if($key=='name'){
                        $key='category_name';
                    }
                    if($key!='id'){
                        $inventory->put($key,$item);
                    }
                });
                $allRemarks->push($inventory);
            }

            $allRemarks = $allRemarks->filter(function ($value, $key) use ($categoryFilter){
                if($categoryFilter!='all'){
                    return $value['category_name'] == $categoryFilter;
                }else{
                    return $value;
                }
            })->filter(function ($value, $key) use ($itemFilter){
                if($itemFilter!='all'){
                    return $value['item_name'] ==$itemFilter;
                }else{
                    return $value;
                }
            })->filter(function ($value, $key) use ($modifyFilter){
                if($modifyFilter!='all'){
                    return $value['input_type'] ==$modifyFilter;
                }else{
                    return $value;
                }
            })->sortBy(['item_name'])->values();
            $allRemarks=$allRemarks->values();
            $catalogCategory=$catalogCategory::all()->toArray();
            $catalog=$catalog::all()->toArray();

            return view('inventory.remarks')->with(compact('allRemarks', $allRemarks))->with(compact('catalogCategory', $catalogCategory))->with(compact('catalog', $catalog))->with(compact('categoryFilter', $categoryFilter))->with(compact('itemFilter', $itemFilter))->with(compact('modifyFilter', $modifyFilter));
        }else{
            abort(403, "Unauthorized");
        }
    }
}
