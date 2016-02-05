<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:title" content="Lunch Matcher">
<meta property="og:image" content="<?php echo base_url();?>assets/images/LM_logo.png" />  
<meta property="og:description" content="Use your lunch breaks to network with new, nearby professionals every day!. Why have lunch at your desk or with the same old colleagues? Make use of your lunch time to meet new like-minded professionals in your area, and discover interesting new places to eat! " />

<meta name="description" content="USE YOUR LUNCH BREAKS TO NETWORK WITH NEW, NEARBY PROFESSIONALS EVERY DAY!" />
<meta name="keywords" content="lunch matcher matching food restaurant" />

<title>Lunch Matcher</title>
<link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link href="<?php echo base_url();?>assets/css/theme.css" rel="stylesheet" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

</head>
<!--<div class="background_loader" style="display:none;">
	<img src="<?php /*?><?php echo base_url() ?><?php */?>assets/images/ploader.GIF" class="ajax_loader" width="75"/>
</div>-->
<body>
<header role="banner" id="top" class="navbar navbar-fixed-top cust_nav fx_top">
  <div class="container">
    <div class="navbar-header">
      <button data-target=".bs-navbar-collapse" data-toggle="collapse" type="button" class="navbar-toggle collapsed">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo site_url();?>"><img src="<?php echo base_url();?>assets/images/logo.png" class="img-responsive"></a>
    </div>
    <nav class="collapse navbar-collapse bs-navbar-collapse">
      
      <ul class="nav navbar-nav navbar-right cust_nav_lnk">
        <li><a class="navlink"  href="<?php echo base_url();?>#home" >HOME</a></li>
        <li><a class="navlink" rel="overvw" href="<?php echo site_url();?>#overvw" data-top="650">OVERVIEW</a></li>
        <li><a class="navlink" rel="feature" href="<?php echo site_url();?>#feature" data-top="1250">FEATURES</a></li>
        <li><a class="navlink" rel="gallery" href="<?php echo site_url();?>#gallery" data-top="1800">GALLERY</a></li>
        <li><a class="navlink active" rel="tutorial" href="<?php echo site_url();?>cms/tutorials" data-top="1800">TUTORIALS</a></li>
        <li><a class="navlink" rel="contact" href="<?php echo site_url();?>#contact" data-top="2800">CONTACT</a></li>
      </ul>
    </nav>
  </div>
</header>


<div class="overview" style="font-size: 18px;">

<div class="col-lg-9" style="color: #000!important; text-align:center;">
<h2>TUTORIALS</h2>
</div>
<?php echo $cms['content']?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-64690081-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>