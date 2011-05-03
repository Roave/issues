/* -------------------------------------------------------------------------- */
/* ----| SEARCH AUTOPOPULATE |----------------------------------------------- */
/* -------------------------------------------------------------------------- */

$(document).ready(function() {
    $siteSearch = $('#siteSearchInput');
    $defaultSearchValue = 'Search issues and milestones...';

    if ($siteSearch.val() == '') {
        $siteSearch.css('color','#999');
        $siteSearch.val($defaultSearchValue);
    }
    
    if ($siteSearch.val() == $defaultSearchValue) {
        $siteSearch.css('color','#999');
    }
    
    $siteSearch.focus(function(){
        if ($(this).val() == $defaultSearchValue) {
            $(this).val('');
            $(this).css('color','#333');
        }
    });
    
    $siteSearch.blur(function(){
        if ($(this).val() == '') {
            $(this).css('color','#999');
            $(this).val($defaultSearchValue);
        } else if ($(this).val() == $defaultSearchValue) {
            $(this).css('color','#999');        
        } else {
            $(this).css('color','#333');
        }
    });
});