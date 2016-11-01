<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>Administration - E-CURRENCY EXCHANGE PHP SOFTWARE</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME ICONS  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
     <!-- HTML5 Shiv and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <strong>Support email: </strong>admin@me4onkof.info
                </div>

            </div>
        </div>
    </header>
    <!-- HEADER END-->
	<div class="navbar navbar-inverse set-radius-zero" style="height:140px;">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./">

                    <img src="../assets/imgs/logo.png" />
                </a>

            </div>

            </div>
        </div>
    <!-- LOGO HEADER END-->
   
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Please Login To Enter </h4>
					<?php
					if(isset($_POST['btn_login'])) {
						$username = protect($_POST['username']);
						$password = protect($_POST['password']);
						$password = md5($password);
						$check = $db->query("SELECT * FROM ec_users WHERE username='$username' and password='$password'");
						if($check->num_rows>0) {
							$row = $check->fetch_assoc();
							if($row['status'] == "666") {
								$_SESSION['ec_a_uid'] = $row['id'];
								$_SESSION['ec_a_user'] = $row['username'];
								header("Location: ./");
							} else {
								echo error("You do not have admin privileges.");
							}
						} else {
							echo error("Wrong username or password.");
						}
					}	
					?>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
					<h4>Login with admin account:</h4>
					<form action="" method="POST">
						<div class="form-group">
							<label>Username</label>
							<input type="text" class="form-control" name="username">
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" class="form-control" name="password">
						</div>
						<button type="submit" class="btn btn-primary" name="btn_login"><i class="fa fa-user"></i> Authorize</button>
					</form>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info">
                        Welcome to E-CURRENCY EXCHANGE PHP SOFTWARE admin panel. Here you can absolutely control the entire web site with a few clicks and a pleasant and convenient interface.
                        <br />
                         <strong>Sheet with one of the functions:</strong>
                        <ul>
                            <li>
                                Process and manage exchange orders
                            </li>
                            <li>
                                Control user access and manage their accounts
                            </li>
                            <li>
                                Manage payment processors and their currencies
                            </li>
                            <li>
                                Dashboard statistics
                            </li>
                        </ul>
                       
                    </div>
                   
                </div>

            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    Copyright &copy; 2016 <a href="http://www.exchangesoftware.info">E-CURRENCY EXCHANGE PHP SOFTWARE v1.0.0</a>
                </div>

            </div>
        </div>
    </footer>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>
