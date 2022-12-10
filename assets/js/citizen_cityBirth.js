import jQuery from "jquery";

var j_citizen_cityBirth;


    $("#citizen_cityBirth").change(function () {
        $("li").remove('.list-group-item');
        if ($(this).val().length > 2) {
          if (j_citizen_cityBirth) {
            j_citizen_cityBirth.abort();
          }
            var s = queryurl + "/" + $(this).val();
            j_citizen_cityBirth = $.ajax({
                url: s,
                dataType: 'json',
                beforeSend: function (xhr) {
                    xhr.overrideMimeType("text/plain; charset=x-user-defined");
                }
            })
                    .done(function (data) {
                        $("li").remove('.list-group-item');
                        $.each(data, function (key, value) {
                            //console.log(value);
                            if (value.name)
                            {
                                $('#citizen_cityBirth_result').append('<li class="list-group-item link-class" prov="' + value.Province + '" state="Italia" cap="' + value.Cap+'">\n' + value.name + ' - ' + value.Province + '</li>');
                            }
                        });
                    });
        }
    });
    
    $('#citizen_cityBirth_result').on('click', 'li', function () {
        var click_text = $(this).text().split('-');
        $('#citizen_cityBirth').val($.trim(click_text[0]));
        $("li").remove('.list-group-item');
    });

