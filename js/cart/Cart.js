
const pjActionLoadMap = function(callback) {
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


const addToCart = function(props, callback) {
    $.post(`${API_URL}pjActionCart`, props, function(res) {
        var isCart = res.cart;// true or false
        // var option_arr = res.option_arr; //Array
        // var o_currency = res.option_arr.o_currency; //currency
        $(self).removeClass('tbSeatAvailable');
        $(self).addClass('tbSeatSelected');

        if(isCart) {
            //console.log(res.cart);
            callback(res);
            
        }
    });
}

var seatAvailable = function(callback) {

 
    $('.tbSeatAvailable').on('click', function() {

        console.log('yes i am coming here');

        var props = {
            id: $(this).attr('data-id'),
            name: $(this).attr('data-name'),
            price_id: $(this).attr('price_id'),
            price: $(this).attr('data-price'),
        };
        console.log(props);
        callback(props);
        
    });
}




var imp = document.getElementById('plk-map-seat-points-wrapper');
const reloadMap = function() {
	var imp = document.getElementById('plk-map-seat-points-wrapper');
	if(imp){
        $.ajax({
            url: `${API_URL}pjActionLoadMap`,
            type: 'GET',
            context: document.body,
            success: function(res) {
                console.log(res);
                imp.innerHTML = res;
            },
            error: function(res) {
                
            }         
        });
	}
}
$("document").ready(function() {
    pjActionLoadMap(function(res) {
       // console.log(res);
        if(imp){
            // console.log(res);
            imp.innerHTML = res;

            seatAvailable(function(props) {

                addToCart(props, function(addToCartResponse) {

                     var cp = document.getElementById('plk-cart-pini-wrapper');
                       // console.log(addToCartResponse);
                     if(addToCartResponse.cart) {
                        if(cp) {
                            console.log(addToCartResponse.spanArray);
                            cp.innerHTML = addToCartResponse.li;                        
                            imp.innerHTML = addToCartResponse.spanArray;

                            //location.reload();
                            //reloadMap();
                        }
                        
                     } 
                });
            });
        }
    });
    
});