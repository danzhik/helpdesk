$(document).ready(function (){
    $("#application_org").submit(function(e) {
    
    
        var form = $(this);
        var url = form.attr('action');
        console.log (form.serialize());
        $.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: 'json',
               success: function(data)
               {
                   alert(data['message']); // show response from the php script.
               }
             });
    
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
});