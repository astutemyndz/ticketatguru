var imp = document.getElementById('plk-map-seat-points-wrapper');
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

const addToCart = function(props, callback) {
    //console.log(props);
    $.post(`${API_URL}pjActionCart`, props, function(res) {
        var isCart = res.cart;// true or false
        $(self).removeClass('tbSeatAvailable');
        $(self).addClass('tbSeatSelected');
        //notification(options = {message: 'Item added to cart'}, settings = {type: 'success', placement: {}, animate: {}});
        if(isCart) {
            callback(res);
        }
    });
}
const pjActionLoadMap = function(callback) {
    //console.log('map loading...');
    $.ajax({
        url: `${API_URL}pjActionLoadMap`,
        type: 'GET',
      
        context: document.body,
        success: function(res) {
            callback(res);
        },
        error: function(res) {
        }         
    });
}
const seatAvailable = function(callback) {
    $('.tbSeatAvailable').on('click', function() {
        var props = {
            id: $(this).attr('data-id'),
            price_id: $(this).attr('data-price_id'),
            seat_id: $(this).attr('data-id'),
            show_id: $(this).attr('data-show'),
            venue_id: $(this).attr('data-venue'),
            name: $(this).attr('data-name'),
            price: $(this).attr('data-price'),
            type: $(this).attr('data-type')

        };
        console.log(props);
        callback(props);
    });
}
const seatAvailableCallback = function(props) {
    addToCart(props, function(addToCartResponse) {
        //console.log(props);
         var cp = document.getElementById('plk-cart-pini-wrapper');
         if(addToCartResponse.cart) {
            if(cp) {
                cp.innerHTML = addToCartResponse.li;                        
                imp.innerHTML = addToCartResponse.spanArray;
                seatAvailable(seatAvailableCallback);
            }
         } 
    });
};
$("document").ready(function() {
    pjActionLoadMap(function(res) {
        if(imp) {
            // setInterval(function() {
            //     $("#pjWrapperTicketBooking_theme1").loading('stop');
            // }, 2000);
            imp.innerHTML = res;
            seatAvailable(seatAvailableCallback);
        }
    });
    loadCart();
    $('#checkoutLink').on('click', function() {
		setTimeout(() => {
			window.location.href = `${API_URL}auth/login`;
		}, 300);
    });
    $('#continueLink').on('click', function() {
		setTimeout(() => {
			window.location.href = `${API_URL}`;
		}, 300);
    });
    loadCartSummery();
});
const reloadMap = function() {
	var imp = document.getElementById('plk-map-seat-points-wrapper');
	if(imp){
        $.ajax({
            url: `${API_URL}pjActionLoadMap`,
            type: 'GET',
            context: document.body,
            success: function(res) {
                imp.innerHTML = res;
            },
            error: function(res) {
            }         
        });
	}
}
const removeCartItemOnClick = function() {
    $('.btn-danger').on('click', function(){
        var rowId = $(this).attr('data-id');
        $.ajax({
            url: `${API_URL}cart/remove`,
            type: 'POST',
            data: {
                'rowId': rowId
            },
            success: function(res) {
                loadCart();
                var cp = document.getElementById('plk-cart-pini-wrapper');
                //console.log(res);
                   if(cp) {
                       cp.innerHTML = res.li;                        
                   }
                

            },
            error: function(res) {
            }         
        });
    });
}
const loadCart = function(callback) {
    var $loadCart = document.getElementById('loadCart');
    var subtotal = document.getElementById('subtotal');
    if($loadCart) {
        $.ajax({
            url: `${API_URL}loadCart`,
            type: 'GET',
            context: document.body,
            beforeSend: function() {
                $("#cartTable").loading();
             },
            success: function(res) {
               // console.log(res.rows);
                if(res.rows) {
                      $("#cartTable").loading('stop');
                      $loadCart.innerHTML = res.rows;
                      subtotal.innerHTML = res.subtotal;
                      if(res.cart == 0) {
                          console.log('Cart is empty');
                          $('#cartTable').hide();
                          $('#cartEmpty').html('Cart is Empty');
                      }
                      removeCartItemOnClick(callback);
                    
                }
                
                
                ///callback();
            },
            error: function(res) {
            }         
        });
    }
}

const loadCartSummery = function() {
    var $loadCartSummery = document.getElementById('loadCartSummery');
    var subtotal = document.getElementById('subtotal');
    if($loadCartSummery) {
        $.ajax({
            url: `${API_URL}loadCartSummery`,
            type: 'GET',
            context: document.body,
            beforeSend: function() {
                $("#loadCartSummeryTable").loading();
             },
            success: function(res) {
               // console.log(res.rows);
                if(res.rows) {
                      $("#loadCartSummeryTable").loading('stop');
                      $loadCartSummery.innerHTML = res.rows;
                      subtotal.innerHTML = res.subtotal;
                }
            },
            error: function(res) {
            }         
        });
    }
}