
$('#btn_search').click(function(){ 
	var query = $('#search_query').val(); 
	window.location = "/explorer/search/"+query; 
});
