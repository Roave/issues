/**
 * This is for compatibility with ZendX_JQuery_Form_Element_ColorPicker.
 * Apparently jQuery UI had a colorpicker but they dropped it.
 * @author Evan Coury (http://blog.evan.pro/)
 */
(function($){
  $.fn.colorpicker = function(o, data) {
    this.miniColors(o, data);
  };
})( jQuery );
