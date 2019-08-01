$.ajaxSetup({cache:false});
function validateChk(frmName,action)
{	
    
	var action = jQuery('#'+action).val();

	if(action=='')
	{
		jAlert("Please Choose an action to be applied.", "Alert");
	}
        
	var frm_length=document.forms[frmName].elements.length;
	var chk_length=0;
	var chk_total=0;
  
  
	for(i=0;i<frm_length;i++)
	{
		if(document.forms[frmName].elements[i].type=="checkbox")
		{
			
			if(document.forms[frmName].elements[i].checked  && document.forms[frmName].elements[i].name!="chkbox_n" )
				chk_length++;
			else
				chk_total++;
		}
	}
	
	if(chk_length==0)
	{
		if(chk_total==1)
		{
			jAlert("There is nothing to delete.", "Alert");
		}
		else
		{
			jAlert("Please check at least one checkbox", "Alert");
		}		
		return false;
	}
	else
	{
             if(action == "verified"){ 
	   jConfirm('Are you sure you want to verified these record?', 'Confirm Verified', function(r) {             
		
               if(r){ 
                  jQuery('#pageAction').val('verified');	  
            	  document.forms[frmName].submit();            	
            	  return true;
              }
          }
        );
	 
	  return false;
	 } 
           if(action == "unverified"){ 
	   jConfirm('Are you sure you want to unverified these record?', 'Confirm Verified', function(r) {             
		
               if(r){ 
                  jQuery('#pageAction').val('unverified');	  
            	  document.forms[frmName].submit();            	
            	  return true;
              }
          }
        );
	 
	  return false;
	 } 
	
	 if(action == "delete"){ 
	   jConfirm('Are you sure you want to delete these record?', 'Confirm Delete', function(r) {             
		
               if(r){ 
                  jQuery('#pageAction').val('delete');	  
            	  document.forms[frmName].submit();            	
            	  return true;
              }
          }
        );
	 
	  return false;
	 } 
	 
	 if(action == "invite"){ 
		   jConfirm('Are you sure you want to invite selected record(s)?', 'Confirm Activate', function(r) {
	              if(r){	
                        
                           jQuery('#pageAction').val('invite');
	            	  document.forms[frmName].submit();            	  
	            	  return true;
	               }
	          }
	        );
		 
		  return false;
	 } 
	 
	 if(action == "activate"){ 
		   jConfirm('Are you sure you want to activate selected record(s)?', 'Confirm Activate', function(r) {
	              if(r){	            	  
					  jQuery('#pageAction').val('activate');
	            	  document.forms[frmName].submit();            	  
	            	  return true;
	               }
	          }
	        );
		 
		  return false;
	 } 
	 if(action == "deactivate"){ 
		   jConfirm('Are you sure you want to deactivate selected record(s)?', 'Confirm Deactivate', function(r) {
	              if(r){
				      jQuery('#pageAction').val('deactivate');	            	 
	            	  document.forms[frmName].submit();            	  
	            	  return true;
	              }
	          }
	        );
		 
		  return false;
		 } 
		 
		 if(action == "addsubcategory"){ 
		   jConfirm('Are you sure you want to add?',SiteName+' Confirm', function(r) {
	              if(r){
				      jQuery('#pageAction').val('addsubcategory');	            	 
	            	  document.forms[frmName].submit();            	  
	            	  return true;
	              }
	          }
	        );		 
		  return false;
		 }	
	}
	return true;
}
function confirm_delete(x){
	
	jConfirm('Are you sure you want to delete this record?', 'Confirm Delete', function(r) {
		  if(r){
				window.location=jQuery(x).attr('href');	
			}
	 }
  );
  return false;
}



function init(target){
	jQuery(target).parent().parent().parent().css('min-height',25);
	jQuery('#page-loader').css('height',jQuery(target).height());
	jQuery('#page-loader').css('width',jQuery(target).width());
	
	jQuery(target+' thead tr th a, .pagination span a').live('click', function() {
		var location_url = jQuery(this).attr("href");
		
		jQuery.ajax({
			url: location_url,
			beforeSend: function ( xhr ) {
			jQuery('#page-loader').show();
			jQuery(target).fadeTo('fast',0.3);
			},
			success: function ( data ) {
			jQuery(target).html(data);
			jQuery('#page-loader').hide();
			jQuery(target).fadeTo('fast',1);		
			//$('tbody tr:even').addClass("alt-row");
		}});
		
		return false;
	});	
}
$(document).ready(function(){
	
	//Sidebar Accordion Menu:
		
		$("#main-nav li ul").hide(); // Hide all sub menus
		$("#main-nav li a.current").parent().find("ul").slideToggle("slow"); // Slide down the current menu item's sub menu
		
		$("#main-nav li a.nav-top-item").click( // When a top menu item is clicked...
			function () {
				$(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
				$(this).next().slideToggle("normal"); // Slide down the clicked sub menu
				return false;
			}
		);
		
		$("#main-nav li a.no-submenu").click( // When a menu item with no sub menu is clicked...
			function () {
				window.location.href=(this.href); // Just open the link instead of a sub menu
				return false;
			}
		); 

    // Sidebar Accordion Menu Hover Effect:
		
		$("#main-nav li .nav-top-item").hover(
			function () {
				$(this).stop().animate({ paddingRight: "25px" }, 200);
			}, 
			function () {
				$(this).stop().animate({ paddingRight: "15px" });
			}
		);

    //Minimize Content Box
		
		$(".content-box-header h3").css({ "cursor":"s-resize" }); // Give the h3 in Content Box Header a different cursor
		$(".closed-box .content-box-content").hide(); // Hide the content of the header if it has the class "closed"
		$(".closed-box .content-box-tabs").hide(); // Hide the tabs in the header if it has the class "closed"
		
		$(".content-box-header h3").click( // When the h3 is clicked...
			function () {
			  $(this).parent().next().toggle(); // Toggle the Content Box
			  $(this).parent().toggleClass("closed-box"); // Toggle the class "closed-box" on the content box
			  $(this).parent().find(".content-box-tabs").toggle(); // Toggle the tabs
			}
		);

    // Content box tabs:
		
		$('.content-box .content-box-content div.tab-content').hide(); // Hide the content divs
		$('ul.content-box-tabs li a.default-tab').addClass('current'); // Add the class "current" to the default tab
		$('.content-box-content div.default-tab').show(); // Show the div with class "default-tab"
		$('.content-box ul.content-box-tabs li a').click( // When a tab is clicked...
			function() { 
				$(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
				$(this).addClass('current'); // Add class "current" to clicked tab
				var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
				
                                var myarr1 = currentTab.split("/");
                                
                                if(myarr1[0]){
                                   currentTab = myarr1[0];
                                }
                               
                                $(currentTab).siblings().hide(); // Hide all content divs
				$(currentTab).show(); // Show the content div with the id equal to the id of clicked tab
				
				var type = $(this).attr('href');
                                
				type=type.replace('#','');
                                var target = '#'+type.replace('#','');
                                
                                var myarr = target.split("/");
                                if(myarr[0]){
                                    var target = '#'+myarr[0].replace('#','');
                                   
                                }
                               
                              
				jQuery.ajax({
                                 
				url: CurrentUrl+'/'+type+'/page:1',
					beforeSend: function ( xhr ) {
					jQuery('#page-loader').show();
                                        jQuery(target).fadeTo('fast',0.3);
					},
					success: function ( data ) {
                                        
                                        jQuery(target).html(data);
                                        jQuery('#page-loader').hide();
					jQuery(target).fadeTo('fast',1);	
					init(target);							
					
				}});
				
				return false; 
			}
		);
		
		
		
		
		
    //Close button:
		
		$(".close").click(
			function () {
				$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
					$(this).slideUp(400);
				});
				return false;
			}
		);
		
		

    // Alternating table rows:
		
		$('tbody tr:even').addClass("alt-row"); // Add class "alt-row" to even table rows

    // Check all checkboxes when the one in a table head is checked:
		
		$('.check-all').live('click',
			function(){
				$(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));   
			}
		);

    // Initialise Facebox Modal window:
		
		//$('a[rel*=modal]').facebox(); // Applies modal window to any link with attribute rel="modal"

    // Initialise jQuery WYSIWYG:
		
		$(".wysiwyg").wysiwyg(); // Applies WYSIWYG editor to any textarea with the class "wysiwyg"

});
  
  
  