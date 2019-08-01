<section class="mt106">    
    <div class="AccMid">
	<?php echo $this->element("account_sidebar");?>
	
<?php echo $this->Form->create('User',array('url'=>array('controller'=>'users', 'action'=>'change_password')));?><?php echo $this->Form->hidden('User.hidden_password',array('value'=>$profiledata['User']['password']));?>
<div class="AccMidRight">
    <h2>Change Password</h2>
	<?php  $this->Layout->sessionFlash(); ?>   
    <ul class="AccSetFrm">
		<li>
			<label style="font-weight:bold;font: 18px Arial,Helvetica,sans-serif;"><?php echo __('Current password',true);?>:</label>
	  <?php echo $this->Form->input('User.oldpassword', array('type'=>'password','label'=>false, 'div'=>false, 'class'=>"AccSetFrmTxtFild"));?>
		</li>
		<li>
			<label style="font-weight:bold;font: 18px Arial,Helvetica,sans-serif;"><?php echo __('New password',true);?></label>
	  <?php echo $this->Form->input('User.newpassword2', array('type'=>'password','label'=>false, 'div'=>false, 'class'=>"AccSetFrmTxtFild"));?>
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
