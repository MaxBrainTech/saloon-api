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
    
  #allss,  #age, #expense_precentage, #expense_precentage1, #sale_expense_column,  #sales_expense, #visits, #repeater, #service_sales, #staff_sales, #staff_sale_salary , #reservation_plat_form , #weekly_reservation , #reservation_time {
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
    .sales-details li {padding: 10px; border:1px solid #eee; margin-bottom: 10px;}
    .sales-details li:last-child {margin-bottom: 0;}
    .sales-details li h6{color: #debd4e;}
    .sales-col-divide {padding: 5px;margin-top: 5px; background: #c4e0d8;}
    .sales-col-divide li{width: 50%; text-align: center;}
   @media (max-width: 767.98px) { 
      #allss, #age, #expense_precentage, #expense_precentage1, #sale_expense_column, #sales_expense, #visits, #repeater, #service_sales, #staff_sales, #staff_sale_salary, #reservation_plat_form, #weekly_reservation, #reservation_time {height: 240px;}
    }
 
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
            顧客分析
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
            来店客数分析
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
            売上・費用
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
	                    <h4 class="mb-20 text-center">売上と費用</h4>
                      <ul class="sales-details">
                        <li>
                          <h6>月別売上</h6>
                          <h3 id= "total_sale" class="mb-0">  </h3>
                        </li>
                        <li>
                          <h6>月別費用</h6>
                          <h3 id= "total_expense" class="mb-0"> </h3>
                        </li>
                        <li>
                          <h6>月別利益</h6>
                          <h3 id= "total_saving" class="mb-0"> </h3>
                        </li>
                      </ul>
	                </div>

	                <div class="item">
	                    <div id="sale_expense_column"></div>
	                </div>
	            </div>

	        </div>

	    </div>

    </div>
  
   <div class="">
                
                
                
                    
                        <div class="panel panel-default panel-table">
                            <div class="panel-heading">
                               月別売上と費用
                            </div>
                            <div class="panel-body">
                                <table class="table table-custom mb-0" style="width:100%">
                                    <thead class="text-center">
                                        <tr class="bg-light-cyan">
                                            <th>月</th>
                                            <th colspan="2" class="text-center">
                                              <div>売上</div>
                                              <ul class="sales-col-divide d-flex mb-0">
                                                <li>売上予測</li>
                                                <li>売上</li>
                                              </ul>
                                            </th>
                                            <th>費用</th>
                                            <th> 利益</th>
                                        </tr>
                                    </thead>
                                    <tbody id= 'table_sale_expense' class="text-center">
                                        
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
      text: "総客数: "+total.toString(),
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
  chart.title("顧客年齢層");
  
  // set container and draw chart
  chart.container("age");
  chart.draw();


// Customer Repeaters
  // create data set
 // dataSet = anychart.data.set([
 //    ["11月", 34, 0, 0, 0, 0, 2],
 //    ["12月", 38, 0, 3, 0, 0, 0],
 //    ["1月", 15, 0, 4, 0, 0, 0],
 //    ["2月", 26, 0, 1, 0, 0, 0],
 //    ["3月", 33, 0, 1, 0, 0, 0],
 //    ["4月", 21, 0, 1, 0, 1, 0],
 //    ["5月", 16, 0, 1, 0, 0, 0],
 //    ["6月", 14, 0, 1, 0, 0, 0],
 //    ["7月", 17, 0, 2, 0, 1, 0],
 //    ["8月", 24, 0, 1, 0, 2, 3],
 //    ["9月", 28, 0, 2, 0, 2, 0],
 //    ["10月", 31, 0, 3, 0, 1, 0]
    
 //  ]);
 var dataSet = anychart.data.set([
        ['11月', '102', '5', '0'],
        ['12月', '123', '41', '4'],
        ['01月', '127', '17', '2'],
        ['02月', '139', '26', '1'],
        ['03月', '141', '33 ', '1'],
        ['04月', '152', '23', '0'],
        ['05月', '71', '3', '0']
    ]);
  var service_data = '[{"name":"新規","color":"#92bfdb"},{"name":"再来","color":"#cccccc"},{"name":"3回目来店","color":"#ffb3d5"}]';
  service_data = $.parseJSON(service_data);

  console.log(service_data); 
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

  var yLabels = chart.yAxis().labels();
  yLabels.format("{%Value}人");

  // set the title of the chart
  chart.title("リピート客数");
  
  // set the title of the y-axis
  chart.yAxis().title("リ\nピ\nー\nト\n客\n数"); 
  chart.yAxis().title().rotation(0);

  // turn the legend on
  chart.legend(true);

  // set the container id for the chart
  chart.container("container");

  // initiate drawing the chart
  chart.draw();

  var request = [
  
  {"month":1, "year":2019},
  {"month":2, "year":2019},
  {"month":3, "year":2019},
  {"month":4, "year":2019},
  {"month":5, "year":2019},
  {"month":6, "year":2019},
  {"month":7, "year":2019},
  {"month":8, "year":2019},
  {"month":9, "year":2019},
  {"month":10, "year":2019},
  {"month":11, "year":2019},
  {"month":12, "year":2019}]
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
       
          // console.log(month_visits);
          // create a chart
          var chart = anychart.area();

          // create an area series and set the data
          var series = chart.area(month_visits);

          var yLabels = chart.yAxis().labels();
          yLabels.format("{%Value}人");

          // set scale mode
          chart.xScale().mode('continuous');
          
          // set the chart title
          chart.title("");

          // set the titles of the axes
          chart.xAxis().title("");
          chart.yAxis().title("来\n店\n客\n数");
          chart.yAxis().title().rotation(0);

          // set the container id
          chart.container("visits");

          // initiate drawing the chart
          chart.draw();
            

        
      }   
  });

  

  var request = [
  
  {"month":1, "year":2019},
  {"month":2, "year":2019},
  {"month":3, "year":2019},
  {"month":4, "year":2019},
  {"month":5, "year":2019},
  {"month":6, "year":2019},
  {"month":7, "year":2019},
  {"month":8, "year":2019},
  {"month":9, "year":2019},
  {"month":10, "year":2019},
  {"month":11, "year":2019},
  {"month":12, "year":2019}]
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
          series1.name("売上");

          // create the second series, set the data and name
          var series2 = chart.column(seriesData_2);
          series2.name("費用");

          // set the padding between columns
          chart.barsPadding(-0.5);

          // set the padding between column groups
          chart.barGroupsPadding(2);

          // set the chart title
          chart.title("売上・費用");


          // adjusting labels
          var yTicks = chart.yScale().ticks();
          yTicks.interval(2000000);
          var yLabels = chart.yAxis().labels();
          yLabels.format("{%value}{scale: (1)(1000)(1000)(1000)|(k)(m)(M)(B)}");

          // force labels to hide value, if the previous one is too big, set text position to center
          var xAxis = chart.xAxis();
          xAxis.overlapMode(false);
          
          // var yScale = chart.yScale();
          // yScale.minimum(500000);
          // yScale.maximum(20000000);
          // var ticks = chart.yScale().ticks();
          // ticks.interval(100000);

          // // set function to format y axis labels
          // var yLabels = chart.yAxis(0).labels();
          // yLabels.format("{%value}万円");
          // set y axis title
          // var yTitle = chart.yAxis(0).title();
          // yTitle.text("Revenue in 円");

          // adjust additional axis
          // var yAxis1 = chart.yAxis(1);
          // yAxis1.orientation("right");
          // yAxis1.title("Revenue in Euros");

          // formats labels of additional axis
          // var yLabels1 = chart.yAxis(1).labels();
          // yLabels1.format("\u20ac{%value}{scale:(113e-2)|( )}");
          


          // set the titles of the axes
          chart.xAxis().title("売上・費用");
          chart.yAxis().title("売\n上\n（円）");
          chart.yAxis().title().rotation(0);

          // set the container id
          chart.container("sales_expense");

          // initiate drawing the chart
          chart.draw();

        
      }   
  });


n = d.getMonth();
// alert(n);
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
        chart.title("利益率");

        // set the container id
        chart.container("expense_precentage");

        // initiate drawing the chart
        chart.draw();
      
        
      }   
  });

// create data
// var data =  JSON.parse(<?php // echo $repeaterData; ?>);  
// create a chart and set the data
chart = anychart.pie(<?php echo $repeaterData;?>);

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
           $("#total_sale").html(addCommas(data[0][1])+' 円')
           $("#total_expense").html(addCommas(data[0][2])+' 円')
           $("#total_saving").html(addCommas(data[0][3])+' 円')
      
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
       console.log(data);
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
        chart.title("売上と費用");

        // set the titles of the axes
        chart.xAxis().title("サロン");
        chart.yAxis().title("円");
          chart.yAxis().title().rotation(0);

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
        chart.title("利益率");

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
           $("#total_sale").html(addCommas(data[0][1])+' 円')
           $("#total_expense").html(addCommas(data[0][2])+' 円')
           $("#total_saving").html(addCommas(data[0][3])+' 円')
      
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
        chart.title("売上と費用");

        // set the titles of the axes
        chart.xAxis().title("サロン");
        chart.yAxis().title("円");

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
          series1.name("売上");

          // create the second series, set the data and name
          var series2 = chart.column(seriesData_2);
          series2.name("費用");

          // set the padding between columns
          chart.barsPadding(-0.5);

          // set the padding between column groups
          chart.barGroupsPadding(2);

          // set the chart title
          chart.title("");

          // set the titles of the axes
          chart.xAxis().title("売上・費用");
          chart.yAxis().title("売\n上\n（円）");
          chart.yAxis().title().rotation(0);
          
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

      