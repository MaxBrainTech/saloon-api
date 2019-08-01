
<div class="content-box"><!-- Start Content Box -->
    <div class="content-box-content">
        <?php
       // echo ($this->Form->create('Employee', array('name' => 'Customer', 'url' => array('controller' => 'customers', 'action' => 'process'))));
        echo ($this->Form->hidden('pageAction', array('id' => 'pageAction')));

        foreach ($tabs as $tab => $count) {
            ?>
            <div class="tab-content<?php echo ($defaultTab == $tab ? ' default-tab' : ''); ?>" id="<?php echo ($tab); ?>"> <!-- This is the target div. id must match the href of this div's tab -->

                <div id="target<?php echo ($tab); ?>">

                    <?php
                    // echo "Hello";die;
                    echo ($defaultTab == $tab ? $this->element('front/employee/table') : '');
                    ?>
                        
                </div>
            </div>
            <?php
        }
        echo ($this->Form->end());
        ?>
    </div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->
<script type="text/javascript">
//var CurrentUrl = SiteUrl+'/admin/customers/index/client';
    jQuery(document).ready(function(){
        //init('#target<?php echo($defaultTab); ?>');
    });
</script>
<?php
if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>