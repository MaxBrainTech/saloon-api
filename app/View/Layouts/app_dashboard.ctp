<?php echo $this->Html->docType('html5'); ?>
<html>
    <head>
<meta name="viewport" content="width=device-width, initial-scale=1">
        <?php echo $this->Html->charset(); ?>

        <title><?php echo $title_for_layout; ?> | <?php echo Configure::read('Site.title'); ?></title>
        <?php
        if (isset($description_for_layout)) {
            echo $this->Html->meta('description', $description_for_layout);
        }
        if (isset($keywords_for_layout)) {
            echo $this->Html->meta('keywords', $keywords_for_layout);
        }
        echo $this->Html->meta('icon');
        echo $this->Html->css(array(
            'bootstrap.min',
            'font-awesome.min',
            'datepicker3',
            'datatables/dataTables.bootstrap',
            'datatables/dataTables.responsive',
            'styles',
            'datatables/dataTables.responsive',
            'custom-style',
        ));

        echo $this->Html->script(array(
            'jquery-3.3.1'
        ));
        ?>
        <?php 
        echo $this->Html->script(array(
            'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
            //'facebox',
            'https://formbuilder.online/assets/js/form-builder.min.js'
            
        ));
        echo $scripts_for_layout;

    ?> 
        <script type="text/javascript">
            var SiteUrl = "<?php echo Configure::read('App.SiteUrl'); ?>";
            var SiteName = "<?php echo Configure::read('App.SiteName'); ?>";
            var CurrentUrl = "<?php echo Router::url(array('controller' => $this->params['controller'], 'action' => $this->params['action'], isset($role) ? $role : '')); ?>";

        </script>
<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    
        <?php
      
    echo $this->Html->css(array(
                'custom-font'
            ));
?>
        <!--[if lte IE 6]><style>
                  img { behavior: url("<?php echo Configure::read('App.SiteUrl'); ?>/css/iepngfix.htc") }
                </style><![endif]-->

        <!--[if lte IE 7]>
        <link rel="stylesheet" href="<?php echo Configure::read('App.SiteUrl'); ?>/css/ie.css" type="text/css" media="screen" />
<![endif]-->


<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->


    </head>

    <body style="padding-top: 0 ; background: #fff ;">
        <div class="profile-sidebar">
            <div class="profile-userpic">
                <?php 
                // pr($this->Auth->('name'));
                    // pr($data);
                    // pr($image);
                    // pr($name);
                    // $this->User->id = $id;
                    // $session_image = $this->Session->read('User');
                    // pr($session_image);
                if(isset($image) && !empty($image)){
                    echo $this->Html->image(SITE_URL ."uploads/my_shop/original". DS.$image, array('title' => '','class'=>"img-responsive"));
                }elseif(isset($this->request->data['User']['image']) && !empty($this->request->data['User']['image'])){ 
                    echo $this->Html->image(SITE_URL ."uploads/my_shop/original". DS.$this->request->data['User']['image'], array('title' => '','class'=>"img-responsive"));
                }elseif($this->Session->read('User.image') !=null){
                    echo $this->Html->image(SITE_URL ."uploads/my_shop/original". DS.$this->Session->read('User.image'), array('title' => '','class'=>"img-responsive"));
                }else{
                 echo $this->Html->image('jts-logo.png', array('title' => '','class'=>"img-responsive", "style" =>"width : 150px"));
                }
                 ?>
            </div>
            <div class="profile-usertitle">
                <div class="profile-usertitle-name">
                    <?php 
                        if(isset($name) && !empty($name)){
                            echo $name;
                        }elseif(isset($this->request->data['User']['name']) && !empty($this->request->data['User']['name'])){ 
                            echo $this->request->data['User']['name'];
                        }elseif($this->Session->read('User.name') !=null){ 
                            echo $this->Session->read('User.name');
                        }else{
                         echo 'UserName';
                        }
                    ?>
                        
                    </div>
                <div class="profile-usertitle-status"><span class="indicator label-success"></span>Online</div>
            </div>
             
            <div class="clear"></div>
            
        </div>
       <?php echo $content_for_layout;?>
    <?php 
        echo $this->Html->script(array(
            'jquery-3.3.1',
            'bootstrap.min',
            //'facebox',
            'chart.min',
            'chart-data',
            'easypiechart',
            'easypiechart-data',
            'bootstrap-datepicker',
            'custom',
            'datatables/jquery.dataTables.min',
            'datatables/dataTables.bootstrap.min',
            'datatables/dataTables.responsive',
            
        ));
        echo $scripts_for_layout;

    ?> 


<script>
        window.onload = function () {
    var chart1 = document.getElementById("line-chart").getContext("2d");
    window.myLine = new Chart(chart1).Line(lineChartData, {
    responsive: true,
    scaleLineColor: "rgba(0,0,0,.2)",
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleFontColor: "#c5c7cc"
        });
    };
    </script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>
   

    </body>
    
</html>