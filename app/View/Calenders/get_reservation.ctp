<!-- <div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">Dashboard</li>
            </ol>
        </div
        
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Dashboard</h1>
            </div>
        </div> -->
        
        <!-- <div class="panel panel-container">
            <div class="row">
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-teal panel-widget border-right">
                        <div class="row no-padding"><em class="fa fa-xl fa-shopping-cart color-blue"></em>
                            <div class="large">120</div>
                            <div class="text-muted">New Orders</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-blue panel-widget border-right">
                        <div class="row no-padding"><em class="fa fa-xl fa-comments color-orange"></em>
                            <div class="large">52</div>
                            <div class="text-muted">Comments</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-orange panel-widget border-right">
                        <div class="row no-padding"><em class="fa fa-xl fa-users color-teal"></em>
                            <div class="large">24</div>
                            <div class="text-muted">New Users</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
                    <div class="panel panel-red panel-widget ">
                        <div class="row no-padding"><em class="fa fa-xl fa-search color-red"></em>
                            <div class="large">25.2k</div>
                            <div class="text-muted">Page Views</div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->     
        
        <!-- <div class="panel panel-default">
            <div class="panel-heading">
                Calendar View
                <div class="col-md-4" style="float: right;">
                    <?php echo $this->Html->link("Customer", array('controller'=>'calenders', 'action'=>'add_reservation','1'), array("class"=>"btn btn-primary", "escape"=>false)); ?>
                    <?php echo $this->Html->link("Event", array('controller'=>'calenders', 'action'=>'add_reservation', '2'), array("class"=>"btn btn-primary", "escape"=>false)); ?>
                    <?php echo $this->Html->link("Staff", array('controller'=>'calenders', 'action'=>'add_reservation', '3'), array("class"=>"btn btn-primary", "escape"=>false)); ?>
                </div>
            </div>
            <?php
                // echo ( $this->element('front/calender/reservation') );
            ?>
        </div> -->


<?php 

    // print_r($data);
?>

<div class="content-box"><!-- Start Content Box -->
    <div class="content-box-content">
        

            <div class="tab-content"> <!-- This is the target div. id must match the href of this div's tab -->

                <div id="target">

                    <?php
                    echo ( $this->element('front/calender/reservation') );
                    ?>
                        
                </div>

            </div>

            <?php
       
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

        