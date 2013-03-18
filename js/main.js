$(document).ready(function(){ 
	$(".toggle i").click(function(){
		if($(this).hasClass('icon-double-angle-down')) { 
			$(this).removeClass('icon-double-angle-down');
			$(this).addClass('icon-double-angle-up');
			window.test = $(this);
			var height = parseInt($(this).parent().prev().children('.row_height').height());
			console.log(height);
			if (height > 400) { 
				$(this).parent().prev().animate({'height': 400}, 500,function(){
					$(this).parent().find(".row_container").css('overflow', 'auto');	
				});
			}
			else { 
				$(this).parent().prev().animate({'height': height})
			}
		}
		else  { 
			$(this).removeClass('icon-double-angle-up');
			$(this).addClass('icon-double-angle-down');
			$(this).parent().prev().animate({'height': 260})
			$(this).parent().find(".row_container").css('overflow', 'hidden');
		}
	});
	
	$('.exchange_header').click(function(){
	    $(this).siblings('.exchange_body:first').toggle();
	});
})