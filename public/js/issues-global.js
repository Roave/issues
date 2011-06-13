/* -------------------------------------------------------------------------- */
/* ----| AUTOPOPULATE INPUT FIELD |------------------------------------------ */
/* -------------------------------------------------------------------------- */

function autoPopulate(targetElementID, defaultValue) {

    if ($(targetElementID).val() == '') {
        $(targetElementID).css('color','#999');
        $(targetElementID).val(defaultValue);
    }
    
    if ($(targetElementID).val() == defaultValue) {
        $(targetElementID).css('color','#999');
    }
    
    $(targetElementID).focus(function(){
        if ($(this).val() == defaultValue) {
            $(this).val('');
            $(this).css('color','#333');
        }
    });
    
    $(targetElementID).blur(function(){
        if ($(this).val() == '') {
            $(this).css('color','#999');
            $(this).val(defaultValue);
        } else if ($(this).val() == defaultValue) {
            $(this).css('color','#999');        
        } else {
            $(this).css('color','#333');
        }
    });
    
}
