$(document).ready(function(){
	var bindResizeOffset = function(){
		var updateBodyOffset = function(){
			var offset = $('#topbar').height() + 10;
			$('#content').css('margin-top', offset);
		}
		$(window).resize(function(e){
			updateBodyOffset();
		});
		updateBodyOffset();
	}();
});
