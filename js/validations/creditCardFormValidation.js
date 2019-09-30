$(function() {

    var $card_number = $('#card_number');
    var $cardNumberField = $('#card-number-field');

    var $cc_code = $("#cc_code");
    var $cardCvvField = $('#card-cvv-field');

    var $formatCardExpiry = $("#formatCardExpiry");
    var $formatCardExpiryField = $('#formatCardExpiryField');
    
    var $cc_type = $('#cc_type');

    var $finish = $("#finish");
    
    var $masterCard = $("#mastercard");
    var $visa = $("#visa");
    var $amex = $("#amex");
   

    $card_number.payment('formatCardNumber');
    $formatCardExpiry.payment('formatCardExpiry');
    $cc_code.payment('formatCardCVC');

    var validateDetails = function() {
        var validateCardNumber  = $card_number.payment('formatCardNumber');
        var expiry              = $('#formatCardExpiry').payment('cardExpiryVal');
       // console.log(expiry);
        var validateExpiry      = $.payment.validateCardExpiry(expiry["month"], expiry["year"]);
        var validateCVC         = $.payment.validateCardCVC($cc_code.val());
        var cardType = null;

    if (validateCardNumber) {
        
        $cardNumberField.addClass('has-success');
        $cardNumberField.removeClass('has-error');
        cardType = $.payment.cardType($card_number.val());
    } else {
        $cardNumberField.removeClass('has-success');
        $cardNumberField.addClass('has-error');
        cardType = null;
    }
    console.log(validateCardNumber);
    $cc_type.val(cardType);

    if ($.payment.cardType($card_number.val()) == 'visa') {
        $masterCard.addClass('transparent');
        $amex.addClass('transparent');
    } else if ($.payment.cardType($card_number.val()) == 'amex') {
        $masterCard.addClass('transparent');
        $visa.addClass('transparent');
    } else if ($.payment.cardType($card_number.val()) == 'mastercard') {
        $amex.addClass('transparent');
        $visa.addClass('transparent');
    }

    if (validateExpiry) {
        $formatCardExpiryField.addClass('has-success');
        $formatCardExpiryField.removeClass('has-error');
        $("#finish").attr("disabled", false);
    } else {
        $formatCardExpiryField.removeClass('has-success');
        $formatCardExpiryField.addClass('has-error');
        $("#finish").attr("disabled", true);
    }

    if (validateCVC) {
        $cardCvvField.addClass('has-success');
        $cardCvvField.removeClass('has-error');
    } else {
        $cardCvvField.removeClass('has-success');
        $cardCvvField.addClass('has-error');
    }

    }

    $('.paymentInput').bind('change paste keyup', function() {
        validateDetails();
    });

});
