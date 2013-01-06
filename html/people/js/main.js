$(document).ready({ 
	var origHeight = $("#person_listing").css('height');
	$("#person_listing").css({"height" : "80px"});
	$("#person_listing .toggle").bind("click", function(event){ toggleFoo(event, lastSearchesMidHeight); });
	
})