jQuery(document).ready(function($){
	var wpcfm_dashboard = wpshconAjaxHandler.wpcfm_dashboard;
	// Close plugin modal
	$('.close-wpcshcon-dialog').on('click',function(e){
		e.preventDefault();
		$(this).parent().parent().toggle();
	});
	//select all checkboxes
	$("#select-all").change( function(){  //"select all" change
		var status = this.checked; // "select all" checked status
		$('.shipment-options').each( function(){ //iterate all listed checkbox items
			this.checked = status; //change ".checkbox" checked status
		});
	});

	$('.shipment-options').change(function(){ //".checkbox" change
		//uncheck "select all", if one of the listed checkbox item is unchecked
		if(this.checked == false){ //if this item is unchecked
			$("#select-all")[0].checked = false; //change "select all" checked status to false
		}

		//check "select all" if all checkbox items are checked
		if ($('.shipment-options:checked').length == $('.shipment-options').length ){
			$("#select-all")[0].checked = true; //change "select all" checked status to true
		}
	});
	//** Validation for currentcy and number
	$("input.price, input.number").keydown(function (e) {
		validateCurrency(e)
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