{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.dashboard')

@section('title', '| orderHistory')

@section('content')

<div class="col-lg-10 col-lg-offset-1 mx-auto">
    <hr>
    <form method='GET' action="{{route('OrderSumamry')}}">
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
    <div class="table-responsive panel-table" style="margin-bottom: 5rem;">
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
                @foreach ($allSumamryData as $key => $sumamryData)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $sumamryData->item_name }}</td>
                    <td>{{ $sumamryData->quantity }}</td>
                    <td>{{ $sumamryData->price }}</td>
                    <td>{{ $sumamryData->discount }}</td>
                    <td>{{ $sumamryData->price - $sumamryData->discount }}</td>
                </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td style="font-weight: bold;">Total</td>
                    <td style="font-weight: bold;">{{ $SummaryTotal['total_price'] }}</td>
                    <td style="font-weight: bold;">{{ $SummaryTotal['total_discount'] }}</td>
                    <td style="font-weight: bold;">{{ $SummaryTotal['total_price_after_discount'] }}</td>
                </tr>
            </tbody>

        </table>
    </div>

</div>

@endsection