
<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		
		<h3 style="cursor: s-resize;">Manage Email Templates</h3>
		<div class="total">
            <?php echo $this->element('Admin/admin_total', array("paging_model_name" => "Template", "total_title" => "Templates")); ?>
        </div>
		
		<div class="clear"></div>
		
	</div> <!-- End .content-box-header -->
	
	<div class="content-box-content">
		<div id="page-loader">
			<?php
				echo ($this->Html->image('admin/loading.gif'));
			?>
		</div>
		
		<?php
		/* echo ($this->Form->create('Template', array('name'=>'Template', 'url' => array('action' => 'process'))));
		echo ($this->Form->hidden('pageAction', array('id' => 'pageAction'))); */
		
		foreach($tabs as $tab=>$count){?>
		
		<div class="tab-content<?php echo ($defaultTab==$tab?' default-tab':'');?>" id="<?php echo ($tab);?>"> <!-- This is the target div. id must match the href of this div's tab -->
			
			
			
			
			<div id="target<?php echo ($tab);?>"><?php
					echo ($defaultTab==$tab?$this->element('Admin/Template/table'):'');
			?></div>
			
		</div> 
		
		<?php 
		}
		//echo ($this->Form->end());
		?>
		
	</div> <!-- End .content-box-content -->
	
</div> <!-- End .content-box -->
<script type="text/javascript">
var controller = 'templates';
jQuery(document).ready(function(){
	//init('#target<?php echo($defaultTab);?>');
	
	jQuery("#TemplateAdminIndexForm").submit(function(){
	jQuery("#error_div").html('');
	flag = false;
	if(jQuery("#TemplateUserId").val() == '')
	{
		
		flag = true;
	}
	if(jQuery("#TemplateSubject").val() == '')
	{
		
		flag = true;
	}
	
	if(flag)
	{
		jQuery("#error_div").html('Please fill the required fields.');
		return false;
	}
	else
	{
		return true;
	}
	
	
	
	});
	
	
	
});
</script>