
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
    
  #days, #months, #years, #weeks, #allsss, #allss, #staff_sales, #staff_sale_salary , #reservation_plat_form , #weekly_reservation , #reservation_time {
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
<link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css?hcode=be5162d915534272a57d0bb781d27f2b" rel="stylesheet" type="text/css">
  <link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css?hcode=be5162d915534272a57d0bb781d27f2b" rel="stylesheet" type="text/css">
 
<body>


<div class="main-content dashboard-chart">
   
  <div class="filter-tab">
    <ul class="filter-date-tab">
      <li><?php echo $this->Html->link("Analytic", array('controller' => 'users','action' => 'analytics', $user_id ), array('escape' => false)); ?></li>
      <li><?php echo $this->Html->link("Prediction", array('controller' => 'users','action' => 'predictions', $user_id ), array('escape' => false)); ?></li>
      
    </ul>
  </div>


 <div class="panel panel-default">
        <div class="panel-heading">
            Total Sales
        </div>
        <div class="panel-body">
            <div id="owl-demo" class="owl-carousel">
                <div class="item">
                    <div id="days"></div>
                </div>
                
                <div class="item">
                    <div id="weeks"></div>
                </div>
                <div class="item">
                    <div id="months"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            New Total Sales
        </div>
        <div class="panel-body">
            <div id="owl-demo1" class="owl-carousel">
                <div class="item">
                    <div id="allsss"></div>
                </div>
               
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            sfd Total Sales
        </div>
        <div class="panel-body">
            <div id="owl-demo2" class="owl-carousel">
                <div class="item">
                    <div id="allss"></div>
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
       // 'anychart-base.min',
    ));
    ?>

<script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js?hcode=be5162d915534272a57d0bb781d27f2b"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js?hcode=be5162d915534272a57d0bb781d27f2b"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js?hcode=be5162d915534272a57d0bb781d27f2b"></script>
  <link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css?hcode=be5162d915534272a57d0bb781d27f2b" type="text/css" rel="stylesheet">
  <link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css?hcode=be5162d915534272a57d0bb781d27f2b" type="text/css" rel="stylesheet">
  

<script>
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
    chart.container('allsss');
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
          items : 3,
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


      });

       
    </script>

</body>

</html>

      