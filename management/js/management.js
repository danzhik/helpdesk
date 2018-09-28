$(document).ready(function (){
    $("#login_form").submit(function(e) {
    
    
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

    $(".nav-tab").click(function () {

      $(".nav-tab-active").removeClass("nav-tab-active");
      $(this).addClass("nav-tab-active");
    
      id_to_disable = $(".active").attr("id");
      console.log(id_to_disable);
      $(".active").removeClass("active");
      $("#"+id_to_disable).addClass("inactive");

      $("#"+$(this).attr("id")+"_tab").removeClass("inactive");
      $("#"+$(this).attr("id")+"_tab").addClass("active");
    });
});

function logout(user_id) {
  $.ajax({
               type: "POST",
               url: './logging/logout.php',
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
}