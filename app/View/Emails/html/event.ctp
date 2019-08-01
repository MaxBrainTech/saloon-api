<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>JTS Board</title>

     
</head>

<body>
  <link href="https://fonts.googleapis.com/css?family=Sawarabi+Mincho" rel="stylesheet"> 
  <link href="https://fonts.googleapis.com/css?family=Arvo:400,400i,700,700i" rel="stylesheet">
  <style type="text/css">

  *, ::after, ::before {
      box-sizing: border-box;
  }

  body{padding: 0; margin:0; background-color: #141c30; font-family: 'Sawarabi Mincho', sans-serif; font-size: 13px;letter-spacing: 0.025em;}

  ul {list-style-type: none;padding: 0; margin-bottom: 0;}
  dl, ol, ul { margin-top: 0; margin-bottom: 1rem;}
  a {color: #e7c240; -webkit-transition: all .3s; -moz-transition: all .3s;-o-transition: all .3s;transition: all .3s; outline: 0 none; text-decoration: none;}
  img {max-width: 100%;border-style: none; height: auto; width: auto; vertical-align: middle;}

  .mb-100{margin-bottom: 100px;}
  .mb-80{margin-bottom: 80px;}
  .mb-60{margin-bottom: 60px;}
  .mb-50{margin-bottom: 50px;}
  .mb-40{margin-bottom: 40px;}
  .mb-30{margin-bottom: 30px;}
  .mb-20{margin-bottom: 20px;}
  .mb-15{margin-bottom: 15px;}
  .mb-10{margin-bottom: 10px;}
  .mb-5{margin-bottom: 5px;}
  .mb-0{margin-bottom: 0 !important;}


  .ml-100{margin-left: 100px;}
  .ml-80{margin-left: 80px;}
  .ml-60{margin-left: 60px;}
  .ml-50{margin-left: 50px;}
  .ml-40{margin-left: 40px;}
  .ml-30{margin-left: 30px;}
  .ml-20{margin-left: 20px;}
  .ml-15{margin-left: 15px;}
  .ml-10{margin-left: 10px;}
  .ml-5{margin-left: 5px;}
  .ml-0{margin-left: 0 !important;}

  .mr-100{margin-right: 100px;}
  .mr-80{margin-right: 80px;}
  .mr-60{margin-right: 60px;}
  .mr-50{margin-right: 50px;}
  .mr-40{margin-right: 40px;}
  .mr-30{margin-right: 30px;}
  .mr-20{margin-right: 20px;}
  .mr-15{margin-right: 15px;}
  .mr-10{margin-right: 10px;}
  .mr-5{margin-right: 5px;}
  .mr-0{margin-right: 0 !important;}

  .mt-100{margin-top: 100px;}
  .mt-80{margin-top: 80px;}
  .mt-60{margin-top: 60px;}
  .mt-50{margin-top: 50px;}
  .ml-40{margin-top: 40px;}
  .mt-30{margin-top: 30px;}
  .mt-20{margin-top: 20px;}
  .mt-15{margin-top: 15px;}
  .mt-10{margin-top: 10px;}
  .mt-5{margin-top: 5px;}
  .mt-0{margin-top: 0 !important;}
  
  .btn { display: inline-block; font-weight: 400; text-align: center; white-space: nowrap; vertical-align: middle; -webkit-user-select: none; -moz-user-select: none;
      -ms-user-select: none; user-select: none; border: 1px solid transparent; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem;
       transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;}

  .btn{background: #e7c240;border: none;color: #fff;padding: 10px 30px;font-size: 0.85rem; transition: all .3s; -webkit-transition: all .3s; -moz-transition: all .3s;-o-transition: all .3s; border-radius: 2px;-moz-border-radius: 2px -webkit-border-radius: 2px;}
    .btn-1 {font-size: 1.75rem; width: 100%;}
  .btn:hover, .btn:focus {background: #281e05; color: #fff; outline: 0; box-shadow: 0 0 0 0.05rem rgba(40,30,5,.20); -webkit-box-shadow: 0 0 0 0.05rem rgba(40,30,5,.20);} 

  .justify-content-center { -ms-flex-pack: center!important; justify-content: center!important;}
  .d-flex {display: -ms-flexbox!important; display: flex!important;}
  .align-self-end {-ms-flex-item-align: end!important; align-self: flex-end!important;}
  .justify-content-around {-ms-flex-pack: distribute!important; justify-content: space-around!important;}
  .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
    margin-bottom: .5rem; font-family: inherit; font-weight: 500; line-height: 1.2; color: inherit;    margin-top: 0;}
    .h1, h1 {font-size: 2.5rem;}
    .h2, h2 { font-size: 2rem;}
    .h3, h3 {font-size: 1.75rem;}
    .h4, h4 {font-size: 1.5rem;}
    .h5, h5 {font-size: 1.25rem;}
    .h6, h6 { font-size: 1rem;}
  
  .row {display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -15px;    margin-left: -15px;}

.col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
    position: relative;width: 100%; min-height: 1px; padding-right: 15px; padding-left: 15px;}
    @media (min-width: 768px){
      .col-md-8 {-ms-flex: 0 0 66.666667%; flex: 0 0 66.666667%; max-width: 66.666667%;}
      .col-md-4 { -ms-flex: 0 0 33.333333%; flex: 0 0 33.333333%; max-width: 33.333333%;}
    }
    .col-6 {-ms-flex: 0 0 50%;flex: 0 0 50%; max-width: 50%;}

    .d-none {display: none!important;}

    @media (min-width: 576px){
      .d-sm-block { display: block!important;}
    }

    .d-block {display: block!important;}

    @media (min-width: 576px){
      .d-sm-none {display: none!important;}
    }
    .text-center { text-align: center!important;}

    .container {width: 100%; max-width: 1170px; padding: 0 15px; margin: 0 auto;}
    .padding-panel {padding-top: 50px; padding-bottom: 50px;}
    .event-panel {background-color: #141c30;}
    .event-panel * {color: #fff;}
    .event-heading { font-size: 3rem; font-family: 'Arvo', serif; font-weight: bold; letter-spacing: 3px; margin-top: 0; text-align:center; }
    .event-first-box  {position: relative;}
    .event-heading-color {background: -webkit-linear-gradient(#dcbc4d, #9c7e2e); -webkit-background-clip: text;-webkit-text-fill-color: transparent;-webkit-text-stroke: 0.1rem white;text-stroke: 0.1rem white; }
    .event-heading-color1 {-webkit-text-stroke: 0.02rem white;text-stroke: 0.02rem white; }
    .event-first-box  {position: relative;}
    .event-image-face {position: absolute; top: 0; right: 0; width: 40%;}
    .event-venue {width: 270px; border-radius: 10px;-webkit-border-radius: 10px;-moz-border-radius: 10px;-o-border-radius: 10px; border:1px solid #fff; text-align: center; font-size: 1rem;
    padding: 15px 0;}
    .event-second-box .fa-circle {font-size: 0.75rem}
    .event-detail span{display: block;line-height: normal;}
    .event-detail span.year{font-size: 1.5rem}
    .event-detail .date{font-size: 3.5rem;line-height: 3.5rem;}
    .event-form {background-color: rgba(131,135,121,1)}
    /*.event-form-heading {color: #525252;}*/
    .event-text {position: relative; z-index: 1}

    .bullet-circle {position: relative; padding-left: 30px;}
    .bullet-circle:before {position: absolute; height: 12px; width: 12px; border-radius: 6px; background-color: #fff; content: ""; left: 0; top: 5px;}

    @media (max-width: 991.98px) {
    .mb-100{margin-bottom: 50px;}
    .mb-80{margin-bottom: 40px;}
    .mb-60{margin-bottom: 30px;}
    .mb-50{margin-bottom: 25px;}
    .mb-40{margin-bottom: 20px;}
    .mb-30{margin-bottom: 15px;}
    .mb-20{margin-bottom: 10px;}
    .mb-15{margin-bottom: 8px;}
    .mb-10{margin-bottom: 5px;}

    .ml-100{margin-left: 50px;}
    .ml-80{margin-left: 40px;}
    .ml-60{margin-left: 30px;}
    .ml-50{margin-left: 25px;}
    .ml-40{margin-left: 20px;}
    .ml-30{margin-left: 15px;}
    .ml-20{margin-left: 10px;}
    .ml-15{margin-left: 8px;}
    .ml-10{margin-left: 5px;}

    .mr-100{margin-right: 50px;}
    .mr-80{margin-right: 40px;}
    .mr-60{margin-right: 30px;}
    .mr-50{margin-right: 25px;}
    .mr-40{margin-right: 20px;}
    .mr-30{margin-right: 15px;}
    .mr-20{margin-right: 10px;}
    .mr-15{margin-right: 8px;}
    .mr-10{margin-right: 5px;}

    .mt-100{margin-top: 50px;}
    .mt-80{margin-top: 40px;}
    .mt-60{margin-top: 30px;}
    .mt-50{margin-top: 25px;}
    .ml-40{margin-top: 20px;}
    .mt-30{margin-top: 15px;}
    .mt-20{margin-top: 10px;}
    .mt-15{margin-top: 8px;}
    .mt-10{margin-top: 5px;}
    .event-heading {font-size: 2rem}
    .event-image-face {top: 100px;}
     }

    @media (max-width: 767.98px) {       
    .h1, h1 {font-size: 1.8rem;}
    .h2, h2 {font-size: 1.6rem;}
    .h3, h3 {font-size: 1.4rem;}
    .h4, h4 {font-size: 1.2rem;}
    .h5, h5 {font-size: 1rem;}
    .h6, h6 {font-size: 0.8rem;}
     p {font-size: 0.9em;}
    .padding-panel {padding-top: 25px;padding-bottom: 25px;}
    .event-heading {font-size: 1.4rem;}
    .event-heading-color {-webkit-text-stroke: 0.05rem white; text-stroke: 0.05rem white;}
    .event-image-face {right: unset;width: 100%;text-align: center;top: 0;}
    .event-image-face img{width: 300px;}
    .event-text {padding-top: 215px;}
    .event-panel h2 {font-size: 1.05rem;}
    .event-heading-color1 { -webkit-text-stroke: 0.01rem white; text-stroke: 0.01rem white;}
    }

  </style>
<?php  echo $content;?>


</body>
</html>