<?php $url_segment = $this->uri->segment(2);?>

<!DOCTYPE html>
<head>
        <meta charset="UTF-8">
        <title><?php if(isset($title)) echo $title; ?></title>
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


    </head>

<body class="skin-black">
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
                                <li class="user-header">
                                    <img src="<?php echo $template_url; ?>/img/avatar3.png" class="img-circle" alt="User Image">
                                    <p>
                                         Administrator
                                        <small>Lunch Matcher Administrator</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                              
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
                            <a href="#">
                                <i class="fa fa-users"></i> <span>Members</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                             <ul class="treeview-menu">
                                <li><a href="<?php echo base_url(); ?>admin/users/manage_user" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Users</a></li>
                                <li><a href="<?php echo base_url(); ?>admin/users/manage_drivers" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Drivers</a></li>                            </ul>
                        </li>
                        <li <?php if($url_segment == 'invoices') {?>class="active" <?php } ?>>
                            <a href="<?php echo base_url();?>admin/invoices">
                             <i class="fa fa-laptop"></i>
                                <span>Invoices</span>                                
                            </a>                          
                        </li>
                        <li <?php if($url_segment == 'delivery_jobs') {?>class="active treeview" <?php } else { ?> class="treeview" <?php } ?>>
                            <a href="#">
                                <i class="fa fa-laptop"></i>
                                <span>Delivery Jobs</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">                              
                                <li><a href="<?php echo base_url();?>admin/delivery_jobs" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>All Jobs</span><small class="badge pull-right bg-grey">110</small></a></li>
                                <li><a href="<?php echo base_url();?>admin/delivery_jobs/new_jobs" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>New Jobs</span><small class="badge pull-right bg-green">3</small></a></li>
                                <li><a href="<?php echo base_url();?>admin/delivery_jobs/progress" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Jobs In Progress</span><small class="badge pull-right bg-orange">3</small></a></li>
                                <li><a href="<?php echo base_url();?>admin/delivery_jobs/completed" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i><span>Completed Jobs</span><small class="badge pull-right bg-red">3</small></a></li>
                            </ul>
                        </li>
                       <li <?php if($url_segment == 'contacts') {?>class="active" <?php } ?>>
                            <a href="<?php echo base_url();?>admin/contacts">
                             <i class="fa fa-laptop"></i>
                                <span>Contact Us</span>                                
                            </a>                          
                        </li>
                         
                        <li <?php if($url_segment == 'configuration') {?>class="active treeview" <?php }else { ?> class="treeview" <?php } ?>>
                            <a href="#">
                                <i class="fa fa-cog lg"></i> <span>Configuration</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url();?>admin/configuration" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Settings</a></li>
                                
                            </ul>
                        </li>
                        
                        <li <?php if($url_segment == 'app') {?>class="active treeview " <?php }else { ?> class="treeview" <?php } ?>>
                            <a href="<?php echo base_url();?>admin/app">
                             <i class="fa fa-pencil fa-fw"></i>
                                <span>App Customization</span>                                
                            </a> 
                             <ul class="treeview-menu">                              
                                <li><a href="<?php echo base_url(); ?>admin/app/home_slideshow" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Home Slideshow</span></a></li>
                                <li><a href="<?php echo base_url(); ?>admin/app/vehicles" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Vehicle Models</span> </a></li>
                                 
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
						case "delivery_jobs":
							$controller = 'Hauls';
							break;
						case "contacts":
							$controller = 'Contact Us';
							break;
					}
					
					switch($method){
						case "index" :
							$method = $controller;
							break;
						case "manage_user":
							$method = 'Manage user';
							break;
						case "manage_drivers":
							$method = 'Manage drivers';
							break;
						case "new_jobs":
							$method = 'New hauls';
							break;
						case "progress":
							$method = 'In progress';
							break;
						case "home_slideshow":
							$method = 'Home page slideshow';
							break;
						case "vehicle_details":
							$method = 'Vehicle Details';
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