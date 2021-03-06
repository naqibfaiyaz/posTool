{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.dashboard')

@section('title', '| orderHistory')

@section('content')

<div class="col-lg-10 col-lg-offset-1 mx-auto">
    <hr>
    <div class="table-responsive panel-table" style="margin-bottom: 5rem;">
    <a href="{{ route('catalog.create') }}" class="btn btn-primary pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Create Product</a>
    @if($filter_type=='inventory_only')
        <a href="{{ route('catalogView', 'filter_type=inventory_only') }}" class="btn btn-primary pull-left disabled" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Show Inventory</a>
    @else
    <a href="{{ route('catalogView', 'filter_type=inventory_only') }}" class="btn btn-primary pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Show Inventory</a>
    @endif

    @if($filter_type=='products_only')
        <a href="{{ route('catalogView', 'filter_type=products_only') }}" class="btn btn-primary pull-left disabled" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Show Products</a>
    @else
        <a href="{{ route('catalogView', 'filter_type=products_only') }}" class="btn btn-primary pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Show Products</a>
    @endif

    @if($filter_type=='all')
        <a href="{{ route('catalogView', 'filter_type=all') }}" class="btn btn-primary pull-left disabled" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Show All</a>
    @else
        <a href="{{ route('catalogView', 'filter_type=all') }}" class="btn btn-primary pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Show All</a>
    @endif
        <table class="table table-striped table-active table-hover">

            <thead>
                <tr>
                    <th>Inventory #</th>
                    <th>Category Name</th>
                    <th>Item Name</th>
                    <th>Item Quantity</th>
                    <th>Add Quantity</th>
                    <th>Item Price</th>
                    <th>Item Status</th>
                    @if($filter_type!='inventory_only')
                        <th>Discount Status</th>
                    @endif   
                    <th>Item Image</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($inventory as $inventoryData)
                <tr>

                    <td>{{ $inventoryData['id'] }}</td>
                    <td>{{ $inventoryData['category']}}</td>
                    <td>{{ $inventoryData['name'] }}</td>
                    <td>{{ $inventoryData['quantity'] }}</td>
                    <td><form action="{{ route('updateQuantity', $inventoryData['id']) }}" role="form" method="POST" enctype="application/x-www-form-urlencoded">
                            @method('PUT')
                            @csrf
                            <div class="input-group mb-3 input-group-sm">
                                <input style="width: 3rem;" type="number" min=0 class="form-control" name="addQuantity" value=0 placeholder="Quantity">
                                <input name="currentQuantity" hidden value="{{ $inventoryData['quantity'] }}">
                                <div class="input-group-append">
                                    <input class="btn btn-success" type="submit" value="+"/>  
                                </div>
                            </div>
                        </form></td>
                    <td>{{ $inventoryData['price'] }}</td>
                    @if($inventoryData['status'])
                        <td >Active</td>
                    @else
                        <td ><span class="badge badge-pill badge-danger" style="width: 7rem; font-size: 14px; font-weight: normal;">Discontinued</span></td>
                    @endif
                    @if($filter_type!='inventory_only')
                        @if($inventoryData['discount_status'])
                        <td ><span class="badge badge-pill badge-success" style="width: 7rem; font-size: 14px; font-weight: normal;">Available</span></td>
                        @else
                        <td ><span class="badge badge-pill badge-danger" style="width: 7rem; font-size: 14px; font-weight: normal;">Not Available</span></td>
                        @endif
                    @endif
                    <td><img src="{{ asset('images/catalog/') .'/'.$inventoryData['image'] }}" width=50 height=50 alt="CMPM" class="img-circle"/></td>
                    <td>
                        <a href="{{ route('catalog.edit', $inventoryData['id']) }}" class="btn btn-primary pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Edit</a>
                        <form action="{{ route('changeStatus', $inventoryData['id']) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <button class="btn btn-primary pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Change Status</button>
                        </form>
                        <form action="{{ route('changeDiscount', $inventoryData['id']) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <button class="btn btn-primary pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Change Discount</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
        <?php if($inventory){echo $inventory->render(); }?>
    </div>

</div>

@endsection