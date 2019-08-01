<?php
if (!empty($data)) {
    $this->ExPaginator->options = array('url' => $this->passedArgs);
    ?>
    <table>

        <thead>
            <tr>

                <th width="300px"><?php echo ($this->ExPaginator->sort('TestWebService.title', 'Word')) ?></th>
                <th width="300px"><?php echo ($this->ExPaginator->sort('TestWebService.created', 'Created on')) ?></th>
                <th width="300px"><?php echo ($this->ExPaginator->sort('TestWebService.modified', 'Modified on')) ?></th>
                <th width="300px"><?php echo ($this->ExPaginator->sort('TestWebService.status', 'Status')) ?></th>
                <th width="50px">Action</th>
            </tr>

        </thead>

        <tfoot>
            <tr>
                <td colspan="5">

                    <?php
                    $this->Paginator->options(array(
                        'url' => $this->passedArgs,
                    ));
                    echo $this->element('Admin/admin_pagination', array("paging_model_name" => "TestWebService", "total_title" => "TestWebServices"));
                    ?>

                </td>
            </tr>
        </tfoot>

        <tbody>

            <?php
            $alt = 0;
            foreach ($data as $value) {
                ?>
                <tr <?php
                echo ($alt == 0) ? 'class="alt-row"' : '';
                $alt = !$alt;
                ?>>

                    <td><b><?php echo $this->General->wrap_long_txt($value['TestWebService']['title'], 0, 50); ?></b></td>

                    <td><?php echo ($this->Time->niceShort(strtotime($value['TestWebService']['created']))); ?></td>
                    <td><?php
                        if (!empty($value['TestWebService']['modified'])) {
                            echo ($this->Time->niceShort(strtotime($value['TestWebService']['modified'])));
                        }
                        ?></td>
                    <td><?php
                            $url = array('controller' => 'test_web_services', 'action' => 'status', $value['TestWebService']['id']);
                            echo ($value['TestWebService']['status'] == 1) ? $this->Html->link('Active', $url) : $this->Html->link('Inactive', $url);
                        ?></td>  


                    <td>
                        <!-- Icons -->
                        <?php
                            echo ($this->Html->link($this->Html->image('admin/pencil.png', array('title' => 'Edit', 'alt' => 'Edit')), array('action' => 'edit', $value['TestWebService']['id']), array('escape' => false)));
                        ?>
                        <?php
                            echo ($this->Html->link($this->Html->image('admin/cross.png', array('title' => 'Delete', 'alt' => 'Delete')), array('action' => 'delete', $value['TestWebService']['id'], 'token' => $this->params['_Token']['key']), array('escape' => false, )));
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>

        </tbody>

    </table>
    <?php
} else {
    echo ($this->element('admin_flash_info', array('message' => 'NO RESULTS FOUND')));
}
?>