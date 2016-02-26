if (typeof $.fn.bdatepicker == 'undefined')
    $.fn.bdatepicker = $.fn.datepicker.noConflict();

$(function ()
{

    /* DatePicker */
//	// default 2011-06-05
    $("#quote_expiration_date").bdatepicker({
        format: 'yyyy-mm-dd',
        startDate: "2013-02-14"
    });
    $("#order_orderDate").bdatepicker({
        format: 'yyyy-mm-dd',
        startDate: "2013-02-14"
    });
    $("#order_deliveryDate").bdatepicker({
        format: 'yyyy-mm-dd',
        startDate: "2013-02-14"
    });

    $("#invoice_invoiceDate").bdatepicker({
        format: 'yyyy-mm-dd',
        startDate: "2013-02-14"
    });

    $("#invoice_deliveryDate").bdatepicker({
        format: 'yyyy-mm-dd',
        startDate: "2013-02-14"
    });

    $("#user_birthdate").bdatepicker({
        format: 'yyyy-mm-dd',
        startDate: "2013-02-14"
    });

//
//	// component
//	$('#datepicker2').bdatepicker({
//		format: "dd MM yyyy",
//		startDate: "2013-02-14"
//	});
//
//	// today button
//	$('#datepicker3').bdatepicker({
//		format: "dd MM yyyy",
//		startDate: "2013-02-14",
//		todayBtn: true
//	});

    // advanced
//	$('#quote_expiration_date').bdatepicker({
//		format: "yyyy-dd-mm",
//        autoclose: true,
//        todayBtn: true,
//        startDate: "2013/02/14",
//        minuteStep: 10
//	});

//	// meridian
//	$('#datetimepicker5').bdatepicker({
//		format: "dd MM yyyy - HH:ii P",
//	    showMeridian: true,
//	    autoclose: true,
//	    startDate: "2013-02-14 10:00",
//	    todayBtn: true
//	});

//	// other
//	if ($('#datepicker').length) $("#datepicker").bdatepicker({ showOtherMonths:true });
//	if ($('#datepicker-inline').length) $('#datepicker-inline').bdatepicker({ inline: true, showOtherMonths:true });

});