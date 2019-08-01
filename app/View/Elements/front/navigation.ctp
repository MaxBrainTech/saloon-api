 <ul class="nav menu">
    <?php $classdash = ($this->params['controller'] == 'calenders' && $this->params['action'] == 'get_reservation') ? 'active' : null; ?>

    <li class="<?php echo $classdash;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-dashboard')) . " カレンダー", array('plugin' => null, 'controller' => 'calenders', 'action' => 'get_reservation'),  array( 'escape' => false)));
        ?>
    </li>
     <?php $classshop = ($this->params['controller'] == 'users' && $this->params['action'] == 'my_shop') ? 'active' : null; ?>
     <li class="<?php echo $classshop;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-dashboard')) . " マイショップ", array('plugin' => null, 'controller' => 'users', 'action' => 'my_shop'),  array( 'escape' => false)));
        ?>
    </li>
     <?php $customersclass = ($this->params['controller'] == 'customers') ? 'active' : null; ?>
    <li class="<?php echo $customersclass;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-customer-list')) . " 顧客リスト", array('plugin' => null, 'controller' => 'customers', 'action' => 'customer_list'),  array( 'escape' => false)));
        ?>
    </li>            
     <?php $employeeclass = ($this->params['controller'] == 'employees') ? 'active' : null; ?>
     <li class="<?php echo $employeeclass;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-staff')) . " JTSスタッフ", array('plugin' => null, 'controller' => 'employees', 'action' => 'employee_list'),  array( 'escape' => false)));
        ?>
    </li>
    <?php $class = ($this->params['controller'] == 'services' && $this->params['action'] == 'list') ? 'active' : null; ?>
    <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-sales')) . " マイサービス", array('plugin' => null, 'controller' => 'services', 'action' => 'service_list'),  array( 'escape' => false)));
        ?>
    </li>
               
    <?php $class = ($this->params['controller'] == 'Customersform' && $this->params['action'] == 'list') ? 'active' : null; ?>
    <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-dashboard')) . " カスタムフォーム", array('plugin' => null, 'controller' => 'Customersform', 'action' => 'list_form'),  array( 'escape' => false)));
        ?>
    </li> 
     <?php $class = ($this->params['controller'] == 'users' && $this->params['action'] == 'paymentInfo') ? 'active' : null; ?>
     <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-expenses')) . "支払い情報", array('plugin' => null, 'controller' => 'users', 'action' => 'payment_info'),  array( 'escape' => false)));
        ?>
    </li>
    <?php $class = ($this->params['controller'] == 'users' && $this->params['action'] == 'get_today_sell') ? 'active' : null; ?>
    <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-sales')) . " 販売", array('plugin' => null, 'controller' => 'users', 'action' => 'get_today_sell'),  array( 'escape' => false)));
        ?>
    </li>
    <?php $class = ($this->params['controller'] == 'user_categories' && $this->params['action'] == 'get_main_categories') ? 'active' : null; ?>
    <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-attendance')) . " カテゴリ", array('plugin' => null, 'controller' => 'user_categories', 'action' => 'get_main_categories'),  array( 'escape' => false)));
        ?>
    </li>
    <?php $class = ($this->params['controller'] == 'budgets' && $this->params['action'] == 'budget_list') ? 'active' : null; ?>
    <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-attendance')) . " 予算", array('plugin' => null, 'controller' => 'budgets', 'action' => 'budget_list'),  array( 'escape' => false)));
        ?>
    </li>
    <?php $class = ($this->params['controller'] == 'expenses' && $this->params['action'] == 'manual_expense_list') ? 'active' : null; ?>
     <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-expenses')) . " 費用", array('plugin' => null, 'controller' => 'expenses', 'action' => 'manual_expense_list'),  array( 'escape' => false)));
        ?>
    </li>
    <?php $class = ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_dasshboard') ? 'active' : null; ?>
    <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-sales')) . " 商品", array('plugin' => null, 'controller' => 'products', 'action' => 'product_list'),  array( 'escape' => false)));
        ?>
     <?php $class = ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_dashsssboard') ? 'active' : null; ?>
    <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-attendance')) . " チケット", array('plugin' => null, 'controller' => 'tickets', 'action' => 'ticket_list'),  array( 'escape' => false)));
        ?>
    </li>
     <?php $class = ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_dassshboard') ? 'active' : null; ?>
     <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-expenses')) . " 休日", array('plugin' => null, 'controller' => 'holidays', 'action' => 'holiday_list'),  array( 'escape' => false)));
        ?>
    </li>  
    <?php $class = ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_dassshboard') ? 'active' : null; ?>
    <li class="<?php echo $class;?>">
        <?php  echo ($this->Html->link($this->Html->tag('span', '&nbsp;', array('class' => 'icon-logout')) . " ログアウト", array('plugin' => null, 'controller' => 'users', 'action' => 'logout'),  array( 'escape' => false)));
        ?>
    </li>             
</ul>



