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

<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>                
<script type="text/javascript">
	window.onload = function()
	{
		CKEDITOR.replace( 'mytextarea1' );
                CKEDITOR.replace( 'mytextarea2' );
                CKEDITOR.replace( 'mytextarea3' );
                CKEDITOR.replace( 'mytextarea4' );
                CKEDITOR.replace( 'mytextarea5' );
                CKEDITOR.replace( 'mytextarea6' );
                CKEDITOR.replace( 'mytextarea7' );
	};
</script>                
                
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <strong><a href="http://intermoneda.com/" style="color:#fff;">Frontend</a></strong>
                     &nbsp;&nbsp;&nbsp;
                     <strong><a href="http://intermoneda.com/blog/admin" style="color:#fff;">Admin del Blog</a></strong>
                     &nbsp;&nbsp;&nbsp;
                     <strong><a href="http://intermoneda.com/blog" style="color:#fff;">Ir directo al Blog</a></strong>
                     &nbsp;&nbsp;&nbsp;
                    <strong><i class="fa fa-user"></i> <?php echo $_SESSION['ec_a_user']; ?></strong>
                    &nbsp;&nbsp;&nbsp;
                    <strong><a href="./?a=logout" style="color:#fff;">Logout</a></strong>
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
    <!-- LOGO HEADER END-->
    <section class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="./">Dashboard</a></li>
                            <li><a href="./?a=exchanges">Exchanges</a></li>
                            <li><a href="./?a=companies">Companies</a></li>
                            <li><a href="./?a=users">Users</a></li>
                            <li><a href="./?a=testimonials">Testimonials</a></li>
                            <li><a href="./?a=faq">FAQ</a></li>
							<li><a href="./?a=pages">Pages</a></li>
                                                        <li><a href="./?a=emails">Emails</a></li>
							<li><a href="./?a=settings">Web Settings</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- MENU SECTION END-->