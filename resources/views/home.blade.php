@extends('layouts.dashboard')

@section('content')
<script>
  var user = {!! auth()->user()->toJson() !!};
  localStorage.setItem("catalog", JSON.stringify({!! $catalog ? "$catalog": "" !!}));
  localStorage.setItem("category", JSON.stringify({!! $category ? "$category" : "" !!}));
  localStorage.setItem("quantity", JSON.stringify({!! $quantity ? "$quantity" : "" !!}));
</script>
<script src="{{asset('js/jquery-1.12.4.js')}}"></script>
<script src="{{ asset('js/prefixfree.js') }}" type="text/javascript" type="text/javascript"></script>
<script>
        $(document).ready(function () {  
            $('#dismiss, .overlay').on('click', function () {
                $('#sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').addClass('active');
                $('.overlay').addClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });
        });
</script>
<script type="text/VBScript" language="VBScript">
        Sub Print()
               OLECMDID_PRINT = 6
               OLECMDEXECOPT_DONTPROMPTUSER = 2
               OLECMDEXECOPT_PROMPTUSER = 1
               call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
        End Sub
        document.write "<object ID='WB' WIDTH=0 HEIGHT=0 CLASSID='CLSID:8856F961-340A-11D0-A96B-00C04FD705A2'></object>"
</script>

<script src="{{ asset('js/calculator.js') }}" type="text/javascript" type="text/javascript"></script>
<script src="{{ asset('js/catalogEntry.js') }}" type="text/javascript" type="text/javascript"></script>
<div class="container" id="app">
<iframe name="iframe_a" id="ifrmPrint" style="display: none;"></iframe>
    <div class="row card-background-color">
        <div class="col col-lg-6 min-width-small-device">
            <div class="row panel panel-primary list-group-item front-row-blue"  id='orderList'>
                <div class="dropdown">
                    <button class="btn btn-primary customerType" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>Walk In </span>  <i class="fas fa-angle-down px-1" style="color: white;"></i>
                    </button>
                    <button class="btn btn-primary float-right" type="button" id="clearAll">
                         <i class="fas fa-trash px-1"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Walk In</a>
                        <a class="dropdown-item" href="#">Pathao</a>
                    </div>
                </div>
            </div>
            <div class="row orderListSection front-row"  id='orderListBox'>
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
        <div class="col col-lg-6 min-width-small-device">
            <div class="panel panel-primary" id='catalog'>
                @foreach ($category as $categorykey => $categoryitem)
                    <a href="#" class="list-group-item front-row" style="padding-bottom: 20px;">
                        <span class="text-muted small">
                            <h4>{{ $categoryitem['name'] }}</h4>
                        </span>
                    </a>
                    <div class="row">
                    @foreach ($catalog as $catalogkey => $catalogitem)
                        @if($catalogitem['category_id']==$categoryitem['id'])
                            <div class="col col-lg-3">
                                <div class="panel panel-primary items">
                                    <a id="catalog_{{ $catalogitem['id'] }}" class="catalog_id" href="#">
                                        <div class="panel-heading">
                                            <div class="row justify-content-center font-color-black">{{ $catalogitem['name'] }}</div>
                                            <div class="row justify-content-center"><img src="{{ asset('images/catalog/') .'/'.$catalogitem['image'] }}" width=100 height=100 alt="CMPM" class="img-circle"/></div>
                                        </div>
                                        <div class="panel-footer">
                                            <span class="row justify-content-center font-color-black">৳{{ $catalogitem['price'] }}</span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    </div>
                @endforeach
            </div>
            <div id="popupnav" class="row panel panel-primary list-group-item front-row-blue" style="display:none;">
            <button id="backButton" class="btn btn-primary" type="button">
                <i class="fas fa-arrow-left px-2"></i>Back
            </button>
            </div>
            <div id="popupWindow" style="display:none;">
                <div class="row mx-auto justify-content-center" id="afterProcess">
                </div>
                <div id="calculator">
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
                            <div class="col px-0 mx-auto"><span class="w-100 h-100 operator">+</span></div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col px-0"><span class="w-100 h-100">4</span></div>
                            <div class="col px-0"><span class="w-100 h-100">5</span></div>
                            <div class="col px-0"><span class="w-100 h-100">6</span></div>
                            <div class="col px-0"><span class="w-100 h-100 operator">-</span></div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col px-0"><span class="w-100 h-100">1</span></div>
                            <div class="col px-0"><span class="w-100 h-100">2</span></div>
                            <div class="col px-0"><span class="w-100 h-100">3</span></div>
                            <div class="col px-0"><span class="w-100 h-100 operator">÷</span></div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col px-0"><span class="w-100 h-100">.</span></div>
                            <div class="col px-0"><span class="w-100 h-100">0</span></div>
                            <div class="col px-0"><span class="w-100 h-100 eval">=</span></div>
                            <div class="col px-0"><span class="w-100 h-100 operator">x</span></div>
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
    <h1 style="text-align: center;">Preetom</h1>
    <h3 style="text-align: center;">Address</h3>
    <h3 style="text-align: center;">Phone</h3>
    <h4 style="text-align: center;">Sale</h4>
    <hr style="border-top: 4px solid rgba(0,0,0,.1);"/>
    <div>
    </div>
    <hr style="border-top: 4px solid rgba(0,0,0,.1);"/>
    <h4 style="text-align: center;">Thank You!<h4>
    <hr style="border-top: 4px solid rgba(0,0,0,.1);"/>
</div>
</div>
@endsection
