<ul id="main-nav">  <!-- Accordion Menu -->
    <li>
        <?php
        $class = ($this->params['controller'] == 'sale_users' && $this->params['action'] == 'sales_admin_list') ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Admin Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
                <?php
                $class = $this->params['controller'] == "sale_users" && $this->params['action'] == 'sales_admin_list' ? array('class' => 'current') : null;
                echo ($this->Html->link(__('List Admins', true), array('plugin' => null, 'controller' => 'sale_users', 'action' => 'sales_admin_list'), $class));
                ?>
            </li>
        </ul>
    </li>
    <li>
        <?php
        $class = ($this->params['controller'] == 'sale_users' && $this->params['action'] == 'sales_user_list') ? 'current' : null;
        ?>
        <?php echo ($this->Html->link('Sales User Management', array(), array('class' => 'nav-top-item ' . $class))); ?>
        <ul>
            <li>
                <?php
                $class = $this->params['controller'] == 'sale_users' && $this->params['action'] == 'sales_user_list' ? array('class' => 'current') : null;
                echo ($this->Html->link(__('Sales User List', true), array('plugin' => null, 'controller' => 'sale_users', 'action' => 'sales_user_list'), $class));
                ?>
            </li>
        </ul>
    </li>
    <li>
        <?php echo ($this->Html->link('Logout', array('plugin' => null, 'controller' => 'sale_users', 'action' => 'logout'), array('class' => 'nav-top-item no-submenu '))); ?>
    </li>
</ul>
