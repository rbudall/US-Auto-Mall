// JavaScript Document
//////////////////////////////////////////////////////////////////////////////////////////////////
//
//	mPlayer Popup Window
//
//////////////////////////////////////////////////////////////////////////////////////////////////
function mPlayer(playlist) {
	window.open( 
		"/testserver/audio/mplayer.php?album="+playlist, 
		"mPlayer", 
		"status = 0, height = 356, width = 941, toolbar = no, scrollbars = no, location = no, resizable = no" )
}
//////////////////////////////////////////////////////////////////////////////////////////////////
//	End mPlayer Popup Window
//////////////////////////////////////////////////////////////////////////////////////////////////

function confirmDelete() {
	confirm('Are you sure you want to delete this post?');
}

//////////////////////////////////////////////////////////////////////////////////////////////////
//
//	Form validation
//
//////////////////////////////////////////////////////////////////////////////////////////////////

function checkRegForm(){

var v_fname, v_lname, v_username, v_password, v_password2, v_email, v_email2;

with(window.document.register){
	v_fname = fname;
	v_lname = lname;
	v_username = username;
	v_password = password;
	v_password2 = password2;
	v_email = email;
	v_email2 = email2;
}
	
	if ((trim(v_fname.value) == '') || (trim(v_fname.value) == 'First Name')){
		alert("Please enter your first name.");
		v_fname.focus();
		return false;
	} else if ((trim(v_lname.value) == '') || (trim(v_lname.value) == 'Last Name')){
		alert("Please enter your last name.");
		v_lname.focus();
		return false;
	} else if (trim(v_username.value) == ''){
		alert("Please enter a username.");
		v_username.focus();
		return false;
	} else if ((v_username.value.length < 6) || (v_username.value.length > 25)){
		alert("your username must be between 6 and 25 characters long. Please try again.");
		v_username.focus();
		return false;
	} else if (trim(v_password.value) == ''){
		alert("Please enter a password");
		v_password.focus();	
		return false;
	} else if (v_password.value.length < 6){
		alert("your password must be at least 6 characters long. Please try again.");
		v_password.focus();	
		return false;
	} else if (trim(v_password2.value) == ''){
		alert("Please re-enter your password.");
		v_password2.focus();
		return false;
	} else if (v_password2.value != v_password.value){
		alert("The password you entered does not match. Please try again.");
		v_password2.focus();
		return false;
	} else if (trim(v_email.value) == ''){
		alert("Please enter your E-Mail.");
		v_email.focus();
		return false;
	} else if (!isEmail(trim(v_email.value))){
		alert("Please enter a valid E-Mail.");
		v_email.focus();
		return false;
	} else if (trim(v_email2.value) == ''){
		alert("Please re-enter your E-Mail.");
		v_email2.focus();
		return false;
	} else if (v_email2.value != v_email.value){
		alert("The E-Mail address you entered does not match. Please try again.");
		v_email2.focus();
		return false;
	} 

}

function checkChgPassForm(){

var v_old, v_new, v_new2;

with(window.document.chgpass){
	v_old = old_password;
	v_new = new_password;
	v_new2 = new_password2;
}
	if (trim(v_old.value) == ''){
		alert("Please enter your old password.");
		v_old.focus();
		return false;
	} else if (trim(v_new.value) == ''){
		alert("Please enter a password");
		v_new.focus();	
		return false;
	} else if (v_new.value.length < 6){
		alert("your password must be at least 6 characters long. Please try again.");
		v_new.focus();	
		return false;
	} else if (trim(v_new2.value) == ''){
		alert("Please re-enter your password.");
		v_new2.focus();
		return false;
	} else if (v_new2.value != v_new.value){
		alert("The password you entered does not match. Please try again.");
		v_new2.focus();
		return false;
	} 
	

}

function checkEmail(v_email){
	if (!isEmail(trim(v_email.value))){
		alert("Please enter a valid E-Mail.");
		v_email.focus();
		return false;
	}
}

function checkChgEmailForm(){

var v_old, v_new, v_new2;

with(window.document.chgemail){
	v_old = old_email;
	v_new = new_email;
	v_new2 = new_email2;
}	
	if (trim(v_old.value) == ''){
		alert("Please enter your E-Mail.");
		v_old.focus();
		return false;
	} else if (!isEmail(trim(v_old.value))){
		alert("Please enter a valid E-Mail.");
		v_old.focus();
		return false;
	} else if (trim(v_new.value) == ''){
		alert("Please enter your E-Mail.");
		v_new.focus();
		return false;
	} else if (!isEmail(trim(v_new.value))){
		alert("Please enter a valid E-Mail.");
		v_new.focus();
		return false;
	} else if (trim(v_new2.value) == ''){
		alert("Please re-enter your E-Mail.");
		v_new2.focus();
		return false;
	} else if (v_new2.value != v_new.value){
		alert("The E-Mail address you entered does not match. Please try again.");
		v_new2.focus();
		return false;
	}
	

}

function checkRstPassForm(){

with(window.document.rstpass){
	var v_new = email;
}	
	if (trim(v_new.value) == ''){
		alert("Please enter your E-Mail.");
		v_new.focus();
		return false;
	} else if (!isEmail(trim(v_new.value))){
		alert("Please enter a valid E-Mail.");
		v_new.focus();
		return false;
	} 
}

function checkSearchForm(){

var v_srch

with(window.document.srch){
	v_srch = srchfield;
}	
	if (trim(v_srch.value) == ''){
		alert("Please enter a Search Term.");
		v_srch.focus();
		return false;
	}
}
	
function trim(str){
   return str.replace(/^\s+|\s+$/g,'');
}

function isEmail(str){
	
   var regex = /^[-_.a-z0-9]+@(([-_a-z0-9]+\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i;

return regex.test(str);
}


function checkInstallForm(){
	
		var v_fname, v_lname, v_username, v_password, v_password2, v_email, v_email2;
		
		with(window.document.install){
			v_fname = fname;
			v_lname = lname;
			v_username = username;
			v_password = password;
			v_password2 = password2;
			v_email = email;
			v_email2 = email2;
		}
			
		if ((trim(v_fname.value) == '') || (trim(v_fname.value) == 'First Name')){
			alert("Please enter your first name.");
			v_fname.focus();
			return false;
		} else if ((trim(v_lname.value) == '') || (trim(v_lname.value) == 'Last Name')){
			alert("Please enter your last name.");
			v_lname.focus();
			return false;
		} else if (trim(v_username.value) == ''){
			alert("Please enter a username.");
			v_username.focus();
			return false;
		} else if ((v_username.value.length < 6) || (v_username.value.length > 25)){
			alert("your username must be between 6 and 25 characters long. Please try again.");
			v_username.focus();
			return false;
		} else if (trim(v_password.value) == ''){
			alert("Please enter a password");
			v_password.focus();	
			return false;
		} else if (v_password.value.length < 6){
			alert("your password must be at least 6 characters long. Please try again.");
			v_password.focus();	
			return false;
		} else if (trim(v_password2.value) == ''){
			alert("Please re-enter your password.");
			v_password2.focus();
			return false;
		} else if (v_password2.value != v_password.value){
			alert("The password you entered does not match. Please try again.");
			v_password2.focus();
			return false;
		} else if (trim(v_email.value) == ''){
			alert("Please enter your E-Mail.");
			v_email.focus();
			return false;
		} else if (!isEmail(trim(v_email.value))){
			alert("Please enter a valid E-Mail.");
			v_email.focus();
			return false;
		} else if (trim(v_email2.value) == ''){
			alert("Please re-enter your E-Mail.");
			v_email2.focus();
			return false;
		} else if (v_email2.value != v_email.value){
			alert("The E-Mail address you entered does not match. Please try again.");
			v_email2.focus();
			return false;
		} 
	
	}

//////////////////////////////////////////////////////////////////////////////////////////////////
//	End Form validation
//////////////////////////////////////////////////////////////////////////////////////////////////