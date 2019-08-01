function openOffersDialog() {
	$('.popup_wrapper').show();	
	$('.overlay').fadeIn('fast', function() {
		$('.boxpopup').css('display','block');
       // $('.boxpopup').animate({'left':'30%'},500);
	});
}

function closeOffersDialog(div) {
	//$('.notification').remove();
	
	if(div=='play'){
		var src= $(".rplacescript").find('iframe').attr('src');
		$(".rplacescript").find('iframe').attr('src',src+"&autoplay=0");
	}
    
    if(div=='add_in_folder'){
        
        $('.'+div).hide();
    } 
     
    if(div=='add_in_folder_project'){
         $('.'+div).hide();
    }
	$('.overlay').fadeOut('fast');
	$('.boxpopup').hide();
	$('.show_'+div).hide();
	$('.popup_wrapper').hide();
	

	//$('#update_about').val($.trim($('.user_about').html()));
}

/* function data_update(data,div){
	$('.notification').remove();
	
	if(data==1){
		
		var successDiv = '<div class="notification success png_bg"><a class="close" href="/elance/login"><img alt="close" title="Close this notification" src="/elance/img/admin/cross_grey_small.png"></a><div><h2>Profile has been updated succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		//var fo=$("#update_form_"+div).serializeArray();
		if(div=='identity'){
			var img='<img src="'+$('#CountryFlag').val()+'" width="16" title="'+$("#UserCountryId :selected").text()+'" alt="'+$("#UserCountryId :selected").text()+'" >';
			$('.user_'+div).html(img+' '+$("#UserCountryId :selected").text()+', '+$('#UserCity').val()+', '+$("#UserStateId :selected").text());
		}else if(div=='general'){
			$('.user_'+div).html($('#UserFirstName').val()+' '+$('#UserLastName').val());
		}else{			
			$('.user_'+div).html($('#update_'+div).val());
		}
		closeOffersDialog(div);
	}else{		
		$('.show_'+div).html(data);
		$(".boxpopup").effect("shake", { times: 3 }, 64);
	}
} */

function video_update(data,div){
	$('.notification').remove();	
	if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Profile has been updated succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		$('.user_video img').attr('src',data.img_url);
		$('.LogFrm .rplacescript').html(data.video_url);
		$('#update_hidden_video').html(data.video_url);
		closeOffersDialog(div);
	}else{
		$('.show_'+div).html(data.content);
		$(".boxpopup").effect("shake", { times: 3 }, 64);
	}
}

function avtar_update(data){
	var avtarJson   = eval('(' +data+ ')');
	if(avtarJson.action==1){
		$("#mainClientImage").attr('src',avtarJson.value);
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Profile has been updated succesfully.</h2></div></div>';
		closeOffersDialog('avtar');
		$(".header").after(successDiv);
	}else{
		var errorDiv = '<div class="notification error png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>'+avtarJson.value+'</h2></div></div>';
		closeOffersDialog('avtar');
		$(".header").after(errorDiv);
	}
}

function about_update(data){
	$('.notification').remove();
	 if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Profile has been updated succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		$('.user_about').html($('#update_about').val());
		$('#update_hidden_about').html($('#update_about').val());
		closeOffersDialog('about');
	} else {
		$('.show_about').html(data.content);
		$(".boxpopup").effect("shake", {times: 3 }, 64);
	} 
}
function invite_apply(data){
	
	$('.notification').remove();
	if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Invitation sent successfully.</h2></div></div>';
		$(".header").after(successDiv);
		$("#UserProfileDescription").val('');
		$("#project_list").val('');
		closeOffersDialog('about');
		closeOffersDialog('invite');
	} else {
		$('.show_invite').html(data.content);
           $(".boxpopup").effect( "shake", {times:4}, 64 );
        
//		$(".").effect( "shake", {times: 3 }, 1064);
	} 
}
function send_mail(data){
	$('.notification').remove();
	 
	 if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Invitation sent successfully.</h2></div></div>';
		$(".header").after(successDiv);
		$("#send_desc").val('');
		closeOffersDialog('send_mail');
	} else {
		$('.show_send_mail').html(data.content);
		$(".boxpopup").effect("shake", {times: 3 }, 64);
	} 
}
function general_update(data){
	$('.notification').remove();
	 if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Profile has been updated succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		$('.user_general').html($('#UserFirstName').val()+' '+$('#UserLastName').val());
		$('#UserFirstNameHidden').val($('#UserFirstName').val());
		$('#UserLastNameHidden').val($('#UserLastName').val());
		closeOffersDialog('general');
	} else {
		$('.show_general').html(data.content);
		$(".boxpopup").effect("shake", {times: 3 }, 64);
	} 
}

function identity_update(data){
	$('.notification').remove();
	 if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Profile has been updated succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		var img='<img src="'+$('#CountryFlag').val()+'" width="16" title="'+$("#UserCountryId :selected").text()+'" alt="'+$("#UserCountryId :selected").text()+'" >';
		$('.user_identity').html(img+' '+$("#UserCountryId :selected").text()+', '+$('#UserCity').val()+', '+$("#UserStateId :selected").text());
		
		$('#UserCountryIdHidden').val($('#UserCountryId').val());
		$('#UserStateIdHidden').val($('#UserStateId').val());
		$('#UserCityHidden').val($('#UserCity').val());
		closeOffersDialog('identity');
	} else {
		$('.show_identity').html(data.content);
		$(".boxpopup").effect("shake", {times: 3 }, 64);
	} 
}

function add_set(data){
	$('.notification').remove();
	if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Set has been added succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		$(".RightProRUL").append("<li><a>"+data.set_name+"(0)</a><div class='InnerUlSetDiv' style='display:none;'><ul class='InnerUlSet'><li class='InnerUlSetedit'><a data-id="+data.set_id+">Edit</a></li><li class='InnerUlSetdelete'><a data-id="+data.set_id+">Delete</a></li></ul></div></li>");
		$("#hiddenforSet").val('1');
		$('.InnerUlSetDiv').hide();
		closeOffersDialog('add_set');
	} else {
		$('.show_add_set').html(data.content);
		$(".boxpopup").effect("shake", {times: 3 }, 64);
	} 
}

function edit_set(data){
	$('.notification').remove();
	if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Set has been updated succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		$('[data-id='+data.set_id+']').parents('div').parents('li').find('a:first').html(data.set_name+'('+data.count+')');
		$('.InnerUlSetDiv').hide();
		closeOffersDialog('edit_set');
	} else {
		$('.show_edit_set').html(data.content);
		$(".boxpopup").effect("shake", {times: 3 }, 64);
	} 
}

function delete_set(data){
	$('.notification').remove();
	if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Set has been deleted succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		$('[data-id='+data.set_id+']').parents('div').parents('li').remove();
		closeOffersDialog('delete_set');
	} else {
		$('.show_delete_set').html(data.content);
		$('.error-message').addClass('input-notification-popup');
		$('.input-notification-popup').removeClass('error-message');
		$(".boxpopup").effect("shake", {times: 3}, 64);
	} 
}

function update_currency(data){
	if(data.action==1){
		$('.input-notification-popup').remove();
		$(".getAmount").show();
		$("#ProjectConvertedAmount").val(data.convertedAmount);
	} else {
		$('.show_currencyconverter').html(data.content);
		$(".boxpopup").effect("shake", {times: 3 }, 64);
	} 
}

function post_proposal(data){
// console.log(data); 
	if(data.action==1){
		location.reload();
	}else if(data.action==5){
		$(location).attr('href', SiteUrl+'/user_profiles/list_connects');	
	}else if(data.action==6){
		$(location).attr('href', SiteUrl+'/user_profiles/client_profile');	
	}else if(data.action==4){
		location.reload();
	}else if(data.action==3){
		$(location).attr('href', SiteUrl+'/user_profiles/update_contractor_profile');		
	}else if(data.action == 2) {
		var url = $(location).attr('href');
		var new_url = url.replace(data.id,'');
		$(location).attr('href', new_url);
	}else if(data.action==7){
		$(location).attr('href', SiteUrl+'/profile/'+data.username);		
	} else {
		$('.show_post_proposal').html(data.content);
	} 
}

function subscribe(data){
	
	if(data.action==1){
		location.reload();
	}else if(data.action == 2) {
		$('.subscribe').html(data.content);
	} 
}

function update_bid_message(data){
$('.notification').remove();
	if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="/elance/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Message has been sent succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		//$('[data-id='+data.set_id+']').parents('div').parents('li').remove();
		closeOffersDialog('show_about');
	} else{
		$('.show_about').html(data.content);
		$(".proTitle").html(data.protitle);
		$(".to_user_name").html(data.user_first_name);
		$('.error-message').addClass('input-notification-popup');
		$('.input-notification-popup').removeClass('error-message');
	}  
}

function update_feedback_rating(data){
$('.notification').remove();
	if(data.action==1){
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>Feedback has been saved succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		//$('[data-id='+data.set_id+']').parents('div').parents('li').remove();
		closeOffersDialog('show_about');
	} else{
		$('.show_about').html(data.content);
		$(".proTitle").html(data.protitle);
		$(".to_user_name").html(data.user_first_name);
		$('.error-message').addClass('input-notification-popup');
		$('.input-notification-popup').removeClass('error-message');
	}  
}
function message_box(project_id,user_id){	
	jQuery('.hiddenDiv').show();
	jQuery.ajax({		
			url: SiteUrl + '/messages/project_message/'+project_id+'/'+user_id,
			success: function ( data ) {
			//console.log(data.content);
			jQuery('#message_pro').html(data.content);
			if(!$('#message_pro').is(':visible')){
				jQuery('#message_pro').show();
				$('#message_pro').animate({right: '0px'}, 300);
			}else{
				$('#message_pro').animate({right: '-620px'}, 300,function(){
					jQuery('#message_pro').hide();
				});
				
			}
			}
		});
}
function update_bid_decline(data){
$('.notification').remove();
	if(data.action==1){location.reload();
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>User has been declined succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		//$('[data-id='+data.set_id+']').parents('div').parents('li').remove();
		closeOffersDialog('show_decline');
	} else{
		$('.show_decline').html(data.content);
		$(".proTitle").html(data.protitle);
		$(".to_user_name").html(data.user_first_name);
		$('.error-message').addClass('input-notification-popup');
		$('.input-notification-popup').removeClass('error-message');
	}  
}
function update_bid_award(data){
$('.notification').remove();
	if(data.action==1){location.reload();
		var successDiv = '<div class="notification success png_bg"><a class="close" href="'+SiteUrl+'/login"><img alt="close" title="Close this notification" src="'+SiteUrl+'/img/admin/cross_grey_small.png"></a><div><h2>User has been awarded succesfully.</h2></div></div>';
		$(".header").after(successDiv);
		//$('[data-id='+data.set_id+']').parents('div').parents('li').remove();
		closeOffersDialog('show_award');
	} else{
		$('.show_award').html(data.content);
		$(".proTitle").html(data.protitle);
		$(".to_user_name").html(data.user_first_name);
		$('.error-message').addClass('input-notification-popup');
		$('.input-notification-popup').removeClass('error-message');
	}  
}


function request_pay_message(data){
$('.loading').hide();
$('.notification').remove();
	if(data.action==1){
		//$(".update_about").val('');
		location.reload();
		
	} else{
		$('.show_about').html(data.content);
		$('.error-message').addClass('input-notification-popup');
		$('.input-notification-popup').removeClass('error-message');
		$(".boxpopup").effect("shake", {times: 3 }, 64);
	}  
}

function advertisment_from(data){
$('.loading').hide();
$('.notification').remove();
	if(data.action==1){
		//$(".update_about").val('');
		$('.show_advertise_forum').html(data.content);
		$("#AdvertisementOrderAdvertisementId").val(data.id);
		$("#AdvertisementOrderAmount").val(data.amount);
		
	} else{
		$('.show_advertise_forum').html(data.content);
		$('.error-message').addClass('input-notification-popup');
		$('.input-notification-popup').removeClass('error-message');
		$(".boxpopup").effect("shake", {times: 3 }, 64);
	}  
}


function advertisement_pay(data){
$('.loading').hide();
$('.notification').remove();
	if(data.action==1){
		location.reload();
		
	} 
	else if(data.action==2){
		location.reload();
	}
	else{
		$('.show_advertise_forum').html(data.content);
		$('.error-message').addClass('input-notification-popup');
		$('.input-notification-popup').removeClass('error-message');
		$(".boxpopup").effect("shake", {times: 3 }, 64);
	}  
}
