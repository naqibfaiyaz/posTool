{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.dashboard')

@section('title', '| orderHistory')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <hr>
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
                    <td>
                    <a href="{{ url('/OrderHistory/edit/', $orderDetail->order_id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                    <a href="{{ url('/OrderHistory/edit/', $orderDetail->order_id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Details</a>
                    <a href="{{ url('/OrderHistory/edit/', $orderDetail->order_id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Reprint</a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>

@endsection