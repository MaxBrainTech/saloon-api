
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <?php 
        echo $this->Html->charset(); 
        if (isset($description_for_layout)) {
            echo $this->Html->meta('description', $description_for_layout);
        }
        if (isset($keywords_for_layout)) {
            echo $this->Html->meta('keywords', $keywords_for_layout);
        }
        echo $this->Html->meta('icon');
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title_for_layout; ?> | <?php echo Configure::read('Site.title'); ?></title>

    <!-- Bootstrap CSS -->
    <!-- Bootstrap CSS -->
    <style type="text/css">
      .anychart-credits-logo , .anychart-credits-text{
        display: none;
      }
    
  #age, #visits, #repeater, #service_sales, #staff_sales, #staff_sale_salary , #reservation_plat_form , #weekly_reservation , #reservation_time {
    width: 100%;
    height: 400px;
    margin: 0;
    padding: 0;
}</style>
    <?php
    echo $this->Html->css(array(
        'bootstrap/bootstrap.min',
        'anychart-ui.min',
        'owl.carousel',
        'owl.theme',
        'all',
        'style_analytics',
    ));
    
    ?>

</head>
<body>


<div class="main-content dashboard-chart">
   
  
<div class="filter-tab">
  <ul class="filter-date-tab">
    <li><?php echo $this->Html->link("Analytic", array('controller' => 'users','action' => 'analytics', $user_id ), array('escape' => false)); ?></li>
    <li><?php echo $this->Html->link("Prediction", array('controller' => 'users','action' => 'predictions', $user_id ), array('escape' => false)); ?></li>
    
  </ul>
</div>

<div class="date-filter">
  <ul class="filter-date-input">
    <?php 
      echo $this->Form->create('User', 
        array('url' => array('controller' => 'users', 'action' => 'analytic_predications', $user_id),
            'inputDefaults' => array(
              'error' => array(
                'attributes' => array(
                  'wrap' => 'span',
                  'class' => 'input-notification error png_bg'
                )
            )
          )
         )  
        );?>
    <li><input type="text" name="start_date" value="<?php echo $japanese_start_date;?>" id = "startDate" placeholder="Start Date" class="form-control"></li>
    <li><input type="text" name="end_date" value="<?php echo $japanese_end_date;?>" id = "endDate"  placeholder="End Date" class="form-control"></li>
    <li><?php echo ($this->Form->submit('Go', array('class' => 'btn', "div" => false))); ?></li>
    <?php
        echo ($this->Form->end());
      ?>  
  </ul>
</div>

 <div class="panel panel-default">
        <div class="panel-heading">
            Customers
        </div>
        <div class="panel-body">
            <div id="owl-demo" class="owl-carousel">
                <div class="item">
                    <div id="age"></div>
                </div>
                <div class="item">
                    <div id="visits"></div>
                </div>
                <div class="item">
                    <div id="repeater"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Staff and Services
        </div>
        <div class="panel-body">
            <div id="owl-demo1" class="owl-carousel">
                <div class="item">

                    <div id="service_sales"></div>
                </div>
                <div class="item">
                    <div id="staff_sales"></div>
                </div>
                <div class="item">
                    <div id="staff_sale_salary"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Reservation
        </div>
        <div class="panel-body">
            <div id="owl-demo2" class="owl-carousel">
                <div class="item">
                    <div id="reservation_plat_form"></div>
                </div>
                <div class="item">
                    <div id="weekly_reservation"></div>
                </div>
                <div class="item">
                    <div id="reservation_time"></div>
                </div>
            </div>
        </div>
    </div>


    
   
</div>
 <!-- jQuery CDN - Slim version (=without AJAX) -->
   <?php
    echo $this->Html->script(array(
       'jquery.min',
       'bootstrap.min',
       'anychart-base.min',
    ));
    ?>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>      
<link href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />   
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/i18n/jquery-ui-i18n.min.js"></script> 

    <?php 
    echo $this->Html->script(
                        array(
                            'datepicker/jquery.js',
                            'datepicker/jquery.datetimepicker.full.js'
                        ));
    echo $this->Html->css(array('datepicker/jquery.datetimepicker.css'));
?>

<script>
 $(document).ready(function () {
   $('#startDate').datetimepicker({
      // format:'Y-m-d H:i'
      format:'Y-m-d',
      timepicker:false
    });
   $('#endDate').datetimepicker({
      // format:'Y-m-d H:i'
      format:'Y-m-d',
      timepicker:false
    });
}); 
    
</script>


<script type="text/javascript">
anychart.onDocumentReady(function() {
 
  // set data and define chart type
  var dataSet = anychart.data.set(<?php echo $customerAgeArr;?>);
  
  // variable for total sales
  var total = 0;
  
  for (var i = 0; i<dataSet.mapAs().getRowsCount();i++)
    total+=dataSet.mapAs().get(i,"value");
  
  var chart = anychart.pie(dataSet);
  
  // legend settings
  var legend = chart.legend();
  // enables legend
  legend.enabled(true);
  // set legend position
  legend.position("left");
  // set legend align
  legend.align("top");
  // legend items layout
  legend.itemsLayout("vertical");
  // adjust legend items
  legend.itemsFormatter(function(items){
    // push into items array
    items.push({
      // set item text
      text: "Total: "+total.toString(),
      // disable icon for a new item
      iconEnabled: false,
      // bold text of the item
      fontWeight: 900
    });
    // return items array
    return items;
  });
  
  // configure tooltip considering new item 
  var tooltip = chart.legend().tooltip();
  tooltip.enabled(true);
  tooltip.format(function(){
    // to avoid failing on "total" item
    if(this.meta.pointValue === undefined)
      return this.value;
    // for all other points
    return this.value+"\n"+this.meta.pointValue.toString();
  });

    // set title
  chart.title("Age");
  
  // set container and draw chart
  chart.container("age");
  chart.draw();

});

</script>










<script type="text/javascript">
anychart.onDocumentReady(function() {
  
  // set data and define chart type
  var dataSet = anychart.data.set(<?php echo $customerRepeaterCountArrData;?>);
  
  // variable for total sales
  var total = 0;
  
  for (var i = 0; i<dataSet.mapAs().getRowsCount();i++)
    total+=dataSet.mapAs().get(i,"value");
  
  var chart = anychart.pie(dataSet);
  
  // legend settings
  var legend = chart.legend();
  // enables legend
  legend.enabled(true);
  // set legend position
  legend.position("left");
  // set legend align
  legend.align("top");
  // legend items layout
  legend.itemsLayout("vertical");
  // adjust legend items
  legend.itemsFormatter(function(items){
    // push into items array
    items.push({
      // set item text
      text: "Total: "+total.toString(),
      // disable icon for a new item
      iconEnabled: false,
      // bold text of the item
      fontWeight: 900
    });
    // return items array
    return items;
  });
  
  // configure tooltip considering new item 
  var tooltip = chart.legend().tooltip();
  tooltip.enabled(true);
  tooltip.format(function(){
    // to avoid failing on "total" item
    if(this.meta.pointValue === undefined)
      return this.value;
    // for all other points
    return this.value+"\n"+this.meta.pointValue.toString();
  });

    // set title
  chart.title("Repeater");

  // set container and draw chart
  chart.container("repeater");
  chart.draw();
});

</script>





<script type="text/javascript">
anychart.onDocumentReady(function() {
  
  // set data and define chart type
  var dataSet = anychart.data.set(<?php echo $servicePriceArrData;?>);
  
  // variable for total sales
  var total = 0;
  
  for (var i = 0; i<dataSet.mapAs().getRowsCount();i++)
    total+=dataSet.mapAs().get(i,"value");
  
  var chart = anychart.pie(dataSet);
  
  // legend settings
  var legend = chart.legend();
  // enables legend
  legend.enabled(true);
  // set legend position
  legend.position("left");
  // set legend align
  legend.align("top");
  // legend items layout
  legend.itemsLayout("vertical");
  // adjust legend items
  legend.itemsFormatter(function(items){
    // push into items array
    items.push({
      // set item text
      text: "Total: "+total.toString(),
      // disable icon for a new item
      iconEnabled: false,
      // bold text of the item
      fontWeight: 900
    });
    // return items array
    return items;
  });
  
  // configure tooltip considering new item 
  var tooltip = chart.legend().tooltip();
  tooltip.enabled(true);
  tooltip.format(function(){
    // to avoid failing on "total" item
    if(this.meta.pointValue === undefined)
      return this.value;
    // for all other points
    return this.value+"\n"+this.meta.pointValue.toString();
  });

    // set title
  chart.title("Service Sales");

  // set container and draw chart
  chart.container("service_sales");
  chart.draw();
});

</script>




<script type="text/javascript">
anychart.onDocumentReady(function() {
  
  // set data and define chart type
  var dataSet = anychart.data.set(<?php echo $employeePriceArrData;?>);
  
  // variable for total sales
  var total = 0;
  
  for (var i = 0; i<dataSet.mapAs().getRowsCount();i++)
    total+=dataSet.mapAs().get(i,"value");
  
  var chart = anychart.pie(dataSet);
  
  // legend settings
  var legend = chart.legend();
  // enables legend
  legend.enabled(true);
  // set legend position
  legend.position("left");
  // set legend align
  legend.align("top");
  // legend items layout
  legend.itemsLayout("vertical");
  // adjust legend items
  legend.itemsFormatter(function(items){
    // push into items array
    items.push({
      // set item text
      text: "Total: "+total.toString(),
      // disable icon for a new item
      iconEnabled: false,
      // bold text of the item
      fontWeight: 900
    });
    // return items array
    return items;
  });
  
  // configure tooltip considering new item 
  var tooltip = chart.legend().tooltip();
  tooltip.enabled(true);
  tooltip.format(function(){
    // to avoid failing on "total" item
    if(this.meta.pointValue === undefined)
      return this.value;
    // for all other points
    return this.value+"\n"+this.meta.pointValue.toString();
  });

    // set title
  chart.title("Staff Sales");

  // set container and draw chart
  chart.container("staff_sales");
  chart.draw();
});

 </script>










<script type="text/javascript">
anychart.onDocumentReady(function() {
  
  // set data and define chart type
  var dataSet = anychart.data.set(<?php echo $ReservationPlatFormDataArr;?>);
  
  // variable for total sales
  var total = 0;
  
  for (var i = 0; i<dataSet.mapAs().getRowsCount();i++)
    total+=dataSet.mapAs().get(i,"value");
  
  var chart = anychart.pie(dataSet);
  
  // legend settings
  var legend = chart.legend();
  // enables legend
  legend.enabled(true);
  // set legend position
  legend.position("left");
  // set legend align
  legend.align("top");
  // legend items layout
  legend.itemsLayout("vertical");
  // adjust legend items
  legend.itemsFormatter(function(items){
    // push into items array
    items.push({
      // set item text
      text: "Total: "+total.toString(),
      // disable icon for a new item
      iconEnabled: false,
      // bold text of the item
      fontWeight: 900
    });
    // return items array
    return items;
  });
  
  // configure tooltip considering new item 
  var tooltip = chart.legend().tooltip();
  tooltip.enabled(true);
  tooltip.format(function(){
    // to avoid failing on "total" item
    if(this.meta.pointValue === undefined)
      return this.value;
    // for all other points
    return this.value+"\n"+this.meta.pointValue.toString();
  });

    // set title
  chart.title("Reservation plat Form");

  // set container and draw chart
  chart.container("reservation_plat_form");
  chart.draw();
});

</script>




<script type="text/javascript">
anychart.onDocumentReady(function() {
  
  // set data and define chart type
  var dataSet = anychart.data.set(<?php echo $weeklyReservationDataArr; ?>);
  
  // variable for total sales
  var total = 0;
  
  for (var i = 0; i<dataSet.mapAs().getRowsCount();i++)
    total+=dataSet.mapAs().get(i,"value");
  
  var chart = anychart.pie(dataSet);
  
  // legend settings
  var legend = chart.legend();
  // enables legend
  legend.enabled(true);
  // set legend position
  legend.position("left");
  // set legend align
  legend.align("top");
  // legend items layout
  legend.itemsLayout("vertical");
  // adjust legend items
  legend.itemsFormatter(function(items){
    // push into items array
    items.push({
      // set item text
      text: "Total: "+total.toString(),
      // disable icon for a new item
      iconEnabled: false,
      // bold text of the item
      fontWeight: 900
    });
    // return items array
    return items;
  });
  
  // configure tooltip considering new item 
  var tooltip = chart.legend().tooltip();
  tooltip.enabled(true);
  tooltip.format(function(){
    // to avoid failing on "total" item
    if(this.meta.pointValue === undefined)
      return this.value;
    // for all other points
    return this.value+"\n"+this.meta.pointValue.toString();
  });

    // set title
  chart.title("Weekly Reservation");

  // set container and draw chart
  chart.container("weekly_reservation");
  chart.draw();
});

 </script>


 <script type="text/javascript">
anychart.onDocumentReady(function() {

  var themeSettings = {
    "column":{
      "defaultSeriesSettings":{
        "column":{
          "labels": {
            "enabled": true,
            "anchor": "Center",
            "position": "Center",
            "fontFamily": "Courier",
            "fontSize": 8,
            "fontColor": "#ffffff",
            "format": "${%Value}"
          }
        }
      }
    }
  };

  

   var data = <?php echo $monthPerArr;?>

  anychart.theme(themeSettings);

  // chart type
  var chart = anychart.column(data);
  
  // set title
  chart.title("Visits");

  // assign a container and draw a chart
  chart.container("visits");
  chart.draw();


  var reservtiontimedata = <?php echo $upcomingReservationDataArr;?>;

  anychart.theme(themeSettings);

  // chart type
  var reservtiontimechart = anychart.column(reservtiontimedata);
  
  // set title
  reservtiontimechart.title("Reservation Time");

  // assign a container and draw a chart
  reservtiontimechart.container("reservation_time");
  reservtiontimechart.draw();







    // create a staff sale salary set
    var data = anychart.data.set(<?php echo $employeeSaleSalaryArrData;?>);

    // map the data
    var seriesData_1 = data.mapAs({x: 0, value: 1, fill: 3, stroke: 5, label: 6});
    var seriesData_2 = data.mapAs({x: 0, value: 2, fill: 4, stroke: 5, label: 6});

    // create a chart
    var chart = anychart.bar();

    // create the first series, set the data and name
    var series1 = chart.bar(seriesData_1);
    series1.name("Sales");

    // create the second series, set the data and name
    var series2 = chart.bar(seriesData_2);
    series2.name("Salary");

    // set the chart title
    chart.title("Staff Salary & Sales Compare");

    // set the titles of the axes
    var xAxis = chart.xAxis();
    xAxis.title("Staff");
    var yAxis = chart.yAxis();
    yAxis.title("Sales, Salary");

    // set the container id
    chart.container("staff_sale_salary");

    // initiate drawing the chart
    chart.draw();
});
</script>
<?php
    echo $this->Html->script(array(
       'owl.carousel'
    ));
    ?>
    <script type="text/javascript">
        

      


        $(document).ready(function() {
        $("#owl-demo").owlCarousel({
          pagination: false,
          autoPlay: false,
          items : 3,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [991,3],
          itemsMobile : [767,2],
          itemsMobile : [479,1]
        });

        $("#owl-demo1").owlCarousel({
          pagination: false,
          autoPlay: false,
          items : 3,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [991,3],
          itemsMobile : [767,2],
          itemsMobile : [479,1]
        });

        $("#owl-demo2").owlCarousel({
          pagination: false,
          autoPlay: false,
          items : 3,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [991,3],
          itemsMobile : [767,2],
          itemsMobile : [479,1]
        });


      });

       
    </script>

</body>

</html>

      