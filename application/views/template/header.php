
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo BASE_PATH; ?>application/views/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_PATH; ?>application/views/css/style.css" rel="stylesheet">
    <link href="<?php echo BASE_PATH; ?>application/views/css/particles.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="<?php echo BASE_PATH; ?>application/views/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div id='main-wrap'>
        <div class="mainmenu">
          <ul>
            <li onclick='window.location="<?php echo BASE_PATH; ?>"'><i class="icon-home icon-large"></i>&nbsp;<main>Home</main><desc>a 10-minute mail</desc></li>
            <li onclick='window.location="<?php echo BASE_PATH; ?>main/about/"'><i class="icon-comments icon-large"></i>&nbsp;<main>About us</main><desc>Who we are?</desc></li>
          </ul>
        </div>