const messageBox = function(targetElement, props) {
	
	return $(targetElement).append(
		`<div class="alert ${props.className} alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>${props.message.title}</strong> ${props.message.text}
		</div>`
	);
}

var foo = function(props) {
	console.log(props.name);
	$('.signupFlash').append("<p>"+ props.name + "</p>");
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
				minlength: 8
			},
			password_confirm: {
				required: true,
				minlength: 8,
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
				minlength: "Your password must be at least 8 characters long"
			},
			password_confirm: {
				required: "Please provide a password",
				minlength: "Your password must be at least 8 characters long",
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
				beforeSend: function() {
					$("#auth").loading({theme: 'dark'});
				},
				success: function(res) {
					if(res.status) {
						$("#auth").loading('stop');
							messageBox('.signupFlash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-success'});
							setTimeout(() => {
									$('.alert').delay(2000).fadeOut();
							}, 2000);
						setTimeout(function() {
							window.location.href = `${API_URL}cart/checkout`;
						}, 3000);
					} else {
						const errors = res.errors;
						$("#auth").loading('stop');
						if($.isArray(errors)) {
							$.each(errors, function( index, value ) {
								messageBox('.signupFlash', {message: {title: 'MessageBox', text: value}, className: 'alert-danger'});
							});
							setTimeout(() => {
								$('.alert').delay(2000).fadeOut();
							}, 2000);
						} else {
							messageBox('.signupFlash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-danger'});
							setTimeout(() => {
								$('.alert').delay(2000).fadeOut();
							}, 2000);
						}
						
						
					}
					
				},
				error: function(res) {
					const errors = res.errors;
						console.log(errors);
						
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
				data: {
					identity: $('#signInIdentity').val(),
					password: $('#password').val()
				},
				beforeSend: function() {
					$("#auth").loading({theme: 'dark'});
				},
				success: function(res) {
					console.log(res);
					
					if(res.status) {
						$("#auth").loading('stop');
						messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-success'});
						setTimeout(() => {
							$('.alert').delay(2000).fadeOut();
						}, 2000);
						setTimeout(function() {
							window.location.href = `${API_URL}cart/checkout`;
						}, 2000);
					} else {
						$("#auth").loading('stop');
							messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-danger'});
							setTimeout(() => {
								$('.alert').delay(2000).fadeOut();
							}, 2000);
					}
					
				},
				error: function(res) {
					console.log(res);
					//this.messageBox('.flash', {message: {title: res.message, text: res.message}, className: 'alert-danger'});
				}         
			});
		}
	});

    
});