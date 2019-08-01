<script type="text/javascript">
    jQuery(document).ready(function()
    {
        jQuery(".req input").blur(function()
        {

            if (jQuery(".rbordertext").length > 0) {

                jQuery(this).removeClass('rbordertext');
            }
        });

        jQuery(".btn2").click(function(event) {
            jQuery(".req input").each(function()
            {
                if (jQuery(this).val() === "")
                {
                    jQuery(this).addClass("rbordertext");
                }
                else
                    jQuery(this).removeClass("rbordertext");
            });
            if (jQuery(".rbordertext").length > 0)
            {
                event.preventDefault();
            }

        });

    });
</script>

	<table width="800" align="center">
		<tr><td align="center">
		<?php echo $this->Html->link($this->Html->image('logo.png', array('alt'=>'Girlforhire')), "/", array('escape'=>false));?>
		</td></tr>
		<tr><td align="center">
		<font face="Verdana, Geneva, sans-serif" size="3" color="#FF00FF">Seriously Talented Females & Those looking For Some, Login Below:
		</font>
		</td></tr>
	</table>
<br />
<?php echo $this->element("social_login_connect");?>

<script type="text/javascript">
    jQuery(document).ready(function()
    {
        jQuery(".req input").blur(function()
        {

            if (jQuery(".rbordertext").length > 0) {

                jQuery(this).removeClass('rbordertext');
            }
        });

        jQuery(".btn2").click(function(event) {
            jQuery(".req input").each(function()
            {
                if (jQuery(this).val() === "")
                {
                    jQuery(this).addClass("rbordertext");
                }
                else
                    jQuery(this).removeClass("rbordertext");
            });
            if (jQuery(".rbordertext").length > 0)
            {
                event.preventDefault();
            }

        });

    });
</script>
</section>
<!-- end login -->