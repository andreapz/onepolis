var $collectionHolder;

// setup an "add a ticket" link
var $addTicketLink = $('<a href="#" class="add_ticket_link">Add a ticket</a>');
var $newLinkLi = $('<li></li>').append($addTicketLink);

jQuery(function () {
    // Get the ul that holds the collection of tickets
    $collectionHolder = $('ul.tickets');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function () {
        addTicketFormDeleteLink($(this));
    });

    // add the "add a ticket" anchor and li to the tickets ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTicketLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new ticket form (see next code block)
        addTicketForm($collectionHolder, $newLinkLi);
    });
});

function addTicketForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tickets field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a ticket" link li
    var $newFormLi = $('<li class="ticket_form"></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    addTicketFormDeleteLink($newFormLi);
}

function addTicketFormDeleteLink($ticketFormLi) {
    var $removeFormA = $('<a href="#">delete this ticket</a>');
    $ticketFormLi.append($removeFormA);

    $removeFormA.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $ticketFormLi.remove();
    });
}

//// Ticket Restaurant

var $collectionRestaurantHolder;

// setup an "add a ticket" link
var $addTicketRestaurantLink = $('<a href="#" class="add_ticket_restaurant_link">Add a restaurant ticket</a>');
var $newRestaurantLinkLi = $('<li></li>').append($addTicketRestaurantLink);

jQuery(function () {
    // Get the ul that holds the collection of tickets
    $collectionRestaurantHolder = $('ul.tickets_restaurant');

    // add a delete link to all of the existing tag form li elements
    $collectionRestaurantHolder.find('li.ticket_restaurant_form').each(function () {
        addTicketRestaurantFormDeleteLink($(this));
    });

    // add the "add a ticket" anchor and li to the tickets ul
    $collectionRestaurantHolder.append($newRestaurantLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionRestaurantHolder.data('index', $collectionRestaurantHolder.find(':input').length);

    $addTicketRestaurantLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new ticket form (see next code block)
        addTicketRestaurantForm($collectionRestaurantHolder, $newRestaurantLinkLi);
    });
});

function addTicketRestaurantForm($collectionRestaurantHolder, $newRestaurantLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionRestaurantHolder.data('prototype');

    // get the new index
    var index = $collectionRestaurantHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tickets field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionRestaurantHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a ticket" link li
    var $newFormLi = $('<li class="ticket_restaurant_form"></li>').append(newForm);
    $newRestaurantLinkLi.before($newFormLi);

    addTicketRestaurantFormDeleteLink($newFormLi);
}

function addTicketRestaurantFormDeleteLink($ticketFormLi) {
    var $removeFormA = $('<a href="#">delete this ticket</a>');
    $ticketFormLi.append($removeFormA);

    $removeFormA.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $ticketFormLi.remove();
    });
}


//// Ticket HOTEL

var $collectionHotelHolder;

// setup an "add a room" link
var $addRoomHotelLink = $('<a href="#" class="add_room_hotel_link">Add a hotel room</a>');
var $newHotelLinkLi = $('<li></li>').append($addRoomHotelLink);

jQuery(function () {
    // Get the ul that holds the collection of rooms
    $collectionHotelHolder = $('ul.rooms_hotel');

    // add a delete link to all of the existing tag form li elements
    $collectionHotelHolder.find('li.room_hotel_form').each(function () {
        addRoomHotelFormDeleteLink($(this));
    });

    // add the "add a room" anchor and li to the rooms ul
    $collectionHotelHolder.append($newHotelLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHotelHolder.data('index', $collectionHotelHolder.find(':input').length);

    $addRoomHotelLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new room form (see next code block)
        addRoomHotelForm($collectionHotelHolder, $newHotelLinkLi);
    });
});

function addRoomHotelForm($collectionHotelHolder, $newHotelLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHotelHolder.data('prototype');

    // get the new index
    var index = $collectionHotelHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your rooms field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHotelHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a room" link li
    var $newFormLi = $('<li class="room_hotel_form"></li>').append(newForm);
    $newHotelLinkLi.before($newFormLi);

    addRoomHotelFormDeleteLink($newFormLi);
}

function addRoomHotelFormDeleteLink($roomFormLi) {
    var $removeFormA = $('<a href="#">delete this room</a>');
    $roomFormLi.append($removeFormA);

    $removeFormA.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $roomFormLi.remove();
    });
}

var $collectionRoomHolder;

// setup an "add a ticket" link
var $addTicketRoomLink = $('<a href="#" class="add_ticket_room_link">Add a room ticket</a>');
var $newRoomLinkLi = $('<li></li>').append($addTicketRoomLink);

jQuery(function () {
    // Get the ul that holds the collection of tickets
    $collectionRoomHolder = $('ul.tickets_room');

    // add a delete link to all of the existing tag form li elements
    $collectionRoomHolder.find('li.ticket_room_form').each(function () {
        addTicketRoomFormDeleteLink($(this));
    });

    // add the "add a ticket" anchor and li to the tickets ul
    $collectionRoomHolder.append($newRoomLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionRoomHolder.data('index', $collectionRoomHolder.find(':input').length);

    $addTicketRoomLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new ticket form (see next code block)
        addTicketRoomForm($collectionRoomHolder, $newRoomLinkLi);
    });
});

function addTicketRoomForm($collectionRoomHolder, $newRoomLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionRoomHolder.data('prototype');

    // get the new index
    var index = $collectionRoomHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tickets field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionRoomHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a ticket" link li
    var $newFormLi = $('<li class="ticket_room_form"></li>').append(newForm);
    $newRoomLinkLi.before($newFormLi);

    addTicketRoomFormDeleteLink($newFormLi);
}

function addTicketRoomFormDeleteLink($ticketFormLi) {
    var $removeFormA = $('<a href="#">delete this ticket</a>');
    $ticketFormLi.append($removeFormA);

    $removeFormA.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $ticketFormLi.remove();
    });
}

jQuery(function () {

    if (typeof restaurantcosts !== 'undefined') {

        restaurantcosts.forEach(function (entry) {
            console.log(entry);
            $('#citizen_restaurantCosts_' + entry).prop('checked', true);
        });
    }

});

jQuery(function () {

    if (typeof roomcosts !== 'undefined') {

        roomcosts.forEach(function (entry) {
            $('#citizen_rooms option').each(function(){
            if ($(this).val() === entry) {
                $(this).attr('selected','selected');
            }
         });
        });
        
       
    }

    if (typeof mealcosts !== 'undefined') {

        mealcosts.forEach(function (entry) {
            //console.log(entry);
            $('#citizen_meals_' + entry).prop('checked', true);
        });
    }

    if (typeof ticketcosts !== 'undefined') {

        ticketcosts.forEach(function (entry) {
            $('#citizen_tickettypes option').each(function(){
            if ($(this).val() === entry) {
                $(this).attr('selected','selected');
            }
         });
        });
    }
    
    if (typeof ticketbuses !== 'undefined') {

        ticketbuses.forEach(function (entry) {
            //console.log(entry);
            $('#citizen_buses_' + entry).prop('checked', true);
        });
    }
    
    if (typeof mealscount !== 'undefined') {

        for(var m in mealscount) {
            f = mealscount[m]['t'] - mealscount[m]['c'];
            $('#citizen_meals_' + mealscount[m]['id']).parent().append(" [" + f + "]");
            if (f <= 0) {
                $('#citizen_meals_' + mealscount[m]['id']).attr("disabled", true);
            }
        }
            
        /*mealscount.forEach(function (entry) {
            console.log(entry);
            $('#citizen_meals_' + entry).parent().append(entry)
        });*/
    }
    
     if (typeof roomscount !== 'undefined') {

        $('#citizen_rooms option').each(function(){
            var optext = $(this).text();
            var opi = $(this).val();
            f = roomscount[opi]['t'] - roomscount[opi]['c'];
            $(this).text(optext + ' [' + f + ']');
            if (f <= 0) {
                $(this).attr('disabled','disabled') 
            }
            //$this.text($this.text().replace(" ","-"));    
            //console.log($this);
         });
        /*for(var m in roomscount) {
            f = roomscount[m]['t']-roomscount[m]['c'];
            $('#citizen_meals_' + roomscount[m]['id']).parent().append(" [" + f + "]");
            if (f <= 0) {
                $('#citizen_meals_' + roomscount[m]['id']).attr("disabled", true);
            }
        }*/
    }
    
    if (typeof roommeals !== 'undefined') {
        $('#citizen_rooms').change(function() {
            //console.log($(this).val());
            $('#citizen_meals').find(':checkbox').each(function(){
                $(this).prop('checked', false);
            }); 
            r = $(this).val();
            for(var m in roommeals[r]) {
              //console.log(m);  
              if (roommeals[r][m] == 's') {
                $('#citizen_meals_' + m).prop('checked', true);
              }
            }
            //'citizen_meals_'
        });
    }
    
    if (typeof hotelselected !== 'undefined') {

        hotelselected.forEach(function (entry) {
            //console.log(entry);
            $('#hotel_real_hotels_' + entry).prop('checked', true);
        });
    }
    
    if (typeof restaurantselected !== 'undefined') {

        restaurantselected.forEach(function (entry) {
            //console.log(entry);
            $('#restaurant_real_restaurants_' + entry).prop('checked', true);
        });
    }

});


// listen to the submit event
$('#task_order').on('submit', function (e) {
    // prevent form from being submitted
    e.preventDefault();

    confirmDelete();
});

function confirmDelete() {
    var result = confirm('Are you sure you want to delete this question?');

    // I do not know what result returns but in case that yes is true
    if (result === true) {
        $('#delete_form').submit();
    }
}

function loadTask() {
    $.post(urlt, function(response) {
        console.log(response);
    }, 'JSON');
}

$(document).ready(AjaxInit());



function AjaxInit() {
    var j_citizen_address_city;
    $("#citizen_address_city").on("keyup", function () {
        $("li").remove('.list-group-item');
        if ($(this).val().length > 2) {
          if (j_citizen_address_city) {
            j_citizen_address_city.abort();
          }
            var s = queryurl + "/" + $(this).val();
            j_citizen_address_city = $.ajax({
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
                                $('#cityresult').append('<li class="list-group-item link-class" prov="' + value.Province + '" state="Italia" cap="' + value.Cap+'">\n' + value.name + ' - ' + value.Province + '</li>');
                            }
                        });


                    });
        }
    });

    $('#cityresult').on('click', 'li', function () {
        var click_text = $(this).text().split('-');
        var prov = $(this).attr('prov');
        var cap = $(this).attr('cap');
        var state = $(this).attr('state');
        $('#citizen_address_city').val($.trim(click_text[0]));
        $('#citizen_address_province').val(prov);
        $('#citizen_address_postcode').val(cap);
        $('#citizen_address_state').val(state);
        $("li").remove('.list-group-item');
    });

    var j_event_address_city;
    $("#event_address_city").on("keyup", function () {
        $("li").remove('.list-group-item');
        if ($(this).val().length > 2) {
          if (j_event_address_city) {
            j_event_address_city.abort();
          }
            var s = queryurl + "/" + $(this).val();
            j_event_address_city = $.ajax({
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
                                $('#event_city_result').append('<li class="list-group-item link-class" prov="' + value.Province + '" state="Italia" cap="' + value.Cap+'">\n' + value.name + ' - ' + value.Province + '</li>');
                            }
                        });
                    });
        }
    });

    $('#event_city_result').on('click', 'li', function () {
        var click_text = $(this).text().split('-');
        var prov = $(this).attr('prov');
        var cap = $(this).attr('cap');
        var state = $(this).attr('state');
        $('#event_address_city').val($.trim(click_text[0]));
        $('#event_address_province').val(prov);
        $('#event_address_postcode').val(cap);
        $('#event_address_state').val(state);
        $("li").remove('.list-group-item');
    });

    var j_hotel_real_address_city;
    $("#hotel_real_address_city").on("keyup", function () {
        $("li").remove('.list-group-item');
        if ($(this).val().length > 2) {
          if (j_hotel_real_address_city) {
            j_hotel_real_address_city.abort();
          }
            var s = queryurl + "/" + $(this).val();
            j_hotel_real_address_city = $.ajax({
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
                                $('#hotelrealcityresult').append('<li class="list-group-item link-class" prov="' + value.Province + '" state="Italia" cap="' + value.Cap+'">\n' + value.name + ' - ' + value.Province + '</li>');
                            }
                        });


                    });
        }
    });

    $('#hotelrealcityresult').on('click', 'li', function () {
        var click_text = $(this).text().split('-');
        var prov = $(this).attr('prov');
        var cap = $(this).attr('cap');
        var state = $(this).attr('state');
        $('#hotel_real_address_city').val($.trim(click_text[0]));
        $('#hotel_real_address_province').val(prov);
        $('#hotel_real_address_postcode').val(cap);
        $('#hotel_real_address_state').val(state);
        $("li").remove('.list-group-item');
    });

    var j_restaurant_real_address_city;
    $("#restaurant_real_address_city").on("keyup", function () {
        $("li").remove('.list-group-item');
        if ($(this).val().length > 2) {
          if (j_restaurant_real_address_city) {
            j_restaurant_real_address_city.abort();
          }
            var s = queryurl + "/" + $(this).val();
            j_restaurant_real_address_city = $.ajax({
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
                                $('#restaurantrealcityresult').append('<li class="list-group-item link-class" prov="' + value.Province + '" state="Italia" cap="' + value.Cap+'">\n' + value.name + ' - ' + value.Province + '</li>');
                            }
                        });


                    });
        }
    });

    $('#restaurantrealcityresult').on('click', 'li', function () {
        var click_text = $(this).text().split('-');
        var prov = $(this).attr('prov');
        var cap = $(this).attr('cap');
        var state = $(this).attr('state');
        $('#restaurant_real_address_city').val($.trim(click_text[0]));
        $('#restaurant_real_address_province').val(prov);
        $('#restaurant_real_address_postcode').val(cap);
        $('#restaurant_real_address_state').val(state);
        $("li").remove('.list-group-item');
    });



    // var j_citizen_cityBirth;
    // $("#citizen_cityBirth").on("keyup", function () {
    //     $("li").remove('.list-group-item');
    //     if ($(this).val().length > 2) {
    //       if (j_citizen_cityBirth) {
    //         j_citizen_cityBirth.abort();
    //       }
    //         var s = queryurl + "/" + $(this).val();
    //         j_citizen_cityBirth = $.ajax({
    //             url: s,
    //             dataType: 'json',
    //             beforeSend: function (xhr) {
    //                 xhr.overrideMimeType("text/plain; charset=x-user-defined");
    //             }
    //         })
    //                 .done(function (data) {
    //                     $("li").remove('.list-group-item');
    //                     $.each(data, function (key, value) {
    //                         //console.log(value);
    //                         if (value.name)
    //                         {
    //                             $('#citizen_cityBirth_result').append('<li class="list-group-item link-class" prov="' + value.Province + '" state="Italia" cap="' + value.Cap+'">\n' + value.name + ' - ' + value.Province + '</li>');
    //                         }
    //                     });
    //                 });
    //     }
    // });

    // $('#citizen_cityBirth_result').on('click', 'li', function () {
    //     var click_text = $(this).text().split('-');
    //     $('#citizen_cityBirth').val($.trim(click_text[0]));
    //     $("li").remove('.list-group-item');
    // });

    var j_citizen_delegate_custom;
    $("#citizen_delegate_custom").on("keyup", function () {
        $("li").remove('.list-group-item');
        if ($(this).val().length == 0) {
            $('#citizen_delegate').val(0);
        }
        if ($(this).val().length > 2) {
          if (j_citizen_delegate_custom) {
            j_citizen_delegate_custom.abort();
          }
            var s = citizenqueryurl + "/" + $(this).val();
            j_citizen_delegate_custom = $.ajax({
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
                                $('#citizen_delegate_result').append('<li class="list-group-item link-class" id="' + value.id + '">\n' + value.name + ' ' + value.surname + '</li>');
                            }
                        });
                    });
        }
    });

    $('#citizen_delegate_result').on('click', 'li', function () {
        var click_text = $(this).text().split('-');
        var click_id = $(this).attr('id');

        $('citizen_delegate_detail').remove('#citizen_detail');
        $('#citizen_delegate').val(0);

        $('#citizen_delegate_id').val(click_id);
        $('#citizen_delegate_custom').val($.trim(click_text[0]));
        //$('#citizen_delegate_detail').append('<div id="citizen_detail">'+$.trim(click_text[0])+'</div>');
        $("li").remove('.list-group-item');

        $('#citizen_delegate').val(click_id);

    });

    $('.confirm-delete').on('click', function(e) {
        e.preventDefault();
        $('#modal-from-dom').modal('show');
    });

    $('.show-reset-order').on('click', function(e) {
        e.preventDefault();
        $('#modal-from-dom-reset-order').modal('show');
    });

    $('.show-add-payment').on('click', function(e) {
        e.preventDefault();
        $('#modal-from-dom').modal('show');
    });

    $('.show-add-room').on('click', function(e) {
        e.preventDefault();
        $('#modal-from-dom').modal('show');
    });

    $('.delete-payment').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');
        $('#modal-delete-payment').attr('href', href);
        $('#modal-delete-payment').attr('rel', rel);
        $('#modal-from-dom-payment-delete').modal('show');
    });

    $('#modal-delete-payment').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');

        $.post(href, function(response) {
            if (response['status']=='OK') {
                $('#'+rel).remove();
            }
            $('#modal-from-dom-payment-delete').modal('hide');
            $('#payment-diff').text(response['diff']);
            $('#payment-total').text(response['total']);
        }, 'JSON');
    });

    $('.delete-room').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');
        $('#modal-delete-room').attr('href', href);
        $('#modal-delete-room').attr('rel', rel);
        $('#modal-from-dom-room-delete').modal('show');
    });

    $('#modal-delete-room').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');

        $.post(href, function(response) {
            if (response['status']=='OK') {
                $('#'+rel).remove();
            }
            $('#modal-from-dom-room-delete').modal('hide');
        }, 'JSON');
    });

    var j_citizen_task;

    $("#citizen_task").on("keyup", function () {
        $("#tasksearch li").remove('.list-group-item');
        if ($(this).val().length == 0) {
            $('#citizen_task').val("");
        }
        if ($(this).val().length > 2) {
            if (j_citizen_task) {
              j_citizen_task.abort();
            }
            var s = citizenqueryurl + "/" + $(this).val();
            j_citizen_task = $.ajax({
                url: s,
                dataType: 'json',
                beforeSend: function (xhr) {
                    xhr.overrideMimeType("text/plain; charset=x-user-defined");
                }
            })
                    .done(function (data) {
                        $("#tasksearch li").remove('.list-group-item');
                        $.each(data, function (key, value) {
                            //console.log(value);
                            s = value.name != null ? value.name : '';
                            s += value.surname != null ? ' ' + value.surname : '';
                            s += value.cityb != null ? ' ' + value.cityb : '';
                            d = value.dateb.date.substring(0, 10);
                            born = d.substring(8) + '/' + d.substring(5,7) + '/' + d.substring(0,4);
                            s += value.dateb != null ? ' ' + born : '';
                            if (s)
                            {
                                $('#citizen_task_result').append('<li class="list-group-item link-class" id="' + value.id + '"><a href="' + value.url + '">' + s + '</a></li>');
                            }
                        });
                    });
        }
    });

    $('#citizen_payment').unbind('submit').bind('submit', function(e) {

        $('.alert').remove();
        e.preventDefault();

        var formSerialize = $(this).serialize();
        $('#citizen_payment_submit').hide();
        $.post(formurl, formSerialize, function(response) {

            if (response['status']=='OK') {
                $('#paymentresult').append('<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Well done!</strong> Il pagamento è adato a buon fine.</div>');
                $('#payment-diff').text(response['diff']);
                $('#payment-total').text(response['total']);
                $('#payment-list').append('<span class="text-secondary" id="payment-' + response['pid'] + '">' + $('#citizen_payment_paymentDate').val() + ' ' + $('#citizen_payment_type').find(":selected").text() + ' (' + $('#citizen_payment_value').val() +'€) <a href="' + response['del'] + '" class="alert-text alert-link delete-payment" rel="payment-' + response['pid'] + '">x</a></span>');
                $('#citizen_payment_submit').show();
                AjaxInit();
            } else {
                $('#paymentresult').append('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>Ops!</strong> Qualcosa è andato storto. Controlla i dati e riprova.</div>');
                $('#citizen_payment_submit').show();
            }
        }, 'JSON');
    });

    $('#room_add').unbind('submit').bind('submit', function(e) {

        $('.alert').remove();
        e.preventDefault();

        var formSerialize = $(this).serialize();

        $.post(formurl, formSerialize, function(response) {

            if (response['status']=='OK') {
                $('.table-room').append(response['html']);
                $('#roomresult').append('<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Well done!</strong> La camera è stata inserita.</div>');

                /*$('#room_real_name').val("");
                $('#room_real_floor').val("");
                $('#room_real_rooms').val("");
                $('#room_real_guests').val("");
                $('#room_real_single').val("");
                $('#room_real_double').val("");
                $('#room_real_twin').val("");
                $('#room_real_sofa').val("");
                $('#room_real_bunk').val("");*/
                AjaxInit();
            } else {
                $('#roomresult').append('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>Ops!</strong> Qualcosa è andato storto. Controlla i dati e riprova.</div>');
            }
        }, 'JSON');
    });

    $('.show-add-extracost').on('click', function(e) {
        e.preventDefault();
        $('#modal-from-dom-extracost').modal('show');
    });

    $('#extracost_add').unbind('submit').bind('submit', function(e) {

        $('.alert').remove();
        e.preventDefault();

        var formSerialize = $(this).serialize();

        $.post(extracosturl, formSerialize, function(response) {

            if (response['status']=='OK') {
                $('.table-extracost').append(response['html']);
                $('#extra_cost_price').val("");
                $('#extracostresult').append('<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Well done!</strong> Il costo è stata inserito.</div>');

                AjaxInit();
            } else {
                $('#extracostresult').append('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>Ops!</strong> Qualcosa è andato storto. Controlla i dati e riprova.</div>');
            }
        }, 'JSON');
    });

    $('.delete-extracost').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');
        $('#modal-delete-extracost').attr('href', href);
        $('#modal-delete-extracost').attr('rel', rel);
        $('#modal-from-dom-extracost-delete').modal('show');
    });

    $('#modal-delete-extracost').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');

        $.post(href, function(response) {
            if (response['status']=='OK') {
                $('#'+rel).remove();
            }
            $('#modal-from-dom-extracost-delete').modal('hide');
        }, 'JSON');
    });

    $('.show-add-roomcost').on('click', function(e) {
        e.preventDefault();
        isVisible = $('#modal-from-dom-roomcost').hasClass("in");

        if ( !isVisible ) {
            var href = $(this).attr('href');
            var rel = $(this).attr('rel');
            $('#roomcost_add').attr('href', href);
            $('#roomcost_add').attr('rel', rel);
            $('#modal-from-dom-roomcost').modal('show');
        }
    });

    $('#roomcost_add').unbind('submit').bind('submit', function(e) {

        $('.alert').remove();

        e.preventDefault();

        var href = $(this).attr('href');
        var rel = $(this).attr('rel');

        var formSerialize = $(this).serialize();

        $.post(href, formSerialize, function(response) {

            if (response['status']=='OK') {
                $('#'+rel).append(response['html']);
                $('#room_real_price_price').val("");
                $('#room_real_price_guests').val("");
                $('#roomcostresult').append('<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Well done!</strong> Il costo è stata inserito.</div>');

                AjaxInit();
            } else {
                $('#roomcostresult').append('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>Ops!</strong> Qualcosa è andato storto. Controlla i dati e riprova.</div>');
            }
        }, 'JSON');

    });

    $('.delete-roomcost').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');
        $('#modal-delete-roomcost').attr('href', href);
        $('#modal-delete-roomcost').attr('rel', rel);
        $('#modal-from-dom-roomcost-delete').modal('show');
    });

    $('#modal-delete-roomcost').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');

        $.post(href, function(response) {
            if (response['status']=='OK') {
                $('#'+rel).remove();
            }
            $('#modal-from-dom-roomcost-delete').modal('hide');
        }, 'JSON');
    });
    
    $('.show-add-restaurantextracost').on('click', function(e) {
        e.preventDefault();
        $('#modal-from-dom-restaurantextracost').modal('show');
    });

    $('#restaurantextracost_add').unbind('submit').bind('submit', function(e) {

        $('.alert').remove();
        e.preventDefault();

        var formSerialize = $(this).serialize();

        $.post(restaurantextracosturl, formSerialize, function(response) {

            if (response['status']=='OK') {
                $('.table-restaurantextracost').append(response['html']);
                $('#extra_cost_price').val("");
                $('#restaurantextracostresult').append('<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Well done!</strong> Il costo è stata inserito.</div>');

                AjaxInit();
            } else {
                $('#restaurantextracostresult').append('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>Ops!</strong> Qualcosa è andato storto. Controlla i dati e riprova.</div>');
            }
        }, 'JSON');
    });

    $('.delete-restaurantextracost').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');
        $('#modal-delete-restaurantextracost').attr('href', href);
        $('#modal-delete-restaurantextracost').attr('rel', rel);
        $('#modal-from-dom-restaurantextracost-delete').modal('show');
    });

    $('#modal-delete-restaurantextracost').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');

        $.post(href, function(response) {
            if (response['status']=='OK') {
                $('#'+rel).remove();
            }
            $('#modal-from-dom-restaurantextracost-delete').modal('hide');
        }, 'JSON');
    });
    
    $('.show-add-restaurantmeal').on('click', function(e) {
        e.preventDefault();
        $('#modal-from-dom').modal('show');
    });
    
    $('#meal_add').unbind('submit').bind('submit', function(e) {

        $('.alert').remove();
        e.preventDefault();

        var formSerialize = $(this).serialize();

        $.post(formurl, formSerialize, function(response) {

            if (response['status']=='OK') {
                $('.table-room').append(response['html']);
                $('#roomresult').append('<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Well done!</strong> L\'inserimento è andato a buon fine.</div>');
                AjaxInit();
            } else {
                $('#roomresult').append('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>Ops!</strong> Qualcosa è andato storto. Controlla i dati e riprova.</div>');
            }
        }, 'JSON');
    });
    
    $('.delete-restaurantmeal').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');
        $('#modal-delete-restaurantmeal').attr('href', href);
        $('#modal-delete-restaurantmeal').attr('rel', rel);
        $('#modal-from-dom-restaurantmeal-delete').modal('show');
    });

    $('#modal-delete-restaurantmeal').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');

        $.post(href, function(response) {
            if (response['status']=='OK') {
                $('#'+rel).remove();
            }
            $('#modal-from-dom-restaurantmeal-delete').modal('hide');
        }, 'JSON');
    });
    
    
   $('.show-add-mealcost').on('click', function(e) {
        e.preventDefault();
        isVisible = $('#modal-from-dom-mealcost').hasClass("in");

        if ( !isVisible ) {
            var href = $(this).attr('href');
            var rel = $(this).attr('rel');
            $('#restaurantrealmealprice_add').attr('href', href);
            $('#restaurantrealmealprice_add').attr('rel', rel);
            $('#modal-from-dom-mealcost').modal('show');
        }
    });

    $('#restaurantrealmealprice_add').unbind('submit').bind('submit', function(e) {

        $('.alert').remove();

        e.preventDefault();

        var href = $(this).attr('href');
        var rel = $(this).attr('rel');

        var formSerialize = $(this).serialize();

        $.post(href, formSerialize, function(response) {

            if (response['status']=='OK') {
                $('#'+rel).append(response['html']);
                $('#restaurant_real_meal_price_price').val("");
                $('#restaurant_real_meal_price_guests').val("");
                $('#mealcostresult').append('<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Well done!</strong> Il costo è stata inserito.</div>');

                AjaxInit();
            } else {
                $('#mealcostresult').append('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>Ops!</strong> Qualcosa è andato storto. Controlla i dati e riprova.</div>');
            }
        }, 'JSON');

    });

    $('.delete-restaurantrealmealprice').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');
        $('#modal-delete-mealcost').attr('href', href);
        $('#modal-delete-mealcost').attr('rel', rel);
        $('#modal-from-dom-mealcost-delete').modal('show');
    });

    $('#modal-delete-mealcost').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var rel = $(this).attr('rel');

        $.post(href, function(response) {
            if (response['status']=='OK') {
                $('#'+rel).remove();
            }
            $('#modal-from-dom-mealcost-delete').modal('hide');
        }, 'JSON');
    });
}
