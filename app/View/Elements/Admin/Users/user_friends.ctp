<style>
	.frndImg li{
		width:145px;
		float:left;
		/* border:1px solid #CCCCCC; */
		text-align:center;
		margin-left:4px;
	}
	.frndImg li img{
		border:1px solid #FC31CE;
		padding:3px;
		border-radius:100%;
		text-align:center;
	}
</style>
<section class="mt106">    
    <div class="AccMid">
	<?php echo $this->element("account_sidebar");?>
	<div class="AccMidRight">
    <h2>My Friends</h2>
		<?php  $this->Layout->sessionFlash(); ?>   
		<ul class="AccSetFrm frndImg" style="min-height:400px;">
			<?php
			if(!empty($friendData)){
					foreach($friendData as $key=>$value){
						?>
						<li>
							<?php /* ?><a href="https://www.facebook.com/<?php echo $value['UserFriend']['facebook_id'];?>"></a><?php  */?>
							<img src='<?php echo "https://graph.facebook.com/".$value['UserFriend']['facebook_id']."/picture?type=large";?>' width="120" />
							<br>
							<span style="font-size:12px;">
							<?php 
							if(strlen($value['UserFriend']['name'])>15){
							echo substr($value['UserFriend']['name'], 0, 13)."..";
							}else{
							echo $value['UserFriend']['name'];			
							}
							?>
							</span>
						</li>
						<?php 
					}
				}
			else 
			{
				echo ($this->element('admin_flash_info', array('message' => 'NO RESULTS FOUND')));
			}
			?>
		</ul>
     </div>
    <div class="clear"></div>
    </div>
</section>
