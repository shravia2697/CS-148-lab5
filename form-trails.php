<?php
/*
 * this form will allow users to add a trail that they have hiked
 */

include 'top.php';

print  PHP_EOL . '<!-- SECTION: 1 Initialize variables -->' . PHP_EOL;       

print  PHP_EOL . '<!-- SECTION: 1a. debugging setup -->' . PHP_EOL;
if (DEBUG){ 
    print '<p>Post Array:</p><pre>';
    print_r($_POST);
    print '</pre>';
}

print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;

$trailNameNew = "";
$trailDistance ="";
$hikingTime= "";
$verticalRise= "";
$trailRating = "";

print PHP_EOL . '<!-- SECTION: 1c form error flags -->' . PHP_EOL;

$trailNameERROR = false;
$trailDistanceERROR = false;
$hikingTimeERROR = false;
$verticalRiseERROR = false;
$trailRatingERROR = false;

print PHP_EOL . '<!-- SECTION: 1d misc variables -->' . PHP_EOL;

$errorMsg = array(); 

print PHP_EOL . '<!-- SECTION: 2 Process for when the form is submitted -->' . PHP_EOL;

if (isset($_POST["btnSubmit"])){
    print PHP_EOL . '<!-- SECTION: 2a Security -->' . PHP_EOL;
    
    $thisURL = DOMAIN . PHP_SELF;
    
    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page.</p>';
        $msg.= '<p>Security breach detected and reported.</p>';
        die($msg);
    }
    print PHP_EOL . '<!-- SECTION: 2b Sanitize (clean) data  -->' . PHP_EOL;

    $trailNameNew = htmlentities($_POST["txtTrailNameNew"], ENT_QUOTES, "UTF-8");
    $trailDistance = htmlentities($_POST["txtTrailDistance"], ENT_QUOTES, "UTF-8");
    $hikingTime = htmlentities($_POST["txtHikingTime"], ENT_QUOTES, "UTF-8");
    $verticalRise = htmlentities($_POST["txtVerticalRise"], ENT_QUOTES, "UTF-8");
    $trailRating = htmlentities($_POST["radTrailRating"], ENT_QUOTES, "UTF-8");
    
    print PHP_EOL . '<!-- SECTION: 2c Validation -->' . PHP_EOL;
    
    
     
}