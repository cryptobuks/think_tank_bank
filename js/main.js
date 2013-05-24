thinktanks = {};

thinktanks.person_id ='';
thinktanks.thinktank_name='';
thinktanks.content_filter ='';

$(document).ready(function() {
   $('#myTab a:first').tab('show'); 
   thinktanks.clickEvents();
});


thinktanks.selectPerson = function(person_id) { 
    
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
    
    $(".tweet_listing [data-id='" + person_id + "']").parent().css('background-color', '#ccc');
    
    thinktanks.person_id = person_id;    
} 


thinktanks.selectThinkTank = function(thinktank_name) { 
    
    $.get('fragments/thinktank.php?thinktank_name=' + thinktank_name, function(html) { 
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
    
    $(".tweet_listing [data-thinktank-name='" + thinktank_name + "']").parent().css('background-color', '#ccc');
    
    thinktanks.thinktank_name = thinktank_name;    
}


thinktanks.selectFilter = function(filter) { 
    thinktanks.content_filter = filter;
    
    $('.infosection').css('display', 'none');
    var selector = '#'+ filter; 
    console.log(selector);
    $(selector).css('display', 'block');
    
    //this because SVG gets confused about how big it is if it's hidden 
    var width = $('.span4').width();
    console.log(width);
    $('#followers-donut, #followees-donut').css('width', width);
    $('#followers-donut, #followees-donut').css('height', width);  
    thinktanks.updateGraph();  
}

thinktanks.updateGraph = function() { 
    Morris.Donut({
      element: 'followers-donut',
      data: followers_json,
      colors:followers_colors
    });
    
    Morris.Donut({
      element: 'followees-donut',
      data: followees_json,
      colors:followees_colors
    });
    
        
}


thinktanks.clickEvents = function(){ 
    $("#saveBtn").unbind("click");
    $('.person_link').click(function(){

        var person_id = $(this).attr('data-id');
    
        thinktanks.selectPerson(person_id);
    });
    
    $('.thinktank_link').click(function(){
        var thinktank_name = $(this).attr('data-thinktank-name');
        thinktanks.selectThinkTank(thinktank_name);
    });


}