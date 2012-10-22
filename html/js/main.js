
$('#btn_search').click(function(){ 
	var query = $('#search_query').val(); 
	window.location = "/explore/search/"+query; 
});

$('.btn_gather').click(function(){ 
	var type 			= $(this).attr('data-type');
	var thinktank_id 	= $(this).attr('data-id');
	var iframe_id 		= "#" + type + "_" + thinktank_id; 
	var name			= $(this).attr('data-name');
	var scrape_address 	= "people/" + name + ".php"; 
	
	$(iframe_id).attr('src', "people/" + name + ".php");
	$(iframe_id).css('height', '400px');
});	
