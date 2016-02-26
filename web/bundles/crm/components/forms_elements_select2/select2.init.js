$('#quoteproduct_productname').change(function() {
  //alert($(this).val());    
  this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);
});
$('#orderproduct_productname').change(function() {
    //alert($(this).val());
    this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);
});

$('#invoiceproduct_productname').change(function() {
    //alert($(this).val());
    this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);
});
$('#status_quoteStatus').change(function() {
    //alert($(this).val());
    this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);
});

$('#status_orderStatus').change(function() {
    //alert($(this).val());
    this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);
});

$('#status_invoiceStatus').change(function() {
    //alert($(this).val());
    this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);
});
$(function()
{

    /* Select2 - Advanced Select Controls */
    if (typeof $.fn.select2 != 'undefined')
    {
        // Basic
        if ($('#quote_account_name').length)
            $('#quote_account_name').select2();

        // Multiple
        if ($('#user_access_mode').length)
            $('#user_access_mode').select2();

        // Placeholders
        $("#quote_quote_send_to").select2({
            allowClear: true
        });

        $("#select2_4").select2({
            placeholder: "Select a State",
            allowClear: true
        });

        // tagging support
        $("#select2_5").select2({tags: ["red", "green", "blue"]});


        $("#quote_account_manager").select2({tags: ["red", "green", "blue"]});
        // enable/disable mode
        $("#select2_6_1").select2();
        $("#select2_6_2").select2();
        $("#select2_6_enable").click(function() {
            $("#select2_6_1,#select2_6_2").select2("enable");
        });
        $("#select2_6_disable").click(function() {
            $("#select2_6_1,#select2_6_2").select2("disable");
        });

        // templating
        function format(state) {
            if (!state.id)
                return state.text; // optgroup
            return "<img class='flag' src='http://ivaynberg.github.com/select2/images/flags/" + state.id.toLowerCase() + ".png'/>" + state.text;
        }
        $("#select2_7").select2({
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function(m) {
                return m;
            }
        });


    }



});