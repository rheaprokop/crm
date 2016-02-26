$(function()
{
	/*
	 * Input Masks
	 */
	$.extend($.inputmask.defaults, {
        'autounmask': true
    });
    $(".inputmaskphone").inputmask("mask", {"mask": "+999 999 999 999"});  
      
    $(".inputmasknumber").inputmask("999999999");  //static mask
    $(".inputmasktax").inputmask({"mask": "99-9999999"});
    $(".inputmaskdate").inputmask("d/m/y", {autoUnmask: true});
    $(".inputmaskdate-1").inputmask("d/m/y",{ "placeholder": "*"});
    $(".inputmaskdate-2").inputmask("d/m/y",{ "placeholder": "dd/mm/yyyy" });
    $(".inputmaskdecimal").inputmask('decimal', { rightAlignNumerics: false });
    $(".inputmaskcurrency").inputmask('\u20AC 999,999,999.99', { numericInput: true, rightAlignNumerics: false, greedy: false});
    $(".inputmaskssn").inputmask("999-99-9999", {clearMaskOnLostFocus: true });

});