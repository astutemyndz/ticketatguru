const messageBox = function(targetElement, props) {
	$('.alert').alert();
	return $(targetElement).html(
		`<div class="alert ${props.className} alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>${props.message.title}</strong> ${props.message.text}
		</div>`
	);
}
$("document").ready(function() {


	$('#loginLink').on('click', function() {
		setTimeout(() => {
			window.location.href = `${API_URL}auth/login`;
		}, 300);
	});
	$('#logoutLink').on('click', function() {
		setTimeout(() => {
			window.location.href = `${API_URL}auth/logout`;
		}, 300);
	});
	
	// validate signup form on keyup and submit
	$("#registerForm").validate({
		rules: {
			firs_tname: "required",
			last_name: "required",
			main_password: {
				required: true,
				minlength: 5
			},
			password_confirm: {
				required: true,
				minlength: 5,
				equalTo: "#main_password"
			},
			email: {
				required: true,
				email: true
			},
			registerIdentity: {
				required: true,
			},
			confirm_email: {
				required: true,
				email: true,
				equalTo: "#email"
			},
			agree: "required"
		},
		messages: {
			firs_tname: "Please enter your firstname",
			last_name: "Please enter your lastname",
			main_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			password_confirm: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			},
			email: "Please enter a valid email address",
			registerIdentity: "Please enter a valid username",
			confirm_email: {
				required: "Please provide a confirm email",
				minlength: "Your email must be at least 5 characters long",
				equalTo: "Please enter the same email as above"
			},
			//agree: "I agree with the Terms and Conditions",
		},
		submitHandler: function(form) {
			$.ajax({
				url: form.action,
				type: form.method,
				data: $(form).serialize(),
				success: function(res) {
					///console.log(res);
					if(res.status) {
						messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-success'});
						setTimeout(function() {
							window.location.href = `${API_URL}account`;
						}, 2000);
					} else {
						messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-danger'});
					}
					
				},
				error: function(res) {
					
				}         
			});
		}
	});
	$("#loginForm").validate({
		rules: {
			password: {
				required: true,
				minlength: 5
			},
			identity: {
				required: true,
				email: true
			},
			agree: "required"
		},
		messages: {
			email: "Please enter a valid email address",
			password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			agree: "I agree with the Terms and Conditions",
		},
		submitHandler: function(form) {
			$.ajax({
				url: form.action,
				type: form.method,
				data: $(form).serialize(),
				success: function(res) {
					///console.log(res);
					if(res.status) {
						messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-success'});
						setTimeout(function() {
							window.location.href = `${API_URL}account`;
						}, 2000);
					} else {
						messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-danger'});
					}
					
				},
				error: function(res) {
					console.log(res);
					//this.messageBox('.flash', {message: {title: res.message, text: res.message}, className: 'alert-danger'});
				}         
			});
		}
	});

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
                        console.log('click');
                        let data = props;
                        //console.log(data);
                        $.ajax({
                            type: "POST",
                            url: API_URL + 'event/pjActionSeatsAjax',
                            data: data,
                            success: function(res) {
                                //console.log(res);
                                let data = res.data;
                                const STATUS = data.status;
                                const status = res.status

                                if(STATUS === "OK" && status === true) {
                                    setTimeout(() => {
                                        window.location.href = `${API_URL}event/seats`;
                                    }, 500);

                                } else {
                                    console.log("ERR:",STATUS,"===",status);
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
            success: function(res) {
                //console.log(res);
                //return false;
                let data = res.data;
                const STATUS = data.status;
                const status = res.status

                if(STATUS === "OK" && status === true) {
                    setTimeout(() => {
                        window.location.href = `${API_URL}event/seats`;
                    }, 500);

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