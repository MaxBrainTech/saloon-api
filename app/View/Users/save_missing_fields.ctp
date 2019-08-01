<script>
    $(function() {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            maxDate: "0",
            dateFormat: 'yy-mm-dd',
			yearRange : '1880:2014'
        });
    });
</script>
<section class="mt106">    
    <div class="AccMid">
	<?php echo $this->element("account_sidebar");?>
	
	<?php 
			echo $this->Form->create('User', 
				array('url' => array('controller' => 'users', 'action' => 'save_missing_fields'),'type'=>'file',
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
    <h2>Fill required fields</h2>
	<?php  //$this->Layout->sessionFlash(); ?>
		
		<div style="color:red;padding-top:5px;font-size:12px;"><strong>NOTE</strong>: Please Complete Below.</div>
	<?php /*?>
		
		<?php $stylePicBg = ""; 
		$innerImgMargin = "";
			
		if(!empty($userData['User']['social_media_cover_image_url'])){
			$coverImage = $userData['User']['social_media_cover_image_url'];
		}else{
			$coverImage = "";
		}
	
		if(!empty($coverImage)){
		$size = getimagesize($coverImage);
		//pr($size);die;
		$width = $size[0]."px";
		$height = $size[0]."px";
		if($height>400){
			$height = "400px";
			$innerImgMargin = "margin-top:160px;";
		}
		//$height = 700
		if(isset($width)){
			$heightNew = ($height/$width)*700;
			if(!empty($heightNew)){
				$height = $heightNew."px";
				$mrgnTop = ($heightNew/2)-60;
				$innerImgMargin = "margin-top:".$mrgnTop."px;";
			}
		}
		
		$stylePicBg = "background:url('$coverImage') no-repeat scroll 0 0 / cover  rgba(0, 0, 0, 0);height:$height;";
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
			echo "<img src='".$userData['User']['social_media_image_url']."' width='150'  alt='".$userData['User']['username']."' />";
		}
		else
		{
			echo $this->Html->image('no-picture.gif', array('alt'=>'img', 'width'=>'150', 'style'=>$innerImgMargin));
		}
		?>
    </div> 
	<?php */?>
    <ul class="AccSetFrm">
		<!--<li> 
		<div style="color:red;padding-top:5px;font-size:12px;"><strong>NOTE</strong>: Please Complete Below.</div></li>-->
		<li>
			<label>First name</label>			
			<?php echo ($this->Form->input('first_name', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Firstname", "error" => false))); ?><br/>
			<?php
				if ($this->Form->isFieldError('first_name')){
					echo $this->Form->error('first_name');
				}
			?>
		</li>
		<li>
			<label>Last name</label>			
			<?php echo ($this->Form->input('last_name', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Lastname", "error" => false))); ?><br/>
			<?php
				if ($this->Form->isFieldError('last_name')){
					echo $this->Form->error('last_name');
				}
			?>
		</li>
		<li>
			<label>Username</label>			
			<?php echo ($this->Form->input('username', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Username", "error" => false))); ?><br/>
			<?php
				if ($this->Form->isFieldError('username')){
					echo $this->Form->error('username');
				}
			?>
		</li>
		<li style="width:300px;">
			<label>Gender</label><br/>
			<div class="form_select">
			<?php echo ($this->Form->input('gender', array('div' => false, 'label' => false, "class" => "AccSetFrmSeFild", "style"=>'border:0px;', "options" => array(""=>"Choose Gender", 1=>"Male", 2=>"Female")))); ?>
			</div>
		</li>
		<li>
			<label>Date Of Birth</label>
			<?php echo ($this->Form->input('dob', array('div' => false,'label'=>false,'type'=>'text','placeholder' => 'Birthday', "class" => "datepicker AccSetFrmTxtFild", 'id'=>'datepicker'))); ?>
		</li>
		<li>
			<label>Email</label>
			<?php echo ($this->Form->input('email', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Email", 'error'=>false))); ?>
			<br/>
			<?php
				if ($this->Form->isFieldError('email')) {
					echo $this->Form->error('email');
				}
			?>
		</li>
		<li>
			<label>Password</label>
			<?php echo ($this->Form->input('password2', array('type' => 'password', 'div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Enter Password", 'error'=>false))); ?>
			<br/>
			<?php
				if ($this->Form->isFieldError('password2')){
					echo $this->Form->error('password2');
				}
			?>
		</li>
		<li>
			<label>Phone number</label>			
			<?php echo ($this->Form->input('phone_no', array('div' => false, 'label' => false, "class" => "AccSetFrmTxtFild", "placeholder" => "Phone No.", "error" => false))); ?><br/>
			<?php
				/* if ($this->Form->isFieldError('phone_no')){
					echo $this->Form->error('phone_no');
				}  */
			?>
		</li>
    </ul>

	<div class="AccSetBtnRow">
		<?php echo ($this->Form->submit('Save', array('class' => 'OrangeBtn', "div" => false, 'onclick' => "return customvalidation()"))); ?>
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