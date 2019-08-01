<div>
	<?php
	if(isset($type) && !empty($type)){
		echo($this->Form->create('Number', array('url'=>array('controller' => $this->params['controller'], 'action' => 'index',$type))));
	}
	else
	{
		if(isset($action_name) && $action_name =='email_history')
		{
			echo($this->Form->create('Number', array('url'=>array('controller' => $this->params['controller'], 'action' => 'history'))));
		}
		else if(isset($action_name) && $action_name =='send_offer_email')
		{
			echo($this->Form->create('Number', array('url'=>array('controller' => $this->params['controller'], 'action' => 'send_offer_email', $this->request->params['pass']['0']))));
		}
		else
		{	
			echo($this->Form->create('Number', array('url'=>array('controller' => $this->params['controller'], 'action' => 'index'))));
		}
	}
	?>
	<div style="float:right;"> 
		<?php  
		echo $this->Form->input('Number.number_of_record', array('options'=>Configure::read('App.PerPage'),'div'=>false, 'label'=>false,'onchange'=>'this.form.submit();', "class" => "smallTextbox"));
		echo $this->Form->end();
		?> 
	</div>
	<div  class="totalDropdown"> 
		<strong> <?php echo "Total ".$total_title." : " . $this->params["paging"][$paging_model_name]["count"]; ?> </strong>
		<strong>Number of record per page:</strong>
	</div>
</div>