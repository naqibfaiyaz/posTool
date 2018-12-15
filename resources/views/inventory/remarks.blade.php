{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.dashboard')

@section('title', '| orderHistory')

@section('content')
<script src="{{asset('js/export_csv.js')}}"></script>
<script>
    var orderData={!! isset($exportData) ? "$exportData": "" !!};
    
    function exportData(){
        downloadCSV('Quantity_handling.csv', orderData);
    }
</script>
<div class="col-lg-10 col-lg-offset-1 mx-auto py-5">
<input id="export" type="button" onClick="exportData()" value="Export" class="btn btn-primary pull-left"/>
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
                    @if($modifyFilter=="all")
                        <option value="all" selected>All</option>
                        <option value="1">Add Item</option>
                        <option value="0">Remove Item</option>
                        <option value="2">Cancelled Item</option>
                        <option value="3">Transational Item</option>
                    @elseif($modifyFilter==1)
                        <option value="1" selected>Add Item</option>
                        <option value="all">All</option>
                        <option value="0">Remove Item</option>
                        <option value="2">Cancelled Item</option>
                        <option value="3">Transational Item</option>
                    @elseif($modifyFilter==0)
                        <option value="0" selected>Remove Item</option>
                        <option value="all">All</option>
                        <option value="1">Add Item</option>
                        <option value="2">Cancelled Item</option>
                        <option value="3">Transational Item</option>
                    @elseif($modifyFilter==2)
                        <option value="2" selected>Cancelled Item</option>
                        <option value="all">All</option>
                        <option value="1">Add Item</option>
                        <option value="0">Remove Item</option>
                        <option value="3">Transational Item</option>
                    @elseif($modifyFilter==3)
                        <option value="3" selected>Transational Item</option>
                        <option value="all">All</option>
                        <option value="1">Add Item</option>
                        <option value="0">Remove Item</option>
                        <option value="2">Cancelled Item</option>
                    @endif
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="StartDateFilter">Start Date:</label>
                <input type="date" name="StartDateFilter" class="form-control" value="{{$StartDate}}">
            </div>
            <div class="form-group col-lg-2">
                <label for="EndDateFilter">End Date:</label>
                <input type="date" name="EndDateFilter" class="form-control" value="{{$EndDate}}">
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
            <?php if($allRemarks){echo $allRemarks->render(); }?>
        </table>
    </div>

</div>

@endsection