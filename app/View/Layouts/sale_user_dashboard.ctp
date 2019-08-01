<?php echo $this->Html->docType('html5'); ?>
<html>
    <head>

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
            'reset',
            'admin',
            'invalid',
            'jquery/jquery.alerts',
            'jquery/smoothness/jquery-ui-1.8.22.custom'
        ));
        ?>
        <script type="text/javascript">
            var SiteUrl = "<?php echo Configure::read('App.SiteUrl'); ?>";
            var SiteName = "<?php echo Configure::read('App.SiteName'); ?>";
            var CurrentUrl = "<?php echo Router::url(array('controller' => $this->params['controller'], 'action' => $this->params['action'], isset($role) ? $role : '')); ?>";

        </script>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
        <style type="text/css">
            .page-item.active .page-link { background: #000; border-color: #000;}   
            .page-link { color: #000; }
            a { color: #000; }
            #sidebar #main-nav li a.current { background: #fff;}    
            #sidebar #main-nav li a.nav-top-item{ background: #c08f33; }
            div#profile-links { font-size: 16px; color: #c08f33; font-weight: 600;}
            #sidebar #main-nav li a.current { background: #fff; background-image: none !important;}
            .kitchen_list_table_link{ color: #6bb003; font-weight: 700; }
            .kitchen_list_table_link:hover { text-decoration: none; color: #c5ea8d; cursor: pointer; }
            .kitchen_list_table_link_delete{ color: #dc3545; font-weight: 700; }
            .kitchen_list_table_link_delete:hover { text-decoration: none; color: #e07b85; cursor: pointer;}
            .good-bitee-button{background: #c08f33; padding: 10px; color: #fff; font-size: 18px;}
            a.good-bitee-button:hover { text-decoration: none; color: #fff; }
            a.button, .button { background: #c08f33 !important; padding: 8px 10px !important; color: #fff !important; font-size: 14px !important; border: none !important; display: inline-block !important; }
        #main-content ul li {padding: 4px 0 4px 5px;}
        </style>
        <?php
        echo $this->Html->script(array(
            'jquery/jquery-1.7.2.min',
            'jquery/jquery.configuration',
            //'facebox',
            'jquery',
            'jquery-1',
            'jquery/jquery.wysiwyg',
            'jquery/jquery.alerts',
            'jquery/jquery-ui.min',
            
        ));
        echo $scripts_for_layout;
        ?>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    </head>

    <body >
        <div id="body-wrapper" style="background: none;"> <!-- Wrapper for the radial gradient background -->

            <div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->


                    <?php
                    echo $this->Html->link($this->Html->image('home-logo.png', array( 'id' => 'logo','style'=>'margin-left:10px;width:200px;','title'=>'JTS Board','alt'=>'JTS Board')), array('controller' => 'sale_users', 'action' => 'dashboard'), array('escape' => false ,'title'=>'JTS Board','alt'=>'JTS Board'));
                    ?>


                    <!-- Sidebar Profile links -->
                    <div id="profile-links">
                        Hello, 

                        <?php
                        if($this->Session->read('saleuser.SaleUser.name') !=null){ 
                            echo $this->Session->read('saleuser.SaleUser.name');
                        }else{
                         echo 'UserName';
                        }
                    ?>

                    </div>

                    <?php  echo $this->element("front/sale_user/navigation"); ?>


                </div></div> <!-- End #sidebar -->

            <div id="main-content"> <!-- Main Content Section with everything -->

                <noscript> <!-- Show a notification if the user has disabled javascript -->
                <div class="notification error png_bg">
                    <div>
                        Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a> your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a> Javascript to navigate the interface properly.
                    </div>
                </div>
                </noscript>

                <!-- Page Head -->
                <h2><?php echo ($title_for_layout); ?></h2>
                <p id="page-intro"><?php //echo ($subtitle);  ?></p>
                <?php
                $this->Layout->sessionFlash();
                ?>

                <?php
                echo $content_for_layout;
                ?>

                <div class="clear"></div> <!-- End .clear -->



                <div id="footer">
                    <small> <!-- Remove this notice or replace it with whatever you want -->
                        <a href="#">Top</a>
                    </small>
                </div><!-- End #footer -->

            </div> <!-- End #main-content -->

        </div>


        <div id="facebox" style="display:none;">
            <div class="popup">
                <table>
                    <tbody>
                        <tr>
                            <td class="tl"></td>
                            <td class="b"></td>
                            <td class="tr"></td>
                        </tr>
                        <tr>
                            <td class="b"></td>
                            <td class="body">
                                <div class="content">
                                </div>
                                <div class="footer">
                                    <a href="#" class="close">
                                        <?php echo $this->Html->image('admin/closelabel.gif', array('title' => 'close', 'class' => 'close_image')); ?>
                                    </a>
                                </div>
                            </td>
                            <td class="b"></td>
                        </tr>
                        <tr>
                            <td class="bl"></td>
                            <td class="b"></td>
                            <td class="br"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
    <script>
        $("document").ready(function() {

            $('#footer a').click(function() {

                $('html, body').animate({scrollTop: 0}, 'slow');

                return false;

            });


        });
    </script>
</html>