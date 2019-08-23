
$(document).ready(function() {
    
    // $('#creditCardNumber').on('change', function() {
       
    //    console.log(creditCardInfo);
    // });
   
    //creditCardInfo.type         = $('#type').val();
   
      /*
    $('#finish').on('click', function() {
        $.ajax({
          url: `${API_URL}paypal/pay/credit-card`,
          type: 'POST',
          data: props,
          // beforeSend: function() {
          //     $(".wizard-container").loading({
          //       message: 'Please Wait – We are processing your payment, do not click away from this page. This process can take up to 30 seconds'
          //     });
          //  },
          success: function(res) {
              console.log(res);
              if(res) {
                //$(".wizard-container").loading('stop');
                alert('Booked');
              }
          },
          error: function(res) {
            console.log(res);
          }         
      });
        // $.post(`${API_URL}/paypal/pay/credit-card`, props, function(res) {
        //   console.log(res);
        // });

    });
    */
    var creditCardInfo = {}, 
    billingAddress = {};

    $('form.checkoutForm').on('submit', function(e) {
      
      e.preventDefault(); //'return false' is deprecated according to jQuery documentation, use this instead.
      console.log('clicked');
      var that = $(this),
      url = that.attr('action'),
      type = that.attr('method'),
      data = that.serialize(); //This will capture all form input values, no need to reinvent the wheel
/*
      creditCardInfo.number       = $('#card_number').val();
      creditCardInfo.expireMonth  = $('#expireMonth').val();
      creditCardInfo.expireYear   = $('#expireYear').val();
      creditCardInfo.cvv2         = $('#cvv2').val();
      creditCardInfo.type = creditCard.IsValidCreditCardNumber(creditCardInfo.number);
      
      billingAddress.firstName  = $('#firstName').val();
      billingAddress.lastName   = $('#lastName').val();
      billingAddress.email      = $('#email').val();
      billingAddress.phone      = $('#phone').val();
      billingAddress.countryCode    = $('#countryCode').val();
      billingAddress.city       = $('#city').val();
      billingAddress.address    = $('#_address').val();
      billingAddress.postalCode   = $('#postalCode').val();
    */
      // var props = {
      //   creditCardInfo: creditCardInfo,
      //   billingAddress: billingAddress,
      //   data: data
      // };

      //console.log(props);
      $.ajax({
          url: url,
          type: type,
          data: data,
          success: function(response) {
             console.log(response);
          }
      });
  });

    function CreditCard() {
        const AmexCardNumber = function(cardNumber) {
            var regex = /^(?:3[47][0-9]{13})$/;
            return regex.test(cardNumber); 
        }
        const VisaCardNumber = function(cardNumber) {
            var regex = /^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
            return regex.test(cardNumber);
        }
        const MasterCardNumber = function(cardNumber) {
            var regex = /^(?:5[1-5][0-9]{14})$/;
            return regex.test(cardNumber);
        }
        const DiscoverCardNumber = function(cardNumber) {
            var regex = /^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;
            return regex.test(cardNumber);
        }
        const DinerClubCardNumber = function(cardNumber) {
            var regex = /^(?:3(?:0[0-5]|[68][0-9])[0-9]{11})$/;
            return regex.test(cardNumber);
        }
        const JCBCardNumber = function(cardNumber) {
            var regex = /^(?:(?:2131|1800|35\d{3})\d{11})$/;
            return regex.test(cardNumber);
        }
        CreditCard.prototype.IsValidCreditCardNumber = function(cardNumber) {
          var cardType = null;
          if (VisaCardNumber(cardNumber)) {
            cardType = "visa";
          } else if (MasterCardNumber(cardNumber)) {
            cardType = "mastercard";
          } else if (AmexCardNumber(cardNumber)) {
            cardType = "americanexpress";
          } else if (DiscoverCardNumber(cardNumber)) {
            cardType = "discover";
          } else if (DinerClubCardNumber(cardNumber)) {
            cardType = "dinerclub";
          } else if (JCBCardNumber(cardNumber)) {
            cardType = "jcb";
          }
        
          return cardType;
        }
    }

    var creditCard = new CreditCard();
    
}); 
    



    





