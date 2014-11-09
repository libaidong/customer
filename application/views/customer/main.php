<body class="fixed-navigation">
<div id="wrapper">
<?php if ($user == '' || $user == null){
echo $messages;
} else {
$_SESSION["user_info"] = $user;
}?>
<input id="userNameHid" type="hidden" value="<?php echo $user[0]['username']?>"/>
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
            <div class="sidebard-panel">
                <div>
                    <h4>Messages <span class="badge badge-info pull-right">16</span></h4>
                    <div class="feed-element">
                        <a href="#" class="pull-left">
                            <img alt="image" class="img-circle" src="img/a1.jpg">
                        </a>
                        <div class="media-body">
                            There are many variations of passages of Lorem Ipsum available.
                            <br>
                            <small class="text-muted">Today 4:21 pm</small>
                        </div>
                    </div>
                    <div class="feed-element">
                        <a href="#" class="pull-left">
                            <img alt="image" class="img-circle" src="img/a2.jpg">
                        </a>
                        <div class="media-body">
                            TIt is a long established fact that.
                            <br>
                            <small class="text-muted">Yesterday 2:45 pm</small>
                        </div>
                    </div>
                    <div class="feed-element">
                        <a href="#" class="pull-left">
                            <img alt="image" class="img-circle" src="img/a3.jpg">
                        </a>
                        <div class="media-body">
                            Many desktop publishing packages.
                            <br>
                            <small class="text-muted">Yesterday 1:10 pm</small>
                        </div>
                    </div>
                    <div class="feed-element">
                        <a href="#" class="pull-left">
                            <img alt="image" class="img-circle" src="img/a4.jpg">
                        </a>
                        <div class="media-body">
                            The generated Lorem Ipsum is therefore always free.
                            <br>
                            <small class="text-muted">Monday 8:37 pm</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                           
							<div class="ibox-content">
								<div id="calendar"></div>
							</div>
                        </div>
                    </div>
                </div>


                <div class="row">

                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-primary pull-right">Yesterday</span>
                                <h5>achievement</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins">....</h1>
                                <div class="stat-percent font-bold text-navy"><i class="fa fa-level-up"></i></div>
                                <small>perfect</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right">Today</span>
                                <h5>achievement</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins">...</h1>
                                <div class="stat-percent font-bold text-info"><i class="fa fa-level-up"></i></div>
                                <small>doing</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-warning pull-right">Tomorrow</span>
                                <h5>achievement</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins">...</h1>
                                <div class="stat-percent font-bold text-warning"><i class="fa fa-level-up"></i></div>
                                <small>plan</small>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="row">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>View assigned specialist info</h5>
							</div>

							<div class="ibox-content inspinia-timeline">

								<div class="timeline-item">
									<div class="row">
										<div class="col-xs-3 date">
											<i class="fa fa-briefcase"></i>
											6:00 am
											<br/>
											<small class="text-navy">2 hour ago</small>
										</div>
										<div class="col-xs-7 content no-top-border">
											<p class="m-b-xs"><strong>Meeting</strong></p>

											<p>Conference on the sales results for the previous year. Monica please examine sales trends in marketing and products.</p>

										</div>
									</div>
								</div>
								<div class="timeline-item">
									<div class="row">
										<div class="col-xs-3 date">
											<i class="fa fa-file-text"></i>
											7:00 am
											<br/>
											<small class="text-navy">3 hour ago</small>
										</div>
										<div class="col-xs-7 content">
											<p class="m-b-xs"><strong>Send documents to Mike</strong></p>
											<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since.</p>
										</div>
									</div>
								</div>
								<div class="timeline-item">
									<div class="row">
										<div class="col-xs-3 date">
											<i class="fa fa-coffee"></i>
											8:00 am
											<br/>
										</div>
										<div class="col-xs-7 content">
											<p class="m-b-xs"><strong>Coffee Break</strong></p>
											<p>
												Go to shop and find some products.
												Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's.
											</p>
										</div>
									</div>
								</div>
								<div class="timeline-item">
									<div class="row">
										<div class="col-xs-3 date">
											<i class="fa fa-phone"></i>
											11:00 am
											<br/>
											<small class="text-navy">21 hour ago</small>
										</div>
										<div class="col-xs-7 content">
											<p class="m-b-xs"><strong>Phone with Jeronimo</strong></p>
											<p>
												Lorem Ipsum has been the industry's standard dummy text ever since.
											</p>
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