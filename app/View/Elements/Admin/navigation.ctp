<ul id="main-nav">  <!-- Accordion Menu -->
    <li>
        <?php
        $class = ($this->params['controller'] == 'admins' && $this->params['action'] != 'admin_dashboard') ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Admin Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
                <?php
                $class = $this->params['controller'] == "admins" && ($this->params['action'] == 'admin_edit' || $this->params['action'] == 'admin_change_password') ? array('class' => 'current') : null;
                echo ($this->Html->link(__('List Admins', true), array('plugin' => null, 'controller' => 'admins', 'action' => 'index'), $class));
				?>
            </li>
        </ul>
    </li>
    <li>
        <?php
        $class = $this->params['controller'] == 'users' ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('User Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
				<?php
                $class = $this->params['controller'] == 'users' && $this->params['action'] == 'admin_index' && (!empty($this->params['pass']) && $this->params['pass'][0] == 'Contractor') ? array('class' => 'current') : null;
                echo ($this->Html->link(__('User List', true), array('plugin' => null, 'controller' => 'users', 'action' => 'index'), $class));
				if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
				{
				// echo ($this->Html->link(__('User Add', true), array('plugin' => null, 'controller' => 'users', 'action' => 'add'), $class));
				} 
                ?>
            </li>
        </ul>
    </li>
    <li>
        <?php
        $class = $this->params['controller'] == 'customers' ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Customer Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
				<?php
                $class = $this->params['controller'] == 'customers' && $this->params['action'] == 'admin_index' && (!empty($this->params['pass']) && $this->params['pass'][0] == 'Contractor') ? array('class' => 'current') : null;
                echo ($this->Html->link(__('Customer List', true), array('plugin' => null, 'controller' => 'customers', 'action' => 'index'), $class));
				//echo ($this->Html->link(__('Customer Add', true), array('plugin' => null, 'controller' => 'customers', 'action' => 'add'), $class));
			    ?>
            </li>
        </ul>
    </li>
    <li>
        <?php
        $class = $this->params['controller'] == 'services' ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Service Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
                <?php
                $class = $this->params['controller'] == 'services' && $this->params['action'] == 'admin_index' && (!empty($this->params['pass']) && $this->params['pass'][0] == 'Contractor') ? array('class' => 'current') : null;
                echo ($this->Html->link(__('Service List', true), array('plugin' => null, 'controller' => 'services', 'action' => 'index'), $class));
                /*
                if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
                {
                 echo ($this->Html->link(__('Service Add', true), array('plugin' => null, 'controller' => 'services', 'action' => 'add'), $class));
                } */
                ?>
            </li>
        </ul>
    </li>
    <li>
        <?php
        $class = $this->params['controller'] == 'categories' ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Category Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
                <?php
                $class = $this->params['controller'] == 'categories' && $this->params['action'] == 'admin_index' && (!empty($this->params['pass']) && $this->params['pass'][0] == 'Contractor') ? array('class' => 'current') : null;
                echo ($this->Html->link(__('Category List', true), array('plugin' => null, 'controller' => 'categories', 'action' => 'index'), $class));
                
                if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
                {
                 echo ($this->Html->link(__('Category Add', true), array('plugin' => null, 'controller' => 'categories', 'action' => 'add'), $class));
                } 
                ?>
            </li>
        </ul>
    </li>
     <li>
        <?php
        $class = $this->params['controller'] == 'colors' ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Color Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
                <?php
                $class = $this->params['controller'] == 'colors' && $this->params['action'] == 'admin_index' && (!empty($this->params['pass']) && $this->params['pass'][0] == 'Contractor') ? array('class' => 'current') : null;
                echo ($this->Html->link(__('Color List', true), array('plugin' => null, 'controller' => 'colors', 'action' => 'index'), $class));
                
                if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
                {
                 echo ($this->Html->link(__('Color Add', true), array('plugin' => null, 'controller' => 'colors', 'action' => 'add'), $class));
                }
                ?>
            </li>
        </ul>
    </li>
    <li>
        <?php
        $class = $this->params['controller'] == 'roles' ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Role Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
                <?php
                $class = $this->params['controller'] == 'roles' && $this->params['action'] == 'admin_index' && (!empty($this->params['pass']) && $this->params['pass'][0] == 'Contractor') ? array('class' => 'current') : null;
                echo ($this->Html->link(__('Role List', true), array('plugin' => null, 'controller' => 'roles', 'action' => 'index'), $class));
                /*
                if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
                {
                 echo ($this->Html->link(__('Role Add', true), array('plugin' => null, 'controller' => 'roles', 'action' => 'add'), $class));
                } */
                ?>
            </li>
        </ul>
    </li>
    <li>
        <?php
        $class = ($this->params['controller'] == 'templates' || $this->params['controller'] == 'footers') ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Email Templates Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
                <?php
                $class = $this->params['controller'] == "templates" && ($this->params['action'] == 'admin_index' || $this->params['action'] == 'admin_edit') ? array('class' => 'current') : null;
					echo ($this->Html->link(__('Email Templates', true), array('plugin' => null, 'controller' => 'templates', 'action' => 'index'), $class));
                ?>
            </li>
        </ul>
    </li>

     <li>
        <?php
        $class = $this->params['controller'] == 'test_web_services' ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Test API Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
                <?php
                $class = $this->params['controller'] == 'test_web_services' && $this->params['action'] == 'admin_index' && (!empty($this->params['pass']) && $this->params['pass'][0] == 'Contractor') ? array('class' => 'current') : null;
                echo ($this->Html->link(__('API List', true), array('plugin' => null, 'controller' => 'test_web_services', 'action' => 'index'), $class));
                
                if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
                {
                 echo ($this->Html->link(__('API Add', true), array('plugin' => null, 'controller' => 'test_web_services', 'action' => 'add'), $class));
                } 
                ?>
            </li>
        </ul>
    </li>

    <li>
        <?php
        $class = $this->params['controller'] == 'pages' ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Static Page Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
                <?php
                $class = $this->params['controller'] == 'pages' && $this->params['action'] == 'admin_index' && (!empty($this->params['pass']) && $this->params['pass'][0] == 'Contractor') ? array('class' => 'current') : null;
                echo ($this->Html->link(__('Page List', true), array('plugin' => null, 'controller' => 'pages', 'action' => 'index'), $class));
                
                if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role'))
                {
                 echo ($this->Html->link(__('Page Add', true), array('plugin' => null, 'controller' => 'pages', 'action' => 'add'), $class));
                } 
                ?>
            </li>
        </ul>
    </li>
	
	<?php if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.Admin.role')){ ?>
	
	 <li>
        <?php echo ($this->Html->link('Settings', array('plugin' => null, 'controller' => 'settings', 'action' => 'index'), array('class' => 'nav-top-item no-submenu '))); ?>
    </li> 
    <?php } ?>  
    <li>
        <?php echo ($this->Html->link('Logout', array('plugin' => null, 'controller' => 'admins', 'action' => 'logout'), array('class' => 'nav-top-item no-submenu '))); ?>
    </li>
</ul>
