{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.dashboard')

@section('title', '| orderHistory')

@section('content')

<div class="col-lg-10 col-lg-offset-1 mx-auto">
    <hr>
    <form method='GET' action="{{route('InvRemarks')}}">
        <div class="row">
            <div class="form-group col-lg-2">
                <label for="category_filter">Category Name:</label>
                <select class="form-control" name="category_filter">
                    <option value="all">All</option>
                    @foreach($catalogCategory as $item)
                        @if($categoryFilter==$item['name'])
                            <option value="{{$item['name']}}" selected>{{$item['name']}}</option>
                        @else
                            <option value="{{$item['name']}}">{{$item['name']}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="item_filter">Item Name:</label>
                <select class="form-control" name="item_filter">
                    <option value="all" selected>All</option>
                    @foreach($catalog as $item)
                        @if($itemFilter==$item['name'])
                            <option value="{{$item['name']}}" selected>{{$item['name']}}</option>
                        @else
                            <option value="{{$item['name']}}">{{$item['name']}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="modify_Type">Item Name:</label>
                <select class="form-control" name="modify_Type">
                    <option value="all" selected>All</option>
                    @if($modifyFilter==1)
                        <option value="1" selected>Add Item</option>
                    @else
                        <option value="1">Add Item</option>
                    @endif
                    @if($modifyFilter==0)
                        <option value="0" selected>Remove Item</option>
                    @else
                        <option value="0">Remove Item</option>
                    @endif
                    @if($modifyFilter==2)
                        <option value="2" selected>Cancelled Item</option>
                    @else
                        <option value="2">Cancelled Item</option>
                    @endif
                    @if($modifyFilter==3)
                        <option value="3" selected>Transational Item</option>
                    @else
                        <option value="3">Transational Item</option>
                    @endif
                </select>
            </div>
            <div class="form-group col-lg-1">
                <label for="category_id"></label>
                <button id="submitNewInv" class= 'form-control btn btn-primary'>Submit</button>
            </div>
        </div>
    </form>
    <div class="table-responsive panel-table" style="margin-bottom: 5rem;">
        <table class="table table-striped table-active table-hover">

            <thead>
                <tr>
                    <th>Remark #</th>
                    <th>Category Name</th>
                    <th>Item Name</th>
                    <th>Modified Quantity</th>
                    <th>Remarks</th>
                    <th>User</th>
                    <th>Modification Time</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($allRemarks as $key => $remarksData)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $remarksData['category_name']}}</td>
                    <td>{{ $remarksData['item_name'] }}</td>
                    <td>{{ $remarksData['modified_quantity'] }}</td>
                    <td>{{ $remarksData['remarks'] }}</td>
                    <td>{{ $remarksData['user'] }}</td>
                    <td>{{ $remarksData['created_at'] }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>

@endsection