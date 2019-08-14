const messageBox = function(targetElement, props) {
	
	return $(targetElement).append(
		`<div class="alert ${props.className} alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>${props.message.title}</strong> ${props.message.text}
		</div>`
	);
}
var notification = function(options, settings) {
	$.notify({
		// options
		icon: (options.icon) ? options.icon : '',
		title:  (options.title) ? options.title : '',
		message:  (options.message) ? options.message :'Turning standard Bootstrap alerts into "notify" like notifications',
		url:  (options.url) ? options.url :'https://github.com/mouse0270/bootstrap-notify',
		target:  (options.target) ? options.target :'_blank'
	},{
		// settings
		element: (settings.element) ? settings.element :'body',
		position: (settings.position) ? settings.position : null,
		type: (settings.type) ? settings.type : "info",
		allow_dismiss: (settings.allow_dismiss) ? settings.allow_dismiss : true,
		newest_on_top: (settings.newest_on_top) ? settings.newest_on_top : false,
		showProgressbar: (settings.showProgressbar) ? settings.showProgressbar : false,
		placement: {
			from: (settings.placement.from) ? settings.placement.from : "top",
			align: (settings.placement.align) ? settings.placement.align : "right"
		},
		offset: (settings.offset) ? settings.offset : 20,
		spacing: (settings.spacing) ? settings.spacing : 10,
		z_index: (settings.z_index) ? settings.z_index : 1031,
		delay: (settings.delay) ? settings.delay : 5000,
		timer: (settings.timer) ? settings.timer : 1000,
		url_target: (settings.url_target) ? settings.url_target : '_blank',
		mouse_over: (settings.mouse_over) ? settings.mouse_over : null,
		animate: {
			enter: (settings.animate.enter) ? settings.animate.enter : 'animated bounceIn',
			exit: (settings.animate.exit) ? settings.animate.exit : 'animated bounceOut'
		},
		onShow: (settings.onShow) ? settings.onShow : null,
		onShown: (settings.onShown) ? settings.onShown : null,
		onClose: (settings.onClose) ? settings.onClose : null,
		onClosed: (settings.onClosed) ? settings.onClosed : null,
		icon_type: 'class',
		template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
			'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
			'<span data-notify="icon"></span> ' +
			'<span data-notify="title">{1}</span> ' +
			'<span data-notify="message">{2}</span>' +
			'<div class="progress" data-notify="progressbar">' +
				'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
			'</div>' +
			'<a href="{3}" target="{4}" data-notify="url"></a>' +
		'</div>' 
	});
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
							window.location.href = `${API_URL}account`;
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
					///console.log(res);
					
					if(res.status) {
						$("#auth").loading('stop');
						messageBox('.flash', {message: {title: 'MessageBox', text: res.message}, className: 'alert-success'});
						setTimeout(() => {
							$('.alert').delay(2000).fadeOut();
						}, 2000);
						setTimeout(function() {
							window.location.href = `${API_URL}account`;
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