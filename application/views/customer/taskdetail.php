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
				<h2>Task detail</h2>
				<ol class="breadcrumb">
					<li>
						<a href="index.html">Home</a>
					</li>
					<li class="active">
						<strong>Task detail</strong>
					</li>
				</ol>
			</div>
		</div>
           <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
            <div class="col-lg-9">
                <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="m-b-md">
                                        <a href="#" name="spe_complete" class="btn btn-white btn-xs pull-right" id="editTask">Edit Task</a>
                                        <h2>Test Task 1</h2>
                                    </div>
                                    <dl class="dl-horizontal">
                                        <dt>Status:</dt> <dd><span class="label label-primary">Active</span></dd>
                                    </dl>
                                </div>
                            </div>
							 <?php foreach ($taskInfo as $v) {?>
								
                            <div class="row">
                                <div class="col-lg-5">
                                    <dl class="dl-horizontal">

                                        <dt>Created by:</dt> <dd><?php echo $v['username'] ?></dd>
                                        <dt>Created:</dt> <dd> 	10.07.2014 23:36:57 </dd>
                                        <dt>Last Updated:</dt> <dd>16.08.2014 12:15:57</dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <dl class="dl-horizontal">
										<?php if($v['status'] == 'complete') { ?>
													<dt>Completed</dt>
											<?php	} ?>
											<?php	if($v['status'] == 'partially') {?>
												    <dt>Partially Complete</dt>
											<?php	}?>
											<?php	if($v['status'] == 'not') {?>
													<dt>Not Completed</dt>
											<?php	} ?>

                                    </dl>
                                </div>
                            </div>
							<?php	} ?>
                            <div class="row m-t-sm">
                                <div class="col-lg-12">
                                <div class="panel blank-panel">
                                <div class="panel-heading">
                                    <div class="panel-options">
                                        <ul class="nav nav-tabs">
                                            <li class=""><a href="#tab-2" data-toggle="tab">Last activity</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body">

                                <div class="tab-content">
                               
                                <div class="tab-pane active" id="tab-2">

                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Update Time</th>
                                            <th>Comments</th>
                                        </tr>
                                        </thead>
                                        <tbody>
										<?php foreach ($taskInfo as $v) {?>
                                        <tr>
                                            <td>
                                                <span class="label label-primary"><i class="fa fa-check"></i>
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
                                            <td>
                                                14.07.2014 10:16:36
                                            </td>
                                            <td>
                                            <p class="small">
                                                <?php echo $v['comments'] ?>
                                            </p>
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
                </div>
            </div>
                <div id="taskEditDIV" class="col-lg-3" style="display:none">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Edit Task</h5>
                        </div>
                        <div class="ibox-content">
                            <form class="form-horizontal">
                               <p> Task Status:</p>
				            		<div class="col-sm-10">
                                        <div class="radio"><label> <input type="radio" checked="" value="option1" id="optionsRadios1" name="optionsRadios">Active</label></div>
                                        <div class="radio"><label> <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">Complete</label></div>
                                        <div class="radio"><label> <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">Partially Complete</label></div>
                                        <div class="radio"><label> <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">Not Completed</label></div>
                                    </div>
                                    <textarea rows="5" cols="25" placeholder="comment here"></textarea> <br/>
				            	<input type="submit" value="Submit"></input>
				            	<input type="button" value="Cancle" id="taskEditCancle"></input>
                            </form>
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
</body>