

<div class="content-box"><!-- Start Content Box -->
    <div class="content-box-content">
       

            <div class="tab-content" id=""> <!-- This is the target div. id must match the href of this div's tab -->

                <div id="target">

                    <?php
                    echo ( $this->element('front/employee/employee_attendance_table') );
                    ?></div>

            </div>

            
    </div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->
<script type="text/javascript">

</script>
<?php
// if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
//     echo $this->Js->writeBuffer();
?>