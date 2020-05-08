$(document).ready(function(){
    
    // -- PRODUCT DETAIL THUMB SELECT / SWITCHING --
    $('.thumb-link').click(function(e){
        e.preventDefault();
        
        $(this).parent().find('a').each(function(){
           $(this).removeClass("selected"); 
        });
        
        var newSrc = $(this).data("largesrc");
        $(this).addClass("selected");
        $(".product_details_main_image img").attr('src',newSrc);
    });
    
    //-- CHANGE BASKET QUANTITY SELECT
//    $("#basket-grid select").change(function(e){
//        e.preventDefault();
//        var action = $(this).data("action");
//        var item = $(this).data("item");
//        var qty = $(this).val();
//        var uri = $(this).data('uri');
//        var completeUrl = uri + '?action=' + action + "&id=" + item + "&qty=" + qty;
//        //console.log(completeUrl);
//        document.location.href = completeUrl;
//    });
    
    //-- CHECKOUT -- SELECT BILLING ADDRESS --
//    $(".select-billing-address").click(function(e){
//        e.preventDefault();
//        $("#checkout-billing").find('.address-box').each(function(){
//           $(this).removeClass("selected"); 
//        });
//        
//        $(this).parent(".address-box").addClass("selected");
//        $("#selected-billing-address").val($(this).data("address"));
//    });

    //-- CHECKOUT -- USE BILLING FOR SHIPPING
    $("#shippingAsBilling").change(function(){
        if($(this).is(":checked")) {
            $("#checkout-newshippingform").css('display','none');
            $(this).parents('form').find('input:radio').each(function(){
                $(this).prop("disabled", true);  
            });
        } else {
            $("#checkout-newshippingform").css('display','block');
            $(this).parents('form').find('input:radio').each(function(){
                $(this).prop("disabled", false);
            });
        }
    });
    
});


