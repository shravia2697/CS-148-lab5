<?php
    $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
    $path_parts = pathinfo('PHP_SELF');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Hiking Trails</title>
        <meta charset="utf-8">
        <meta name="author" content="Shravya Suddala">
        <meta name="description" content="Labs for CS 148 on Hiking Trails">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
        <![endif]-->

        <?php
        $debug = false;
        // This if statement allows us in the classroom to see what our variables are
        // This is NEVER done on a live site 
        if (isset($_GET["debug"])) {
            $debug = true;
        }
        $isAdmin = true;
        
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// PATH SETUP
        $domain = '//';
        $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');
        $domain .= $server;
        
        if ($debug) {
            print '<p>php Self: ' . PHP_SELF;
            print '<p>domain: ' . DOMAIN;
            print '<p>Path Parts<pre>';
            print_r($path_parts);
            print '</pre></p>';
        }

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// inlcude all libraries. 
// 
        print PHP_EOL . '<!-- include libraries -->' . PHP_EOL;
        
        include 'lib/constants.php';
        
        include LIB_PATH . '/Connect-With-Database.php';
        
        require_once 'lib/security.php';
        
        include_once 'lib/validation-functions.php';
        
//include_once 'lib/mail-message.php';       
        
        print PHP_EOL . '<!-- finished including libraries -->' . PHP_EOL;
        ?>	

    </head>

    <!-- **********************     Body section      ********************** -->
    <?php    
    print '<body id="' . $path_parts['filename'] . '">';
    include 'header.php';
    print PHP_EOL;
    include 'nav.php';
    print PHP_EOL;
    ?>
    <link rel="stylesheet" href="css/base.css?version=<?php print time(); ?>" type="text/css" media="screen">
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <div class="container">  
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="3"></li>
            <li data-target="#myCarousel" data-slide-to="4"></li>
          </ol>
        <div class="carousel-inner">
            <div class="item active">
                <img src="images/Mansfield.jpg" alt="Mount Mansfield" style="width:100%;">
            </div>

            <div class="item">
                <img src="images/Camels-Hump-Fall.jpg" alt="Camel's Hump" style="width:100%;">
            </div>

            <div class="item">
                <img src="images/skylight-pond.jpg" alt="Skylight Pond" style="width:100%;">
            </div>
            <div class="item">
                <img src="images/prospect-park.jpg" alt="Prospect Rock" style="width:100%;">
            </div>
            <div class="item">
                <img src="images/snake-mountain.jpg" alt="Snake Mountain" style="width:100%;">
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
<!-- ######################       Main Section       ######################## -->