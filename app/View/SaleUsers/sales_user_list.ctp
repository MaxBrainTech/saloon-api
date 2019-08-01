
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Created on</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($saleUserData as $value){ ?>
            <tr>
                <td><?php echo $value['SaleUser']['kana']." ". $value['SaleUser']['kanji']; ?></td>
                <td><?php echo $value['SaleUser']['email']; ?></td>
                <td><?php echo $value['SaleUser']['tel']; ?></td>
                <td><?php echo ($this->Time->niceShort(strtotime($value['SaleUser']['created']))); ?></td>
                <td><a class="kitchen_list_table_link" href="/sale_users/sales_user_list_view/<?php echo $value['SaleUser']['id']; ?>">View</a> | <a class="kitchen_list_table_link_delete" href="/sale_users/sales_user_list_delete/<?php echo $value['SaleUser']['id']; ?>" onclick="return confirm('Are you sure to delete this record?')">Delete</a></td>
            </tr>
        <?php } ?>
        </tbody>
        <!-- <tfoot>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </tfoot> -->
    </table>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>