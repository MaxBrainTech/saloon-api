<?php 
	if($this->Paginator->params['paging'][$paging_model_name]['pageCount'] >= 2){
?>
<div class="pagination">
	<?php
		
		 echo $this->Paginator->first('&laquo; First', array('title' => 'First Page','escape' => false));
		 echo $this->Paginator->prev('&laquo; Previous', array('title' => 'Previous Page','escape' => false));
		 echo $this->Paginator->numbers(array('class'=>'number',  'separator'=>false));			           
		 echo $this->Paginator->next('Next &raquo;', array('title' => 'Next Page','escape' => false));
		 echo $this->Paginator->last('Last &raquo;', array('title' => 'Last Page','escape' => false));
	?>
</div> <!-- End .pagination -->
<div class="clear"></div>
<?php 
   }
?>