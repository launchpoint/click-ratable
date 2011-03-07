$(document).ready( function() {
  jQuery.fn.dec = function() {
		val = parseInt($(this).html());
		val--;
		$(this).html(val);  
  };
  
  jQuery.fn.inc = function()
  {
  		val = parseInt($(this).html());
  		val++;
  		$(this).html(val);  
  };
  

});
