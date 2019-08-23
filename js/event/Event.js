$("document").ready(function() {
    $(".pjCbDaysNav").on('click', function(event) {
        //console.log('fetch times...');
        $('.timesSection').html("");

        let url = API_URL + '/EventController/pjEventsTimesDateWise';
        /* Send the data using post with element id name and name2*/
        var event = {
            id: $(this).attr('data-event_id'), 
            date: $(this).attr('data-date')
        };

        var posting = $.post(url, event);
            // posting.beforeSend(function() {
            //     //CradleLoader();
            // });
        
            posting.done(function( res ) {
                let status = res.status;
                //console.log(res);
                var props = {};
                if(status) {
                    $.each(res.time_arr, function( key, value ) {
                        console.log(value);
                        props = {
                            id: event.id,
                            date: event.date,
                            time: value.time,
                            key: event.id,
                            value: value.show_time,
                            className: "timeSlot",
                            elementId: `timeSlot_${event.id}`
                        };

                        time_arr.push(TimeComponent(props))
                    });
                    //console.log(time_arr);

                    $('.timesSection').html(time_arr);
                    
                    $(".timeSlot").on('click', function() {
                        let data = props;
                        console.log(data);
                        $.ajax({
                            type: "POST",
                            url: API_URL + 'event/pjActionSeatsAjax',
                            data: data,
                            beforeSend: function() {
                                //$("#eventDetails").loading();
                                $("#eventDetails").loading({theme: 'dark'});
                             },
                            success: function(res) {
                                //console.log(res);
                               // let data = res.data;
                               
                                const status = res.status

                                if(status == true) {
                                    
                                    setTimeout(() => {
                                        $("#eventDetails").loading('stop');
                                        window.location.href = `${API_URL}event/seats`;
                                    }, 1000);

                                } else {
                                    console.log("ERR:",status,"===",status);
                                }
                            },
                            error: function(res) {
                            console.log(res);
                        }
                    });
                    });
                }
            });
        var time_arr = [];
        /* Alerts the results */
    
        
    
    });

    $(".pjCbDaysNav")[0].click();

    $(".getTicket").on('click', function() {
    // console.log('getTicket');
        var data = {
            id: $(this).attr('data-id'), 
            date: $(this).attr('data-date'),
            time: $(this).attr('data-time'),
        };
    // console.log(data);
    
        $.ajax({
            type: "POST",
            url: API_URL + 'event/pjActionSeatsAjax',
            data: data,
            beforeSend: function() {
                $(".section-todays-schedule").loading({theme: 'dark'});
             },
            success: function(res) {
                //console.log(res);
                //return false;
                let data = res.data;
                const STATUS = data.status;
                const status = res.status

                if(STATUS == "OK" && status == true) {
                    setTimeout(() => {
                        $(".section-todays-schedule").loading('stop');
                        window.location.href = `${API_URL}event/seats`;
                    }, 1000);

                } else {
                    console.log("ERR:",STATUS,"===",status);
                }
            },
            error: function(res) {
            //console.log(res);
        }
    
        });
        
    });
});