{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.dashboard')

@section('title', '| orderHistory')

@section('content')
<a href="{{ route('orderHistory.index') }}" class="btn btn-info" style="margin: 50px; color: white;"><i class="fas fa-arrow-left"> Back</i></a>
<div class="col-lg-10 col-lg-offset-1 mx-auto">
    <hr>
    <div class="row py-3" style="margin:auto;">
        <div class="col">
            <span>Order # </span><span> {{ $orderSumHistory[0]->order_id }} </span>
        </div>
        <div class="col">
            <span>Customer Type : </span><span> {{ $orderSumHistory[0]->customer_type }} </span>
        </div>
        <div class="col">
            <span>Seller Name : </span><span> {{ $orderSumHistory[0]->seller_name }} </span>
        </div>
    </div>
    <div class="row py-1"  style="margin:auto;">
        <div class="col">
            <span>Token # </span><span> {{ $orderSumHistory[0]->token_no }} </span>
        </div>
        <div class="col">
            <span>Order Date & Time : </span><span> {{ $orderSumHistory[0]->order_time }} </span>
        </div>
        <div class="col">
           
        </div>
    </div>
    <div class="table-responsive panel-table">
        <table class="table table-striped table-active table-hover">

            <thead>
                <tr>
                    <th>Item #</th>
                    <th>Item Name</th>
                    <th>Item Quantity</th>
                    <th>Item Price</th>
                    <th>Item Discount</th>
                    <th>Price after Discount</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orderDataDetails as $key=>$orderDetail)
                <tr>

                    <td>{{ $key+1 }}</td>
                    <td>{{ $orderDetail->item_name }}</td>
                    <td>{{ $orderDetail->item_quantity }}</td>
                    <td>{{ $orderDetail->item_price }}</td>
                    <td>{{ $orderDetail->item_discount }}</td>
                    <td>{{ $orderDetail->item_price - $orderDetail->item_discount }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>{{ $orderSumHistory[0]->subtotal }}</td>
                    <td>{{ $orderSumHistory[0]->Total_discount }}</td>
                    <td>{{ $orderSumHistory[0]->total_price }}</td>
                </tr>
            </tbody>

        </table>
    </div>
</div>

        <a href="{{ route('orderHistory.edit', $orderDetail->order_id) }}" class="btn btn-success float-right" style="margin: 50px; color: white;">Reprint</a>
@endsection