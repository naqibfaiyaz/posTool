{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.dashboard')

@section('title', '| Add User')

@section('content')
<script src="{{ asset('js/catalogEntry.js') }}" type="text/javascript" type="text/javascript"></script>
<div class='col-lg-10 col-lg-offset-1 mx-auto py-4'>

    <h1><i class='fa fa-user-plus'></i> Add Inventory Item</h1>

    <form method='POST' action="{{route('catalog.update', $inventory['id'])}}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class='row'>
            <div class='col'>
                <div class="form-group">
                    <label for="category_id">Category Name:</label>
                    <select class="form-control" name="category_id">
                        @foreach($catalogCategory as $item)
                            @if($item->name==$inventory['category_name'])
                                <option value="{{$item->id}}" selected>{{$item->name}}</option>
                            @else
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Item Name:</label>
                    <input type="text" class="form-control" id="name" name="name"  placeholder="Enter the item Name. eg. Cheese Burger" value="{{$inventory['name']}}">
                </div>
                <div class="form-group">
                    <div class="upload-image-preview"><img src="{{ asset('images/catalog/' . $inventory['image']) }}" height= "100px" width="100px"></div>
                    <label for="image">Image:</label>
                    <input class="form-control" name="image" type="file" id="image" value="{{$inventory['image']}}">
                </div>
            </div>

            <div class='col'>
                <div class="form-group">
                    <label for="quantity">Decrease Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter how much to decrease" value=0>
                </div>
                <div class="form-group">
                    <label for="remarks">Remarks:</label>
                    <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks">
                </div>
                <div class="form-group">
                    <label for="price">Item Price:</label>
                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter the price" value="{{$inventory['price']}}">
                </div>
                <div class="form-group">
                    <label for="discount_status">Discount:</label>
                    <select class="form-control" name="discount_status">
                        @if($inventory['discount_status']==1)
                            <option value=1 selected>Available</option>
                        @else
                            <option value=1>Available</option>
                        @endif

                        @if($inventory['discount_status']==0)
                            <option value=0 selected>Not Available</option>
                        @else
                            <option value=0>Not Available</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-8 py-5">
            <button id="submitNewInv" class= 'btn btn-primary'>Submit</button>
        </div>
    </form>
</div>
@endsection