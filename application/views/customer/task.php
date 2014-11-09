<body class="fixed-navigation">
<div id="wrapper">

	<nav class="navbar-default navbar-static-side" role="navigation">
		<div class="sidebar-collapse">
			<ul class="nav" id="side-menu">
				<li class="nav-header">
					<div class="dropdown profile-element"> <span>
						<img alt="image" class="img-circle" src="img/profile_small.jpg" />
						 </span>
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $_SESSION["user_info"][0]['username']?></strong>
						 </span> <span class="text-muted text-xs block">Customer<b class="caret"></b></span> </span> </a>
						<ul class="dropdown-menu animated fadeInRight m-t-xs">
							<li><a href="/registrationSelectInfo">Profile</a></li>
							<li><a href="/mailbox">Messages box</a></li>
							<li class="divider"></li>
							<li><a href="/logout">Logout</a></li>
						</ul>
					</div>
					<div class="logo-element">
						FITPA
					</div>

				</li>
				<li class="active">
					<a href="#"><i class="fa fa-th-flask"></i> <span class="nav-label">Dashboard</span></a>
				</li>
				  <li>
                        <a href="mailbox.html"><i class="fa fa-envelope"></i> <span class="nav-label">Messages box</span><span class="label label-warning pull-right">0/1</span></a>
                        <ul class="nav nav-second-level">
                           <li><a href="/mailbox">Inbox</a></li>
							<li><a href="/maildetail">Messages view</a></li>
							<li><a href="/mailcompose">Send Messages</a></li>
                        </ul>
                    </li>
				<li>
					<a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">Customer</span></a>
					<ul class="nav nav-second-level">
						<li><a href="/registrationSelectInfo">Profile</a></li>
						<li><a href="/task">Tasks</a></li>
						<li><a href="/task">Task detail</a></li>
						<li><a href="/calendar">Routine Calendar</a></li>
					</ul>
				</li>
			</ul>

		</div>
	</nav>

	<div id="page-wrapper" class="gray-bg sidebar-content">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
  
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Welcome to fitPA Admin Theme.</span>
                </li>
				<li>
				<a href=¡±http://www.facebook.com/sharer.php?u='USER'&t='TITLE'¡± target=¡±blank¡±>Facebook integration</a>
				</li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a7.jpg">
                                </a>
                                <div>
                                    <small class="pull-right">46h ago</small>
                                    <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a4.jpg">
                                </a>
                                <div>
                                    <small class="pull-right text-navy">5h ago</small>
                                    <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/profile.jpg">
                                </a>
                                <div>
                                    <small class="pull-right">23h ago</small>
                                    <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                    <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="mailbox.html">
                                    <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="glyphicon glyphicon-shopping-cart"></i>  
					</a>
                 
                </li>


                <li>
                    <a href="/logout">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

        </nav>
        </div>
		<div class="row wrapper border-bottom white-bg page-heading">
			<div class="col-sm-4">
				<h2>Task list</h2>
				<ol class="breadcrumb">
					<li>
						<a href="index.html">Home</a>
					</li>
					<li class="active">
						<strong>Task list</strong>
					</li>
				</ol>
			</div>
		</div>
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInUp">

                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>All tasks assigned to this account</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row m-b-sm m-t-sm">
                                <div class="col-md-1">
                                    <button type="button" id="loading-example-btn" class="btn btn-white btn-sm" ><i class="fa fa-refresh"></i> Refresh</button>
                                </div>
                                <div class="col-md-11">
                                    <div class="input-group"><input type="text" placeholder="Search" class="input-sm form-control"> <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-primary"> Go!</button> </span></div>
                                </div>
                            </div>

                            <div class="project-list">

                                <table class="table table-hover">
                                    <tbody>
                                    <?php foreach ($taskInfo as $v) {?>
									<tr>
                                        <td class="project-status">
                                            <span class="label label-primary">
											<?php if($v['status'] == 'complete') { ?>
													Completed
											<?php	} ?>
											<?php	if($v['status'] == 'partially') {?>
												    Partially Complete
											<?php	}?>
											<?php	if($v['status'] == 'not') {?>
													Not Completed
											<?php	} ?>
											
											</span>
                                        </td>
                                        <td class="project-title">
                                            <a href="/taskEdit?trackprogressid=<?php echo $v['trackprogressid'];?>"><?php echo $v['comments'] ?></a>
                                        </td>
                              
                                        <td class="project-actions">
                                            <a href="/taskEdit?trackprogressid=<?php echo $v['trackprogressid'];?>" class="btn btn-white btn-sm"><i class="fa fa-folder"></i> View </a>
                                        </td>
                                    </tr>
									<?php	} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		</div>
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2015
            </div>
        </div>

        </div>
        </div>
    </div>
<script src="js/main.js"></script>
</body>