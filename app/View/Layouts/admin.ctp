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

        <!--[if lte IE 6]><style>
                  img { behavior: url("<?php echo Configure::read('App.SiteUrl'); ?>/css/iepngfix.htc") }
                </style><![endif]-->

        <!--[if lte IE 7]>
        <link rel="stylesheet" href="<?php echo Configure::read('App.SiteUrl'); ?>/css/ie.css" type="text/css" media="screen" />
<![endif]-->


<!--[if IE]><script type="text/javascript" src="<?php echo Configure::read('App.SiteUrl'); ?>/js/jquery/jquery.bgiframe.js"></script><![endif]-->


    </head>

    <body>
        <div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->

            <div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->


                    <?php
                    echo $this->Html->link($this->Html->image('logo.png', array( 'id' => 'logo','style'=>'margin-left:10px;width:200px;','title'=>'JTS Board','alt'=>'JTS Board')), array('controller' => 'admins', 'action' => 'dashboard'), array('escape' => false ,'title'=>'JTS Board','alt'=>'JTS Board'));
                    ?>


                    <!-- Sidebar Profile links -->
                    <div id="profile-links">
                        Hello, <?php echo $admin_data['User']['username'];?><?php //echo ($this->Html->link(ucwords($admin_data['User']['first_name']." ".$admin_data['User']['last_name']), array('controller' => 'admins', 'action' => 'edit', $this->Session->read('Auth.User.id')), array('title' => 'Edit your profile'))); ?>
                        <?php //echo $this->Html->link("View the Site", '/', array("title"=>"View the Site"));?> | <?php echo $this->Html->link("logout", array('controller' => 'admins', 'action' => 'logout', 'plugin' => null), array("title" => "Sign Out")); ?>
                    </div>

                    <?php echo $this->element("Admin/navigation"); ?>


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