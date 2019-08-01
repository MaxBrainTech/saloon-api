<?php 

    // print_r($expenseData);
?>

<div class="content-box"><!-- Start Content Box -->
    <div class="content-box-content">
        
            <div class="tab-content" id=""> <!-- This is the target div. id must match the href of this div's tab -->

                <div id="target">

                    <?php
                    echo ( $this->element('front/user_categories/user_categories_list') );
                    ?></div>

            </div>

            <?php
        //}
        //echo ($this->Form->end());
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