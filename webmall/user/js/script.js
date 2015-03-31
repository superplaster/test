/**
 * -------------------------------------------
 * @author			Prodzect
 * @copyright		Copyright (c) 2008-2012              
 * @author page http://llb.lt
 * @contacts		prodzect@gmail.com
 * --------------------------------------------
**/
if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0) {
	var _interval = window.setInterval(function () {
		var autofills = jQuery('input:-webkit-autofill');
			if (autofills.length > 0) {
				window.clearInterval(_interval);
				autofills.each(function() {
					var clone = jQuery(this).clone(true, true);
          jQuery(this).after(clone).remove();
        });
      }
  }, 200);
}

function showLoader() {
	jQuery.blockUI({ 
    message: 'Loading..'
  });
}