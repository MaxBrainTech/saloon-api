<?php 
	if($this->Paginator->params['paging'][$paging_model_name]['pageCount'] >= 2){
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pagination-col text-center">
	<ul class="pagination">
		<?php
		echo $this->Paginator->prev( '<i class="glyphicon glyphicon-chevron-left"> </i>', array( 'class' => '', 'tag' => 'li', 'escape' => false), null, array( 'class' => 'disabled', 'tag' => 'li', 'escape' => false, 'style'=>'display:none;'));
		echo $this->Paginator->numbers( array( 'tag' => 'li', 'separator' => '', 'currentClass' => 'active', 'currentTag' => 'a'));
		echo $this->Paginator->next( '<i class="glyphicon glyphicon-chevron-right"> </i>', array( 'class' => '', 'tag' => 'li', 'escape' => false), null, array( 'class' => 'disabled', 'tag' => 'li', 'escape' => false, 'style'=>'display:none;'));
		?>
	</ul>
</div>
<?php /* ?>
<div class="pagination">
	<?php		
		 echo $this->Paginator->first('&laquo; First', array('title' => 'First Page','escape' => false));
		 echo $this->Paginator->prev('&laquo; Previous', array('title' => 'Previous Page','escape' => false));
		 echo $this->Paginator->numbers(array('class'=>'number',  'separator'=>false));			           
		 echo $this->Paginator->next('Next &raquo;', array('title' => 'Next Page','escape' => false));
		 echo $this->Paginator->last('Last &raquo;', array('title' => 'Last Page','escape' => false));
	?>
</div> <!-- End .pagination -->
<?php  */?>
<div class="clear"></div>
<?php 
   }
?>