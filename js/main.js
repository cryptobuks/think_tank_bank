$(document).ready(function(){ 
    
    
    $('.keyword_search').click(function(){
        var query_string= encodeURIComponent($(this).text());
        
        $('.keyword_search').removeClass('active');
        $(this).addClass('active');
       
        
        $.get("/fragments/tweets_by_keyword.php?query_string=" + query_string, function(data){
           $('#keyword_search_results').html('');
           
           $.each(data.results, function(key,val){
               $('#keyword_search_results').append('<strong><a href="">' + val.twitter_handle + '</a></strong> ');
               $('#keyword_search_results').append('<strong>' + val.name + '</strong> ');
               $('#keyword_search_results').append('<span href="">' + val.text + '</span><br/><br/>');
           });
        });
    });
    
    $('.keyword_search').filter(":first").trigger('click');
});