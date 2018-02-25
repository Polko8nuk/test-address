$(document).ready(function () {
    //получение списка город Ajax запросом, после выбора поля area
    $(".area").on('change' ,function () {
        var token = $('meta[name="csrf-token"]').attr('content');
        //удаляем старые значения
        $('.selcities').remove();
        //записываем в поле input значение поля area
        $("form input[name='title-area']").val($(".area option[value='" + $(this).val() + "']").text());

        $.ajax({
            type: "post",
            url: "/getcities",
            data: {
                id: $(this).val(),
                _token: token
            },
            success: function (data) {
                //отображение списка городов
                $.each(data, function (key, val) {
                    $('<option>').attr("value", val.title).addClass("selcities").insertAfter('.cities .plh').html(val.title);
                });
            }

        });
    });

    //по нажатии на кнопку Карта, получем ответ от Google Maps JavaScript API
    $('.uo_adr_list').on('click', '.button' , function () {

        var LAT = $(this).data('lat');
        var LNG = $(this).data('lng');

        //выполняем запрос с соответствующими координатами и получаем ответ в форме карты
        $.getScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyD3PbQCET-rlw4IFbcieE4C9Ay4Sh82XEw", function () {

            $('#map').css("display", "block");

            var uluru = {lat: LAT, lng: LNG};

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 18,
                center: uluru
            });
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        });
    });

    //при нажатии на любое место, кроме карты, карта закрывается
    $('.main').on('click', function () {
        $('#map').css("display", "none");
    });


});