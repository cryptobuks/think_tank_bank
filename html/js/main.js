
//FOR THE EXPLORE SECTION 

$('#btn_search').click(function(){ 
	var query = $('#search_query').val(); 
	window.location = "/explore/search/"+query; 
});

$(".save_tags_btn").click(function(){ 
	var tags = $(this).siblings().val();
	tags = encodeURIComponent(tags);
	
	var pub_id = $(this).siblings().attr('data-pub_id');
	var url = '/api/publications/save_tags.php?pub_id=' + pub_id + '&tags=' + tags ; 
	
	$(this).parent().append("<img src='/img/ajax-loader-balls.gif' class='loader' />"); 
		
	$.getJSON(url, function(data){ 
		$(this).parent('.loader').remove();
		console.log(data);
	});
	
	
});


//FOR THE GATHER SECTION 

$('.btn_gather').click(function(){ 
	var type 			= $(this).attr('data-type');
	var thinktank_id 	= $(this).attr('data-id');
	var iframe_id 		= "#" + type + "_" + thinktank_id; 
	var name			= $(this).attr('data-name');
	var debug			= $(this).attr('data-debug');
	var scrape_address 	= "people/" + name + ".php"; 
	if (type==='people') { 
		$(iframe_id).attr('src', "people/" + name + ".php?debug=" + debug);
	}
	if (type==='publication') { 
		$(iframe_id).attr('src', "publications/" + name + ".php?debug=" + debug);	
	}
	$(iframe_id).css('height', '400px');
});	
