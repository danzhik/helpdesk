$(document).ready(function (){
    $("#login_form").submit(function(e) {
    
    
        var form = $(this);
        var url = form.attr('action');
        $.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: 'json',
               success: function(data)
               {
                  if (data['success'] === true){
                   alert(data['message']); // show response from the php script.
                   location.reload()
                  } else {
                    alert(data['message']); // show response from the php script.
                  }
               }
             });
    
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
});