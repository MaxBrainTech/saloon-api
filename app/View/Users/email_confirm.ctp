 <style>
  #age, #visits, #repeater, #service_sales, #staff_sales, #staff_sale_salary , #reservation_plat_form , #weekly_reservation , #reservation_time {
    width: 100%;
    height: 400px;
    margin: 0;
    padding: 0;
}</style>

<div class="page-title">
    <h4 class="">Dashboard</h4>
</div>
<div class="main-content dashboard-chart">

    <div class="row">
        <div class="col-sm-4">
            <div class="dashboard-block first">
                <div class="row"> 
                    <div class="col-lg-7">
                        <h3><?php echo $customerCount; ?></h3>
                        <h6>Total Customer</h6>
                        
                    </div>
                    <div class="col-lg-5">
                        <i class="far fa-file-alt"></i>
                    </div>   
                </div>
            </div>
        </div>
        <div class="col-sm-4">                       
            <div class="dashboard-block second">
                <div class="row"> 
                    <div class="col-lg-7">
                        <h3><?php echo $upcomingReservationCount; ?></h3>
                        <h6>Up Coming Reservations</h6>
                        <?php /* <p>Other hand, we denounce</p> */ ?>
                    </div>
                    <div class="col-lg-5">
                        <i class="far fa-eye"></i>
                    </div>   
                </div>
            </div>
        </div>
        <div class="col-sm-4 ">                       
            <div class="dashboard-block third">
                <div class="row"> 
                    <div class="col-lg-7">
                        <h3><?php echo $reservationUnReadCount; ?></h3>
                        <h6>New Messages</h6>
                       
                    </div>
                    <div class="col-lg-5">
                        <i class="far fa-envelope"></i>
                    </div>   
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Customers
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div id="age"></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div id="visits"></div>
                </div>
                <div class="col-lg-4 col-md-6">
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
            <div class="row">
                <div class="col-lg-4 col-md-6">

                    <div id="service_sales"></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div id="staff_sales"></div>
                </div>
                <div class="col-lg-4 col-md-6">
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
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div id="reservation_plat_form"></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div id="weekly_reservation"></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div id="reservation_time"></div>
                </div>
            </div>
        </div>
    </div>

     <div class="panel panel-default">
        <div class="panel-heading">
            Sales and Payments
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div id="reservation_plat_form"></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div id="weekly_reservation"></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div id="reservation_time"></div>
                </div>
            </div>
        </div>
    </div>

</div>



    <script type="text/javascript">
        $(document).ready(function () {
            $('.anychart-credits').hide();
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });
        });
    </script>

<script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js?hcode=a0c21fc77e1449cc86299c5faa067dc4"></script>


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


