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
				beforeSend: function() {
					$("#auth").loading({theme: 'dark'});
				},
				success: function(res) {
					///console.log(res);
					if(res.status) {
						
						
						setTimeout(function() {
							$("#auth").loading('stop');
							messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-success'});
							window.location.href = `${API_URL}account`;
						}, 2000);
					} else {
						setTimeout(function() {
							$("#auth").loading('stop');
							messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-danger'});
						}, 2000);
						
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
				beforeSend: function() {
					$("#auth").loading({theme: 'dark'});
				},
				success: function(res) {
					///console.log(res);
					
					if(res.status) {
						setTimeout(function() {
							$("#auth").loading('stop');
							messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-success'});
							window.location.href = `${API_URL}account`;
						}, 2000);
					} else {
						
						setTimeout(function() {
							$("#auth").loading('stop');
							messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-danger'});
							//window.location.href = `${API_URL}account`;
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