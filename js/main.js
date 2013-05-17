$(document).ready(function(){ 
    
    
    $('.keyword_search').click(function(){
        var query_string= encodeURIComponent($(this).text());
        
        $('.keyword_search').removeClass('active');
        $(this).addClass('active');
       
        
        $.get("/fragments/tweets_by_keyword.php?query_string=" + query_string, function(data){
           $('#keyword_search_results').html('');
           
           $.each(data.results, function(key,val){
               console.log(val);
               $('#keyword_search_results').append('<strong>' + val.name_primary + '</strong> ');
               if (val.organisation_type == 'thinktank') { 
                   $('#keyword_search_results').append('<em> (' + val.thinktank_name + ')</em> ');
               }
               else { 
                   $('#keyword_search_results').append('<em> (' + val.organisation_type + ')</em> ');
               }
               $('#keyword_search_results').append('<span href="">' + val.text + '</span><br/><br/>');
           });
        });
    });
    
    $('.keyword_search').filter(":first").trigger('click');
});