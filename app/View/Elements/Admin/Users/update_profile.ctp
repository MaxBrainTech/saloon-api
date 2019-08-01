<script type="text/javascript" src="https://connect.facebook.net/en_US/all.js"></script>
<script type="text/javascript">
   /*  $(function() {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            maxDate: "0",
            dateFormat: 'yy-mm-dd',
			yearRange : '1880:2014'
        });
    }); */
</script>
<?php 
 echo $this->Html->css(array('phone_autofill_css/intlTelInput'));
 echo $this->Html->script(array('ajaxuploadimage/ajaxupload'));?>
<?php 
	/* for($i = $totExtraImage; $i<=Configure::read('App.max_user_images')-1;$i++) */
	for($i = 0; $i<=Configure::read('App.max_user_images')-1;$i++)
	{
	?>
	<script type="text/javascript">
			jQuery(function($){
			
			//code for uploading project image starts here
			var btnUpload = $('#testupload_<?php echo $i;?>');
			var status = $('#status');
			var k = 0;
			new AjaxUpload(btnUpload, {
						
				action: '<?php echo(SITE_URL);?>/users/userextra_picupload',

				//Name of the file input box temp_extraimages
				name: 'data[UserImage][image]',
				data: {'data[UserImage][imageid]': btnUpload.attr("title")},
				id:	'image',
				onSubmit: function(file, ext){
				
					if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
						// check for valid file extension
						status.text('Only JPG, PNG or GIF files are allowed.').addClass('errorTxt');
						return false;
					}
					
				   $('#status').html('<img src="<?php echo(SITE_URL); ?>/img/ajax-loader_2.gif"/>');
					
				},

				onComplete: function(file, response){
					btnUpload.hide();
					//On completion clear the status               
					$('#status').html('');
					//Add uploaded file to list
					var myimageresponse = response.split('|');
					
					if(myimageresponse[0]==="success"){
					
						$('#userimageid'+<?php echo $i;?>).html('<p id="p_'+myimageresponse[2]+'" class="extraImage" style="float:left;margin-top: 0px; position: relative;"><img width="100" height="100" src="<?php echo(SITE_URL); ?>/uploads/user_extra_image/thumb/'+myimageresponse[1]+'" title="'+myimageresponse[2]+'" id="testupload_<?php echo $i;?>" /><input type="hidden" name="data[UserImage]['+<?php echo $i;?>+'][image]" value="'+myimageresponse[1]+'"/>');
					}else{
						$('<li></li>').appendTo('#files').text(file).addClass('errorTxt');
					}
					k++;
				}
			});	
			
			$(".delete"+<?php echo $i;?>).live('click',function(){			
				var id 		= 	$(this).attr('id');			
				if(confirm('Are you sure you want to delete this image?'))
				{
						$('#del_'+id).html('<img src="<?php echo(SITE_URL); ?>/img/ajax-loader_2.gif"/>');
						$.ajax({
						type:"GET",
						url:"<?php echo Router::url(array('controller'=>'users', 'action'=>'extra_image_delete')); ?>/"+ id,
						success : function(data) {
							
							$('#p_'+id).remove();
							$('#del_'+id).remove();
							$('#testupload_'+<?php echo $i;?>).show();
						},
						error : function() {
							alert('File could not be deleted. Please try again', 'Alert Dialog');
						},
					})
					
				}
			});
			// code for uploading user image end here
		});
		
		// code for uploading user image end here
	</script>
<?php }?>
<script type="text/javascript">
    jQuery(function($){	
        var profileImageUpload = $('#main_profile_photo');
        var status = $('#status');
		var k = 0;
        new AjaxUpload(profileImageUpload, {
					
            action: '<?php echo(SITE_URL);?>/users/upload_profile_pic',

            //Name of the file input box temp_extraimages
            name: 'data[User][profile_image]',
			id:	'profile_image',
            onSubmit: function(file, ext){
			
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    // check for valid file extension
                    status.text('Only JPG, PNG or GIF files are allowed.').addClass('errorTxt');
                    return false;
                }
				
               $('#status').html('<img src="<?php echo(SITE_URL); ?>/img/ajax-loader_2.gif"/>');
                
            },

            onComplete: function(file, response){
				profileImageUpload.hide();
                //On completion clear the status               
				$('#status').html('');
                //Add uploaded file to list
                var myimageresponse = response.split('|');
				
                if(myimageresponse[0]==="success"){
					$('#MainProfilePic').html('<img width="200" height="200" src="<?php echo(SITE_URL); ?>/uploads/user/large/'+myimageresponse[1]+'"/><p style="font-size:16px;float:left;padding: 16px 0 0;text-align: center;width: 83%;">(Profile Photos)</p><br />');
                }else{
                    $('<li></li>').appendTo('#files').text(file).addClass('errorTxt');
                }
				k++;
            }
        });
	});
</script>
<section class="mt106">    
    <div class="AccMid">
	<?php echo $this->element("account_sidebar");?>
	
	<?php 
			echo $this->Form->create('User', 
				array('url' => array('controller' => 'users', 'action' => 'update_profile'),'type'=>'file',
			'inputDefaults' => array(
				'error' => array(
					'attributes' => array(
						'wrap' => 'span',
						'class' => 'input-notification error png_bg'
					)
				)
			)
			));?>
			<?php echo ($this->Form->hidden('id'));?>
			<div class="AccMidRight">
    <h2>My Account</h2>
	<?php  $this->Layout->sessionFlash(); ?>   
		<?php $stylePicBg = ""; 
		/* if(!empty($userData['User']['profile_cover_image']))
		{
			$coverImage = SITE_URL."/uploads/user_cover/large/".$userData['User']['profile_cover_image'];
		}else */
		
		if(!empty($userData['User']['social_media_cover_image_url'])){
			$coverImage = $userData['User']['social_media_cover_image_url'];
		}else{
			$coverImage = "";
		}
	
		if(!empty($coverImage)){
		/* $size = getimagesize($coverImage);
		//pr($size);die;
		$width = $size[0]."px";
		$height = $size[0]."px";
		if($height>400){
			$height = "400px";
		} */
		$stylePicBg = "background:url('$coverImage') no-repeat scroll 0 0 / cover  rgba(0, 0, 0, 0);";
		}
		//echo $stylePicBg;die;
		?>
    <div class="ProfPhotoAccSet" style="<?php echo $stylePicBg;?>">
	<?php //echo $this->Html->image('profile_img1.png', array('alt'=>'img'));?>
	<?php		
		if(!empty($imageInfo['User']['profile_image']) &&  file_exists(WWW_ROOT . USER_THUMB_DIR
	. DS .$imageInfo['User']['profile_image']) )
		{
			echo $this->General->user_show_pic($imageInfo['User']['profile_image'], 'UPDATEPROFILE',$imageInfo['User']['username'], $imageInfo['User']['id']);
		}
		elseif(!empty($userData['User']['social_media_image_url']))
		{
		//echo $userData['User']['social_media_image_url'];
			echo "<img src='".$userData['User']['social_media_image_url']."' width='150' height='150'  alt='".$userData['User']['username']."' />";
		}
		else
		{
			echo $this->Html->image('no-picture.gif', array('alt'=>'img', 'width'=>'150'));
		}
		?>
    </div>    
    <ul class="AccSetFrm">
		<li>
			<label style="font-weight:bold;font: 18px Arial,Helvetica,sans-serif;">Name</label>
			<?php echo ($this->Form->input('first_name', array('id'=>'UserFirstName', 'div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Firstname", "error" => false))); ?>
			<?php echo ($this->Form->input('last_name', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Lastname"))); ?>
			<br/>
			<span id="errorName" style="color:red;font-size:13px;"></span>
			<?php
				if ($this->Form->isFieldError('first_name')){
					echo $this->Form->error('first_name');
				}
			?>
		</li>
		<li>
			<label style="font-weight:bold;font:18px Arial,Helvetica,sans-serif;">Username</label>
			<?php echo ($this->Form->input('username', array('id'=>'username', 'div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Firstname", "error" => false))); ?>
			<br/>
			<span id="errorName" style="color:red;font-size:13px;"></span>
			<?php
				if ($this->Form->isFieldError('username')){
					echo $this->Form->error('username');
				}
			?>
		</li>
		<?php /*?>
		<?php */?>		
		<li style="width:300px;">
			<label>Gender</label><br/>
			<div class="form_select">
			<?php echo ($this->Form->input('gender', array('id'=>'UserGender','div' => false, 'label' => false, "class" => "AccSetFrmSeFild", "style"=>'border:0px;', "options" => array(""=>"Choose Gender", 1=>"Male", 2=>"Female")))); ?>
			</div><span id="errorGender" style="color:red;font-size:13px;"></span>
		</li>
		<li>
			<label>Date Of Birth</label>
			<?php //echo ($this->Form->input('dob', array('div' => false,'label'=>false,'type'=>'text','placeholder' => 'Birthday', "class" => "datepicker AccSetFrmTxtFild", 'id'=>'datepicker'))); //FOR THIS 1)UNCOMMENT TOP DATEPICKER JS. 2)UNCOMMENT THIS.?>
			<?php echo ($this->Form->input('dob', array('div' => false,'label'=>false,'type'=>'text','placeholder' => 'Birthday', "class" => "datepicker AccSetFrmTxtFild"))); ?><br/>
			<span id="errorDOB" style="color:red;font-size:13px;"></span>	
		</li>
    </ul>
    
    
    
    <div class="AccSetConDeBox">
    <h3>Contact Detail</h3>
    <ul class="AccSetConDeFrm">
		<li>
			<div class="ConDeLeTxtFild  inputAction email req">
			<?php echo ($this->Form->input('email', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "admin@girlforhire.com", 'error'=>false))); ?>
			<br/>
			<?php
				if ($this->Form->isFieldError('email')) {
					echo $this->Form->error('email');
				}
			?>
			<span id="errorEmail" style="color:red;font-size:13px;"></span>	
			</div>
			<div class="ConDeRiTxtFild  inputAction alter_email req">
			<?php echo ($this->Form->input('alternate_email', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Alternate Email", "error" => false))); ?>
			<br/>
			<span id="errorAlterEmail" style="color:red;font-size:13px;"></span>
			<?php
				if ($this->Form->isFieldError('alternate_email')){
					echo $this->Form->error('alternate_email');
				}
			?>	
			</div>
		</li>
		<li>
			<div class="ConDeLeTxtFild  inputAction phone req">
			<?php echo ($this->Form->input('phone_number', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Mobile/Cell Number", "error" => false,'style'=>'padding:16px 41px;width:339px'))); ?>
			<br/>
			<span id="errorPhone" style="color:red;font-size:13px;"></span>
			<?php
				if ($this->Form->isFieldError('phone_number')){
					echo $this->Form->error('phone_number');
				}
			?>
			</div>
			<div class="ConDeRiTxtFild  inputAction alter_phone req">
			<?php echo ($this->Form->input('alternate_phone_number', array('div' => false, 'label' => false,  "error" => false,'style'=>'padding:16px 41px;width:339px',"placeholder" => "Office Number",))); ?>
			<br/>
			<span id="errorAlterPhone" style="color:red;font-size:13px;"></span>
			<?php
				if ($this->Form->isFieldError('alternate_phone_number')){
					echo $this->Form->error('alternate_phone_number');
				}
			?>
			</div>
		</li>
		<li>
			<div class="ConDeLeTxtFild skypeIcon">
				<?php echo ($this->Form->input('skype', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Skype Name"))); ?>
				<a href="#" class="SkypIconFild">
					<?php echo $this->Html->image('skype.png', array('alt'=>'skype'));?>
				</a>
			</div>
			<div class="ConDeRiTxtFild  inputAction website req">
				<?php echo ($this->Form->input('website', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Add Your Website"))); ?><br/>
				<span id="errorWebsite" style="color:red;font-size:13px;"></span>
			</div>
		</li>
		<li>
			<?php echo ($this->Form->input('facebook_link', array('div' => false, 'label' => false, "class" => "SocialFild", "placeholder" => "Facebook Profile Link"))); ?>
			<a href="#" class="SocialIconFrm">
				<?php echo $this->Html->image('facebook.png', array('alt'=>'facebook'));?>
			</a>
				<span id="#socialLnks"></span>
				<?php if($this->Session->check('Facebook.User')){?>
				<?php //echo $this->Html->link('<input name="" type="button" class="AddBtnOrange" value="OFF">', array('controller' => 'users', 'action' => 'disconnect_social', 'facebook'), array('escape'=>false));?>
				<?php }else{?>
				<input name="" type="button" class="AddBtnOrange" value="Connect" onclick="fbInit();">
				<?php }?>
				
		</li>
		<li>
		<?php 
		//pr($this->Session->read('Auth.User'));die;
		?>
			<?php echo ($this->Form->input('twitter_link', array('div' => false, 'label' => false, "class" => "SocialFild", "placeholder" => "Twitter Profile Link"))); ?>
			<a href="#" class="SocialIconFrm">
				<?php echo $this->Html->image('twitter.png', array('alt'=>'twitter'));?>
			</a>
				<?php if($this->Session->check('Twitter.User')){?>
				<?php //echo $this->Html->link('<input name="" type="button" class="AddBtnOrange" value="OFF">', array('controller' => 'users', 'action' => 'disconnect_social', 'twitter'), array('escape'=>false));?>
				<?php }else{?>
				<?php echo $this->Html->link('<input name="" type="button" class="AddBtnOrange" value="Connect">', array('controller' => 'users', 'action' => 'tlogin'), array('escape'=>false));?>
				<?php }?>
		</li>
		<li>
			<?php echo ($this->Form->input('google_plus_link', array('div' => false, 'label' => false, "class" => "SocialFild", "placeholder" => "Google+ Profile Link"))); ?>
			<a href="#" class="SocialIconFrm">
				<?php echo $this->Html->image('google_plash.png', array('alt'=>'google plash'));?>
			</a>
				<?php if($this->Session->check('GooglePlus.User')){?>
				<?php //echo $this->Html->link('<input name="" type="button" class="AddBtnOrange" value="OFF">', array('controller' => 'users', 'action' => 'disconnect_social', 'google_plus'), array('escape'=>false));?>
				<?php }else{?>
				<?php echo $this->Html->link('<input name="" type="button" class="AddBtnOrange" value="Connect">', array('controller' => 'users', 'action' => 'glogin'), array('title'=>"glogin", 'escape'=>false));?>
				<?php }?>
		</li>
		<li>
			<?php echo ($this->Form->input('linkdin_link', array('div' => false, 'label' => false, "class" => "SocialFild", "placeholder" => "Linked in Profile Link"))); ?>
			<a href="#" class="SocialIconFrm">
				<?php echo $this->Html->image('linked_in.png', array('alt'=>'linked_in'));?>
			</a>		
				<?php if($this->Session->check('LinkedIn.User')){?>
				<?php //echo $this->Html->link('<input name="" type="button" class="AddBtnOrange" value="OFF">', array('controller' => 'users', 'action' => 'disconnect_social', 'linkedin'), array('escape'=>false));?>
				<?php }else{?>		
				<?php echo $this->Html->link('<input name="" type="button" class="AddBtnOrange" value="Connect">',array('controller'=>'users','action'=>'linked_connect','linkedin'),array('onclick'=>"window.location('".SITE_URL."/users/linked_connect/linkedin'); return false;", 'escape' =>false));?>
				<?php }?>
		</li>
    </ul>
    </div>
    
    <div class="AccSetConDeBox">
    <h3>Education</h3>    
    <ul class="AccSetConDeFrm">
	<?php $User_data_edu	= explode(";" ,$this->request->data['User']['education']);					
		  $total = count($User_data_edu);
		?>
		<ol class="education-list OlText">
		<?php
			$i=1;
		 foreach ($User_data_edu as $key => $value) {
			if(!empty($value)){
		 ?>
			<li id=<?php echo 'edu_'.$i;?> class="listContent"><?php echo $value;?>&nbsp;&nbsp;&nbsp;
			<a href="javascript:void(0);" class="delete_education" id="<?php echo $i.'_'.$value;?>"><?php echo $this->Html->image('admin/cross.png',array('alt'=>'Delete','title'=>'Delete')); ?></a>
			</li>
			
		<?php
				}
			$i++;
			}
		?>
		
		</ol>					
    <li>
	<?php  echo $this->Form->input('education', array("class" => "text-input medium-input eductaion_text_hidden",'type'=>'hidden'));?>
	<?php  echo ($this->Form->input('eductaion_text', array('div'=>false, 'label'=>false, "class" => "AccSetFrmTxtFild eductaion_text", "placeholder"=>'Where you have studied?')));?><input name="" type="button" class="AddBtnOrange add-education addMreBtn" value="ADD">
		<p id="erroreducation"></p>
		</li>
    </ul>
    </div>
        
    <div class="AccSetConDeBox">
    <h3>Interest</h3> 
		<?php $total_int = 0;
				/* if(isset($this->request->data['User']['interests'])){ */
					 $User_data_int	= explode(";" ,$this->request->data['User']['interests']);
					 $total_int = count($User_data_int);
					 $total_exp = 0;
					?>
					<ol class="interest-list OlText">
					<?php
						$i=1;
					 foreach ($User_data_int as $key => $value) {
						if(!empty($value)){
					 ?>
						<li id=<?php echo 'int_'.$i;?> class="listContent"><?php echo $value;?>&nbsp;&nbsp;&nbsp;
						<a href="javascript:void(0);" class="delete_interest" id="<?php echo $i.'_'.$value;?>"><?php echo $this->Html->image('admin/cross.png',array('alt'=>'Delete','title'=>'Delete')); ?></a>
						</li>
						
					<?php
						}
						$i++;
						}
					?>
					</ol>
		<ul class="AccSetConDeFrm">
			<li><?php  echo $this->Form->input('interests', array('type'=>'hidden'));?> 
				<?php  echo ($this->Form->input('interest_text', array('div'=>false, 'label'=>false, "class" => "AccSetFrmTxtFild interest_text", 'placeholder'=>'What are your interests?')));?> 
				<input name="" type="button" class="AddBtnOrange add-interest addMreBtn" value="ADD">
		<p id="errorinterest"></p>
			</li>
		</ul>    
    </div>   
    
    <div class="AccSetConDeBox">
    <h3>Work</h3>
		<?php 	$total = 0;
				$User_data_exp	= explode(";" ,$this->request->data['User']['experience']);
				$total_exp = count($User_data_exp);
			?>
					<ol class="experience-list OlText">
					<?php
						$i=1;
					 foreach ($User_data_exp as $key => $value){
						if(!empty($value)){
					 ?>
						<li id=<?php echo 'exp_'.$i;?> class="listContent"><?php echo $value;?>&nbsp;&nbsp;&nbsp;
						<a href="javascript:void(0);" class="delete_experience" id="<?php echo $i.'_'.$value;?>"><?php echo $this->Html->image('admin/cross.png',array('alt'=>'Delete','title'=>'Delete')); ?></a>
						</li>
					<?php
							}
						$i++;
						}
					?>
					</ol>
				 
		<ul class="AccSetConDeFrm">
			<li><?php  echo $this->Form->input('experience', array('type'=>'hidden'));?> 	
		<?php  echo ($this->Form->input('experience_text', array('div'=>false, 'label'=>false, "class" => "AccSetFrmTxtFild experience_text", 'placeholder'=>'Where you have worked?')));?> 
				<input name="" type="button" class="AddBtnOrange add-experience addMreBtn" value="ADD">
		<p id="errorexperience"></p>
			</li>
		</ul>
    </div>
		<a href="#" class="DeleteIconFrm">
			<?php //echo $this->Html->image('delete_icon.png', array('alt'=>'delete_icon'));?>
		</a>
	<div class="AccSetBtnRow">
	<div id="MainProfilePic" style="width:260px;float:left;padding-top:30px;">
	<?php		
		if(!empty($imageInfo['User']['profile_image']) &&  file_exists(WWW_ROOT . USER_THUMB_DIR
	. DS .$imageInfo['User']['profile_image']) )
		{
			//echo $this->General->user_show_pic($imageInfo['User']['profile_image'], 'UPDATEPROFILE',$imageInfo['User']['username'], $imageInfo['User']['id']);
			echo $this->General->user_show_pic($imageInfo['User']['profile_image'], 'UPDATEPROFILE',$imageInfo['User']['username'], 'main_profile_photo');
		}
		elseif(!empty($userData['User']['social_media_image_url']))
		{
			echo "<img src='".$userData['User']['social_media_image_url']."' width='200' height='200'  alt='".$userData['User']['username']."' id='main_profile_photo' />";
		}
		else
		{
			echo $this->Html->image('no-picture.gif', array('alt'=>'img'  ,'id'=>"main_profile_photo", 'width'=>'200', 'height'=>'200'));
		}
		?>
		<p style="font-size:16px;float:left;padding: 16px 0 0;text-align: center;width: 83%;">(Profile Photos)</p>		
		<br />
		<?php
		//echo ($this->Form->input('profile_image', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", 'type' => 'file', 'error'=>false)));
        ?><br/>
			<?php
				if ($this->Form->isFieldError('profile_image')){
					echo $this->Form->error('profile_image');
				}
			?>
			
	</div>
	<div style="width:400px;float:left;margin-left:0px;">
		<div class="AccSetBtnRow">
		  <div id="extraimgup">
			<?php  //pr($extraimageInfo);
				if(!empty($extraimageInfo))
				{
					foreach($extraimageInfo as $key=>$adsVal)
					{
					?>
					<span id="userimageid<?php echo $key;?>">
						<p class="extraImage" id="extraImage_<?php echo $key;?>" style="float: left; margin-top: 0px; position: relative;">
							<?php if($adsVal['UserImage']['image_type']==1){ ?>
								<img src="<?php echo $adsVal['UserImage']['image'];?>" width="100" title="<?php echo $adsVal['UserImage']['id'];?>" id="testupload_<?php echo $key;?>" height="100" />
								&nbsp;&nbsp;&nbsp;
								<?php /* echo $this->Html->link($this->Html->image('admin/cross.png',array('onclick'=>'javascript:return confirm("Are you sure you want to delete this image?")','alt'=>'Delete')),array('controller'=>'users','action'=>'extra_image_delete',$adsVal['UserImage']['id']), array('escape'=>false, 'style'=>'position: absolute; left: 76px; top: 0px;')); */
								?>
							<?php }else{ ?>
							<?php  
								if(!empty($adsVal['UserImage']['image']) &&  file_exists(WWW_ROOT . USER_EXTRAIMAGE_THUMB_DIR . DS .$adsVal['UserImage']['image']) )
								{ ?>
								<img src="<?php echo SITE_URL."/uploads/user_extra_image/thumb/".$adsVal['UserImage']['image'];?>" title="<?php echo $adsVal['UserImage']['id'];?>" id="testupload_<?php echo $key;?>" width="100" height="100" />
								<?php
								/* 	echo $this->General->user_extra_pic($adsVal['UserImage']['image'], 'THUMB','','').'&nbsp;&nbsp;&nbsp;'.$this->Html->link($this->Html->image('admin/cross.png',array('onclick'=>'javascript:return confirm("Are you sure you want to delete this image?")','alt'=>'Delete')),array('controller'=>'users','action'=>'extra_image_delete',$adsVal['UserImage']['id']), array('escape'=>false, 'style'=>'position: absolute; left: 76px; top: 0px;')); */
								}else{?>
								<img src="<?php echo SITE_URL;?>/img/add-photo.jpg" class="testuplo adMoreImagePic" type="button"  id="testupload_<?php echo $key;?>" title="<?php echo $key;?>"  value="Upload" width="100" height="100"/>
								<?php }?>
							<?php }?>
						</p>
					</span>
					<?php		
					}
			   }/* else{
					echo $this->Html->image(DEFAULT_USER_EXTRAIMAGE, array('alt'=>'img', 'width'=>'100'));
			   } */
			 
				
				
				for($j = $totExtraImage;$j<=Configure::read('App.max_user_images')-1;$j++)
				{
				?>
					<span id="userimageid<?php echo $j;?>" title="" >
					<p class="extraImage" style="float:left;margin-top:0px;">				
					<img src="<?php echo SITE_URL;?>/img/add-photo.jpg" class="testuplo adMoreImagePic" type="button"  id="testupload_<?php echo $j;?>" title=""  value="Upload" width="100" height="100"/>
					</p>	</span>
					<!--<input name="image" class="testuplo adMoreImagePic" type="button"  id="testupload_<?php echo $j;?>"  value="Upload" />-->
				
				<?php } ?>
			</div>	
			<br />
			<?php
				//echo $this->Form->input('UserImage.extra_image', array('type'=>'file','name' => 'data[UserImage][extra_image][]','multiple'=>'multiple','div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", 'type' => 'file', 'error' => 'false')); 			
			?>
			<br/>
		</div>
		<p style="font-size:16px;float:left;padding-left: 70px;">(Additional Photos)</p>
	</div>

    </div>
	<div class="AccSetBtnRow">		
		<?php echo ($this->Form->submit('Save', array('class' => 'OrangeBtn', "div" => false, 'onclick' => "return customvalidation();"))); ?>
		<?php
			echo $this->Html->link("Cancel", array('controller' => 'user_ads', 'action' => 'index'), array("class" => "GrayBtn", "escape" => false));
		?>
	</div>
     </div>
		<?php echo ($this->Form->end()); ?>
    <div class="clear"></div>
    </div>
</section>
<?php
	if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) 
	echo $this->Js->writeBuffer();
?>
<script type="text/javascript">
	jQuery(document).ready(function()
     {
			jQuery(".inputAction input").keyup(function()
            {
                parentdiv = jQuery(this).parent('div');				
                ahrefObj = parentdiv.find('span');
				phoneObj1 = $('div.phone').find('span');
				phoneObj = $('div.alter_phone').find('span');
                textObj = jQuery(this);
				
                if (textObj.val() === "")
                {
                    ahrefObj.html('');                    
                }
				else
                {
                    textObj.removeClass("rbordertext");
                    var regxEmail = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
					var regxNumeric = /[0-9]/;
					var regxAlpha = /[A-Za-z]/;
					var regxUrl = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/; 
					
					
					if (parentdiv.hasClass("email"))
                    {
                        if ((textObj.val()).match(regxEmail))
                        {
                            ahrefObj.html('');  
                        }
                        else {
                            ahrefObj.html('Email is not valid.');
                            
                        }
                    }else if (parentdiv.hasClass("alter_email"))
                    {
                        if ((textObj.val()).match(regxEmail))
                        {
                            ahrefObj.html('');  
                        }
                        else {
                            ahrefObj.html('Alternate Email is not valid.');
                            
                        }
                    }/* 
					else if ($('div.phone').children().hasClass('pretty'))
                    {
                        if (textObj.val().length == 10)
                        {
                            phoneObj1.html('');  
                        }else if(regxAlpha.test(textObj.val())){
						
							phoneObj1.html('Phone number must be numeric.');
						}
                        else {
                            phoneObj1.html('');
                            
                        }
                    }
					 else if ($('div.alter_phone').children().hasClass('pretty'))
                    {
						
                        if (textObj.val().length == 10)
                        {	
                            phoneObj.html('');  
                        }
						else if(regxAlpha.test(textObj.val())){
						
							phoneObj.html('Alternate Phone number must be numeric.');
						}
                        else {
							phoneObj.html('');  
                           // phoneObj.html('Alternate Phone number must be 10 digits.');
                            
                        }
                    }  */
					else if (parentdiv.hasClass("website"))
                    {
                        if ((textObj.val()).match(regxUrl))
                        {
                            ahrefObj.html('');  
                        }
                        else {
                            ahrefObj.html('Website url not valid.');
                            
                        }
                    }								
				}
			});	
	});
	function customvalidation(){
		  
		  var strFname = document.getElementById('UserFirstName').value;
		  //var strGender = document.getElementById('UserGender').value;
		  //var strDob = document.getElementById('datepicker').value;
		  var strEmail = document.getElementById('UserEmail').value;
		  var strPhone = document.getElementById('UserPhoneNumber').value;
		  var strAlterPhone = document.getElementById('UserAlternatePhoneNumber').value; 
		  /* var strAlterEmail = document.getElementById('UserAlternateEmail').value;		  
		  var strWebsite = document.getElementById('UserWebsite').value; */
		  var regxAlpha = /[A-Za-z]/;
		  var stampf = 2;
		  if(strFname == "")
		  {
			document.getElementById('errorName').innerHTML ="First Name is required.";
			stampf = 1;
		  }
		  else{
			document.getElementById('errorName').innerHTML ="";
		  }
		  
		  /* if(strGender == "")
		  {
			document.getElementById('errorGender').innerHTML ="Gender is required.";
			stampf = 1;
		  }
		  else{
			document.getElementById('errorGender').innerHTML ="";
		  } 
		  
		  if(strDob == "")
		  {
			document.getElementById('errorDOB').innerHTML ="Date of birth is required.";
			stampf = 1;
		  }
		  else{
		 	document.getElementById('errorDOB').innerHTML ="";
		  } 
		  */
		  if(strEmail == "")
		  {
			document.getElementById('errorEmail').innerHTML ="Email is required.";
			stampf = 1;
		  }
		  else{
		 	document.getElementById('errorEmail').innerHTML ="";
		  }
		  
		   if(regxAlpha.test(strPhone))
		  {
			document.getElementById('errorPhone').innerHTML ="Phone number should be numeric.";
			stampf = 1;
		  }
		  else{
				document.getElementById('errorPhone').innerHTML ="";
		  }	
		  
		  if(regxAlpha.test(strAlterPhone))
		  {
			document.getElementById('errorAlterPhone').innerHTML ="Alternate Phone should be numeric.";
			stampf = 1;
		  }
		  else{
				document.getElementById('errorAlterPhone').innerHTML ="";
		  }	
		  /*if(strAlterEmail == "")
		  {
			document.getElementById('errorAlterEmail').innerHTML ="Alternate Email is required.";
			stampf = 1;
		  }
		  else{
		 	document.getElementById('errorAlterEmail').innerHTML ="";
		  }
		  if(strPhone == "")
		  {
			document.getElementById('errorPhone').innerHTML ="Phone number is required.";
			stampf = 1;
		  }
		  else if(isNaN(strPhone))
		  {
			document.getElementById('errorPhone').innerHTML ="Phone number should be numeric.";
			stampf = 1;
		  }
		  else if(strPhone < 10  || strPhone > 10)
		  {
			document.getElementById('errorPhone').innerHTML ="Phone number should be 10 digits.";
			stampf = 1;
		  }
		  else{
		 	document.getElementById('errorPhone').innerHTML ="";
		  }
		  if(strAlterPhone == "")
		  {
			document.getElementById('errorAlterPhone').innerHTML ="Alternate Phone number is required.";
			stampf = 1;
		  }
		  else if(isNaN(strAlterPhone))
		  {
			document.getElementById('errorPhone').innerHTML ="Alternate Phone number should be numeric.";
			stampf = 1;
		  }
		  else if(strAlterPhone < 10  || strAlterPhone > 10)
		  {
			document.getElementById('errorAlterPhone').innerHTML ="Phone number should be 10 digits.";
			stampf = 1;
		  }
		  else{
		 	document.getElementById('errorAlterPhone').innerHTML ="";
		  } 
		  if(strWebsite == "")
		  {
			document.getElementById('errorWebsite').innerHTML ="Website is required.";
			stampf = 1;
		  }
		  else{
		 	document.getElementById('errorWebsite').innerHTML ="";
		  }*/
		  
		  if(stampf ==1){
			return false;
		  }
		  else{
		  
		  return true;
		  }
	}
</script>	

<script type="text/javascript">
   unique_number = "<?php echo $total; ?>";
   jQuery(".add-education").click(function(e) {
		e.preventDefault();
		var val_hidden = jQuery("#UserEducation").val();
		var val2 = jQuery(".eductaion_text").val();
		if (val2 != "") {
			valuer = jQuery("#UserEducation").val();
			if(valuer != ""){
				valuer_arr = valuer.split(";");
				if(valuer_arr != ""){
					for(i=0;i <= valuer_arr.length;i++){
						if(valuer_arr[i] == val2){
							jQuery('#erroreducation').html("Education must be unique.");
							return false;
						}
					}
				}		
			}			
			//jQuery(".education-list").append("<li>" + val2 + "</li>");
			jQuery(".education-list").append("<li>" + val2 + "<a id='fsfafafa' class='delete_experience' href='javascript:void(0);'>  <img title='Delete' alt='Delete' src='/girlsforhire/img/admin/cross.png'></a></li>");
			valuern = valuer+";"+val2;
			jQuery("#UserEducation").val(valuern);
			jQuery('#erroreducation').html("");
		} else {
			jQuery('#erroreducation').html("Please add education title.");
			return false;
		}
		jQuery(".eductaion_text").attr("value", "");
		jQuery('#erroreducation').html("");
		unique_number++;
	});
	
	jQuery('.delete_education').live('click', function() {
		var id =jQuery(this).attr('id');		
		var idArr = id.split("_");		
		//jQuery('#edu_'+idArr[0]).remove();	
		jQuery(this).parent('li').remove();	
		valsearch = jQuery('#UserEducation').val().search(";"+idArr[1]);
		if(valsearch > -1){		
			var replit = $('#UserEducation').val().replace(";"+idArr[1],"");			
		}
		else{		
			var replit = $('#UserEducation').val().replace(idArr[1]+";","");
		}
		jQuery('#UserEducation').val(replit); 
		
	});
	
   unique_number_int = "<?php echo $total_int; ?>";
   jQuery(".add-interest").click(function(e) {	
		e.preventDefault();
		var val_hidden = jQuery("#UserInterests").val();
		var val2 = jQuery(".interest_text").val();
		
		if (val2 != "") {		
			valuer = jQuery("#UserInterests").val();			
			if(valuer != ""){
				valuer_arr = valuer.split(";");
				if(valuer_arr != ""){
					for(i=0;i <= valuer_arr.length;i++){
						if(valuer_arr[i] == val2){
							jQuery('#errorinterest').html("Interest must be unique.");
							return false;
						}
					}
				}		
			}
			
			//jQuery(".interest-list").append("<li>" + val2 + "</li>");
			jQuery(".interest-list").append("<li>" + val2 + "<a id='FSFS' class='delete_experience' href='javascript:void(0);'>  <img title='Delete' alt='Delete' src='/girlsforhire/img/admin/cross.png'></a></li>");
			valuern = valuer+";"+val2;
			jQuery("#UserInterests").val(valuern);
			jQuery('#errorinterest').html("");
			
		} else {		
			jQuery('#errorinterest').html("Please add interest.");
			return false;
		}
		jQuery(".interest_text").attr("value", "");
		jQuery('#errorinterest').html("");
		unique_number_int++;
	});
	
	jQuery('.delete_interest').live('click', function() {
		var id =jQuery(this).attr('id');		
		var idArr = id.split("_");		
		//jQuery('#int_'+idArr[0]).remove();
		jQuery(this).parent('li').remove();
		valsearch = jQuery('#UserInterests').val().search(";"+idArr[1]);
		if(valsearch > -1){		
			var replit = $('#UserInterests').val().replace(";"+idArr[1],"");			
		}
		else{		
			var replit = $('#UserInterests').val().replace(idArr[1]+";","");
		}
		jQuery('#UserInterests').val(replit); 
		
	});
	
	unique_number_int = "<?php echo $total_exp; ?>";
	//alert(unique_number_int);
	
   jQuery(".add-experience").click(function(e) {
		e.preventDefault();
		var val_hidden = jQuery("#UserExperience").val();
		var val2 = jQuery(".experience_text").val();
		
		if (val2 != "") {
			valuer = jQuery("#UserExperience").val();
			if(valuer != ""){
				valuer_arr = valuer.split(";");
				if(valuer_arr != ""){
					for(i=0;i <= valuer_arr.length;i++){
						if(valuer_arr[i] == val2){
							jQuery('#errorexperience').html("Experience must be unique.");
							return false;
						}
					}
				}		
			}
			
			//jQuery(".experience-list").append("<li>" + val2 + "</li>");
				
			jQuery(".experience-list").append("<li>" + val2 + "<a id='hfhdggs' class='delete_experience' href='javascript:void(0);'>  <img title='Delete' alt='Delete' src='/girlsforhire/img/admin/cross.png'></a></li>");
			valuern = valuer+";"+val2;
			jQuery("#UserExperience").val(valuern);
			jQuery('#errorexperience').html("");
			
		} else {
			jQuery('#errorexperience').html("Please add experience.");
			return false;
		}
		jQuery(".experience_text").attr("value", "");
		jQuery('#errorexperience').html("");
		unique_number_int++;
	});
	
	jQuery('.delete_experience').live('click', function() {
		var id =jQuery(this).attr('id');		
		var idArr = id.split("_");		
		//jQuery('#exp_'+idArr[0]).remove();		
		//alert(jQuery(this).parent('li').html());
		jQuery(this).parent('li').remove();		
		valsearch = jQuery('#UserExperience').val().search(";"+idArr[1]);
		if(valsearch > -1){		
			var replit = $('#UserExperience').val().replace(";"+idArr[1],"");			
		}
		else{		
			var replit = $('#UserExperience').val().replace(idArr[1]+";","");
		}
		jQuery('#UserExperience').val(replit); 
		
	});
</script>

<script type="text/javascript">
		function fbInit(){
			FB.init({appId: "<?php echo Configure::read("App.FacebookKey");?>", status: true, cookie: false, xfbml: true});
			var response="";
		    FB.login(function(response) 
			{
				if (response.authResponse) 
				{
							FB.api('/me', function(response)
							{
								var uid=response.id;
							if(uid > 1)
							{	
 								fb_doLogin(response);
							}
							//alert("my Id="+response.id);
						    });
				}
		    }, {
			scope:'email, user_about_me, user_birthday, user_likes, user_website,user_friends, user_education_history, user_work_history, user_interests, user_photos'
			//scope:'email,offline_access'
			}); 
		}
		
	function fb_doLogin(response)
		{
			jQuery.ajax({
				type:'POST',	
				url: "<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'fbconnect'));?>",
				data:response,
				beforeSend:function(xhr){
				},
				success: function(data){
					//alert(data);
					if(data==1){		
						//fb_save_cover_photo();
						window.location	= "<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'fb_data'));?>";
						
					}
				}
			});
		}
	
		
 	function fb_save_cover_photo(){
			var image;
			FB.api('/me/albums', function (response) {			
			  for (album in response.data) {
				// Find the Profile Picture album
				if (response.data[album].name == "Cover Photos"){
				  FB.api(response.data[album].id + "/photos", function(response){
					image = response.data[0].images[0].source;
					
					jQuery.ajax({
						type:'GET',	
						url: "<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'save_cover_photo'));?>",
						data:{value : image},
						beforeSend:function(xhr){
						},
						success: function(data){
							//alert(data);
							if(data==1){
								window.location	= "<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'update_profile'));?>";
							}
						}
					});
					
				  });
				}
			  }
			});
		} 
</script>
<?php 
 echo $this->Html->script(array('phone_autofill/intlTelInput','phone_autofill/isValidNumber'));
?>	
<script type="text/javascript">

		var website_url = $("#UserWebsite").val();
		var url = $("#UserWebsite").val();
		
		if(url.length>0){
			if(url.indexOf("http") == 0){
				
			}else{
				 website_url = "http://"+url;
				 jQuery("#UserWebsite").val(website_url);
			}
		}
		
		jQuery("#UserWebsite").click(function(){
						 jQuery("#UserWebsite").val("http://");
		});
				
		jQuery("#UserWebsite").keyup(function()
		{
			if(jQuery("#UserWebsite").val()!=""){
				if((jQuery("#UserWebsite").val()).length>=7){
					var website_url = $("#UserWebsite").val();
					var url = $("#UserWebsite").val();
					if(url.indexOf("http") == 0){
						
					}else{
						 website_url = "http://"+url;
						 jQuery("#UserWebsite").val(website_url);
					}
				}
			}else{
				jQuery("#UserWebsite").click(function(){
						 jQuery("#UserWebsite").val("http://");
				})
			}
		});
		
		/* jQuery("#UserWebsite").focus(function()
		{
			if(jQuery("#UserWebsite").val()!=""){
				if((jQuery("#UserWebsite").val()).length>=7){
					var website_url = $("#UserWebsite").val();
					var url = $("#UserWebsite").val();
					if(url.indexOf("http") == 0){
						
					}else{
						 website_url = "http://"+url;
						 jQuery("#UserWebsite").val(website_url);
					}
				}
			}
		}); */
		//$("#UserGender").attr("disabled","disabled");
		//$("#UserGender").css("background-color","#FFF");
		$("#username").attr("readonly","true");
		//$("#UserDob").attr("readonly","true");
			
      $("#UserAlternatePhoneNumber").intlTelInput();
	  $("#UserPhoneNumber").intlTelInput();
</script>	