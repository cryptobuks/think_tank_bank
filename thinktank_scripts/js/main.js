
//FOR THE EXPLORE SECTION 

$(document).ready(function() { 

	$('#btn_search').click(function(){ 
		var query = $('#search_query').val(); 
		window.location = "explore/search/"+query; 
	});

	$(".save_tags_btn").click(function(){ 
		var tags = []; 
		var tags_string
		var pub_id = $(this).attr('data-pub-id');
		console.log(pub_id);
		var tag_elems = $('span [data-pub-id="'+pub_id+'"]:checked');

		$.each(tag_elems, function(key, val){
			tags.push($(val).val());
			console.log($(val).val());
		}); 
	
		tags_string = encodeURIComponent(tags.join());
	
		var url = '/api/publications/save_tags.php?pub_id=' + pub_id + '&tags=' + tags_string; 
	
		$(this).parent().append("<img src='/img/ajax-loader-balls.gif' class='loader' />"); 
		
		$.getJSON(url, function(data){ 
			$('.loader').remove();
			console.log(data);
		
		});
	});



	$(".save_twitter_btn").click(function(){ 

		var twitter_handle = $(this).siblings().val();
		twitter_handle = encodeURIComponent(twitter_handle);
	
		var person_id = $(this).siblings().attr('data-person_id');
		var url = '/api/people/save_twitter_handle.php?person_id=' + person_id + '&twitter_handle=' + twitter_handle ; 
	
		$(this).parent().append("<img src='/img/ajax-loader-balls.gif' class='loader' />"); 
		
		$.getJSON(url, function(data){ 
			$('.loader').remove();
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

});






