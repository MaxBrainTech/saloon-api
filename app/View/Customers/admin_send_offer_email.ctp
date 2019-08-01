<?php	echo ($this->Html->script(array('ckeditor/ckeditor')));?>
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
            <?php echo ($this->Form->create('User', array('id' => 'SearchForm', 'url' => array('admin' => true, 'controller' => 'users', 'action' => 'send_offer_email',$id), 'onsubmit' => 'javascript: return true;'))); ?>
            <fieldset>
             <?php /* ?>  <p>
                    <label>Username</label>
                    <?php echo ($this->Form->input('username', array('div' => false, 'label' => false, "class" => "text-input small-input"))); ?>
                </p>
				
                <p>
                    <label>Mobile number</label>
                    <?php echo ($this->Form->input('phone_number', array('div' => false, 'label' => false, "class" => "text-input small-input"))); ?>
                </p>
				<?php  */?>
                <p>
                    <label>Email</label>
                    <?php echo ($this->Form->input('email', array('div' => false, 'label' => false, "class" => "text-input small-input"))); ?>
                </p>
                <p>
                    <label>Alternate Email</label>
                    <?php echo ($this->Form->input('alternate_email', array('div' => false, 'label' => false, "class" => "text-input small-input"))); ?>
                </p>
                
                <p>
                    <label>Status</label>
                    <?php echo ($this->Form->input('status', array('options' => Configure::read('Status'), 'empty' => 'All', 'div' => false, 'label' => false, "class" => "small-input"))); ?>

                </p>
                <p>
                    <?php echo ($this->Form->submit('Search', array('class' => 'button', "div" => false))); ?>
                    <?php echo $this->Html->link("Show All", array('action' => 'index'), array("class" => "button", "escape" => false)); ?>
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
        <h3 style="cursor: s-resize;">Manage Usres</h3>
        <div class="total">
            <?php echo $this->element('Admin/admin_total', array("paging_model_name" => "User","action_name" => "send_offer_email", "total_title" => "Users")); ?>
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
        echo ($this->Form->create('User', array('name' => 'User', 'url' => array('controller' => 'users', 'action' => 'offer_email',$id))));
        echo ($this->Form->hidden('pageAction', array('id' => 'pageAction')));

        foreach ($tabs as $tab => $count) {
            ?>

            <div class="tab-content<?php echo ($defaultTab == $tab ? ' default-tab' : ''); ?>" id="<?php echo ($tab); ?>"> <!-- This is the target div. id must match the href of this div's tab -->

                <div id="target<?php echo ($tab); ?>"><?php
                    echo ($defaultTab == $tab ? $this->element('Admin/User/offer_table') : '');
                    ?></div>

            </div>

            <?php
        } ?>

		<?php
        echo ($this->Form->end());
        ?>
    </div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->
<script type="text/javascript">
//var CurrentUrl = SiteUrl+'/admin/users/index/client';
    jQuery(document).ready(function(){
        //init('#target<?php echo($defaultTab); ?>');
    });
</script>
<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>