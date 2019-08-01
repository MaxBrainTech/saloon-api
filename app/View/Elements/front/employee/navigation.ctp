 <ul class="nav menu">
    <?php $classdash = ($this->params['controller'] == 'employees' && $this->params['action'] == 'employee_home') ? 'active' : null; ?>

    <li class="<?php echo $classdash;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-dashboard')) . " Attendance", array('plugin' => null, 'controller' => 'employees', 'action' => 'employee_home'),  array( 'escape' => false)));
        ?>
    </li>
     <?php //$classshop = ($this->params['controller'] == 'employees' && $this->params['action'] == 'services_list') ? 'active' : null; ?>
     <!-- <li class="<?php echo $classshop;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-dashboard')) . " Services", array('plugin' => null, 'controller' => 'employees', 'action' => 'services_list'),  array( 'escape' => false)));
        ?>
    </li> -->
     <?php $customersclass = ($this->params['controller'] == 'employees' && $this->params['action'] == 'customer_list') ? 'active' : null; ?>
    <li class="<?php echo $customersclass;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-customer-list')) . " Customer List", array('plugin' => null, 'controller' => 'employees', 'action' => 'customer_list'),  array( 'escape' => false)));
        ?>
    </li>      
     <?php $class = ($this->params['controller'] == 'employees' && $this->params['action'] == 'admin_dassshboard') ? 'active' : null; ?>
    <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-logout')) . " Logout", array('plugin' => null, 'controller' => 'employees', 'action' => 'logout'),  array( 'escape' => false)));
        ?>
    </li>            
</ul>



