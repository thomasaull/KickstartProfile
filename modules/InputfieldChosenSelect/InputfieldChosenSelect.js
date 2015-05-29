$(document).ready(function() {
	$(".InputfieldChosenSelect select:not([multiple])").each(function() {
		var $t = $(this); 

		if(typeof config === 'undefined') {
			var options = {};
		} else {
			var options = config[$t.attr('id')]; 
		}
    if($t.not("[required]")) $t.children().first().text("");
		$t.chosen(options); 
	}); 
}); 
