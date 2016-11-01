<!DOCTYPE html>

<html lang="es-MX" >



<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="<?php echo $settings['description']; ?>">

    <meta name="author" content="apostosoft">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <meta name="apple-mobile-web-app-capable" content="yes">

    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <meta itemprop="name" content="Intermoneda.com">

    <meta itemprop="url" content="http://intermoneda.com/">

    <link rel="canonical" href="http://intermoneda.com/">

    <meta property="og:locale" content="es_MX">

    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary">

    <meta name="twitter:description" content="Compra venta de Bitcoin">

    <meta name="twitter:title" content="Intermoneda.com - Cpmpra venta de Bitcoin">    

    <!-- Iconos -->

    <link rel="apple-touch-icon" sizes="57x57" href="../assets/imgs/iconos/apple-icon-57x57.png">

<link rel="apple-touch-icon" sizes="60x60" href="../assets/imgs/iconos/apple-icon-60x60.png">

<link rel="apple-touch-icon" sizes="72x72" href="../assets/imgs/iconos/apple-icon-72x72.png">

<link rel="apple-touch-icon" sizes="76x76" href="../assets/imgs/iconos/apple-icon-76x76.png">

<link rel="apple-touch-icon" sizes="114x114" href="../assets/imgs/iconos/apple-icon-114x114.png">

<link rel="apple-touch-icon" sizes="120x120" href="../assets/imgs/iconos/apple-icon-120x120.png">

<link rel="apple-touch-icon" sizes="144x144" href="../assets/imgs/iconos/apple-icon-144x144.png">

<link rel="apple-touch-icon" sizes="152x152" href="../assets/imgs/iconos/apple-icon-152x152.png">

<link rel="apple-touch-icon" sizes="180x180" href="../assets/imgs/iconos/apple-icon-180x180.png">

<link rel="icon" type="image/png" sizes="192x192"  href="../assets/imgs/iconos/android-icon-192x192.png">

<link rel="icon" type="image/png" sizes="32x32" href="../assets/imgs/iconos/favicon-32x32.png">

<link rel="icon" type="image/png" sizes="96x96" href="../assets/imgs/iconos/favicon-96x96.png">

<link rel="icon" type="image/png" sizes="16x16" href="../assets/imgs/iconos/favicon-16x16.png">

<link rel="manifest" href="../assets/imgs/iconos/manifest.json">

<meta name="msapplication-TileColor" content="#ffffff">

<meta name="msapplication-TileImage" content="../assets/imgs/iconos/ms-icon-144x144.png">

<meta name="theme-color" content="#ffffff">

    <!-- Iconos -->



    <title><?php echo $settings['title']; ?></title>



 <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->

    <link href="<?php echo $settings['url']; ?>assets/css/bootstrap.min.css" rel="stylesheet">



    <!-- Custom CSS -->

    <link href="<?php echo $settings['url']; ?>assets/css/freelancer.css" rel="stylesheet">



    <!-- Custom Fonts -->

    <link href="<?php echo $settings['url']; ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">



    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- Va aquí porque si no no responde la pagina -->

        

    <script src="<?php echo $settings['url']; ?>assets/js/jquery-1.12.3.js"></script> 

        

        

<?php

      // Display the pricing chart if we're doing a US Dollar conversion

      // Use curl to get pricing chart data for the past 60 days

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, "https://blockchain.info/charts/market-price?showDataPoints=true&format=json");

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      $chartdata = json_decode(curl_exec($ch), true);

    ?>



    <script type="text/javascript">

         google.charts.load('current', {'packages':['corechart']});

      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([

          ['Fecha', 'Blockchain'],

                    <?php

          // Loop through the x-y coordinates Blockchain.info's API provides and add them to a JavaScript array

          foreach($chartdata["values"] as $xy) {

            echo "['" . date("Y/m", $xy["x"]) . "'," . $xy["y"] . "],";

          }

          ?>

        ]);



        var options = {

          title: 'USD/BTC',

          hAxis: {title: 'Mes',  titleTextStyle: {color: '#333'}},

          vAxis: {minValue: 0}

        };



        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));

        chart.draw(data, options);

      }

    </script>

   

        <!-- Comentarios de face -->

       

<script>(function(d, s, id) {

  var js, fjs = d.getElementsByTagName(s)[0];

  if (d.getElementById(id)) return;

  js = d.createElement(s); js.id = id;

  js.src = "//connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.6";

  fjs.parentNode.insertBefore(js, fjs);

}(document, 'script', 'facebook-jssdk'));</script>

<!-- Comentarios de face -->



<!-- Google Analitycs -->

<script>

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');



  ga('create', 'UA-79662923-1', 'auto');

  ga('send', 'pageview');



</script>

<!-- Google Analitycs -->

</head>



<body id="page-top" class="index">



    <!-- Navigation -->

    <nav class="navbar navbar-default navbar-fixed-top">

        <div class="container">

            <!-- Brand and toggle get grouped for better mobile display -->

            <div class="navbar-header page-scroll">

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">

                    <span class="sr-only">Toggle navigation</span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                </button>

                <a class="navbar-brand" href="<?php echo $settings['url']; ?>index.php"><img src="<?php echo $settings['url']; ?>assets/imgs/logo.png" height="75px" style="margin-top:-25px;"></a>

            </div>



            <!-- Collect the nav links, forms, and other content for toggling -->

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <ul class="nav navbar-nav navbar-right">

                    <li>

                    <a href="http://blog.intermoneda.com/"><i ></i> Blog</a>

                    </li>
                    <li>

                    <a href="http://faucet.intermoneda.com/"><i ></i> Faucet</a>

                    </li>

                     <li>

                    <a href="<?php echo $settings['url']; ?>blog/about/"><i ></i> Nosotros</a>

                    </li>

					<?php if(checkSession()) { ?>

					<li>

                        <a href="<?php echo $settings['url']; ?>account"><i class="fa fa-user"></i> <?php echo $_SESSION['ec_user']; ?></a>

                    </li>

					<li>

                        <a href="<?php echo $settings['url']; ?>logout"><?php echo $lang['logout']; ?></a>

                    </li>

					<?php } else { ?>

                    <li>

                        <a href="<?php echo $settings['url']; ?>login"><?php echo $lang['login']; ?></a>

                    </li>

                    <li>

                        <a href="<?php echo $settings['url']; ?>register"><?php echo $lang['create_account']; ?></a>

                    </li>

					<?php } ?>

                </ul>

            </div>

            <!-- /.navbar-collapse -->

        </div>

        <!-- /.container-fluid -->

    </nav>

