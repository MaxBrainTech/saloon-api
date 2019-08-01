<?php $siteUrl = Configure::read('App.SiteUrl');?>
<?php 
if(!preg_match("/^[0-9+-]+$/", '5524124314')){
	
}
?>
<style>
#content{
	font-family:monospace;
}
#content td{
	border-right:1px solid #CCC;
	border-bottom:1px solid #CCC;
	word-wrap: break-word;
	padding:10px;
}
#content tr{
}

.latest{
	background-color: #FFFFD1;
}
.Desc{
	border:1px dotted #333333;
	background-color: #E6F3FF;
	padding:10px;
	margin:5px;
}
.btn a{
	border:2px solid red;
	color:#ffffff;
	text-decoration:none;
	background-color: green;
	padding:3px;
	margin:2px;
}
.indexDiv{
	margin-left:150px;
	padding:5px;
	border:1px dotted #CCC;
	width:600px;
	margin-bottom:10px;
	float:left;
}

.indexDiv a{
	font-size:14px;
	color:green;
	text-decoration:none;
}

.LasModiDate{
	font-size:14px;
	color:#333333;
	background-color:#99FF33;
	padding:3px;
	border:1px dotted blue;
}
</style>
<table border="0" cellpadding="5" cellspacing="0" width="80%" align="center">
	<tr>
		<td colspan="4"><h3>WEBSERVICE API READ DOC</h3></td>
	</tr>
</table>
<div class="indexDiv">
	<span>Webservice Index</span><br>
	<div style="width:40%;float:left;">
	<?php if(!empty($dataIndex)){
		$SNO = 1;?>
		<?php foreach($dataIndex as $keyVal=>$dataIndexValue){?>
			<a href="#wb_<?php echo $keyVal;?>">(<?php echo $SNO;?>). <?php echo $dataIndexValue;?></a><br/>
		<?php $SNO++;}?>
	<?php }?>
	</div>
	<div style="width:40%;float:left;font-size:24px;">
	Recent Updated<br>
	<blink><a href="#wb_<?php echo $data[0]['TestWebService']['id'];?>" style="font-size:24px;background-color:yellow;padding:2px;"><?php echo $data[0]['TestWebService']['title'];?></a></blink>
	</div>
</div>
<table border="0" cellpadding="5" cellspacing="0" width="80%" align="center" style="border:1px solid #CCC;" id="content">
	<tr style="background-color:green;color:#FFF;">
		<td width="5%">Title</td>
		<td width="25%">API URL</td>
		<td width="35%">INPUTS</td>
		<td width="35%">OUTPUT</td>
	</tr>
	<!-- REGISTER WEB SERVICE -->
	<?php if(!empty($data)){?>
		<?php 
		$count = 0;
		foreach($data as $key=>$value){?>
		<?php $BgColor = ""; if($count=='0'){ $BgColor = "#FFFFD1";}?>
		<tr style="background-color:<?php echo $BgColor;?>" id="wb_<?php echo $value['TestWebService']['id'];?>">
			<td valign="top"><strong><?php echo $value['TestWebService']['title'];?></strong>
			<br>
			<br>
			<br>
			<br>
			<strong>Last Changes:</strong>
			<div class="LasModiDate"><?php echo $value['TestWebService']['modified'];?></div><?php ?>
			</td>
			<td valign="top"><?php echo $siteUrl."/web_services/".$value['TestWebService']['title'];?></td>
			<td valign="top">
			<?php echo nl2br($value['TestWebService']['request']);?><br/>
			<?php if(!empty($value['TestWebService']['description'])){?>
				<h3>Description:</h3>
				<div class="Desc"><?php echo nl2br($value['TestWebService']['description']);?></div>
			<?php }?>
			</td>
			<td valign="top">
			<?php echo nl2br($value['TestWebService']['response']);?>
			
			<span class="btn">
			<?php echo $this->Html->link("Test This =>", array('controller' => 'web_services', 'action' => 'read_doc', 'test', $value['TestWebService']['title']), array('title' => $value['TestWebService']['title']));
			?>
			</span>
			</td>
		</tr>
		<?php $count++;}?>
	<?php }else{?>
		<tr>
			<td colspan="4" align="center">NO DATA AVAILABLE</td>
		</tr>
	<?php }?>
</table>
<div style="float:right;margin-right:100px;margin-bottom:500px;">
	<a href="http://www.w3.org/Protocols/HTTP/HTRESP.html">View All response codes</a><br>
	<a href="http://msdn.microsoft.com/en-us/library/windowsazure/dd179357.aspx">Common REST API Error Codes</a>
</div>