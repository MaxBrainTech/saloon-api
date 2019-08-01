<!-- MiniColors -->
<?php echo $this->Html->script(array('colorpicker/jquery.minicolors.js'));?>
<?php echo $this->Html->css(array('colorpicker/jquery.minicolors.css'));?>
<script>
    $(document).ready( function() {

      $('.demo').each( function() {
        
        $(this).minicolors({
          control: $(this).attr('data-control') || 'hue',
          defaultValue: $(this).attr('data-defaultValue') || '',
          format: $(this).attr('data-format') || 'hex',
          keywords: $(this).attr('data-keywords') || '',
          inline: $(this).attr('data-inline') === 'true',
          letterCase: $(this).attr('data-letterCase') || 'lowercase',
          opacity: $(this).attr('data-opacity'),
          position: $(this).attr('data-position') || 'bottom',
          swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
          change: function(value, opacity) {
            if( !value ) return;
            if( typeof console === 'object' ) {
              console.log(value);
              $('#text-field').val(value);
            }
          },
          theme: 'bootstrap'
        });

      });

    });
  </script>

<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Edit Service</li>
    </ol>
    </div><!--/.row-->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
        </div><!--/.row-->
        <div class="panel panel-default">
            <div class="panel-heading">Service Detail</div>
            <div class="panel-body">
                <?php $this->Layout->sessionFlash(); ?>
                <?php echo $this->Form->create('Service',  array('url' => array('controller' => 'services', 'action' => 'edit'),'inputDefaults' => array(  'error' => array('attributes' => array('wrap' => 'span',    'class' => 'input-notification error png_bg'))) ));?>
                <?php  echo ($this->Form->input('id'));?>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo ($this->Form->input('name', array('div' => false, 'label' => 'Service Name', 'type'=>'text', "class" => "form-control","maxlength"=>30))); ?>
                    </div>
                </div>
                
                <div class="col-sm-6">
                  <div class="form-group">
                    <?php echo ($this->Form->input('color_code', array('div' => false, 'label' => 'Select Color', 'type'=>'text', "class" => "form-control demo","id"=>"color_code"))); ?>
                  </div>                  
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group" style="margin-top: 30px;">
                        <?php echo ($this->Form->submit('Submit Button', array('class' => 'btn btn-primary', "div" => false))); ?>
                    </div>                    
                    <!-- <button type="reset" class="btn btn-default">Reset Button</button> -->
                </div>
               <?php echo ($this->Form->end());    ?>
            </div>
        </div>
        </div><!-- /.panel-->



        