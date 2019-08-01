<section id="collaborative-col">
	  <div class="pad0 gradient-transparent">
		<div class="container">
		  <div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			  
				<ul class="tab-sub-nav">
				  <li><a href="#"> Add Content </a></li>
				  <li><a href="#"> More Links </a></li>
				</ul>
				<!--
				<ul class="tab-sort-by">
				  <li> Sort by : <a href="#" class="transition active"> Name </a></li>
				  <li> | </li>
				  <li><a href="#" class="transition"> Date </a></li>
				</ul>
				-->
				<?php
				$sortName = "";
				$sortDate = "";
				if(isset($this->request->params['named']['sort'])){
					if($this->request->params['named']['sort']=='Feed.name'){
						$sortName = "active";
					}
					if($this->request->params['named']['sort']=='Feed.created'){
						$sortDate = "active";					
					}
				}
				?>
				<ul class="tab-sort-by">
				  <li> Sort by : <?php echo ($this->ExPaginator->sort('Feed.name', 'Name', array('class'=>"transition $sortName"))); ?></li>
				  <li> | </li>
				  <li><?php echo ($this->ExPaginator->sort('Feed.created', 'Date', array('class'=>"transition $sortDate"))); ?></li>
				</ul>
			  
			</div>
		  </div>
		  
		  <div class="row">
		<?php if(!empty($user_feeds)){?>
			<?php foreach($user_feeds as $keyFeed=>$feedVal){
				$feedId = $feedVal['Feed']['id'];
			?>
			
			<!--  VIDEO POPUP  -->
				<!-- Modal -->
			<div class="modal fade" id="myModal<?php echo $feedId;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel" style="color:#000;">
						<?php echo $feedVal['Feed']['name'];?>
					</h4>
				  </div>
				  <div class="modal-body" style="text-align:center;">
					<iframe src="<?php echo $feedVal['Feed']['video_url'];?>" style="border:1px solid #ccc;background-color:#000;padding:5px;width:95%;height:100%;" id="VideoObj">
					</iframe>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  </div>
				</div>
			  </div>
			</div>
		  <!--  VIDEO POPUP  -->
			
			
					<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
						<div class="one-fourth-pic">
						<img src="<?php echo $feedVal['Feed']['original_image_url'];?>" class="image-responsive" width="250" height="165" />
							<div class="pic-hover">   
								<div class="one-fourth-icon transition"> 
									<a href="#"><?php echo $this->Html->image('search_icon.png', array( "data-toggle"=>"modal", "data-target"=>"#myModal$feedId"));?></a>
									<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array('id'=>"delete$feedId", 'class'=>'delete_video'));?></a>
									<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
								</div>   
							</div>
						</div>
						<p> <?php echo (strlen($feedVal['Feed']['name'])>28)?substr($feedVal['Feed']['name'], 0, 28)."...":$feedVal['Feed']['name'];   ?></p>
						<p> <span> Posted on : <?php echo date("M d, Y"); ?></span> </p>
					</div>
			<?php }?>
		<?php }?>
			
			
			<?php /*?>
			<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 one-fourth col-xs-offset-2 col-sm-offset-0 col-md-offset-0 col-lg-offset-0"> 
				<div class="one-fourth-pic"> <?php echo $this->Html->image('pic01.jpg', array('class'=>'image-responsive'));?> 
					<div class="pic-hover">   
						<div class="one-fourth-icon transition"> 
							<a href="#"><?php echo $this->Html->image('search_icon.png', array());?></a>
							<a href="#"><?php echo $this->Html->image('cancel_icon2.png', array());?></a>
							<a href="#"><?php echo $this->Html->image('download_icon.png', array());?></a>
						 </div>   
					</div>
				</div>
				<p> Rachel Beckwith's Mom Visits... </p>
				<p> <span> Posted on : 10 July 2014 </span> </p>
			</div>
			<?php */?>
		  </div>
		  <?php			
				$this->Paginator->options(array(
					'url' => $this->passedArgs,
				));
				echo $this->element('front_pagination', array("paging_model_name" => "Feed", "total_title" => "Feeds"));
            ?>
		  
		  <!--
		  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pagination-col text-center">  
			<ul class="pagination">
				<li><a href="#"> <i class="glyphicon glyphicon-chevron-left"> </i></a></li>
				<li><a href="#">1</a></li>
				<li><a href="#">2</a></li>
				<li><a href="#">3</a></li>
				<li><a href="#">4</a></li>
				<li><a href="#">5</a></li>
				<li><a href="#"> <i class="glyphicon glyphicon-chevron-right"> </i> </a></li>
			</ul>
		 </div>-->
		</div>
	  </div>
</section>
<script type="text/javascript">
jQuery('.delete_video').click(function(){
	 var videoId = jQuery(this).attr('id');
	 var vidId = videoId.replace("delete", "");
	 //alert(vidId);
	 jQuery.ajax({
			type: "get",
			url: "<?php echo $this->Html->url(array('controller'=>'feeds', 'action'=>'delete_video'));?>",
			//data: jQuery('form.test').serialize(),
			data: {id:vidId},
			success: function(response){
					if(response==1){
							var url = jQuery(this).attr("href");
							//jQuery('#image_container_indicator_1').show();
							jQuery('#collaborative-col').load(url, function(response, status, xhr) {
							if (xhr.readyState == 4) {
							// jQuery('#image_container_indicator_1').hide();
							}
							});
					}else{
						
					}
				 },
		error: function(){
			alert("failure");
			}
		});
	 
	 
});

jQuery('.pagination a').click(function(){
	 var url = jQuery(this).attr("href");
	 //jQuery('#image_container_indicator_1').show();
	 jQuery('#collaborative-col').load(url, function(response, status, xhr) {
	  if (xhr.readyState == 4) {
	   // jQuery('#image_container_indicator_1').hide();
	  }
	});
   return false;
});

jQuery('.tab-sort-by li a').click(function(){
	 var url = jQuery(this).attr("href");
	 //jQuery('#image_container_indicator_1').show();
	 jQuery('#collaborative-col').load(url, function(response, status, xhr) {
	  if (xhr.readyState == 4) {
	   // jQuery('#image_container_indicator_1').hide();
	  }
	});
   return false;
});
</script>