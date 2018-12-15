{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.dashboard')

@section('title', '| orderHistory')

@section('content')
<script src="{{asset('js/export_csv.js')}}"></script>
<script>
    var orderData={!! isset($exportData) ? "$exportData": "" !!};
    
    function exportData(){
        downloadCSV('Order_history.csv', orderData);
    }
</script>
<div class="col-lg-10 col-lg-offset-1 mx-auto py-5">
<input id="export" type="button" onClick="exportData()" value="Export" class="btn btn-primary pull-left"/>
    <hr>
    <form method='GET' action="{{route('orderHistory.index')}}">
        <div class="row">
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
    <div class="table-responsive panel-table">
        <table class="table table-striped table-active table-hover">

            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Token No</th>
                    <th>Order Date & Time</th>
                    <th>Customer Type</th>
                    <th>Seller Name</th>
                    <th>Total Order Amount</th>
                    <th>Total Discount</th>
                    <th>Final Amount</th>
                    <th>Order Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orderData as $orderDetail)
                <tr>

                    <td>{{ $orderDetail->order_id }}</td>
                    <td>{{ $orderDetail->token_no }}</td>
                    <td>{{ $orderDetail->order_time}}</td>
                    <td>{{ $orderDetail->customer_type }}</td>
                    <td>{{ $orderDetail->seller_name }}</td>
                    <td>{{ $orderDetail->subtotal }}</td>
                    <td>{{ $orderDetail->Total_discount }}</td>
                    <td>{{ $orderDetail->total_price }}</td>
                    @if($orderDetail->order_status)
                        <td>Processed</td>
                        <td>
                            <!-- <a href="{{ route('orderHistory.edit', $orderDetail->order_id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a> -->
                            <a href="{{ route('orderHistory.show', $orderDetail->order_id) }}" class="btn btn-info pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Details</a>
                            <a href="{{ route('orderHistory.edit', $orderDetail->order_id) }}" class="btn btn-danger pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Cancel Order</a>
                        </td>
                        @else
                        <td class="btn btn-danger disabled">Cancelled</td>
                        <td>
                            <!-- <a href="{{ route('orderHistory.edit', $orderDetail->order_id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a> -->
                            <a href="{{ route('orderHistory.show', $orderDetail->order_id) }}" class="btn btn-info pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Details</a>
                            <a class="btn btn-danger disabled pull-left" style="margin-right: 3px; width: 10rem; margin-bottom: 5px;">Cancel Order</a>
                        </td>
                    @endif
                    
                </tr>
                @endforeach
            </tbody>
            <?php if($orderData){echo $orderData->render(); }?>
        </table>
    </div>
</div>
@endsection