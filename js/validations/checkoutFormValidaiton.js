// $(document).ready(function(){
//     // Code for the Validator
//     var $validator = $('.wizard-card form').validate({
//           rules: {
//             c_firstName: {
//               required: true,
//               minlength: 3
//             },
//             c_lastName: {
//               required: true,
//               minlength: 3
//             },
//             c_email: {
//               required: true
//             },
//             c_phone: {
//                 required: true,
//                 minlength: 3
//             },
//             c_country: {
//                 required: true,
//                 minlength: 3
//             },
//             c_city: {
//                 required: true,
//                 minlength: 3
//             },
//             c_address: {
//                 required: true,
//                 minlength: 3
//             },
//             c_zip: {
//                 required: true,
//                 minlength: 3
//             },
//         },
//         errorClass: "invalid",
//         validClass: "success",
//         messages: {
//             c_firstName: {
//                 required: "Please specify your first name",
//                 minlength: jQuery.validator.format("At least {0} characters required!")
//             },
//             c_lastName: {
//                 required: "Please specify your last name",
//                 minlength: jQuery.validator.format("At least {0} characters required!")
//             },
//             email: {
//               required: "We need your email address to contact you",
//               email: "Your email address must be in the format of name@domain.com"
//             }
//         },
//         highlight: function(element, errorClass, validClass) {
//             $(element).addClass(errorClass).removeClass(validClass);
//             $(element.form).find("label[for=" + element.id + "]")
//               .addClass(errorClass);
//         },
//         unhighlight: function(element, errorClass, validClass) {
//             $(element).removeClass(errorClass).addClass(validClass);
//             $(element.form).find("label[for=" + element.id + "]")
//               .removeClass(errorClass);
//         }
//     });
// });
