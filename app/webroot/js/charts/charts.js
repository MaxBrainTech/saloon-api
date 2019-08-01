
/******* chart first *********/

Pizza.init(document.body, {
  donut: false,
  donut_inner_ratio: 0.4, // between 0 and 1
  percent_offset: 30, // relative to radius
  /*stroke_color: '#333',*/
  stroke_width: 0,
  show_percent: false, // show or hide the percentage on the chart.
  animation_speed: 500,
  animation_type: 'elastic' // options: backin, backout, bounce, easein, 
});


/******* chart Second *********/

$(function() {
  $("#bars li .bar").each( function( key, bar ) {
    var percentage = $(this).data('percentage');
    
    $(this).animate({
      'height' : percentage + '%'
    }, 1000);
  });
});



/******* chart Third *********/

var myChart = {
  "type": "bar",
  "title": {
    "text": "Change me please!"
  },
  "plot": {
    "value-box": {
      "text": "%v"
    },
    "tooltip": {
      "text": "%v"
    }
  },
  "legend": {
    "toggle-action": "hide",
    "header": {
      "text": "Legend Header"
    },
    "item": {
      "cursor": "pointer"
    },
    "draggable": true,
    "drag-handler": "icon"
  },
  "scale-x": {
    "values": [
      "Mon",
      "Wed",
      "Fri"
    ]
  },
  "series": [
    {
      "values": [
        3,
        6,
        9
      ],
      "text": "apples",
      "palette": 0,
      "visible": true
    },
    {
      "values": [
        1,
        4,
        3
      ],
      "text": "oranges",
      "palette": 1
    }
  ]
};
zingchart.render({
  id: "myChart",
  data: myChart,
  height: "480",
  width: "100%"
});