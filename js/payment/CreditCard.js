$(function() {
  // var $cardNumber = $('#card_number');
  // var $cardNumberField = $('#card-number-field');
  // var $cardCvvField = $('#card-cvv-field');
  // var $formatCardExpiryField = $('#formatCardExpiryField');
  // var $cvv = $("#cc_code");
  // var $finish = $("#finish");
  // var $formatCardExpiry = $("#formatCardExpiry");
  // var $masterCard = $("#mastercard");
  // var $visa = $("#visa");
  // var $amex = $("#amex");
  // var $cc_type = $('#cc_type');
  // Use the payform library to format and validate
  // the payment fields.
/*
  $cardNumber.payform('formatCardNumber');
  $cvv.payform('formatCardCVC');
  $formatCardExpiry.payform('formatCardExpiry');


  $cardNumber.keyup(function() {
      $amex.removeClass('transparent');
      $visa.removeClass('transparent');
      $masterCard.removeClass('transparent');
      if ($.payform.validateCardNumber($cardNumber.val()) == false) {
          $cardNumberField.addClass('has-error');
      } else {
          $cardNumberField.removeClass('has-error');
          $cardNumberField.addClass('has-success');
          //set card type
          $cc_type.val($.payform.parseCardType($cardNumber.val()));
      }

      if ($.payform.parseCardType($cardNumber.val()) == 'visa') {
          $masterCard.addClass('transparent');
          $amex.addClass('transparent');
      } else if ($.payform.parseCardType($cardNumber.val()) == 'amex') {
          $masterCard.addClass('transparent');
          $visa.addClass('transparent');
      } else if ($.payform.parseCardType($cardNumber.val()) == 'mastercard') {
          $amex.addClass('transparent');
          $visa.addClass('transparent');
      }
  });
  $cvv.keyup(function() {
      if ($.payform.validateCardCVC($cvv.val()) == false) {
        $cardCvvField.addClass('has-error');
      } else {
        $cardCvvField.removeClass('has-error');
        $cardCvvField.addClass('has-success');
      }
  });
  $formatCardExpiry.keyup(function() {
    if ($.payform.validateCardExpiry($formatCardExpiry.val()) == false) {
      console.log('false');
      $formatCardExpiryField.addClass('has-error');
    } else {
      console.log('true');
      $formatCardExpiryField.removeClass('has-error');
      $formatCardExpiryField.addClass('has-success');
      //$('#finish').show();
      $finish.prop('disabled', false);
    }
});
*/
  $('form.checkoutForm').on('submit', function(e) {
      e.preventDefault();
          // Everything is correct. Add your form submission code here.
            var that = $(this),
            url = that.attr('action'),
            type = that.attr('method'),
            data = that.serialize(); //This will capture all form input values, no need to reinvent the wheel
            var $wizardProfile = $('#wizardProfile');
            $.ajax({
                url: url,
                type: type,
                data: data,
                beforeSend: function() {
                    $(".wizard-container").loading({
                      message: 'Please Wait – We are processing your payment, do not click away from this page'
                    });
                },
                success: function(response) {
                  
                 // console.log(response.status_code);
                  if(response.status_code == 202) {
                    $(".wizard-container").loading('stop');
                   // $('#paymentSuccess').html(response.message);
                    swal({
                      title: "Success",
                      text: response.message,
                      icon: "success",
                      button: "OK",
                    })
                    .then((value) => {
                      ///swal(`The returned value is: ${value}`);
                      location.href = `${BASE_URL}event/list`;
                    });
                  } else {
                    //$('#paymentFailed').html(response.message);
                    swal({
                      title: "Failed!",
                      text: response.message,
                      icon: "error",
                      buttons: true,
                      dangerMode: true,
                    })
                    .then((value) => {
                      ///swal(`The returned value is: ${value}`);
                      location.href = `${BASE_URL}cart`;
                    });
                  }
                  //console.log(response);
                }
            });
      
  });
  
});


    





