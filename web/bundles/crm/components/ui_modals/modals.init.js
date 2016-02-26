$(function ()
{
//	$('#modals-bootbox-alert').click(function()
//	{
//		bootbox.alert("Hello World!", function(result) 
//		{
//			$.gritter.add({
//				title: 'Callback!',
//				text: "I'm just a BootBox Alert callback!"
//			});
//		});
//	}); 

    $('#confirm-delete-contact').click(function (e)
    {
        e.preventDefault();

        bootbox.confirm("You are about to delete this contact. Continue?", function (result) {
            if (result) {
                $('#frmdeletecontact').submit();
            } else {
                $.gritter.add({
                    title: 'Action Cancelled!',
                    text: "You have cancelled deletion of contact!"
                });
            }
        });
    });

    $('#confirm-delete-ticket').click(function (e)
    {
        e.preventDefault();

        bootbox.confirm("You are about to delete this ticket. Continue?", function (result) {
            if (result) {
                $('#frmdeletetick').submit();
            } else {
                $.gritter.add({
                    title: 'Action Cancelled!',
                    text: "You have cancelled deletion of ticket!"
                });
            }
        });
    });
    $('#confirm-delete-user').click(function (e)
    {
        e.preventDefault();
        bootbox.confirm("You are about to delete this user. Continue", function (result) {
            if (result) {
                $('#frmdeluser').submit();
            } else {
                $.gritter.add({
                    title: 'Action Cancelled!',
                    text: "You have cancelled deletion of this user!"
                });
            }
        });
    });
    $('#confirm-delete-product').click(function (e)
    {
        e.preventDefault();
        bootbox.confirm("You are about to delete this product. Continue", function (result) {
            if (result) {
                $('#frmdelproduct').submit();
            } else {
                $.gritter.add({
                    title: 'Action Cancelled!',
                    text: "You have cancelled deletion of this product!"
                });
            }
        });
    });

    $('#confirm-delete-product-category').click(function (e)
    {
        e.preventDefault();
        bootbox.confirm("You are about to delete this product category. Continue", function (result) {
            if (result) {
                $('#frmdelproductcat').submit();
            } else {
                $.gritter.add({
                    title: 'Action Cancelled!',
                    text: "You have cancelled deletion of this product!"
                });
            }
        });
    });

    $('#confirm-delete-account-profile').click(function (e)
    {
        e.preventDefault();
        bootbox.confirm("You are about to delete this account record. Continue", function (result) {
            if (result) {
                $('#frmdeleteaccnt').submit();
            } else {
                $.gritter.add({
                    title: 'Action Cancelled!',
                    text: "You have cancelled deletion of this product!"
                });
            }
        });
    });
    $('#confirm-delete-quote').click(function (e)
    {
        e.preventDefault();
        bootbox.confirm("You are about to delete this deal. Continue", function (result) {
            if (result) {
                $('#frmdeletequote').submit();
            } else {
                $.gritter.add({
                    title: 'Action Cancelled!',
                    text: "You have cancelled deletion of this deal!"
                });
            }
        });
    });
    
    
    $('#confirm-delete-invoice').click(function (e)
    {
        e.preventDefault();
        bootbox.confirm("You are about to delete this invoice. Continue", function (result) {
            if (result) {
                $('#frminvoicedel').submit();
            } else {
                $.gritter.add({
                    title: 'Action Cancelled!',
                    text: "You have cancelled deletion of this invoice!"
                });
            }
        });
    });
    
     $('#confirm-delete-order').click(function (e)
    {
        e.preventDefault();
        bootbox.confirm("You are about to delete this Order. Continue", function (result) {
            if (result) {
                $('#frmdeleteorder').submit();
            } else {
                $.gritter.add({
                    title: 'Action Cancelled!',
                    text: "You have cancelled deletion of this order!"
                });
            }
        });
    });
     
    
//	$('#modals-bootbox-prompt').click(function()
//	{
//		bootbox.prompt("What is your name?", function(result) 
//		{                
//			if (result === null) {                                             
//				$.gritter.add({
//					title: 'Callback!',
//					text: "BootBox Prompt Dismissed!"
//				});                            
//			} else {
//				$.gritter.add({
//					title: 'Hi ' + result,
//					text: "BootBox Prompt Callback with result: "+ result
//				});                          
//			}
//		});
//	});
//	$('#modals-bootbox-custom').click(function()
//	{
//		bootbox.dialog({
//		  	message: "I am a custom dialog",
//		  	title: "Custom title",
//		  	buttons: {
//		    	success: {
//		      		label: "Success!",
//		      		className: "btn-success",
//		      		callback: function() {
//		        		$.gritter.add({
//							title: 'Callback!',
//							text: "Great success"
//						});
//		      		}
//		    	},
//			    danger: {
//			      	label: "Danger!",
//			      	className: "btn-danger",
//			      	callback: function() {
//			        	$.gritter.add({
//							title: 'Callback!',
//							text: "Uh oh, look out!"
//						});
//			      	}
//			    },
//			    main: {
//			      	label: "Click ME!",
//			      	className: "btn-primary",
//			      	callback: function() {
//			        	$.gritter.add({
//							title: 'Callback!',
//							text: "Primary button!"
//						});
//			      	}
//			    }
//			}
//		});
//	});
});