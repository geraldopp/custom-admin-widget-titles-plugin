jQuery(document).ready(function(){
	
	// --------------- Widgets title names ---------------
	
	jQuery('.in-widget-title').css('display', 'none');
	
	jQuery('.button.widget-control-save').on('click', function(){
		makeWidgetsTitles(jQuery(this));
	});
	
	jQuery('.DM-custom-widget-title').keypress(function (e) {
		var allowedChars = new RegExp("^[a-zA-Z0-9\- ]+$");
		var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
		if (allowedChars.test(str)) {
			return true;
		}
		e.preventDefault();
		return false;
	}).keyup(function() {
		var forbiddenChars = new RegExp("[^a-zA-Z0-9\- ]", 'g');
		if (forbiddenChars.test(jQuery(this).val())) {
			jQuery(this).val(jQuery(this).val().replace(forbiddenChars, ''));
		}
	});
	
	function makeWidgetsTitles(target){
		var wtitle = target.closest('.widget').find('.DM-custom-widget-title').val();
		if(wtitle !== ''){
			target.closest('.widget').find('.widget-title').html('<h3>'+wtitle+'</h3>');
		}
	}
	
});