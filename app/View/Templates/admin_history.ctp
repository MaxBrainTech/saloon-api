<div class="content-box <?php echo (($search_flag == 0) ? 'closed-box' : ''); ?>"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3 style="cursor: s-resize;">Search</h3>

        <ul class="content-box-tabs">
            <li></li>
        </ul>

        <div class="clear"></div>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

        <div style="display: block;" class="" id="tab">

            <?php echo ($this->Form->create('Template', array('id' => 'SearchForm', 'url' => array('admin' => true, 'controller' => 'templates', 'action' => 'history'), 'onsubmit' => 'javascript: return true;'))); ?>

            <fieldset>

               
                <p>
                    <label>Name</label>
                    <?php echo ($this->Form->input('name', array('div' => false, 'label' => false, "class" => "text-input small-input"))); ?>
                </p>

                <p>
                    <label>Email</label>
                    <?php echo ($this->Form->input('email', array('div' => false, 'label' => false, "class" => "text-input small-input"))); ?>

                </p>
				<p>
                    <label>Email type</label>
					<?php
					/* pr(Configure::read('App.Email.History'));
					die; */
					?>
                    <?php echo ($this->Form->input('slug', array('options' => Configure::read('App.Email.History'), 'empty' => '--Select--', 'div' => false, 'label' => false, "class" => "small-input"))); ?>

                </p>
                <p>
                    <?php echo ($this->Form->submit('Search', array('class' => 'button', "div" => false))); ?>

                    <?php //echo ($this->Form->submit('Show All', array('class' => 'button', "div"=>false)));?>
                    <?php echo $this->Html->link("Show All", array('controller'=>'templates','action' => 'history'), array("class" => "button", "escape" => false)); ?>

                </p>

            </fieldset>

            <div class="clear"></div><!-- End .clear -->

            <?php
            echo ($this->Form->end());
            ?>

        </div> <!-- End #tab2 -->

    </div> <!-- End .content-box-content -->

</div>
<div class="content-box"><!-- Start Content Box -->
	
	<div class="content-box-header">
		
		<h3 style="cursor: s-resize;">Manage Email History</h3>
		<div class="total">
            <?php echo $this->element('Admin/admin_total', array("paging_model_name" => "TemplatesUser", "total_title" => "Email history of users","action_name"=>'email_history')); ?>
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
		echo ($this->Form->create('Template', array('name'=>'Template', 'url' => array('action' => 'process'))));
		echo ($this->Form->hidden('pageAction', array('id' => 'pageAction'))); 
		
		foreach($tabs as $tab=>$count){?>
		
		<div class="tab-content<?php echo ($defaultTab==$tab?' default-tab':'');?>" id="<?php echo ($tab);?>"> <!-- This is the target div. id must match the href of this div's tab -->
			
			
			
			
			<div id="target<?php echo ($tab);?>"><?php
					echo ($defaultTab==$tab?$this->element('Admin/Template/history_table'):'');
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
	
	
	
	
	
});
</script>