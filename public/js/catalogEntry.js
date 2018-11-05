$(document).ready(function () { 
    $.fn.setCursorToTextEnd = function() {
        $initialVal = this.val();
        this.val($initialVal + ' ');
        this.val($initialVal);
    };

    localStorage.removeItem("selectedItem");
    $('#cashButton').on('click', function () {
        $('#catalog').hide();
        $('#popupWindow').show();
        $('#popupWindowDiscount').hide();
        $('#discountTable .screen').blur();
        $('.screen').focus();
        $('.screen').setCursorToTextEnd();
        $('#popupnav').show();
        $('#afterProcess').html('<div class="col-4">' + 
            '<div class="text-muted py-2 mx-auto" ><h3 style="text-align: center;">Amount Due</h3></div>' +
            '<div class="amountDue  py-2 mx-auto" style="text-align: center;"><span><h1>৳' + $('#totalAmount span').text() +'</h1></span></div>' +
        '</div>');
    });

    $('.processOrder').on('click', function () {
        var screenVal=parseFloat($('.screen').val());
        var totalVal=parseFloat($('#totalAmount span').text());
        
        if(!isNaN(screenVal)){
            if(screenVal>=totalVal){
                var changeDue=screenVal-totalVal;
                var elem='<div class="col-4">' + 
                    '<div class="text-muted py-2 mx-auto" ><h3 style="text-align: center;">Amount Due</h3></div>' +
                    '<div class="amountDue  py-2 mx-auto" style="text-align: center;"><span><h1>৳' + totalVal +'</h1></span></div>' +
                '</div><div class="col-1">' +
                    '<div class="text-muted py-2 mx-auto" style="text-align: center;"><h1>|</h1></div>' + 
                '</div>' + 
                '<div class="col-4">' + 
                    '<div class="text-muted py-2 mx-auto" ><h3 style="text-align: center;">Change Due</h3></div>' +
                    '<div class="changeDue  py-2 mx-auto" style="text-align: center;"><span><h1 style="color: red;">৳' + changeDue +'</h1<></span></div>' +
                '</div>';
                $('#afterProcess').html(elem);
                $('.finalProcess').show();
                $('.processOrder').hide();
            }else{
                show_toast(400, "Given value is less than due");    
            }
        }else{
            show_toast(400, "Given value is less than due");
        }
    });

    $('.finalProcess').on('click', function(){
        console.log('finalProcess');
        $('.finalProcess').hide();
        ClickHereToPrint();
        // location.reload();
    })

    $(".dropdown-menu").on('click', 'a', function(){
        $(".customerType span").text($(this).text());
    });
  
    $('#discountButton').on('click', function () {
        $('#catalog').hide();
        $('#popupWindowDiscount').show();
        $('#discountTable .screen').focus();
        $('#discountTable .screen').setCursorToTextEnd();
        $('#popupnav').show();
        $('#popupWindow').hide();
    });

    $('#backButton').on('click', function () {
        $('#catalog').show();
        $('#popupWindowDiscount').hide();
        $('#popupWindow').hide();
        $('.screen').blur();
        $('#discountTable .screen').blur();
        $('#popupnav').hide();
        $('.finalProcess').hide();
        $('.processOrder').show();
    });

    $('#calculator').on('click', function () {
        $('.screen').focus();
    });

    $('#discountTable').on('click', function () {
        $('#discountTable .screen').focus();
    });

    $('#discountTable .keys').click(function (event) {
        $('#discountTable .screen').val($(event.target).text().slice(0, -1));
    });

    $('#ApplyDiscount').on('click', function () {
        if($('#discountToggle').is(':checked')){
            var discountType='%';
        }else{
            var discountType='taka';
        }
        var discountValue=parseFloat(($.trim($('#discountTable .screen').val())/100));
        var subTotalValue=parseFloat($('#subTotal span').text());
        if(discountType=='%'){
            discountInamount=subTotalValue*discountValue;
            totalAfterDiscount=subTotalValue*(1-discountValue);
        }else{
            discountInamount=parseFloat($.trim($('#discountTable .screen').val()));
            totalAfterDiscount=subTotalValue-discountInamount;
        }

        $('#totalAmount span').text(totalAfterDiscount.toFixed(2));
        $('#DiscountAmount span').text(discountInamount.toFixed(2));
    });

    $('#clearAll').on('click', function () {
        $('#noOrderText').show();
        $('#appendBox').empty();
        $('.paymentSection, .bottomSection').addClass('disabled_div');
        $('.paymentSection, .bottomSection').addClass('disabled_div');
        localStorage.removeItem("selectedItem");
    });

    $('#orderListBox').on('click', function(){
        if($('#orderListBox *:not(:has(*)):visible').text()==''){
            $('#noOrderText').show();
            $('.paymentSection, .bottomSection').addClass('disabled_div');
            $('.paymentSection, .bottomSection').addClass('disabled_div');
        };
    });

    $('#catalog, #orderListBox').on('click', function(){
        var sum=0;
        $('.priceMenu span').each(function(){
            $('.paymentSection, .bottomSection').removeClass('disabled_div');
            $('.paymentSection, .bottomSection').removeClass('disabled_div');
            sum+=parseFloat($(this).text());
        });

        $('#subTotal span').text(sum.toFixed(2));
        $('#totalAmount span').text(($('#subTotal span').text()-$('#DiscountAmount span').text()).toFixed(2));
    });

    $('.catalog_id').click(function (event) {
        selectedItem=JSON.parse(window.localStorage.getItem("catalog"))[this.id.split("_")[1]-1];
        if(previousItem=JSON.parse(localStorage.getItem("selectedItem"))){
            localStorage.removeItem("selectedItem");
            newItem=previousItem.concat([selectedItem]);
            localStorage.setItem("selectedItem", JSON.stringify(newItem));
            
            item_no=newItem.length;
        }else{
            localStorage.setItem("selectedItem", JSON.stringify([selectedItem]));
            item_no=1;
        }
        $('#noOrderText').hide();
        exists=0;
        $('.menuItems').each(function(){
            if(selectedItem['name']==$(this).text()){
                exists=1;
                show_toast(200, "Item already exists in the cart. Increase quantuty.");
            };
        });
        if(!$('.menuItems').length || !exists){
            var orderedMenu='<div class="row itemNo_' + item_no + '" style="width: 100%; margin: 0 auto; height: 75px; background-color: white; border: 1px #f9f9f9 solid;" id="menuItem_' + selectedItem['id'] + '">' + 
                '<div class="col py-3 menuItems" style="text-align: left;" id="menuItem_name_' + item_no + '">'+ selectedItem['name'] + '</div>' +
                '<div class="col py-3 menuItemQuantity" style="text-align: center;" id="menuItem_quantity_' + item_no + '"><a id="orderIncrease" href="#" onclick="itemIncrease(' + item_no + ');"><i class="fas fa-plus"></i></a><span> 1 </span><a id="orderIncrease" href="#" onclick="itemDecrease(' + item_no + ');"><i class="fas fa-minus" id="orderDecrease"></i></a></div>' + 
                '<div class="col py-3 priceMenu" style="text-align: right;" id="menuItem_price_' + item_no + '" data-price="' + selectedItem['price'] + '">৳<span>' + selectedItem['price'] + '</span></div>' + 
                '<div class="col-1 py-3"><a href="#" id="deleteRow" onclick="deleteRow(' + item_no + ');"><i class="fas fa-times" style="color: red;"></i></a></div>' + 
                '</div>';
            $('#appendBox').append(orderedMenu);
            $('#appendBox').animate({
                scrollTop: $('#appendBox').get(0).scrollHeight
            }, 500);
        }
    });
});

function itemIncrease(item_no){
    newQuantity=parseInt($('#menuItem_quantity_' + item_no).text().trim())+1;
    $('#menuItem_quantity_' + item_no + ' span').text(" " + newQuantity + " ");

    base_price=parseInt($('#menuItem_price_' + item_no).data()['price']);
    new_price=base_price*newQuantity;
    $('#menuItem_price_' + item_no + ' span').text(new_price);
}

function itemDecrease(item_no){
    newQuantity=parseInt($('#menuItem_quantity_' + item_no).text().trim())-1;
    if(newQuantity>0){
        $('#menuItem_quantity_' + item_no + ' span').text(" " + newQuantity + " ");

        base_price=parseInt($('#menuItem_price_' + item_no).data()['price']);
        new_price=base_price*newQuantity;
        $('#menuItem_price_' + item_no + ' span').text(new_price);
    }
}

function deleteRow(rowNo){
    $('.itemNo_' + rowNo).remove();
}

function show_toast(status, msg) {
    var x = $("#snackbar");
    x.text(msg);
    x.addClass("show");
	
	if(status!=200){
		x.css("background-color","red");
	}

    setTimeout(function(){  
		x.removeClass("show"); 
	}, 5000);
	
	setTimeout(function(){  
		x.css("background-color","rgb(1, 51, 109)");
	}, 6000);
}

function printData()
{
   var divToPrint=document.getElementById("printSection");
   newWin= window.open("");
   newWin.document.write(divToPrint.outerHTML + "<br/>" + "<br/>" +  "<br/>" +  "<br/>" +  "<br/>" +  "<br/>" +  "<br/>" +  "<br/>" +  "<br/>" +  "<br/>" +  "<br/>" +  "<br/>" +  "<br/>" + divToPrint.outerHTML);
   newWin.print(1);
   newWin.close();
}

function ClickHereToPrint(){
    var now=$.now();
    var d1=new Date();
    var year=d1.getFullYear();
    var month=d1.getMonth()+1;
    var date=d1.getDate();
    console.log();
    var menuItems=$('#appendBox .menuItems');
    var menuItemQuantity=$('#appendBox .menuItemQuantity');
    var priceMenu=$('#appendBox .priceMenu');
    var itemList='';
    
    for(var i=0; i<menuItems.length; i++){
        itemList+='<tr>' +
            '<td style="padding: 10px 0 0 0;">' + $(menuItems[i]).text() + '</td>' +
            '<td style="text-align: right; padding: 10px 0 0 0;">x' + $.trim($(menuItemQuantity[i]).text()) + '</td>' +
            '<td style="text-align: right; padding: 10px 0 0 0;">' + $(priceMenu[i]).text() + '</td>' +
        '</tr>';
    }
    
    // $('#appendBox .menuItems').each(function(){
    // var itemList='<tr>' +
    //     '<td style="padding: 10px 0 0 0;">Cheese Beef</td>' +
    //     '<td style="text-align: right; padding: 10px 0 0 0;">x2</td>' +
    //     '<td style="text-align: right; padding: 10px 0 0 0;">৳340</td>' +
    // '</tr>' +
    // '<tr>' +
    //     '<td>Cheese Chicken</td>' +
    //     '<td style="text-align: right;">x3</td>' +
    //     '<td style="text-align: right;">৳410</td>' +
    // '</tr>' +
    // '<tr>' +
    //     '<td>Classic Beef</td>' +
    //     '<td style="text-align: right;">x2</td>' +
    //     '<td style="text-align: right;">৳660</td>' +
    // '</tr>';
    // });
    // console.log($(this).text());
    // console.log($('#appendBox .menuItems').text() + '<br/>');
    // console.log($('#appendBox .menuItemQuantity').text() + '<br/>');
    // console.log($('#appendBox .priceMenu').text() + '<br/>');
    var printTable='<table>' +
        '<tr>' +
            '<td>Order #:</td>' +
            '<td></td>' +
            '<td class="order_no" style="text-align: right;">' + now.toString().slice(0, -3) + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td>Sold To:</td>' +
            '<td></td>' +
            '<td class="sold_to" style="text-align: right;">' +  $(".customerType span").text() +'</td>' +
        '</tr>' +
        '<tr>' +
            '<td>Order Date:</td>' +
            '<td></td>' +
            '<td class="sold_date" style="text-align: right;">' + year + '-' + (month < 10 ? '0' : '')  + month + '-' + (date < 10 ? '0' : '') + date +'</td>' +
        '</tr>' +
        '<tr>' +
            '<td>Order Time:</td>' +
            '<td></td>' +
            '<td class="sold_time" style="text-align: right;">' + d1.getHours() + ':' + d1.getMinutes() + ':' + d1.getSeconds() + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td>Sales Person:</td>' +
            '<td></td>' +
            '<td class="sales_person" style="text-align: right;">x_user</td>' +
        '</tr>' +
    '<tr style="border-bottom: 4px solid rgba(0,0,0,.1);">' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
'</tr>' +
    itemList +
    '<tr style="border-bottom: 4px solid rgba(0,0,0,.1);">' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
'</tr>' +
        '<tr>' +
            '<td style="padding: 10px 0 0 0;"></td>' +
            '<td style="padding: 10px 0 0 0;">Sub Total</td>' +
            '<td style="text-align: right; adding: 10px 0 0 0;">' + $('#subTotal span').text() + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td></td>' +
            '<td>Discount</td>' +
            '<td style="text-align: right;">' + $('#DiscountAmount span').text() + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td></td>' +
            '<td>Total</td>' +
            '<td style="text-align: right;">' + $('#totalAmount span').text() + '</td>' +
        '</tr>' +
    '<tr style="border-bottom: 4px solid rgba(0,0,0,.1);">' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
'</tr>' +
        '<tr>' +
            '<td style="padding: 10px 0 0 0;">Cash Tendered</td>' +
            '<td style="padding: 10px 0 0 0;"></td>' +
            '<td style="text-align: right; padding: 10px 0 0 0;">' + $('.screen').val() + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td></td>' +
            '<td>Change Due</td>' +
            '<td style="text-align: right;">' + $('.changeDue span').text() + '</td>' +
        '</tr>' +
    '</table>'

    $('#printSection div').append(printTable);
    try{
      var oIframe = document.getElementById('ifrmPrint');
      var oContent = document.getElementById('printSection').innerHTML;
      var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
      if (oDoc.document) oDoc = oDoc.document;
    //   oDoc.write('<head><title>title</title>');
      oDoc.write('<body onload="this.focus(); this.print();" style="max-width: 300px;">');
      oDoc.write(oContent + '</body>');
      oDoc.close();
    } catch(e){
      self.print(0);
    }
  }