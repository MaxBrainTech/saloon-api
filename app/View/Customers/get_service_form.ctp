<?php 
  // echo "<pre>";
  // print_r($formData);die;
?>


<div>
 <?php 
echo ($this->Form->create('CustomerForm', array('name' => 'CustomerForm', 'url' => array('controller' => 'customers', 'action' => 'save_service_form'))));?>

    <div id="markup"></div>
     <?php  echo ($this->Form->input('user_id', array("type"=>'hidden',"value"=>$formData["CustomerForm"]["user_id"])));
            echo ($this->Form->input('service_id', array("type"=>'hidden',"value"=>$formData["CustomerForm"]["service_id"])));
            echo ($this->Form->input('customer_history_id', array("type"=>'hidden',"value"=>$formData["CustomerForm"]["customer_history_id"])));
            echo ($this->Form->input('reservation_id', array("type"=>'hidden',"value"=>$formData["CustomerForm"]["reservation_id"])));
            echo ($this->Form->input('customer_id', array("type"=>'hidden',"value"=>$formData["CustomerForm"]["customer_id"])));
             echo ($this->Form->input('form_json_data', array("type"=>'hidden',"value"=>$formData["CustomerForm"]["form_text"])));
            echo ($this->Form->submit('Save Information', array('class' => 'btn btn-primary', "div" => false)));
            echo ($this->Form->end());    ?>

</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.4.0/highlight.min.js"></script>
<script type="text/javascript" src="https://formbuilder.online/assets/js/form-render.min.js"></script>
<script type="text/javascript">
    var form_text = '<?php echo $formData['CustomerForm']['form_text']; ?>';
    jQuery($ => {
  const escapeEl = document.createElement("textarea");
  const code = document.getElementById("markup");
  const formData = form_text;
  const addLineBreaks = html => html.replace(new RegExp("><", "g"), ">\n<");

  // Grab markup and escape it
  const $markup = $("<div/>");
  $markup.formRender({ formData });

  // set < code > innerText with escaped markup
  code.innerHTML = addLineBreaks($markup.formRender("html"));

  hljs.highlightBlock(code);
});

</script>