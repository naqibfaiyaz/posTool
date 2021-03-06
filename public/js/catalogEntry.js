$(document).ready(function () { 
    $.fn.setCursorToTextEnd = function() {
        $initialVal = this.val();
        this.val($initialVal + ' ');
        this.val($initialVal);
    };

    $("input[name=image]").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                var img = $('<img>').attr('src', e.target.result).css({'width' : '100px'});
                $('.upload-image-preview').html(img);
            };
    
            reader.readAsDataURL(this.files[0]);
        }
    });

    localStorage.removeItem("selectedItem");
    localStorage.removeItem("SelectBunDetail");
    localStorage.removeItem("SelectPattyDetail");
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
            '<div class="amountDue  py-2 mx-auto" style="text-align: center;"><span><h1>Tk. ' + $('#totalAmount span').text() +'</h1></span></div>' +
        '</div>');
    });

    $('#reprintOrder').on('click', function () {
        $('#reprintOrder').hide();
        orderDataForHistoryAll=[];

        for(var i=0; i<orderDataDetails.length; i++){
            orderDataForHistory={
                "token_no" : orderSumHistory[0].token_no,
                "order_id" : orderSumHistory[0].order_id,
                "order_time" : orderSumHistory[0].order_time,
                "seller_name" : orderSumHistory[0].seller_name,
                "item_name": orderDataDetails[i].item_name,
                "item_quantity" : orderDataDetails[i].item_quantity,
                "item_price"    : orderDataDetails[i].item_price,
                "customer_type" : orderSumHistory[0].customer_type,
                "item_discount" : orderDataDetails[i].item_discount,
                "subtotal": orderSumHistory[0].subtotal,
                "discount" : orderSumHistory[0].Total_discount,
                "total_price" : orderSumHistory[0].total_price,
                "catalog_id" : '',
                "current_quantity" : '',
                "cash_tendered" : orderSumHistory[0].cash_tendered,
                "change_due" : orderSumHistory[0].change_due,
            };
            orderDataForHistoryAll.push(orderDataForHistory);
        }

        printData(orderDataForHistoryAll);
    });

    $('.processOrder').on('click', function () {
        var screenVal=parseFloat($('.screen').val());
        var totalVal=parseFloat($('#totalAmount span').text());
        
        if(!isNaN(screenVal)){
            if(screenVal>=totalVal){
                var changeDue=screenVal-totalVal;
                var elem='<div class="col-4">' + 
                    '<div class="text-muted py-2 mx-auto" ><h3 style="text-align: center;">Amount Due</h3></div>' +
                    '<div class="amountDue  py-2 mx-auto" style="text-align: center;"><span><h1>Tk. ' + totalVal +'</h1></span></div>' +
                '</div><div class="col-1">' +
                    '<div class="text-muted py-2 mx-auto" style="text-align: center;"><h1>|</h1></div>' + 
                '</div>' + 
                '<div class="col-4">' + 
                    '<div class="text-muted py-2 mx-auto" ><h3 style="text-align: center;">Change Due</h3></div>' +
                    '<div class="changeDue  py-2 mx-auto" style="text-align: center;"><span><h1 style="color: red;">Tk. ' + changeDue +'</h1<></span></div>' +
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
        orderData=prepareData();
        printData(orderData);
        setTimeout(function(){location.reload();},500);
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
    
    $("#discountToggle").change(function() {
        $('#DiscountAmount span').text('0.00');
        $('#totalAmount span').text(parseFloat($('#subTotal span').text()).toFixed(2));
        $('#appendBox .priceMenu').each(function(){
            currentId=$(this).data("catalogid");
            $('#priceList' + currentId + " span").removeClass('strike-through');
            $('#discountList' + currentId + " span").text(parseFloat($('#priceList' + currentId + " span").text()));
            $('#discountList' + currentId).css( "display", "none");
        });
    });

    $('#ApplyDiscount').on('click', function () {
        if($('#discountToggle').is(':checked')){
            var discountType='%';
        }else{
            var discountType='taka';
        }
        var discountValue=parseFloat(($.trim($('#discountTable .screen').val())/100));
        var subTotalValue=parseFloat($('#subTotal span').text());
        var discountInamount=null;
        var totalAfterDiscount=null;
        if(discountType=='%'){
            $('#appendBox .priceMenu').each(function(){
                currentId=$(this).data("catalogid");
                
                var itemOriginalValue=parseFloat($('#priceList' + currentId + " span").text());
                
                var catalog=JSON.parse(localStorage.getItem("catalog"));
                var discountItem=catalog.find(x => x.id === currentId).discount_status;
                if(discountItem){
                    var itemValueAfterDiscount=itemOriginalValue*(1-discountValue);

                    $('#priceList' + currentId + " span").addClass('strike-through');
                    $('#discountList' + currentId + " span").text( itemValueAfterDiscount);
                    $('#discountList' + currentId).css( "display", "block");
                }else{
                    var itemValueAfterDiscount=itemOriginalValue;
                };
                discountInamount+=itemOriginalValue-itemValueAfterDiscount;
                totalAfterDiscount+=itemValueAfterDiscount;
            });
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
        localStorage.clear();
        localStorage.setItem("catalog", JSON.stringify(catalog_list));
        localStorage.setItem("category", JSON.stringify(category_list));
        localStorage.setItem("quantity", JSON.stringify(quantity_list));
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
        $('.priceMenu .priceList span').each(function(){
            $('.paymentSection, .bottomSection').removeClass('disabled_div');
            $('.paymentSection, .bottomSection').removeClass('disabled_div');
            sum+=parseFloat($(this).text());
        });

        $('#subTotal span').text(sum.toFixed(2));
        $('#totalAmount span').text(($('#subTotal span').text()-$('#DiscountAmount span').text()).toFixed(2));
    });

    $('.catalog_id').click(function (event) {
        selectedItem=JSON.parse(window.localStorage.getItem("catalog")).find(x => x.id == this.id.split("_")[1]);
        console.log(selectedItem);
        var OldBunDetail={};
        var OldPattyDetail={};
        if(selectedItem.category_id!=1){
            var AllQuantity=JSON.parse(localStorage.getItem("quantity"));
            var selectedItemQuantity=AllQuantity.find(x => x.catalog_id == this.id.split("_")[1]).quantity;
        }else{
            var bunANDPatty=getBunNPatty(selectedItem.name);
        }

        if((selectedItem.category_id!=1 && selectedItemQuantity>0) || (selectedItem.category_id==1 && bunANDPatty.selectedBunQuantity>0 && bunANDPatty.selectedPattyQuantity>0)){
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
                }
            });
            if(!$('.menuItems').length || !exists){
                var orderedMenu='<div class="row itemNo_' + item_no + '" style="width: 100%; margin: 0 auto; height: 75px; background-color: white; border: 1px #f9f9f9 solid;" id="menuItem_' + selectedItem['id'] + '">' + 
                    '<div class="col py-3 menuItems" style="text-align: left;" id="catalogId_' + selectedItem['id'] + '">'+ selectedItem['name'] + '</div>' +
                    '<div class="col py-3 menuItemQuantity" style="text-align: center;" id="menuItem_quantity_' + item_no + '"><a id="orderIncrease" href="#" onclick="itemDecrease(' + selectedItem['id'] + ', '  + item_no + ');"><i class="fas fa-minus" id="orderDecrease"></i></a><span> 1 </span><a id="orderIncrease" href="#" onclick="itemIncrease(' + selectedItem['id'] + ', ' + item_no + ');"><i class="fas fa-plus"></i></a></div>' + 
                    '<div class="col py-3 priceMenu" style="text-align: right;" id="menuItem_price_' + item_no + '" data-price="' + selectedItem['price'] + '" data-catalogId="' + selectedItem['id'] + '">' +
                        '<div class="row col priceList" id="priceList' + selectedItem['id'] + '" style="display: block;" >Tk. <span>' + selectedItem['price'] + '</span></div>' + 
                        '<div class="row col text-muted discountList" id="discountList' + selectedItem['id'] + '" style="display: none;" >Tk. <span>' + selectedItem['price'] + '</span></div>' + 
                    '</div>' + 
                    '<div class="col-1 py-3"><a href="#" id="deleteRow" onclick="deleteRow(' + item_no + ');"><i class="fas fa-times" style="color: red;"></i></a></div>' + 
                    '</div>';
                $('#appendBox').append(orderedMenu);
                $('#appendBox').animate({
                    scrollTop: $('#appendBox').get(0).scrollHeight
                }, 500);

                
                if(selectedItem.category_id==1){
                    if(localStorage.getItem(bunANDPatty.selectedBun.name)){
                        OldBunDetail.name=bunANDPatty.selectedBun.name
                        OldBunDetail.RemainingQuantity=localStorage.getItem(bunANDPatty.selectedBun.name);
                    }else{
                        OldBunDetail.name=bunANDPatty.selectedBun.name
                        OldBunDetail.RemainingQuantity=bunANDPatty.selectedBunQuantity;
                    }
                    if(localStorage.getItem(bunANDPatty.selectedPatty.name)){
                        OldPattyDetail.name=bunANDPatty.selectedPatty.name
                        OldPattyDetail.RemainingQuantity=localStorage.getItem(bunANDPatty.selectedPatty.name);
                    }else{
                        OldPattyDetail.name=bunANDPatty.selectedPatty.name
                        OldPattyDetail.RemainingQuantity=bunANDPatty.selectedPattyQuantity;
                    }
                    var BunDetails={
                        "name"  : OldBunDetail.name,
                        "RemainingQuantity"  : OldBunDetail.RemainingQuantity-1,
                    }
                    var PattyDetails={
                        "name"  : OldPattyDetail.name,
                        "RemainingQuantity"  : OldPattyDetail.RemainingQuantity-1,
                    }
                    window.localStorage.setItem(BunDetails.name, BunDetails.RemainingQuantity);
                    window.localStorage.setItem(PattyDetails.name, PattyDetails.RemainingQuantity);
                }
            }
        }else{
            show_toast(401, 'Inventory exhausted for ' + selectedItem['name']);
        }
    });
});

function itemIncrease(catalog_id, item_no){
    var OldBunDetail={};
    var OldPattyDetail={};
    selectedItem=JSON.parse(window.localStorage.getItem("catalog")).find(x => x.id == catalog_id);
    QuantitySelected=parseInt($('#menuItem_quantity_' + item_no).text().trim());
    if(selectedItem.category_id!=1){    
        AllQuantity=JSON.parse(localStorage.getItem("quantity"));
        CurrentItemQuantity=AllQuantity.find(x => x.catalog_id === catalog_id).quantity;
    }else{
        var bunANDPatty=getBunNPatty(selectedItem.name);
           
        if(localStorage.getItem(bunANDPatty.selectedBun.name)){
            OldBunDetail.name=bunANDPatty.selectedBun.name
            OldBunDetail.RemainingQuantity=localStorage.getItem(bunANDPatty.selectedBun.name);
        }else{
            OldBunDetail.name=bunANDPatty.selectedBun.name
            OldBunDetail.RemainingQuantity=bunANDPatty.selectedPattyQuantity;
        }
        if(localStorage.getItem(bunANDPatty.selectedPatty.name)){
            OldPattyDetail.name=bunANDPatty.selectedPatty.name
            OldPattyDetail.RemainingQuantity=localStorage.getItem(bunANDPatty.selectedPatty.name);
        }else{
            OldPattyDetail.name=bunANDPatty.selectedPatty.name
            OldPattyDetail.RemainingQuantity=bunANDPatty.selectedPattyQuantity;
        }
        var BunDetails={
            "name"  : OldBunDetail.name,
            "RemainingQuantity"  : parseInt(OldBunDetail.RemainingQuantity)-1,
        }
        var PattyDetails={
            "name"  : OldPattyDetail.name,
            "RemainingQuantity"  : parseInt(OldPattyDetail.RemainingQuantity)-1,
        }
    }
    
    if((selectedItem.category_id!=1 && QuantitySelected<CurrentItemQuantity) || (selectedItem.category_id==1 && QuantitySelected<bunANDPatty.selectedBunQuantity && QuantitySelected<bunANDPatty.selectedPattyQuantity)){
        newQuantity=QuantitySelected+1;
        if(selectedItem.category_id==1){
            window.localStorage.setItem(BunDetails.name, BunDetails.RemainingQuantity);
            window.localStorage.setItem(PattyDetails.name, PattyDetails.RemainingQuantity);
        }
        $('#menuItem_quantity_' + item_no + ' span').text(" " + newQuantity + " ");

        base_price=parseInt($('#menuItem_price_' + item_no).data()['price']);
        new_price=base_price*newQuantity;
        $('#menuItem_price_' + item_no + ' span').text(new_price);
    }else{
        show_toast(401, "Inventory Finished. Please check.");
    }
}

function itemDecrease(catalog_id, item_no){
    var OldBunDetail={};
    var OldPattyDetail={};
    selectedItem=JSON.parse(window.localStorage.getItem("catalog")).find(x => x.id == catalog_id);
    
    AllQuantity=JSON.parse(localStorage.getItem("quantity"));
    CurrentItemQuantity=AllQuantity.find(x => x.catalog_id === catalog_id).quantity;
    QuantitySelected=parseInt($('#menuItem_quantity_' + item_no).text().trim());

    newQuantity=QuantitySelected-1;
    if(newQuantity>0){
        
        if(selectedItem.category_id==1){    
            var bunANDPatty=getBunNPatty(selectedItem.name);
            
            if(localStorage.getItem(bunANDPatty.selectedBun.name)){
                OldBunDetail.name=bunANDPatty.selectedBun.name
                OldBunDetail.RemainingQuantity=localStorage.getItem(bunANDPatty.selectedBun.name);
            }else{
                OldBunDetail.name=bunANDPatty.selectedBun.name
                OldBunDetail.RemainingQuantity=bunANDPatty.selectedPattyQuantity;
            }
            if(localStorage.getItem(bunANDPatty.selectedPatty.name)){
                OldPattyDetail.name=bunANDPatty.selectedPatty.name
                OldPattyDetail.RemainingQuantity=localStorage.getItem(bunANDPatty.selectedPatty.name);
            }else{
                OldPattyDetail.name=bunANDPatty.selectedPatty.name
                OldPattyDetail.RemainingQuantity=bunANDPatty.selectedPattyQuantity;
            }
            var BunDetails={
                "name"  : OldBunDetail.name,
                "RemainingQuantity"  : parseInt(OldBunDetail.RemainingQuantity)+1,
            }
            var PattyDetails={
                "name"  : OldPattyDetail.name,
                "RemainingQuantity"  : parseInt(OldPattyDetail.RemainingQuantity)+1,
            }
            localStorage.setItem(BunDetails.name, BunDetails.RemainingQuantity);
            localStorage.setItem(PattyDetails.name, PattyDetails.RemainingQuantity);
        }
        $('#menuItem_quantity_' + item_no + ' span').text(" " + newQuantity + " ");

        base_price=parseInt($('#menuItem_price_' + item_no).data()['price']);
        new_price=base_price*newQuantity;
        $('#menuItem_price_' + item_no + ' span').text(new_price);
    }
}

function deleteRow(rowNo){
    BurgerName=$('.itemNo_' + rowNo + ' .menuItems').text();
    BurgerQuantity=parseInt($('.itemNo_' + rowNo + ' .menuItemQuantity').text());
    $('.itemNo_' + rowNo).remove();
    switch (BurgerName) {
        case 'Cheese Chicken':
            bun = "Small Bun";
            patty = "Chicken Patty 60gm";
            break;
        case 'Cheese Beef':
            bun = "Small Bun";
            patty = "Beef Patty 60gm";
            break;
        case 'Classic Chicken':
            bun = "Large Bun";
            patty = "Chicken Patty 120gm";
            break;
        case 'Classic Beef':
            bun = "Large Bun";
            patty = "Beef Patty 120gm";
            break;
        case 'Quarter Pounder (Beef)':
            bun = "Large Bun";
            patty = "Beef Patty 120gm";
            break;
        case 'Quarter Pounder (Chicken)':
           bun = "Large Bun";
           patty = "Chicken Patty 120gm";
            break;
        case 'Half Pounder (Beef)':
            bun = "Large Bun";
            patty = "Beef Patty 270gm";
    }
    StorageBun=parseInt(localStorage.getItem(bun)) + BurgerQuantity;
    localStorage.setItem(bun, StorageBun);
    StoragePatty=parseInt(localStorage.getItem(patty)) + BurgerQuantity;
    localStorage.setItem(patty, StoragePatty);
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
	}, 3000);
	
	setTimeout(function(){  
		x.css("background-color","rgb(1, 51, 109)");
	}, 3000);
}

function prepareData()
{
    var now=$.now();
    var d1=new Date();
    var year=d1.getFullYear();
    var month=d1.getMonth()+1;
    var date=d1.getDate();
    var hour=d1.getHours();
    var min=d1.getMinutes();
    var sec=d1.getSeconds();
    
    var menuItems=$('#appendBox .menuItems');
    var menuItemQuantity=$('#appendBox .menuItemQuantity');
    var priceMenu=$('#appendBox .priceMenu .priceList');
    var priceMenu2=$('#appendBox .priceMenu .priceList span');
    var discountMenu=$('#appendBox .priceMenu .discountList span');
    var itemList='';
    var orderDate=year + '-' + (month < 10 ? '0' : '')  + month + '-' + (date < 10 ? '0' : '') + date;
    var orderTime=(hour < 10 ? '0' : '')  + hour + ':' + (min < 10 ? '0' : '')  + min + ':' + (sec < 10 ? '0' : '')  + sec;
    var currentToken=getCurrentToken();
    var orderDataForHistoryAll=[];
    var discount_rate=parseFloat($('#DiscountAmount span').text())/parseFloat($('#subTotal span').text());
    

    for(var i=0; i<menuItems.length; i++){
        var catalog_id=parseInt($.trim($(menuItems[i]).attr('id').split("_")[1]));
        var selectedItem=JSON.parse(window.localStorage.getItem("catalog")).find(x => x.id == catalog_id);
        console.log(selectedItem);
        var selectedQuantity=parseInt($.trim($(menuItemQuantity[i]).text()));
        var remainingQuantity=[];
        var AllQuantity=JSON.parse(localStorage.getItem("quantity"));
        var CurrentItemQuantity=AllQuantity.find(x => x.catalog_id === catalog_id).quantity;
        if(selectedItem.category_id!=1){
            if(CurrentItemQuantity-selectedQuantity>=0){
                remainingQuantity.push({
                    "id"        : selectedItem.id,
                    "name"      : selectedItem.name,
                    "quantity"  : CurrentItemQuantity-selectedQuantity
                });
            }else{
                show_toast(401, 'Inventory exhausted');
            }
        }else{
            var bunANDPatty=getBunNPatty(selectedItem.name);
            console.log(bunANDPatty);
            if(bunANDPatty.selectedBunQuantity-selectedQuantity>=0 && bunANDPatty.selectedPattyQuantity-selectedQuantity>=0){
                remainingQuantity.push({
                    "id"        : bunANDPatty.selectedBun.id,
                    "name"      : bunANDPatty.selectedBun.name,
                    "quantity"  : localStorage.getItem(bunANDPatty.selectedBun.name)
                });
                remainingQuantity.push({
                    "id"        : bunANDPatty.selectedPatty.id,
                    "name"      : bunANDPatty.selectedPatty.name,
                    "quantity"  : localStorage.getItem(bunANDPatty.selectedPatty.name)
                });
            }else{
                show_toast(401, 'Inventory exhausted');
            }
        }
        
        itemList+='<tr>' +
            '<td style="padding: 10px 0 0 0;">' + $(menuItems[i]).text() + '</td>' +
            '<td style="text-align: right; padding: 0;">x' + $.trim($(menuItemQuantity[i]).text()) + '</td>' +
            '<td style="text-align: right; padding: 10px 0 0 0;">Tk. ' + $(priceMenu[i]).text() + '</td>' +
        '</tr>';

        orderDataForHistory={
            "token_no" : currentToken,
            "order_id" : now.toString().slice(0, -3),
            "order_time" : orderDate + ' ' + orderTime,
            "seller_name" : user.name,
            "item_name": $.trim($(menuItems[i]).text()),
            "item_quantity" : parseInt($.trim($(menuItemQuantity[i]).text())),
            "item_price"    : parseFloat($.trim($(priceMenu2[i]).text())),
            "customer_type" : $(".customerType span").text(),
            "item_discount" : parseFloat($.trim($(priceMenu2[i]).text())) - parseFloat($.trim($(discountMenu[i]).text())),
            "subtotal": $('#subTotal span').text(),
            "discount" : $('#DiscountAmount span').text(),
            "total_price" : $('#totalAmount span').text(),
            "catalog_id" : catalog_id,
            "current_quantity" : CurrentItemQuantity,
            "cash_tendered" : parseFloat($('#calculator .screen').val()),
            "change_due" : parseFloat($('#calculator .screen').val())-$('#totalAmount span').text(),
            "remaining_quantity" : remainingQuantity,
            "modified_quantity" : selectedQuantity
        };

        orderDataForHistoryAll.push(orderDataForHistory);
    }

    console.log(orderDataForHistoryAll);
    orderPosting(orderDataForHistoryAll);
    
    return orderDataForHistoryAll;
}

function printData(orderData){
    itemList='';
    for(var i=0; i<orderData.length; i++){
        itemList+='<tr>' +
            '<td style="text-align: left; padding: 0; width:100px;">' + orderData[i].item_name + '</td>' +
            '<td style="text-align: right; padding: 0;">x' + orderData[i].item_quantity + '</td>' +
            '<td style="text-align: right; padding: 0;">Tk. ' + orderData[i].item_price + '</td>' +
        '</tr>';
    }
    
    var printTable='<table style="font-size: 10px;" id="printTable">' +
        '<tr>' +
            '<td>Order #:</td>' +
            '<td></td>' +
            '<td class="order_no" style="text-align: right;">' + orderData[0].order_id + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td>Sold To:</td>' +
            '<td></td>' +
            '<td class="sold_to" style="text-align: right;">' +  orderData[0].customer_type +'</td>' +
        '</tr>' +
        '<tr>' +
            '<td>Order Time:</td>' +
            '<td></td>' +
            '<td class="sold_date" style="text-align: right;">' + orderData[0].order_time +'</td>' +
        '</tr>' +
        '<tr>' +
            '<td>Sales Person:</td>' +
            '<td></td>' +
            '<td class="sales_person" style="text-align: right;">' + orderData[0].seller_name + '</td>' +
        '</tr>' +
    '<tr style="border-bottom: 4px solid rgba(0,0,0,.1);">' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
    '<td style="padding: 10px 0 10px 0;"></td>' +
'</tr>' +
    itemList +
        '<tr>' +
            '<td style="padding: 10px 0 0 0;"></td>' +
            '<td style="padding: 10px 0 0 0;">Sub Total</td>' +
            '<td style="text-align: right; padding: 10px 0 0 0;">Tk. ' + orderData[0].subtotal + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td></td>' +
            '<td>Discount</td>' +
            '<td style="text-align: right;">Tk. ' + orderData[0].discount + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td></td>' +
            '<td>Total</td>' +
            '<td style="text-align: right;">Tk. ' + orderData[0].total_price + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td style="padding: 10px 0 0 0;">Cash Tendered</td>' +
            '<td style="padding: 10px 0 0 0;"></td>' +
            '<td style="text-align: right; padding: 10px 0 0 0;">Tk. ' + orderData[0].cash_tendered + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td></td>' +
            '<td>Change Due</td>' +
            '<td style="text-align: right;">Tk. ' + orderData[0].change_due + '</td>' +
        '</tr>' +
    '</table>';

    $('#tokenPrint').text('TOKEN: ' + orderData[0].token_no);
    $('#copyName').text('Customer Copy');
    
    $('#printSection div').append(printTable);
    
    // var printHeight=$('#printSection').height();
    // var docHeight=662;
    // var gapHeight=parseInt((docHeight-printHeight));
    // console.log(docHeight, printHeight, gapHeight);
    // $('#printSection').append('<div style="height:' + gapHeight + 'px;"></div>' + $('#printSection').html());
    var divToPrint=document.getElementById("printSection");
    newWin= window.open("",'Print-Window');
    newWin.document.open();
   
    newWin.document.write('<html><body onload="window.print(0)">'+ divToPrint.innerHTML +'</body></html>');    
    newWin.document.close();
    newWin.close();

    $('#copyName').text('Shop Copy');
    var divToPrint2=document.getElementById("printSection");
    newWin2= window.open("",'Print-Window2');
    newWin2.document.open();
   
    newWin2.document.write('<html><body onload="window.print(0)">'+ divToPrint2.innerHTML +'</body></html>');    
    newWin2.document.close();
    newWin2.close();
}

function ClickHereToPrint(){
    try{
      var oIframe = document.getElementById('ifrmPrint');
      var oContent = document.getElementById('printSection').innerHTML;
      var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
      if (oDoc.document) oDoc = oDoc.document;
    //   oDoc.write('<head><title>title</title>');
      oDoc.write('<body onload="this.focus(); window.print();" style="max-width: 300px;">');
      oDoc.write(oContent + '</body>');
      setTimeout(function(){oDoc.close();},5000);
    } catch(e){
      self.print(0);
    }
  }

function NewPrint(Copies){
    var Count = 0;
    while (Count < Copies){
      window.print(0);
      Count++;
    }
}

function orderPosting(data){
    $.ajax({
        url: "catalog",
        method: "POST",
        data: {data : data},
        dataType: "json",
        async: false,
        beforeSend: function(request) {
            request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        },
      }).done(function( data, status, xhr ) {
        console.log(data);
      }).fail(function( jqXHR, textStatus ) {
        alert( "Request failed: " + textStatus );
      });
}

function getCurrentToken(){
    var result="";
    $.ajax({
        url: "getOrderToken",
        method: "GET",
        dataType: "json",
        async: false,  
        beforeSend: function(request) {
            request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        },
      }).done(function( data, status, xhr ) {
            result = data.token;
      }).fail(function( jqXHR, textStatus ) {
        show_toast( 401, "Token fetch failed. Please try again." );
      });

    return result;
}

function getBunNPatty(burgerName){
    switch (burgerName) {
        case 'Cheese Chicken':
            bun = "Small Bun";
            patty = "Chicken Patty 60gm";
            break;
        case 'Cheese Beef':
            bun = "Small Bun";
            patty = "Beef Patty 60gm";
            break;
        case 'Classic Chicken':
            bun = "Large Bun";
            patty = "Chicken Patty 120gm";
            break;
        case 'Classic Beef':
            bun = "Large Bun";
            patty = "Beef Patty 120gm";
            break;
        case 'Quarter Pounder (Beef)':
            bun = "Large Bun";
            patty = "Beef Patty 120gm";
            break;
        case 'Quarter Pounder (Chicken)':
           bun = "Large Bun";
           patty = "Chicken Patty 120gm";
            break;
        case 'Half Pounder (Beef)':
            bun = "Large Bun";
            patty = "Beef Patty 270gm";
    }

    selectedBun=JSON.parse(window.localStorage.getItem("catalog")).find(x => x.name == bun);
    var AllQuantity=JSON.parse(localStorage.getItem("quantity"));
    var selectedBunQuantity=AllQuantity.find(x => x.catalog_id == selectedBun.id).quantity;

    selectedPatty=JSON.parse(window.localStorage.getItem("catalog")).find(x => x.name == patty);
    var AllQuantity=JSON.parse(localStorage.getItem("quantity"));
    var selectedPattyQuantity=AllQuantity.find(x => x.catalog_id == selectedPatty.id).quantity;

    return {
        "selectedBun"           : selectedBun,
        "selectedBunQuantity"   : selectedBunQuantity, 
        "selectedPatty"         : selectedPatty,
        "selectedPattyQuantity" : selectedPattyQuantity
    };
}