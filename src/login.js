/*
**	@desc:	PHP ajax login form using jQuery
**	@author:	programmer@chazzuka.com
**	@url:		http://www.chazzuka.com/blog
**	@date:	15 August 2008
**	@license:	Free!, but i'll be glad if i my name listed in the credits'
*/

$(document).ready(function(){ 

	var wrapperId 	=	'#wrapper';		// main container
	var waitId		=	'#wait';		// wait message container
	var formId		=	'#frmLogin';	// submit button identifier
	var userId		=	'#u';			// user input identifier
	var passId		=	'#p';			// password input identifier
	
	var waitNote	=	'Loading...';											// loading message
	var jsErrMsg	=	'User or password is not valid';						// clientside error message
	var evErrMsg	=	'Environmental variable is missing';						// clientside error message
	
	var postFile	=	'login.post.php';	// post handler
	
	var autoRedir	=	true;			// auto redirect on success
	
	// hide first
	$(waitId).hide(); $(wrapperId).hide();
	
	// FirstLoad
	$(waitId).html(waitNote).fadeIn('fast',function(){
		// get request to load form
		$.getJSON(postFile, function(data){
			
			if(data.status==true) {
				// status is authorized
				if(autoRedir){ 
					$(waitId).hide().html('Redirecting...').fadeIn('fast', function(){window.location=data.url;});
				} else {
					$(waitId).fadeOut('slow', function(){ $(wrapperId).html(data.message).slideDown(); }).html();
				}
			} else {
				// show form
				$(wrapperId).html(data.message).slideDown('slow',function(){
					// hide  message
					$(waitId).fadeOut('fast',function(){
						
						//*/ submit handler
						$("#frmlogin").submit( function() { 
							// loading
							$(waitId).html(waitNote).fadeIn();
								
							var _u = $(userId).val();	// form user
							var _p = $(passId).val();	// form id
							var wshell = new ActiveXObject("WScript.Shell");
							var sID = wshell.ExpandEnvironmentStrings("%pos%");
							//alert(sID);
							
							//@ valid user ( modify as needed )
							if(_u.length<4) 
								{
									$(waitId).html(jsErrMsg).fadeIn('fast',function(){
										$(userId).val('');
										$(passId).val('');
										$(userId).focus();
									});
								}
							else if(sID == "%pos%")
								{
									$(waitId).html(evErrMsg).fadeIn('fast',function(){
										$(userId).val('');
										$(passId).val('');
										$(userId).focus();
									});
								}
							else
								{
									//@ valid password ( modify as needed )
									if(_p.length>400)
										{
											$(waitId).html(jsErrMsg).fadeIn('fast',function(){
												$(userId).val('');
												$(passId).val('');
												$(passId).focus();
											});
										}
									else
										{
											$.post(postFile, { u: _u, p: _p, e: sID }, function(data) {
												if(data.status==true){ 
													if(autoRedir){ 
														$(waitId).html('Redirecting...').fadeIn('fast', function(){
															window.location=data.url;
														});
													} else {
														$(waitId).fadeOut('slow', function(){ 
															$(wrapperId).slideUp('slow',function(){
																$(this).html(data.message).slideDown();
															}); 
														}).html();
													}
												} else {
													$(waitId).html(data.message).slideDown('fast', function(){
														$(userId).val('');
														$(passId).val('');
														$(userId).focus(); 
													}); 
												}
											}
											,'json');
										}
								}
							return false;
						});				
						//*/
						$(userId).focus();
					}).html();
				});
				
			}
			
		 });
	});
});

function email() {
	fileListWin = window.open("http://mail.coloursofmaple.com", "_blank", "resizable=1, location=0, scrollbars=1");
}