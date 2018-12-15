{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.dashboard')

@section('title', '| Add User')

@section('content')
<script src="{{ asset('js/catalogEntry.js') }}" type="text/javascript" type="text/javascript"></script>
<div class='col-lg-10 col-lg-offset-1 mx-auto py-4'>

    <h1><i class='fa fa-user-plus'></i> Add Inventory Item</h1>
    <hr>

    <form method='POST' action="{{route('newInventory')}}" enctype="multipart/form-data">
    <div class='row'>
        <div class='col'>
            <div class='row'>
                <div class='col'>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="category_id">Inventory Name:</label>
                        <select class="form-control" name="category_id">
                            @foreach($catalogCategory as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                            <option value="AddNew">Add New Category</option>
                        </select>
                    </div>
                </div>
                <div class='col'>
                    <div class="form-group">
                        <label for="newCategory">Item Name:</label>
                        <input type="text" class="form-control" id="newCategory" name="newCategory"  placeholder="Enter Category Name. e.g. Burger, drinks">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="name">Item Name:</label>
                <input type="text" class="form-control" id="name" name="name"  placeholder="Enter the item Name. eg. Cheese Burger">
            </div>
            <div class="form-group">
                <div class="upload-image-preview"></div>
                <label for="image">Image:</label>
                <input class="form-control" name="image" type="file" id="image">
            </div>
        </div>
        <div class='col'>
            <div class="form-group">
                <label for="quantity">Item Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter current quantity">
            </div>
            <div class="form-group">
                <label for="price">Item Price:</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Enter the price">
            </div>
            <div class="form-group">
                <label for="discount_status">Discount:</label>
                <select class="form-control" name="discount_status">
                    <option value=1>Available</option>
                    <option value=0>Not Available</option>
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