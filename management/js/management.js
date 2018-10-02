$(document).ready(function (){ // запускаем скрипт только когда весь HTML загружен
    $("#login_form").submit(function(e) { // действие при отправки формы авторизации пользователя
    
    
        var form = $(this); // берем форму из HTML
        var url = form.attr('action'); // берем расположение исполняемого файла
        
        // выполняем POST запрос к исполняемому файлу
        $.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // сериализуем элементы формы
               dataType: 'json',
               success: function(data)
               {
                  if (data['success'] === true){ // в случае успеха перезагрузить страницу
                   alert(data['message']); // показать ответ из PHP скрипта
                   location.reload()
                  } else {
                    alert(data['message']); // показать ответ из PHP скрипта
                  }
               }
             });
    
        e.preventDefault(); // блокировать действие по умолчанию
    });

    $(".nav-tab").click(function () { // обработка переключений между влкдаками интерфейса администратора

      $(".nav-tab-active").removeClass("nav-tab-active"); // убираем класс активной вкладки с активной вкладки в данный момент
      $(this).addClass("nav-tab-active"); // добавляем класс активной вкладки к той, на которую кликнули
    
      id_to_disable = $(".active").attr("id"); // берем ID блока, который был в предыдущей активной вкладке
      
      $(".active").removeClass("active"); // убираем класс из старого активного блока
      $("#"+id_to_disable).addClass("inactive"); // делаем блок бывшей активной вкладки невидимым

      $("#"+$(this).attr("id")+"_tab").removeClass("inactive"); // делаем блок нвоой активной вкладки видимым
      $("#"+$(this).attr("id")+"_tab").addClass("active"); // добавляем класс к новой активной вкладке
    });
});

/**
 * Функция для обрабокт нажатия кнопки выхода из учетной записи
 */
function logout(user_id) {
  // делаем пустой POST запрос в исполняемый файл, который произведет выход
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