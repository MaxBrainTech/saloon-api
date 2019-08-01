<?php
foreach($data as $key=>$value){
	$b_username='<strong>'.$q.'</strong>';
	$final_username = str_ireplace($q, $b_username, $value);
?>
	<div class="show" align="left">
	<!--<img src="author.PNG" style="width:50px; height:50px; float:left; margin-right:6px;" />-->
	<span class="name"><?php echo $final_username; ?></span>&nbsp;<?php //echo $final_email; ?><br/>
	</div>
<?php }?>