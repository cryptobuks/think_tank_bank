thinktanks = {};

thinktanks.person_id ='';
thinktanks.thinktank_name='';
thinktanks.content_filter ='';

$(document).ready(function() {
   //$('#myTab a:first').tab('show');
  thinktanks.clickEvents();
   
   //put the first link in the left hand box on the right...
   //var first_item = $('#thinktank li:first-child a').attr('data-thinktank-name');
   //thinktanks.selectThinkTank(first_item);
   
   //do table sorter
   $(".tablesorter").tablesorter({ 
       widgets: ['zebra'],
       sortList: [[2,1]]
   });
   
   var first_person = $('.person_link:first-child').attr('data-id');
   if (typeof first_person != 'undefined') { 
       thinktanks.selectPerson(first_person);
   }
   
   var first_link = $('.links_list li:first a').attr('data-target');
   if (typeof first_link != 'undefined') { 
       $('.links_list li:first').addClass('selected');
       thinktanks.selectLink(first_link);
   }
});


thinktanks.selectPerson = function(person_id) {
    
    $('#content_target').html("<img id='loading_balls' src='img/ajax-loader.gif' />" );
    
    $.get('fragments/person.php?person_id=' + person_id, function(html) {
        $('#content_target').html(html);
        thinktanks.updateGraph();
        $('.content_filter').click(function(){
            var filter = $(this).attr('data-filter');
            thinktanks.selectFilter(filter);
        });
        thinktanks.selectFilter('tweets');
        thinktanks.clickEvents();
        
    });
    
    $(".tweet_listing a").parent().css('background-color', 'white');
        
    thinktanks.person_id = person_id;
     
}

thinktanks.selectLink = function(expanded_url) {
    
    $('.link_display_target').html("<img id='loading_balls' src='img/ajax-loader.gif' />" );
    
    
    
    $.get('fragments/link.php?expanded_url=' + expanded_url, function(html) {
       
        $('.link_display_target').html(html);
                
    });

     
}


thinktanks.selectThinkTank = function(thinktank_name) {
    
    $('#content_target').html("<img id='loading_balls' src='img/ajax-loader.gif' />" );
    
    $.get('fragments/thinktank.php?thinktank_name=' + thinktank_name, function(html) {
        $('#content_target').html(html);
        
        $('.content_filter').click(function(){
            var filter = $(this).attr('data-filter');
            thinktanks.selectFilter(filter);
        });
        thinktanks.selectFilter('tweets');
        thinktanks.clickEvents();
        thinktanks.updateGraph();
    });
    
    $(".tweet_listing a").parent().css('background-color', 'white');
       
    thinktanks.thinktank_name = thinktank_name;
}


thinktanks.updateGraph = function() {
    Morris.Donut({
      element: 'followers-donut',
      data: followers_json,
      colors:followers_colors
    });

    
    if(typeof followees_json !== 'undefined' && followees_json.length > 1) {
        Morris.Donut({
          element: 'followees-donut',
          data: followees_json,
          colors:followees_colors
        });
    }
    
}


thinktanks.clickEvents = function(){
    $(".person_link").unbind("click");
    $('.person_link').click(function(){
        var person_id = $(this).attr('data-id');
        thinktanks.selectPerson(person_id);
    });
    
    $(".link_link").unbind("click");
    $('.link_link').click(function(){
        $('.links_list li').removeClass('selected');
        
        $(this).parent().addClass('selected');
        var link_id = $(this).attr('data-target');
        thinktanks.selectLink(link_id);
    });
    
    $(".thinktank_link").unbind("click");
    $('.thinktank_link').click(function(){
        var thinktank_name = $(this).attr('data-thinktank-name');
        thinktanks.selectThinkTank(thinktank_name);
    });
    
    $(".hashtag_link").unbind("click");
    $('.hashtag_link').click(function(){
        var hashtag = $(this).attr('data-hashtag');
        thinktanks.selectHashtag(hashtag);
    });
    
    $("#search_submit").unbind("click");
    $('#search_submit').click(function(){
        
        var term = $('#search_text').val();
        var type = $('#search_type').val();
        thinktanks.search(term, type);
        console.log(type);
    });
    
    $("#search_text").unbind("click");
    $('#search_text').click(function(){
        if($('#search_text').val() === 'search') {
            $('#search_text').val('');
        }
    });
}
