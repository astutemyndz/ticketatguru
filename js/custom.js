//import { CradleLoader } from "./CradleLoader";

//import { CradleLoader } from "./CradleLoader";


// const funcCountDown = function () {
// 	// console.log(countEvent);
// 	var i = 1;
// 	for(i; i <= countEvent; i++) {
// 		var countdown = $(`#countdown_${i}`).data("id");
// 		// console.log(countdown);
// 		var countdown_date = $(`#countdown_${i}`).data("date");
		
// 		$(`#countdown_${i}`).countdown(countdown_date, function (event) {
//             console.log(countdown_date+'=='+countdown);
// 			$(`#dday_${i}`).html(event.strftime('%-D'));
// 			$(`#dhour_${i}`).html(event.strftime('%-H'));
// 			$(`#dmin_${i}`).html(event.strftime('%-M'));
// 			$(`#dsec_${i}`).html(event.strftime('%-S'));
// 		});
// 	}
	
// }

//funcCountDown();
window.addEventListener('load', () => {
//    / 
    $( '.hamburger-menu' ).on( 'click', function() {
        $(this).toggleClass('open');
        $('.site-navigation').toggleClass('show');
    });

    
        

});

function getBaseUrl() {
    var re = new RegExp(/^.*\//);
    return re.exec(window.location.href);
}



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



const validationMessage = function(targetElement, props) {
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





