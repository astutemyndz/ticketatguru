//import { CradleLoader } from "./CradleLoader";

//import { CradleLoader } from "./CradleLoader";

window.addEventListener('load', () => {

    $( '.hamburger-menu' ).on( 'click', function() {
        $(this).toggleClass('open');
        $('.site-navigation').toggleClass('show');
    });

    let countdown_date = $('.countdown').data("date");

    $('.countdown').countdown(countdown_date, function (event) {
        $('.dday').html(event.strftime('%-D'));
        $('.dhour').html(event.strftime('%-H'));
        $('.dmin').html(event.strftime('%-M'));
        $('.dsec').html(event.strftime('%-S'));
    });

});


$("document").ready(function() {
    $('#loginButton').on('click', function() {
        console.log(API_URL);
        let identity = $('#identity').val();
        let password = $('#password').val();
        console.log(identity, "and", password);
        $.ajax({
              type: "POST",
              url: API_URL + 'auth/login',
              data: {
                  identity: identity,
                  password: password
              },
              success: function(res) {
                if(res.status) {
                    alert(res.responseText);
                    location.reload();
                    // $('#message').show();
                    // $('#message').addClass('alert-success');
                    // $('#responseText').html(res.responseText);
                } 
                // else {
                //     $('#message').show().addClass('alert-danger');
                //     $('#responseText').html(res.responseText);
                // }
                
              },
              error: function(res) {
                console.log(res);
            }
        });
    });
    /* attach a submit handler to the form */
    $("#registerForm").submit(function(event) {
        var formData = $('#registerForm').serialize();
        /* stop form from submitting normally */
        event.preventDefault();
  
        /* get the action attribute from the <form action=""> element */
        var $form = $( this ),
            url = $form.attr( 'action' );
  
        /* Send the data using post with element id name and name2*/
        var posting = $.post( url, { formData } );
  
        /* Alerts the results */
        posting.done(function( res ) {
            //console.log(res);
            if(!res.status) {
                $.each(res.errors, function( index, value ) {
                    console.log( index + ": " + value );
                     toggleError(document.getElementById(index), true);
                });
            }
        });
    });
    $("#loginForm").submit(function(event) {
        //var formData = $('#loginForm').serialize();
        let identity = $('#identity').val();
        let password = $('#password').val();
        /* stop form from submitting normally */
        event.preventDefault();
  
        /* get the action attribute from the <form action=""> element */
        var $form = $( this ),
            url = $form.attr( 'action' );
  
        /* Send the data using post with element id name and name2*/
        var posting = $.post( url,  {
            identity: identity,
            password: password
        });
  
        /* Alerts the results */
        posting.done(function( res ) {
            console.log(res);
            if(res.formValidation === false) {
                // show list of required fields
               
                    $.each(res.errors, function( index, value ) {
                       // console.log( index + ": " + value );
                         //toggleError(document.getElementById(index), true, index);
                         //this.html(value);
                         validationMessage(`.cd-signin-modal__error`, {className: `cd-signin-modal__error--is-visible ${index}`, removeClass: false,  text: value});
                    });
            
            } else if(res.formValidation === true && res.status === false) {
                //$('.cd-signin-modal__error').html(res.message); // wrong credentials
                messageBox('#message', {className: 'alert-danger', message: { title: 'Error', text: res.message}});
                
            
            } else {
                if(res.formValidation === true && res.status === true) {
                    messageBox('#message', {className: 'alert-successs', message: { title: 'Success', text: res.message}});
                    setTimeout(function(){
                        location.reload();
                    },3000);
                }
            }

        });
    });

   

    $(".pjCbDaysNav").on('click', function(event) {
        console.log('fetch times...');
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

  
});

var TimeComponent = function(props) {
    return `<a id="${props.elementId}" class="${props.className}" href="javascript:void(0);" data-key="${props.key}" data-id="${props.id}" data-date="${props.date}" data-time="${props.time}" class="label label-primary">${props.value}</a>`;
}
/*
var CradleLoader = function(props) {
    return `<div aria-label=${props.label} role="progressbar" className="container">
    <div className="react-spinner-loader-swing">
      <div className="react-spinner-loader-swing-l" />
      <div />
      <div />
      <div />
      <div />
      <div />
      <div className="react-spinner-loader-swing-r" />
    </div>
    <div className="react-spinner-loader-shadow">
      <div className="react-spinner-loader-shadow-l" />
      <div />
      <div />
      <div />
      <div />
      <div />
      <div className="react-spinner-loader-shadow-r" />
    </div>
  </div>`;
}
    */

messageBox = function(targetElement, props) {
    $('.alert').alert();
    return $(targetElement).html(
        `<div class="alert ${props.className} alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>${props.message.title}</strong> ${props.message.text}
        </div>`
    );
}

validationMessage = function(targetElement, props) {
    if(!props.removeClass) {
        $(targetElement).addClass(props.className);
        $(props.className).html(props.text);
    } else {
        $(targetElement).removeClass(props.className);
    }
    
}

toggleError = function(input, bool, additionalClass) {
    // used to show error messages in the form
    console.log(additionalClass);
    toggleClass(input, 'cd-signin-modal__input--has-error', bool);
    toggleClass(input.nextElementSibling, 'cd-signin-modal__error--is-visible', bool);
}


toggleClass = function(el, className, bool) {
    if(bool) addClass(el, className);
    else removeClass(el, className);
}


//class manipulations - needed if classList is not supported
function hasClass(el, className) {
    if (el.classList) return el.classList.contains(className);
    else return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'));
}
function addClass(el, className) {
  var classList = className.split(' ');
   if (el.classList) el.classList.add(classList[0]);
   else if (!hasClass(el, classList[0])) el.className += " " + classList[0];
   if (classList.length > 1) addClass(el, classList.slice(1).join(' '));
}
function removeClass(el, className) {
  var classList = className.split(' ');
    if (el.classList) el.classList.remove(classList[0]);	
    else if(hasClass(el, classList[0])) {
        var reg = new RegExp('(\\s|^)' + classList[0] + '(\\s|$)');
        el.className=el.className.replace(reg, ' ');
    }
    if (classList.length > 1) removeClass(el, classList.slice(1).join(' '));
}
function toggleClass(el, className, bool) {
  if(bool) addClass(el, className);
  else removeClass(el, className);
}





