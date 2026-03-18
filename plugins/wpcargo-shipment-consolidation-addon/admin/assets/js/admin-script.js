jQuery(document).ready(function($){
    console.log( 'IS ADMIN '+wpshconAjaxHandler.isAdmin  );

    $('#wpcsh-consolidate-box-id .wpcshcon-subtotal').text( ( calculateTotalCost() - getTaxvalue() ).toFixed(2) );
    $('#wpcsh-consolidate-box-id .wpcshcon-total').text( calculateTotalCost() );
    function calculateTotalCost(){
        var wpcshcon_cost = 0;
        $("#wpcsh-consolidate-box-id .wpcshcon_cost").each(function(index) {
            var costValue = $(this).val();
            if( isNaN( costValue ) || !$(this).val() ){
                var costValue = 0;
            }
            wpcshcon_cost = wpcshcon_cost + parseFloat( costValue );
        });
        return wpcshcon_cost.toFixed(2);
    }
    function getTaxvalue(){
        var taxValue = $('#wpcsh-consolidate-box-id input.wpcshcon_cost[name="wpcshcon_tax_fee"]').val();
        if( isNaN( taxValue ) || !taxValue ){
            var taxValue = 0;
        }
        return parseFloat( taxValue ).toFixed(2);
    }
    $("#wpcsh-consolidate-box-id").on('change, keyup','.wpcshcon_cost', function(){
        $('.wpcshcon-subtotal').text( ( calculateTotalCost() - getTaxvalue() ).toFixed(2) );
        $('.wpcshcon-total').text( calculateTotalCost() );
    });

    $('#shipping-method-list input[type="radio"]').on('click', function(){
        $('#shipping-method-list .wpcshcon_cost').each(function(index){
            $(this).val('').prop("readonly", true).removeAttr('style');
        });
        $(this).parent().parent().find('input[type="text"]').prop("readonly", false).focus();
        $('#wpcsh-consolidate-box-id .wpcshcon-subtotal').text( ( calculateTotalCost() - getTaxvalue() ).toFixed(2) );
        $('#wpcsh-consolidate-box-id .wpcshcon-total').text( calculateTotalCost() );
    });
    // Item Email NOtification
    $('.item-email-notification').on('click','#send-item-email-notification', function(e){
        e.preventDefault();
        var postID = $(this).data('postid');
        var package = $(this).data('package');
        var shipmentType = $(this).data('type');
        if( package == 0 ){
            if( shipmentType == 'standard' ){
                alert(wpshconAjaxHandler.standardItemErrorMessage);
            }else{
                alert(wpshconAjaxHandler.consolidationItemErrorMessage);
            } 
            return;
        }
        $.ajax({
			type:"POST",
			data:{
				action:'notify_item_customer',	
				postID:postID,
				shipmentType:shipmentType,
			},
			dataType: 'JSON',
			url : wpshconAjaxHandler.ajaxurl,
			beforeSend:function(){
                $('body').append('<div class="wpc-loading">Loading...</div>');
                $( '#send-item-email-notification' ).append('<span class="spinner"></span>').css("pointer-events","none");
                $( '#send-item-email-notification .spinner').css({"visibility":"visible", "float":"none"});
			},
			success:function(data){
                $('body .wpc-loading').remove();
				if( data == 1){
                    console.log( wpshconAjaxHandler );
                    $( '#send-item-email-notification .spinner').remove();
                    $( '#send-item-email-notification').html(' '+wpshconAjaxHandler.messageSent);
                }else{
                    $( '#send-item-email-notification').html(' '+wpshconAjaxHandler.messageFailed);
                }
			}
		});	
    });
	//** Validation for currentcy and number
	$("input.price, input.number").keydown(function (e) {
        validateCurrency(e);
	});
	$("input.qty").keydown(function (e) {
		validateNumber(e);
	});
	function validateCurrency(e){
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
	}
	//** Script for number
	function validateNumber(e){
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105) ) {
            e.preventDefault();
        }
	}
});