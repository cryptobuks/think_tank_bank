$(document).ready(function(){ 

   $('#myTab a:first').tab('show'); 
   
   $('.person_link').click(function(){
       var html;
       var person_id = $(this).attr('data-id');
       
       $.get('fragments/person.php?person_id=' + person_id, function(html) { 
           $('#content_target').html(html);
       });
   });

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
  
   
   
   
});