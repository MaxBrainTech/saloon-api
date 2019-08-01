<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
<?php 
	$ctr = 0;
	foreach($this->data as $setting){?>
	<?php 
		echo ($this->Form->input($ctr.'.Setting.id', array('value'=>$setting['Setting']['id'])));
		echo ($this->Form->hidden($ctr.'.Setting.type', array('value'=>$setting['Setting']['type'])));
	?><?php if($setting['Setting']['id']!=9){ ?> 
	<p>
		<?php 
		$type="text";
		if($setting['Setting']['type']=='textarea')
		{
			$type="textarea";
		}
		echo ($this->Form->input($ctr.'.Setting.value', array('value'=>$setting['Setting']['value'], 'div'=>false, 'type'=>$type, 'label'=>$setting['Setting']['label'].'*', "class" => "text-input medium-input")));
		
		//echo ($this->Form->error($ctr.'.value',null, array('class'=>'input-notification error png_bg', 'wrap'=>'span')));
		?>
		<br><small><?php echo ($setting['Setting']['description']);?></small>		
	</p><?php }

		$ctr++;
	}?>
	
	<p>
		<?php  
		if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
		{
		echo ($this->Form->submit('Submit', array('class' => 'button', "div"=>false)));
		}
		?>
		
		<?php echo $this->Html->link("Cancel", array('admin'=>true, 'controller'=>'admins', 'action'=>'dashboard'), array("class"=>"button", "escape"=>false)); ?>
		
	</p>
	
</fieldset>