function check_uncheck(FormName)
{
	
	var formElementCount = document.forms[FormName].elements.length;
	
	for(i=0;i<formElementCount;i++)
	{
		if(document.getElementById('chkbox_id').checked == true)
		{			
			if(document.forms[FormName].elements[i].type == 'checkbox')
			{
				document.forms[FormName].elements[i].checked = true;
			}
		}
		else
		{
			if(document.forms[FormName].elements[i].type == 'checkbox')
			{
				document.forms[FormName].elements[i].checked = false;
			}
		}
	}
}


function validateChk(frmName, action)
{	
	//alert(frmName);
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
			jAlert("Please select at least one checkbox", "Alert");
		}
		
		return false;
	}
	else
	{
		
	if(action == "set_de" || action == "unset_de"){
		if(chk_length > 1){
			jAlert("Please select only one checkbox for set default", "Alert");
			return false;
		}
	}			
	 if(action == "delete"){ 
	   jConfirm('Are you sure you want to delete this record?', 'Confirm Delete', function(r) {             
			 if(r){  				
            	  jQuery('#pageAction').val('delete');	  
            	  document.forms[frmName].submit();            	
            	  return true;
              }
          }
        );
	 
	  return false;
	 } 
	

	if(action == "auth"){ 
	   jConfirm('Are you sure you want to authenticate selected record(s)?', 'Confirm Authenticate', function(r) {
			  if(r){	            	  
				  jQuery('#pageAction').val('auth');
				  document.forms[frmName].submit();            	  
				  return true;
			   }
		  }
		);
	 
	  return false;
	} 
	 
	if(action == "de_auth"){ 
		   jConfirm('Are you sure you want to deauthenticate selected record(s)?', 'Confirm Deauthenticate', function(r) {
				  if(r){
					  jQuery('#pageAction').val('de_auth');	            	 
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
	if(action == "moved"){ 
		   jConfirm('Are you sure want to moved this deal to main list record(s)?', 'Confirm Activate', function(r) {
	              if(r){	            	  
					  jQuery('#pageAction').val('moved');
					  document.forms[frmName].submit();            	  
	            	  return true;
	               }
	          }
	        );
		 
		  return false;
	} 
	if(action == "set"){ 
		   alert('f');
		   jConfirm('Are you sure you want to set top selected record(s)?', 'Confirm Set Top', function(r) {
	              if(r){
				      jQuery('#pageAction').val('set');	            	 
	            	  document.forms[frmName].submit();            	  
	            	  return true;
	              }
	          }
	        );
		 
		  return false;
		 } 
	if(action == "unset"){ 
		   jConfirm('Are you sure you want to unset top selected record(s)?', 'Confirm UnSet Top', function(r) {
	              if(r){
				      jQuery('#pageAction').val('unset');	            	 
	            	  document.forms[frmName].submit();            	  
	            	  return true;
	              }
	          }
	        );
		 
		  return false;
		 }		 
	if(action == "set_de"){ 
		   jConfirm('Are you sure you want to set default selected record(s)?', 'Confirm Set Default', function(r) {
	              if(r){
				      jQuery('#pageAction').val('set_de');	            	 
	            	  document.forms[frmName].submit();            	  
	            	  return true;
	              }
	          }
	        );
		 
		  return false;
		 } 
	if(action == "unset_de"){ 
		   jConfirm('Are you sure you want to unset default selected record(s)?', 'Confirm UnSet Default', function(r) {
	              if(r){
				      jQuery('#pageAction').val('unset_de');	            	 
	            	  document.forms[frmName].submit();            	  
	            	  return true;
	              }
	          }
	        );
		 
		  return false;
		 }
	if(action == "set_auth_deal"){ 
		   jConfirm('Are you sure you want to set authenticate selected record(s)?', 'Confirm Set Authenticate', function(r) {
	              if(r){
				      jQuery('#pageAction').val('set_auth_deal');	            	 
	            	  document.forms[frmName].submit();            	  
	            	  return true;
	              }
	          }
	        );
		 
		  return false;
		 } 
	if(action == "set_unauth_deal"){ 
		   jConfirm('Are you sure you want to unauthenticate selected record(s)?', 'Confirm UnSet Authenticate', function(r) {
	              if(r){
				      jQuery('#pageAction').val('set_unauth_deal');	            	 
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