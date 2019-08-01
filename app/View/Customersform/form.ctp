
  <script>
  // jQuery(function($) {
  //   $(document.getElementById('fb-editor')).formBuilder();
  // });

  jQuery(function($) {
    var fbEditor = document.getElementById('build-wrap');
    var formBuilder = $(fbEditor).formBuilder();
    var form_text;
    var service_id;
   
    document.getElementById('getJSON').addEventListener('click', function() {
      // alert(formBuilder.actions.getData('json'));
      // console.log(formBuilder.actions.getData('json'));
      service_id = $('#service_id').val;
      console.log(service_id);
      form_text = formBuilder.actions.getData('json');
      // console.log(form_text);

      // $.ajax({
      //       url: "add_form",
      //       type: 'post',
      //       data: {'form_text':form_text},
      //       success: function(result){
      //           console.log(result);
      //           // var obj = JSON.parse(result);
      //           // console.log(obj.status);
      //           // $('#attendance_msg').text(obj.msg);
      //           // setTimeout(function() {
      //           //     location.reload();
      //           // }, 5000);
      //       }
      //   });
    });
    
  });
  </script>
  <div class="col-sm-6">
      <div class="form-group">
          <?php  echo ($this->Form->input('service_id', array('options'=>$serviceList,'div'=>false,  'label' => 'Service',  "class" => "form-control")));?> 
     
      </div>
  </div>
  <div class="setDataWrap">
    <!-- <button id="getXML" type="button">Get XML Data</button> -->
    <button id="getJSON" type="button">Get JSON Data</button>
    <!-- <button id="getJS" type="button">Get JS Data</button> -->
  </div>
  
<div id="build-wrap"></div>