@extends('layouts.dashboard')

@section('content')
<script>
  var user = {!! auth()->user()->toJson() !!};
  localStorage.clear();
  var catalog_list={!! isset($catalog) ? "$catalog": "" !!};
  var category_list={!! isset($category) ? "$category": "" !!};
  var quantity_list={!! isset($quantity) ? "$quantity": "" !!};
  localStorage.setItem("catalog", JSON.stringify(catalog_list));
  localStorage.setItem("category", JSON.stringify(category_list));
  localStorage.setItem("quantity", JSON.stringify(quantity_list));
</script>

<script src="{{ asset('js/calculator.js') }}" type="text/javascript" type="text/javascript"></script>
<script src="{{ asset('js/catalogEntry.js') }}" type="text/javascript" type="text/javascript"></script>
<div class="container" id="app">
<iframe name="iframe_a" id="ifrmPrint" style="display: none;"></iframe>
    <div class="row card-background-color">
        <div class="col col-lg-6 min-width-small-device" style="height: 95%;">
            <div class="row panel panel-primary list-group-item front-row-blue"  id='orderList'>
                <div class="dropdown">
                    <button class="btn btn-primary customerType" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>Table </span>  <i class="fas fa-angle-down px-1" style="color: white;"></i>
                    </button>
                    <button class="btn btn-primary float-right" type="button" id="clearAll">
                         <i class="fas fa-trash px-1"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Table</a>
                        <a class="dropdown-item" href="#">Percel</a>
                        <a class="dropdown-item" href="#">Pathao</a>
                        <a class="dropdown-item" href="#">Food Panda</a>
                    </div>
                </div>
            </div>
            <div class="row orderListSection front-row"  id='orderListBox' style="min-height: 25rem;">
                <h1 class="text-muted" style="margin: auto; text-align: center;" id="noOrderText">Order is Empty<br/> Please add</h1>
                <div id="appendBox" class="w-100"></div>
            </div>
            <div>
                <div class="row orderListBottomSection front-row" id='subTotal'>
                    <div class="col col-lg-6">Subtotal</div>
                    <div class="col col-lg-6"><div class="float-right">৳<span>0.00</span></div></div>
                </div>
                <div class="row orderListBottomSection front-row" id='DiscountAmount'>
                    <div class="col col-lg-6">Discount</div>
                    <div class="col col-lg-6"><div class="float-right">৳<span>0.00</span></div></div>
                </div>
                <div class="row grandTotalDiv front-row" id='totalAmount'>
                    <div class="col col-lg-6 grandTotal">Total</div>
                    <div class="col col-lg-6"><div class="float-right">৳<span>0.00</span></div></div>
                </div>
                <div class="row front-row auto-margin" id='cashButtonDiv'>
                    <div class="col col-lg-6 paymentSection disabled_div" id="cardButton">Card</div>
                    <div class="col col-lg-6 paymentSection disabled_div" id="cashButton">Cash</div>
                </div >
                <div class="row front-row auto-margin" id='discountButtonDiv'>
                    <div class="col col-lg-6 bottomSection disabled_div" id="notesButton">Notes</div>
                    <div class="col col-lg-6 bottomSection disabled_div" id="discountButton">Discount</div>
                </div>
            </div>
        </div>
        <div class="col col-lg-6 min-width-small-device h-100">
            <div class="panel panel-primary" id='catalog'>
                @if(isset($category))
                    @foreach ($category as $categorykey => $categoryitem)
                        <a href="#" class="list-group-item front-row" style="padding-bottom: 20px;">
                            <span class="text-muted small">
                                <h4>{{ $categoryitem['name'] }}</h4>
                            </span>
                        </a>
                        <div class="row">
                        @foreach ($catalog as $catalogkey => $catalogitem)
                            @if($catalogitem['category_id']==$categoryitem['id'])
                                @if($catalogitem['show_as_product'])
                                    <div class="col col-lg-3">
                                        <div class="panel panel-primary items">
                                            <a id="catalog_{{ $catalogitem['id'] }}" class="catalog_id" href="#">
                                                <div class="panel-heading">
                                                    <div class="row justify-content-center font-color-black">{{ $catalogitem['name'] }}</div>
                                                    <div class="row justify-content-center"><img src="{{ asset('images/catalog/') .'/'.$catalogitem['image'] }}" style="height: 4rem;" alt="{{ $catalogitem['image'] }}"/></div>
                                                </div>
                                                <div class="panel-footer">
                                                    <span class="row justify-content-center font-color-black">৳{{ $catalogitem['price'] }}</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
            <div id="popupnav" class="row panel panel-primary list-group-item front-row-blue" style="display:none;">
            <button id="backButton" class="btn btn-primary" type="button">
                <i class="fas fa-arrow-left px-2"></i>Back
            </button>
            </div>
            <div id="popupWindow" style="display:none;">
                <div class="row mx-auto justify-content-center" id="afterProcess">
                </div>
                <div id="calculator" style="max-width: 100%;">
                    <!-- Screen and clear key -->
                    <div class="top">
                        <span class="clear w-25">C</span>
                        <input class="screen"/>
                    </div>
                    
                    <div class="keys">
                        <!-- operators and other keys -->
                        <div class="row mx-auto justify-content-center">
                            <div class="col px-0 mx-auto"><span class="w-100 h-100">7</span></div>
                            <div class="col px-0 mx-auto"><span class="w-100 h-100">8</span></div>
                            <div class="col px-0 mx-auto"><span class="w-100 h-100">9</span></div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col px-0"><span class="w-100 h-100">4</span></div>
                            <div class="col px-0"><span class="w-100 h-100">5</span></div>
                            <div class="col px-0"><span class="w-100 h-100">6</span></div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col px-0"><span class="w-100 h-100">1</span></div>
                            <div class="col px-0"><span class="w-100 h-100">2</span></div>
                            <div class="col px-0"><span class="w-100 h-100">3</span></div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col px-0"><span class="w-100 h-100">.</span></div>
                            <div class="col px-0"><span class="w-100 h-100">0</span></div>
                            <div class="col px-0"><span class="w-100 h-100"></span></div>
                        </div>
                    </div>
                </div>
                <div class="row mx-auto justify-content-center"><button class="btn btn-primary w-50 processOrder">Process</button></div>
                <div class="row mx-auto justify-content-center"><button class="btn btn-primary w-50 finalProcess" style="display: none;">Print</button></div>
            </div>
            <div id="popupWindowDiscount" style="display:none;">
                <div id="discountTable">
                    <!-- Screen and clear key -->
                    <div class="top">
                        <input class="screen w-60"/>
                        <div>
                            <span class="px-2 align-middle">৳</span>
                                <label class="switch">
                                    <input id="discountToggle" type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            <span class="px-2 align-middle">%</span>
                        </div>
                    </div>
                    <div class="top">
                        <button class="btn btn-primary applyDiscount float-right w-100" id="ApplyDiscount">Apply</button>
                    </div>
                    
                    <div class="keys">
                        <!-- operators and other keys -->
                        <div class="row mx-auto justify-content-center">
                            <div class="col px-0 mx-auto"><span class="w-100 h-100">5%</span></div>
                            <div class="col px-0 mx-auto"><span class="w-100 h-100">10%</span></div>
                            <div class="col px-0 mx-auto"><span class="w-100 h-100">15%</span></div>
                            <div class="col px-0 mx-auto"><span class="w-100 h-100">20%</span></div>
                            <div class="col px-0 mx-auto"><span class="w-100 h-100">25%</span></div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col px-0"><span class="w-100 h-100">30%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">35%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">40%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">45%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">50%</span></div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col px-0"><span class="w-100 h-100">55%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">60%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">65%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">70%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">75%</span></div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col px-0"><span class="w-100 h-100">80%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">85%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">90%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">95%</span></div>
                            <div class="col px-0"><span class="w-100 h-100">100%</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<div id="printSection" style="display: none;">
    <h4 style="text-align: center; margin:0; padding: 5px;">Preetom</h4>
    <h4 style="text-align: center; margin:0; padding: 5px;">Address</h4>
    <h4 style="text-align: center; margin:0; padding: 5px;">Phone</h4>
    <h4 style="text-align: center; margin:0; padding: 10px;" id="tokenPrint">Sale</h4>
    <h6 style="text-align: center; margin:0; padding: 10px;" id="copyName">Customer Copy</h6>
    <div>
    </div>
    <hr style="border-top: 1px solid rgba(0,0,0,.1);"/>
    <h4 style="text-align: center; padding: 0; margin: 0;">Thank You!<h4>
    <hr style="border-top: 1px solid rgba(0,0,0,.1);"/>
</div>
</div>
@endsection
