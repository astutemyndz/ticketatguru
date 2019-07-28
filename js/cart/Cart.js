
const loadMap = function(callback) {
    $.ajax({
        url: `${API_URL}loadSeatPage`,
        type: 'GET',
        context: document.body,
        success: function(res) {
            callback(res);
        },
        error: function(res) {
            
        }         
    });
}
const reloadMap = function(callback) {
    $.ajax({
        url: `${API_URL}loadSeatPage`,
        type: 'GET',
        context: document.body,
        success: function(res) {
            callback(res);
        },
        error: function(res) {
            
        }         
    });
}
const reloadCartPini = function(){
    var cp = document.getElementById('plk-cart-pini-wrapper');
    //$('#plk-cart-pini-wrapper').show();
    if(cp){
        $.get(`${API_URL}updateCartPini`, function(res) {
            cp.innerHTML = res.li;
        });
        
    }
}

const addToCart = function(props, callback) {
    $.post(`${API_URL}pjActionCart`, props, function(res) {
        var isCart = res.cart;// true or false
        var option_arr = res.option_arr; //Array
        var o_currency = res.option_arr.o_currency; //currency
        $(self).removeClass('tbSeatAvailable');
        $(self).addClass('tbSeatSelected');

        if(isCart) {
            console.log(res.cart);
            callback();
            
        }
    });
}

var seatAvailable = function(callback) {
    $('.tbSeatAvailable').on('click', function() {
        var self = this;
        var props = {
            id: $(this).attr('data-id'),
            name: $(this).attr('data-name'),
            price: $(this).attr('data-price'),
        };
        callback(props);
    });
}
var imp = document.getElementById('plk-map-seat-points-wrapper');

$("document").ready(function() {
    loadMap(function(res) {
        if(imp){
            imp.innerHTML = res.data;
            seatAvailable(function(props) {
                console.log(props);
                addToCart(props, function() {
                    reloadMap(function(data) {
                        imp.innerHTML = data
                    });
                })
            })
        }
    })
});