
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
    
  #allss,  #age, #expense_precentage, #expense_precentage1, #sale_expense_column,  #sales_expense, #visits, #container, #repeater, #service_sales, #staff_sales, #staff_sale_salary , #reservation_plat_form , #weekly_reservation , #reservation_time {
    width: 100%;
    height: 400px;
    margin: 0;
    padding: 0;}

    #expense_precentage1 {
    width: 100%;
    height: 250px;
    margin: 0;
    padding: 0;}

    .graph-detail {flex-wrap: wrap;}
    .graph-detail li{border-right: 1px solid #ddd; padding: 0 15px; width: 50%; margin-bottom: 20px;}
    .graph-design {display: block;border-top: 4px solid #64b5f6; background-color: #d6edff; height: 20px;}

</style>
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
 
 <?php /* ?>  
  
<div class="filter-tab">
  <ul class="filter-date-tab">
    <li><?php echo $this->Html->link("Analytic", array('controller' => 'users','action' => 'analytics', $user_id ), array('escape' => false)); ?></li>
    <li><?php echo $this->Html->link("Prediction", array('controller' => 'users','action' => 'predictions', $user_id ), array('escape' => false)); ?></li>
    
  </ul>
</div>
<?php */ ?>

<input type="hidden" name="service_data" id="service_data" value='<?php echo $serviceNameData;?>'> 
<?php /*?>
<div class="date-filter">
  <ul class="filter-date-input">
    <?php 
      echo $this->Form->create('User', 
        array('url' => array('controller' => 'users', 'action' => 'analytics', $user_id),
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
     <input type="hidden" name="service_data" id="service_data" value='<?php echo $serviceNameData;?>''>   
    <li><input type="text" name="start_date" value="<?php echo $japanese_start_date;?>" id = "startDate" placeholder="Start Date" class="form-control"></li>
    <li><input type="text" name="end_date" value="<?php echo $japanese_end_date;?>" id = "endDate"  placeholder="End Date" class="form-control"></li>
    <li><?php echo ($this->Form->submit('Go', array('class' => 'btn', "div" => false))); ?></li>
    <?php
        echo ($this->Form->end());
      ?>  
  </ul>
</div>


<?php */ ?>
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
                    <div id="container"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Customer Visits
        </div>
        <div class="panel-body">
          <div class="mb-20">
            <div id="owl-demo1" class="owl-carousel">
                <div class="item">
                    <div id="visits"></div>
                </div>
            </div>
          </div>

          <div class="custom-division">
            <div class="row">
              <div class="col-md-8">
                <ul class="graph-detail d-flex">
                  <?php foreach($customerRepeaterData as $key => $value){?>
                  <li>
                  	<h6><?php echo $key;?></h6>
                  	<h4><?php echo $value;?></h4>
                  	<span class="graph-design"></span>
                  </li>
                <?php } ?>
                 
                </ul>
              </div>

              <div class="col-md-4">
                <div id="owl-demo4" class="owl-carousel">
                    <div class="item">
	                    <div id="expense_precentage1"></div>
	                </div>
                </div>
              </div>
            </div>

          </div>

        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            Sales V/S Expense
        </div>
        <div class="panel-body">
        	<div class="mb-20">
	            <div id="owl-demo2" class="owl-carousel">
	                <div class="item">

	                    <div id="sales_expense"></div>
	                </div>
	            </div>
	        </div>
         	<div class="mb-20">
              <ul class="filter-date-tab">
                <li><a class='show_expense_presentage' title='11' href="#">11月</a></li>
                <li><a class='show_expense_presentage' title='12'  href="#">12月</a></li>
                <li><a class='show_expense_presentage' title='1'  href="#">1月</a></li>
                <li><a class='show_expense_presentage' title='2'  href="#">2月</a></li>
                <li><a class='show_expense_presentage' title='3'  href="#">3月</a></li>
                <li><a class='show_expense_presentage' title='4'  href="#">4月</a></li>
                <li><a class='show_expense_presentage' title='5'  href="#">5月</a></li>
                <li><a class='show_expense_presentage' title='6'  href="#">6月</a></li>
                <li><a class='show_expense_presentage' title='7' href="#">7月</a></li>
                <li><a class='show_expense_presentage' title='8'  href="#">8月</a></li>
                <li><a class='show_expense_presentage' title='9' href="#">9月</a></li>
                <li><a class='show_expense_presentage' title='10' href="#">10月</a></li>
              </ul>
            </div>


            <div class="">

	            <div id="owl-demo3" class="owl-carousel">

	                <div class="item">
	                    <div id="expense_precentage"></div>
	                </div>
	                 <div class="item">
	                    <h3 class="mb-20">Sales, Profit & Expense</h3>
	                    <div class="mb-20">
	                      <h6>Total Monthly Sales </h6>
	                      <h3 id= "total_sale">  </h3>
	                    </div>
                      <hr>
	                    <div class="mb-20">
	                      <h6>Total Monthly Expense </h6>
	                      <h3 id= "total_expense"> </h3>
	                    </div>
                      <hr>
	                    <div class="mb-20">
	                      <h6>Total Monthly Saving </h6>
	                      <h3 id= "total_saving"> </h3>
	                    </div>
                      <hr>

	                </div>

	                <div class="item">
	                    <div id="sale_expense_column"></div>
	                </div>
	            </div>

	        </div>

	    </div>

    </div>
  
   <div class="container">
                
                
                
                    
                        <div class="panel panel-default panel-table">
                            <div class="panel-heading">
                               Monthly Expenses Vs Sales By Numbers
                            </div>
                            <div class="panel-body">
                                <table class="table table-custom mb-0" style="width:100%">
                                    <thead>
                                        <tr class="bg-light-cyan">
                                            <th>Month</th>
                                            <th colspan="2" class="text-center">Sales</th>
                                            <th>Expances</th>
                                            <th>Profits</th>
                                        </tr>
                                    </thead>
                                    <tbody id= 'table_sale_expense'>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>

                       
                      </div>

</div>
 <!-- jQuery CDN - Slim version (=without AJAX) -->
   <?php
    echo $this->Html->script(array(
       'jquery.min',
       'bootstrap.min',
       // 'anychart-base.min',
    ));
    ?>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-core.min.js"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-pie.min.js"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-sparkline.min.js"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-core.min.js"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-bundle.min.js"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js?hcode=be5162d915534272a57d0bb781d27f2b"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js?hcode=be5162d915534272a57d0bb781d27f2b"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js?hcode=be5162d915534272a57d0bb781d27f2b"></script>
<link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css?hcode=be5162d915534272a57d0bb781d27f2b" type="text/css" rel="stylesheet">
<link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css?hcode=be5162d915534272a57d0bb781d27f2b" type="text/css" rel="stylesheet">




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

var d = new Date()

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
  chart.title("Customer Age");
  
  // set container and draw chart
  chart.container("age");
  chart.draw();


// Customer Repeaters
  // create data set
 dataSet = anychart.data.set([
    ["11月", 34, 0, 0, 0, 0, 2],
    ["12月", 38, 0, 3, 0, 0, 0],
    ["1月", 15, 0, 4, 0, 0, 0],
    ["2月", 26, 0, 1, 0, 0, 0],
    ["3月", 33, 0, 1, 0, 0, 0],
    ["4月", 21, 0, 1, 0, 1, 0],
    ["5月", 16, 0, 1, 0, 0, 0],
    ["6月", 14, 0, 1, 0, 0, 0],
    ["7月", 17, 0, 2, 0, 1, 0],
    ["8月", 24, 0, 1, 0, 2, 3],
    ["9月", 28, 0, 2, 0, 2, 0],
    ["10月", 31, 0, 3, 0, 1, 0]
    
  ]);
  var service_data = $("#service_data").val();
  service_data = $.parseJSON(service_data);

  // console.log(service_data); 
    // create a line chart
  var chart = anychart.line();
  var m = 0
  var chart = anychart.line();
  var servicss = ["First", "Second", "Third", "Four", "Five", "Six", "Seven", "Eight"];
  var servicsss = ["FirstService", "SecondService", "ThirdService", "FourService", "FiveService", "SixService", "SevenService", "EightService"];
  $.each( service_data, function( key, value ) {
    servicss[key] = dataSet.mapAs({x: 0, value: key+1});
    servicsss[key] = chart.line(servicss[key]);
    servicsss[key].name(value['name']);
    servicsss[key].color(value['color']);
    if(key == 0)
      maleAverage = servicsss[key].getStat("seriesYAverage");
    else
       femaleAverage = chart.getSeriesAt(key).getStat("seriesYAverage");
    // alert( key + ": " + value['name'] );
    // alert( key + ": " + value['color'] );
  });


  // set the title of the chart
  chart.title("All Services");
  
  // set the title of the y-axis
  chart.yAxis().title("Customer Repeat");

  // turn the legend on
  chart.legend(true);

  // set the container id for the chart
  chart.container("container11");

  // initiate drawing the chart
  chart.draw();

  var request = [
  {"month":5, "year":2019},
  {"month":6, "year":2019},
  {"month":7, "year":2019},
  {"month":8, "year":2019},
  {"month":9, "year":2019},
  {"month":10, "year":2019}]
  // console.log(request);
  $.ajax({
      url: "https://jts-board.appspot.com/monthv", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(request),
      success: function( data ) { 
        month_visits =  JSON.parse(data);
       
          console.log(month_visits);
          // create a chart
          var chart = anychart.area();

          // create an area series and set the data
          var series = chart.area(month_visits);

          // set scale mode
          chart.xScale().mode('continuous');
          
          // set the chart title
          chart.title("Month Wise Customer Visit");

          // set the titles of the axes
          chart.xAxis().title("Month");
          chart.yAxis().title("Number of Customer");

          // set the container id
          chart.container("visits");

          // initiate drawing the chart
          chart.draw();
            

        
      }   
  });

  

  var request = [
  {"month":11, "year":2019},
  {"month":12, "year":2019},
  {"month":1, "year":2019},
  {"month":2, "year":2019},
  {"month":3, "year":2019},
  {"month":4, "year":2019},
  {"month":5, "year":2019},
  {"month":6, "year":2019},
  {"month":7, "year":2019},
  {"month":8, "year":2019},
  {"month":9, "year":2019},
  {"month":10, "year":2019}]
  // console.log(request);
  $.ajax({
      url: "https://jts-board.appspot.com/monthexp", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(request),
      success: function( data ) { 
        data =  JSON.parse(data);
       
          // console.log(data);
          // create a chart
          // map the data
          var data = anychart.data.set(data);
          var seriesData_1 = data.mapAs({x: 0, value: 1});
          var seriesData_2 = data.mapAs({x: 0, value: 2});

          // create a chart
          var chart = anychart.column();

          // create the first series, set the data and name
          var series1 = chart.column(seriesData_1);
          series1.name("Sales");

          // create the second series, set the data and name
          var series2 = chart.column(seriesData_2);
          series2.name("Expense");

          // set the padding between columns
          chart.barsPadding(-0.5);

          // set the padding between column groups
          chart.barGroupsPadding(2);

          // set the chart title
          chart.title("Sales & Expense");

          // set the titles of the axes
          chart.xAxis().title("Sales & Expense");
          chart.yAxis().title("Sales, 円");

          // set the container id
          chart.container("sales_expense");

          // initiate drawing the chart
          chart.draw();

        
      }   
  });


n = d.getMonth();

  var exp_request = [{"month":n, "year":2019}]
  // console.log(request);
  $.ajax({
      url: "https://jts-board.appspot.com/monthexpdonut", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(exp_request),
      success: function( data ) { 
        data =  JSON.parse(data);

        // create a pie chart and set the data
        var chart = anychart.pie(data);

        /* set the inner radius
        (to turn the pie chart into a doughnut chart)*/
        chart.innerRadius("30%");

        // set the chart title
        chart.title("Expense Precentage of Sales");

        // set the container id
        chart.container("expense_precentage");

        // initiate drawing the chart
        chart.draw();
      
        
      }   
  });

  $.ajax({
      url: "https://jts-board.appspot.com/monthexp", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(exp_request),
      success: function( data ) { 
        data =  JSON.parse(data);
          console.log(data);
           $("#total_sale").html(data[0][1]+' 円')
           $("#total_expense").html(data[0][2]+' 円')
           $("#total_saving").html(data[0][3]+' 円')
      
      }   
  });

// create data
// var data =  JSON.parse(<?php // echo $repeaterData; ?>);  
// create a chart and set the data
chart = anychart.pie(<?php echo $repeaterData; ?>);

// set the container id
chart.container("expense_precentage1");

// initiate drawing the chart
chart.draw();

  $.ajax({
      url: "https://jts-board.appspot.com/monthexp", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(exp_request),
      success: function( data ) { 
        data =  JSON.parse(data);
          console.log(data);
           $("#total_sale").html(data[0][1]+' 円')
           $("#total_expense").html(data[0][2]+' 円')
           $("#total_saving").html(data[0][3]+' 円')
      
      }   
  });

  



  $.ajax({
      url: "https://jts-board.appspot.com/monthexpcolumn", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(exp_request),
      success: function( data ) { 
        data =  JSON.parse(data);
       
         // create data
        // var data = [
        //   ["John", 10000],
        //   ["Jake", 12000],
        //   ["Peter", 13000],
        //   ["James", 10000],
        //   ["Mary", 9000]
        // ];

        // create a chart
        var chart = anychart.column();

        // create a column series and set the data
        var series = chart.column(data);

        // set the chart title
        chart.title("Sale Vs Expense");

        // set the titles of the axes
        chart.xAxis().title("Salon");
        chart.yAxis().title("Amount, 円");

        // set the container id
        chart.container("sale_expense_column");

        // initiate drawing the chart
        chart.draw();

        
      }   
  });

  $(".show_expense_presentage").click(function(){
    y = d.getFullYear();
    mont  = $(this).attr("title");
    // alert(mont);
    var exp_request_click = [{"month":parseInt(mont), "year":y}]
    console.log(exp_request_click);
    $.ajax({
      url: "https://jts-board.appspot.com/monthexpdonut", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(exp_request_click),
      success: function( data ) { 
        data =  JSON.parse(data);
        console.log(data);
        // create a pie chart and set the data
        var chart = anychart.pie(data);

        /* set the inner radius
        (to turn the pie chart into a doughnut chart)*/
        chart.innerRadius("30%");

        // set the chart title
        chart.title("Expense Precentage of Sales");

        // set the container id
        chart.container("expense_precentage");

        // initiate drawing the chart
        chart.draw();
       
        
      }  

      // var $container = $("html,body");
      // var $scrollTo = $('.show_expense_presentage');

      // $container.animate({scrollTop: $scrollTo.offset().top - $container.offset().top + $container.scrollTop(), scrollLeft: 0},300); 
  });

  $.ajax({
      url: "https://jts-board.appspot.com/monthexp", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(exp_request_click),
      success: function( data ) { 
        data =  JSON.parse(data);
          console.log(data);
           $("#total_sale").html(data[0][1]+' 円')
           $("#total_expense").html(data[0][2]+' 円')
           $("#total_saving").html(data[0][3]+' 円')
      
      }   
  });

  



  $.ajax({
      url: "https://jts-board.appspot.com/monthexpcolumn", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(exp_request_click),
      success: function( data ) { 
        data =  JSON.parse(data);
       
         // create data
        // var data = [
        //   ["John", 10000],
        //   ["Jake", 12000],
        //   ["Peter", 13000],
        //   ["James", 10000],
        //   ["Mary", 9000]
        // ];

        // create a chart
        var chart = anychart.column();

        // create a column series and set the data
        var series = chart.column(data);

        // set the chart title
        chart.title("Sale Vs Expense");

        // set the titles of the axes
        chart.xAxis().title("Salon");
        chart.yAxis().title("Amount, 円");

        // set the container id
        chart.container("sale_expense_column");

        // initiate drawing the chart
        chart.draw();

        
      }   
  });



  });





  $.ajax({
      url: "https://jts-board.appspot.com/monthexp", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(request),
      success: function( data ) { 
        data =  JSON.parse(data);
       
          // console.log(data);
          // create a chart
          // map the data
          var data = anychart.data.set(data);
          var seriesData_1 = data.mapAs({x: 0, value: 1});
          var seriesData_2 = data.mapAs({x: 0, value: 2});

          // create a chart
          var chart = anychart.column();

          // create the first series, set the data and name
          var series1 = chart.column(seriesData_1);
          series1.name("Sales");

          // create the second series, set the data and name
          var series2 = chart.column(seriesData_2);
          series2.name("Expense");

          // set the padding between columns
          chart.barsPadding(-0.5);

          // set the padding between column groups
          chart.barGroupsPadding(2);

          // set the chart title
          chart.title("Column Chart: Sales & Expense");

          // set the titles of the axes
          chart.xAxis().title("Sales & Expense");
          chart.yAxis().title("Sales, 円");

          // set the container id
          chart.container("sales_expense");

          // initiate drawing the chart
          chart.draw();

        
      }   
  });

  $.ajax({
      url: "https://jts-board.appspot.com/monthexp", 
      type: "POST",
      contentType: "application/json",
      evalScripts: true,
      crossDomain: true,
      data: JSON.stringify(request),
      success: function( data ) { 
        data =  JSON.parse(data);
          // console.log(data);
          var html_data = '';
          $.each( data, function( key, value ) {
            n = d.getMonth();
            var cm = value[0].replace("月", "");
            if(cm <= n || cm >= 11){

             html_data += '<tr><td class="bg-light-cyan">'+addCommas(value[0])+'</td><td></td><td>'+addCommas(value[1])+' 円</td><td>'+addCommas(value[2])+'円</td><td>'+addCommas(value[3])+' 円</td></tr>';
            }else{
              html_data += '<tr><td class="bg-light-cyan">'+addCommas(value[0])+'</td><td>'+addCommas(value[1])+' 円</td><td></td><td>'+addCommas(value[2])+'円</td><td>'+addCommas(value[3])+' 円</td></tr>';
            }
            
          });
          $("#table_sale_expense").html(html_data)
      }   
  });






});


// anychart.onDocumentReady(function () {
    
//  // create line chart
//     var chart = anychart.line();

//     // set chart padding
//     chart.padding([10, 20, 5, 20]);

//     // turn on chart animation
//     chart.animation(true);

//     // turn on the crosshair
//     chart.crosshair(true);

//     // set chart title text settings
//     chart.title('Customers Repeater Monthly');

//     // set y axis title
//     chart.yAxis().title('Customers Number');

//     // create logarithmic scale
//     var logScale = anychart.scales.log();
//     logScale.minimum(1).maximum(45000);

//     // set scale for the chart, this scale will be used in all scale dependent entries such axes, grids, etc
//     chart.yScale(logScale);

//     // create data set on our data,also we can pud data directly to series
//     var dataSet = anychart.data.set([
//         ['11月', '102', '5', '0'],
//         ['12月', '123', '41', '4'],
//         ['01月', '127', '17', '2'],
//         ['02月', '139', '26', '1'],
//         ['03月', '141', '33 ', '1'],
//         ['04月', '152', '23', '0'],
//         ['05月', '71', '3', '0']
//     ]);

//     // map data for the first series,take value from first column of data set
//     var seriesData_1 = dataSet.mapAs({'x': 0, 'value': 1});

//     // map data for the second series,take value from second column of data set
//     var seriesData_2 = dataSet.mapAs({'x': 0, 'value': 2});

//     // map data for the third series, take x from the zero column and value from the third column of data set
//     var seriesData_3 = dataSet.mapAs({'x': 0, 'value': 3});

//     // temp variable to store series instance
//     var series;

//     // setup first series
//     series = chart.line(seriesData_1);
//     series.name('1 Time');
//     // enable series data labels
//     series.labels()
//             .enabled(true)
//             .anchor('left-bottom')
//             .padding(5);
//     // enable series markers
//     series.markers(true);

//     // setup second series
//     series = chart.line(seriesData_2);
//     series.name('2 Time');
//     // enable series data labels
//     series.labels()
//             .enabled(true)
//             .anchor('left-bottom')
//             .padding(5);
//     // enable series markers
//     series.markers(true);

//     // setup third series
//     series = chart.line(seriesData_3);
//     series.name('3 Time');
//     // enable series data labels
//     series.labels()
//             .enabled(true)
//             .anchor('left-bottom')
//             .padding(5);
//     // enable series markers
//     series.markers(true);

//     // turn the legend on
//     chart.legend()
//             .enabled(true)
//             .fontSize(13)
//             .padding([0, 0, 20, 0]);

//     // set container for the chart and define padding
//     chart.container('container');
//     // initiate chart drawing
//     chart.draw();

// });

anychart.onDocumentReady(function () {
    // create line chart
    var chart = anychart.line();

    // set chart padding
    chart.padding([10, 20, 5, 20]);

    // turn on chart animation
    chart.animation(true);

    // turn on the crosshair
    chart.crosshair(true);

    // set chart title text settings
    chart.title('Customers Activity during the Week');

    // set y axis title
    chart.yAxis().title('Activity occurrences');

    // create logarithmic scale
    var logScale = anychart.scales.log();
    logScale.minimum(1)
            .maximum(45000);

    // set scale for the chart, this scale will be used in all scale dependent entries such axes, grids, etc
    chart.yScale(logScale);

    // create data set on our data,also we can pud data directly to series
    var dataSet = anychart.data.set([
        ['Monday', '1120', '4732', '15176'],
        ['Tuesday', '720', '3689', '18910'],
        ['Wednesday', '404', '3904', '19004'],
        ['Thursday', '190', '754', '22233'],
        ['Friday', '15', '187 ', '922'],
        ['Saturday', '10', '45', '534'],
        ['Sunday', '7', '61', '343']
    ]);

    // map data for the first series,take value from first column of data set
    var seriesData_1 = dataSet.mapAs({'x': 0, 'value': 1});

    // map data for the second series,take value from second column of data set
    var seriesData_2 = dataSet.mapAs({'x': 0, 'value': 2});

    // map data for the third series, take x from the zero column and value from the third column of data set
    var seriesData_3 = dataSet.mapAs({'x': 0, 'value': 3});

    // temp variable to store series instance
    var series;

    // setup first series
    series = chart.line(seriesData_1);
    series.name('Review about the product');
    // enable series data labels
    series.labels()
            .enabled(true)
            .anchor('left-bottom')
            .padding(5);
    // enable series markers
    series.markers(true);

    // setup second series
    series = chart.line(seriesData_2);
    series.name('Comment blog posts');
    // enable series data labels
    series.labels()
            .enabled(true)
            .anchor('left-bottom')
            .padding(5);
    // enable series markers
    series.markers(true);

    // setup third series
    series = chart.line(seriesData_3);
    series.name('Email delivery support');
    // enable series data labels
    series.labels()
            .enabled(true)
            .anchor('left-bottom')
            .padding(5);
    // enable series markers
    series.markers(true);

    // turn the legend on
    chart.legend()
            .enabled(true)
            .fontSize(13)
            .padding([0, 0, 20, 0]);

    // set container for the chart and define padding
    chart.container('container');
    // initiate chart drawing
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
          items : 2,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [991,3],
          itemsMobile : [767,2],
          itemsMobile : [479,1]
        });

        $("#owl-demo1").owlCarousel({
          pagination: false,
          autoPlay: false,
          items : 1,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [991,3],
          itemsMobile : [767,2],
          itemsMobile : [479,1]
        });

        $("#owl-demo2").owlCarousel({
          pagination: false,
          autoPlay: false,
          items : 1,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [991,3],
          itemsMobile : [767,2],
          itemsMobile : [479,1]
        });

      $("#owl-demo3").owlCarousel({
          pagination: false,
          autoPlay: false,
          items : 3,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [991,3],
          itemsMobile : [767,2],
          itemsMobile : [479,1]
        });

      $("#owl-demo4").owlCarousel({
          pagination: false,
          autoPlay: false,
          items : 1,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [991,3],
          itemsMobile : [767,2],
          itemsMobile : [479,1]
        });


      });
function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
       
    </script>

</body>

</html>

      