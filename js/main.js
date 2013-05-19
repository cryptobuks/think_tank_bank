$(document).ready(function(){ 

   $('#myTab a:first').tab('show'); 
   
   $('.person_link').click(function(){
       var html;
       var person_id = $(this).attr('data-id');
       
       $.get('fragments/person.php?person_id=' + person_id, function(html) { 
           $('#content_target').html(html);
       });
   });

   
  
   
   
   
});