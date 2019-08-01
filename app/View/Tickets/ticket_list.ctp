<div class="content-box"><!-- Start Content Box -->
    <div class="content-box-content">
            <div class="tab-content" id=""> 
                <div id="target">
                    <?php
                    echo $this->element('front/ticket/list_table');
                    ?>
                        
                </div>
            </div>
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