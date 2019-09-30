const CreditCardFormValidate = function() {
    var cardValid = 0;
      
    // Card number validation
    $('#card_number').validateCreditCard(function(result) {
        var cardType = (result.card_type == null)?'':result.card_type.name;
        if(cardType == 'Visa'){
            var backPosition = result.valid?'2px -163px, 260px -87px':'2px -163px, 260px -61px';
        }else if(cardType == 'MasterCard'){
            var backPosition = result.valid?'2px -247px, 260px -87px':'2px -247px, 260px -61px';
        }else if(cardType == 'Maestro'){
            var backPosition = result.valid?'2px -289px, 260px -87px':'2px -289px, 260px -61px';
        }else if(cardType == 'Discover'){
            var backPosition = result.valid?'2px -331px, 260px -87px':'2px -331px, 260px -61px';
        }else if(cardType == 'Amex'){
            var backPosition = result.valid?'2px -121px, 260px -87px':'2px -121px, 260px -61px';
        }else{
            var backPosition = result.valid?'2px -121px, 260px -87px':'2px -121px, 260px -61px';
        }
        $('#card_number').css("background-position", backPosition);
        if(result.valid){
            $("#card_type").val(cardType);
            $("#card_number").removeClass('required');
            cardValid = 1;
        }else{
            $("#card_type").val('');
            $("#card_number").addClass('required');
            cardValid = 0;
        }
    });
      
    // Card details validation
    var cardName = $("#name_on_card").val();
    var expMonth = $("#expiry_month").val();
    var expYear = $("#expiry_year").val();
    var cvv = $("#cvv").val();
    var regName = /^[a-z ,.'-]+$/i;
    var regMonth = /^01|02|03|04|05|06|07|08|09|10|11|12$/;
    var regYear = /^2017|2018|2019|2020|2021|2022|2023|2024|2025|2026|2027|2028|2029|2030|2031$/;
    var regCVV = /^[0-9]{3,3}$/;
    if(cardValid == 0){
        $("#card_number").addClass('required');
        $("#card_number").focus();
        return false;
    }else if(!regMonth.test(expMonth)){
        $("#card_number").removeClass('required');
        $("#expiry_month").addClass('required');
        $("#expiry_month").focus();
        return false;
    }else if(!regYear.test(expYear)){
        $("#card_number").removeClass('required');
        $("#expiry_month").removeClass('required');
        $("#expiry_year").addClass('required');
        $("#expiry_year").focus();
        return false;
    }else if(!regCVV.test(cvv)){
        $("#card_number").removeClass('required');
        $("#expiry_month").removeClass('required');
        $("#expiry_year").removeClass('required');
        $("#cvv").addClass('required');
        $("#cvv").focus();
        return false;
    }else if(!regName.test(cardName)){
        $("#card_number").removeClass('required');
        $("#expiry_month").removeClass('required');
        $("#expiry_year").removeClass('required');
        $("#cvv").removeClass('required');
        $("#name_on_card").addClass('required');
        $("#name_on_card").focus();
        return false;
    }else{
        $("#card_number").removeClass('required');
        $("#expiry_month").removeClass('required');
        $("#expiry_year").removeClass('required');
        $("#cvv").removeClass('required');
        $("#name_on_card").removeClass('required');
        $('#cardSubmitBtn').prop('disabled', false);  
        return true;
    }
}

$(document).ready(function(){
    // Initiate validation on input fields
    $('#paymentForm input[type=text]').on('keyup',function(){
        cardFormValidate();
    });
    
    // Submit card form
    // $("#cardSubmitBtn").on('click',function(){
    //     $('.status-msg').remove();
    //     if(cardFormValidate()){
    //         var formData = $('#paymentForm').serialize();
    //         $.ajax({
    //             type:'POST',
    //             url:'payment_process.php',
    //             dataType: "json",
    //             data:formData,
    //             beforeSend: function(){
    //                 $("#cardSubmitBtn").prop('disabled', true);
    //                 $("#cardSubmitBtn").val('Processing....');
    //             },
    //             success:function(data){
    //                 if(data.status == 1){
    //                     $('#paymentSection').html('<p class="status-msg success">The transaction was successful. Order ID: <span>'+data.orderID+'</span></p>');
    //                 }else{
    //                     $("#cardSubmitBtn").prop('disabled', false);
    //                     $("#cardSubmitBtn").val('Proceed');
    //                     $('#paymentSection').prepend('<p class="status-msg error">Transaction has been failed, please try again.</p>');
    //                 }
    //             }
    //         });
    //     }
    // });
});