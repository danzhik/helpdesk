$(document).ready(function (){ //запускаем работу скрипта только когда весь html загрузился
    $("#application_org").submit(function(e) { //событие отправки формы заявки
 
        var form = $(this); //получаем форму со страницы
        var url = form.attr('action'); //получаем расположения выполняемого файла
        $.ajax({ //делаем POST запрос к базе данных через исполняемый файл для добавления новой заявки
               type: "POST",
               url: url,
               data: form.serialize(), // сериализация элементов формы
               dataType: 'json',
               success: function(data)
               {
                   alert(data['message']); // показываем ответ, который пришел из php скрипта
               }
             });
    
        e.preventDefault(); // отключаем стоковый функционал кнопки
    });
});