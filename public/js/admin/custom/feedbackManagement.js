$(document).ready(function(){
   $(document).on('click','.operBut',function(e){
       var url= $(this).attr("data-url");
       var id =  $(this).attr("data-id");
       var ajaxurl = admin_url+"#"+url;
       window.location.hash=url;
         
   })
})