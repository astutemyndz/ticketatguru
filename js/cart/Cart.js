var imp = document.getElementById('plk-map-seat-points-wrapper');

const addToCart = function(props, callback) {
    $.post(`${API_URL}pjActionCart`, props, function(res) {
        var isCart = res.cart;// true or false
        $(self).removeClass('tbSeatAvailable');
        $(self).addClass('tbSeatSelected');
        notification(options = {message: 'Item added to cart'}, settings = {type: 'success', placement: {}, animate: {}});
        if(isCart) {
            callback(res);
        }
    });
}
const pjActionLoadMap = function(callback) {
    console.log('map loading...');
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
            name: $(this).attr('data-name'),
            price_id: $(this).attr('price_id'),
            price: $(this).attr('data-price'),
        };
        callback(props);
    });
}
const seatAvailableCallback = function(props) {
    addToCart(props, function(addToCartResponse) {
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