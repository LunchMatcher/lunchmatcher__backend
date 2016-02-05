<?php $url_segment = $this->uri->segment(2);?>

<!DOCTYPE html>
<head>
        <meta charset="UTF-8">
        <title><?php  echo $TITLE?$TITLE:'LunchMatcher'; ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo $template_url; ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <!-- font Awesome -->
        <link href="<?php echo $template_url; ?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Ionicons -->
        <link href="<?php echo $template_url; ?>/css/ionicons.min.css" rel="stylesheet" type="text/css">
        <!-- Morris chart -->
        <link href="<?php echo $template_url; ?>/css/morris/morris.css" rel="stylesheet" type="text/css">
        <!-- jvectormap -->
        <link href="<?php echo $template_url; ?>/css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css">
        <!-- Date Picker -->
        <link href="<?php echo $template_url; ?>/css/datepicker/datepicker3.css" rel="stylesheet" type="text/css">
        <!-- Daterange picker -->
        <link href="<?php echo $template_url; ?>/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="<?php echo $template_url; ?>/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css">
        <!-- Theme style -->
        <link href="<?php echo $template_url; ?>/css/AdminLTE.css" rel="stylesheet" type="text/css">
		


        <link href="<?php echo base_url('assets/css'); ?>/adminstyle.css" rel="stylesheet" type="text/css">
		  <script src="<?php echo base_url('assets/js'); ?>/jquery.js"></script>
		  
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    <script src="<?php echo base_url('assets');?>/js/jquery.bxslider.min.js"></script>
	
<link href="<?php echo base_url('assets');?>/css/jquery.bxslider.css" rel="stylesheet" />
  <script type="application/javascript">
  
  	var h	=	<?php echo date('H');?>;
	var m	=	<?php echo date('i');?>;
	var s	=	<?php echo date('s');?>;
		
    function startTime() {		
		var AMPM = 'AM';
		h1 = checkTime(h);
		m1 = checkTime(m);
		s1 = checkTime(s);
		if(h1 > 12){
			h1 = h1-12;
			AMPM = 'PM';
		}
		document.getElementById('clock').innerHTML = h1+":"+m1+":"+s1+" "+AMPM;
		var t = setTimeout(function(){
			s = s+1;
			if(s>59){
				m= m+1;
				s=1;
			}
			if(m>59){
				h= h+1;
				m=1;
			}
			if(h>23){
				h= 0;
			}
				
			startTime();
		},1000);
	}
	

	function checkTime(i) {
		if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
		return i;
	}
</script>  
    </head>

<body class="skin-black" onload="startTime()">
		<header class="header">
            <a href="<?php echo base_url().'admin/home'; ?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <?php // if(isset($site_name)) {  echo $site_name; } else echo 'Logo OR Title';?>
                <img src="<?php echo base_url('assets/images'); ?>/h.png" />
            </a>
            
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
            	
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
               <div id="clock" title="Server current time:<?php echo date_default_timezone_get();?>"></div>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                       
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>Administrator <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-black">
                                    <img src="<?php echo $template_url; ?>/img/avatar5.png" class="img-circle" alt="User Image">
                                    <p>
                                         Administrator
                                        <small>Lunch Matcher Administrator</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                               <!-- <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li>-->
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                   <!-- <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>-->
                                    <div class="pull-right">
                                        <a href="<?php echo site_url('admin/home/logout'); ?>" class="btn btn-info btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

<div class="wrapper row-offcanvas row-offcanvas-left">

		<aside class="left-side sidebar-offcanvas" style="min-height: 1519px;">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                   
                    
                   <div class="user-panel">
                        
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li <?php if($url_segment == 'home') {?>class="active" <?php } ?>>
                            <a href="<?php echo base_url(); ?>admin/home">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li <?php if($url_segment == 'users') {?>class="active treeview" <?php }else { ?> class="treeview" <?php } ?>>
                            <a href="javascript:void(0);">
                              <i class="fa fa-users"></i> <span>User Management </span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                               <li><a href="<?php echo base_url(); ?>admin/users/lists" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> User list</a></li>
							
                            </ul>
                        </li>
                        <li <?php if($url_segment == 'availability') {?>class="active treeview" <?php }else { ?> class="treeview" <?php } ?>>
                            <a href="javascript:void(0);">
                                <i class="fa fa-dashboard"></i> <span>Availability Management</span>
                            </a>
                            <ul class="treeview-menu">
                               <li><a href="<?php echo base_url(); ?>admin/availability/lists" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Availability</a></li>
							 	<li><a href="<?php echo base_url(); ?>admin/availability/meetings" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Meeting scheduled</a></li>
                            </ul>
                        </li>                       
                                             
						<li <?php if($url_segment == 'points') {?>class="active treeview" <?php }else { ?> class="treeview" <?php } ?>>
                            <a href="javascript:void(0);">
                              <i class="fa fa-cube"></i> <span>Points Management</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                               <li><a href="<?php echo base_url(); ?>admin/points/lists" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Points list</a></li>
							 	<li><a href="<?php echo base_url(); ?>admin/points/purchase" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Point For Purchase</a></li>
                            </ul>
                        </li>
						
						
						<li <?php if($url_segment == 'venue') {?>class="active treeview" <?php }else { ?> class="treeview" <?php } ?>>
                            <a href="javascript:void(0);">
                              <i class="fa fa-globe"></i> <span>Venue Management</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                               <li><a href="<?php echo base_url(); ?>admin/venue/lists" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Today Venue list</a></li>
                                <li><a href="<?php echo base_url(); ?>admin/venue/venuelist" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Venue All list</a></li>
							
                            </ul>
                        </li>
						 
						 
						 
                        <li <?php if($url_segment == 'configuration') {?>class="active treeview" <?php }else { ?> class="treeview" <?php } ?>>
                            <a href="javascript:void(0);">
                                <i class="fa fa-cog lg"></i> <span>General Settings </span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url('admin/configuration');?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Settings</a></li>
                                <li><a href="<?php echo base_url('admin/configuration/changePassowrd');?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Change Password</a></li>
								<li><a href="<?php echo base_url('admin/configuration/emailconfig');?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Email Config</a></li>
                            </ul>
                        </li>
                        
                        
                        
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
            
            
            <aside class="right-side">
            
            <section class="content-header">
                <h1>
                    Lunch Matcher
                    <small>Control panel</small>
                </h1>
                <ol class="breadcrumb">
                	<?php
					$controller = $this->router->fetch_class();
					$method     = $this->router->fetch_method();
					
					switch($controller){
						case "app" :
							$controller = 'App customization';
							break;
						case "contacts":
							$controller = 'Contact Us';
							break;
					}
					
					switch($method){
						case "index" :
							$method = $controller;
							break;
						case "lists":
							$method = 'Manage'.' '.$controller;
							break;
						case "progress":
							$method = 'In progress';
							break;
						default :
							$method = str_replace('_',' ',$method);
					}
					
					?>
                    <li>
                    	<a href="<?php echo base_url().'admin/'.$this->router->fetch_class(); ?>"><i class="fa fa-dashboard"></i> 
							<?php echo ucwords($controller); ?>
                         </a>
                    </li>
                    <li class="active"><?php echo ucwords($method); ?></li>
                </ol>
            </section>
            
            <div id="status"></div>
            <?php if($this->session->flashdata('message')){ ?>
            <section class="alert alert-success">
             <?php echo $this->session->flashdata('message'); ?>
            </section>
            <?php } ?>
            
             <?php if($this->session->flashdata('error')){ ?>
            <section class="alert alert-danger">
             <?php echo $this->session->flashdata('error'); ?>
            </section>
            <?php } ?>
            
            <section class="content">