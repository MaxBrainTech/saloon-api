<style>
div.error-message {
    color: #FF0000;
   
    font: 12px Arial,Helvetica,sans-serif;
   
}
.label_text
{
	font: 12px Arial,Helvetica,sans-serif;
}
#flashMessage {
	color: #D8000C;
	background-color: #FFBABA;
	background-image: url('error.png')
}

.flash_bad,.flash_good,#flashMessage {
	border: 1px solid;
	margin: -12px 0px;
	padding: 10px 0px 9px 48px;
	background-repeat: no-repeat;
	background-position: 10px center;
}

.flash_bad {
	color: #D8000C;
	background-color: #FFBABA;
	background-image: url('error.png')
}

.flash_good {
	color: #4F8A10;
	background-color: #DFF2BF;
	background-image: url('success.png');
}

div.error-message {
	clear: both;
	color: #FF0000;
	/*  padding-left: 178px;*/
	font: 12px Arial, Helvetica, sans-serif;
}
</style>

 <?php echo $this->Layout->sessionFlash(); ?>
 