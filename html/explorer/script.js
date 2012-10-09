think_tank_bank = {};

$(document).ready(function(){ 
	$.getJSON("../api/thinktanks/", function(data){ 
		$('#explorer').append("<h2>Thinktanks</h2>");
		$.each(data['data'], function(index, val){ 
		
			$('#explorer').append("<p class='thinktank_title'>"  + val['name'] + " &nbsp; &nbsp;  <a href='#' class='publications_link' data-thinktank='" + val['thinktank_id'] + "' >Publications</a> | <a href='#' data-thinktank='" + val['thinktank_id'] + "' class='people_link'> People</a> </p>");
		}); 	
		
		think_tank_bank.add_click_handlers();
	}); 
	
	think_tank_bank.add_click_handlers = function() { 
		
		$('.people_link').click(function(){ 
			var thinktank = $(this).attr('data-thinktank');
			$('#explorer').html("<h2>People</h2>");
			$.getJSON("../api/people/?thinktank=" + thinktank, function(data){ 		
				
				$.each(data['data'], function(index, val){ 

					$('#explorer').append("<p>" + val['name_primary'] + "</p>");
				}); 	
			}); 
		})
		
		$('.publications_link').click(function(){ 
			var thinktank = $(this).attr('data-thinktank');
			$('#explorer').html("<h2>Publications</h2>");
			$.getJSON("../api/publications/?thinktank=" + thinktank, function(data){ 		
				
				$.each(data['data'], function(index, val){ 
					$('#explorer').append("<p>" + val['title'] + "</p>");
				}); 	
			}); 
		})		
		
		
		
	}; 
	
});
