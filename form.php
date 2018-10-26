<?php
include 'top.php';
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//     

//query for hikers
$queryHikers = "SELECT pmkHikersId, fldFirstName, fldLastName ";
$queryHikers .= "FROM tblHikers ";
$queryHikers .= "ORDER BY pmkHikersId";

//query for trails
$queryTrails  = "SELECT pmkTrailsId, fldTrailName ";
$queryTrails .= "FROM tblTrails ";
$queryTrails .= "ORDER BY pmkTrailsId";
        
print  PHP_EOL . '<!-- SECTION: 1 Initialize variables -->' . PHP_EOL;       
// These variables are used in both sections 2 and 3, otherwise we would
// declare them in the section we needed them

print  PHP_EOL . '<!-- SECTION: 1a. debugging setup -->' . PHP_EOL;
// We print out the post array so that we can see our form is working.
// Normally i wrap this in a debug statement but for now i want to always
// display it. when you first come to the form it is empty. when you submit the
// form it displays the contents of the post array.
if ($debug){ 
    print '<p>Post Array:</p><pre>';
    print_r($_POST);
    print '</pre>';
}
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;
//
// Initialize variables one for each form element
// in the order they appear on the form

$hikerName = "";
$dateHiked = "";
$trailName = "";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
print PHP_EOL . '<!-- SECTION: 1c form error flags -->' . PHP_EOL;
//
// Initialize Error Flags one for each form element we validate
// in the order they appear on the form
$hikerNameERROR = false;
$dateHikedERROR = false;
$trailNameERROR = false;
////%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
print PHP_EOL . '<!-- SECTION: 1d misc variables -->' . PHP_EOL;
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();       

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
print PHP_EOL . '<!-- SECTION: 2 Process for when the form is submitted -->' . PHP_EOL;
//

if ($thisDatabaseReader->querySecurityOk($queryHikers, 0, 1)) {
    $queryHikers = $thisDatabaseReader->sanitizeQuery($queryHikers);
    $hikers = $thisDatabaseReader->select($queryHikers, '');
}


if ($thisDatabaseReader->querySecurityOk($queryTrails, 0, 1)) {
    $queryTrails = $thisDatabaseReader->sanitizeQuery($queryTrails);
    $trails = $thisDatabaseReader->select($queryTrails, '');
}

        
if (isset($_POST["btnSubmit"])) {
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION: 2a Security -->' . PHP_EOL;
    
    // the url for this form
    $thisURL = DOMAIN . PHP_SELF;
    
    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page.</p>';
        $msg.= '<p>Security breach detected and reported.</p>';
        die($msg);
    }
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION: 2b Sanitize (clean) data  -->' . PHP_EOL;
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order as declared in section 1c.
    $hikerName = htmlentities($_POST["lstHikers"], ENT_QUOTES, "UTF-8");       
    $dateHiked = htmlentities($_POST["txtDateHiked"], ENT_QUOTES, "UTF-8");       
    $trailName = htmlentities($_POST["radTrail"], ENT_QUOTES, "UTF-8");  
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION: 2c Validation -->' . PHP_EOL;
    //
    // Validation section. Check each value for possible errors, empty or
    // not what we expect. You will need an IF block for each element you will
    // check (see above section 1c and 1d). The if blocks should also be in the
    // order that the elements appear on your form so that the error messages
    // will be in the order they appear. errorMsg will be displayed on the form
    // see section 3b. The error flag ($emailERROR) will be used in section 3c.
    if ($hikerName == "") {
        $errorMsg[] = "Please enter your name";
        $hikerNameERROR = true;
    } elseif (!verifyAlphaNum($hikerName)) {
        $errorMsg[] = "Please enter your name.";
        $hikerNameERROR = true;
    }
    
    if ($dateHiked == "") {
        $errorMsg[] = 'Please enter the date';
        $dateHikedERROR = true;
    } elseif (!verifyDate($dateHiked)) {       
        $errorMsg[] = 'Please enter the date';
        $dateHikedERROR = true;    
    }    
    
    if ($trailName == ""){
        $errorMsg[] = "Please select your trail";
        $trailNameERROR= true;
    }elseif (!verifyAlphaNum($trailName)) {
        $errorMsg[] = "Please select your trail";
        $trailNameERROR = true;
    }
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION: 2d Process Form - Passed Validation -->' . PHP_EOL;
    //
    // Process for when the form passes validation (the errorMsg array is empty)
    //    
    if (!$errorMsg) {
        if ($debug){
                print '<p>Form is valid</p>';
        }
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        print PHP_EOL . '<!-- SECTION: 2e Save Data -->' . PHP_EOL;
        // 
        
        // array used to hold form values
        $dataRecord = array($hikerName, $trailName, $dateHiked);       
    
        $queryHikerTrails = 'INSERT INTO tblHikersTrails SET ';
        $queryHikerTrails .= 'fnkHikersId = ?, ';
        $queryHikerTrails .= 'fnkTrailsId = ?, ';
        $queryHikerTrails .= 'fldDateHiked = ?';
       
        if ($thisDatabaseWriter->querySecurityOk($queryHikerTrails, 0)) {
            $queryHikerTrails = $thisDatabaseWriter->sanitizeQuery($queryHikerTrails);
            $result = $thisDatabaseWriter->insert($queryHikerTrails, $dataRecord);
        }
    }
}
//#############################################################################
//
print PHP_EOL . '<!-- SECTION 3 Display Form -->' . PHP_EOL;
//
?>       
<main>     
    <article>
<?php
    //####################################
    //
    print PHP_EOL . '<!-- SECTION 3a  -->' . PHP_EOL;
    // 
    // If its the first time coming to the form or there are errors we are going
    // to display the form.
    
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print '<h2>Thank you for providing your information.</h2>';

    } else {       
     print '<h2>Add your hike!</h2>';
     
        //####################################
        //
        print PHP_EOL . '<!-- SECTION 3b Error Messages -->' . PHP_EOL;
        //
        // display any error messages before we print out the form
   
       if ($errorMsg) {    
           print '<div id="errors">' . PHP_EOL;
           print '<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
           print '<ol>' . PHP_EOL;
           foreach ($errorMsg as $err) {
               print '<li>' . $err . '</li>' . PHP_EOL;       
           }
            print '</ol>' . PHP_EOL;
            print '</div>' . PHP_EOL;
       }
        //####################################
        //
        print PHP_EOL . '<!-- SECTION 3c html Form -->' . PHP_EOL;
        //
        /* Display the HTML form. note that the action is to this same page. $phpSelf
            is defined in top.php
            NOTE the line:
            value="<?php print $email; ?>
            this makes the form sticky by displaying either the initial default value (line ??)
            or the value they typed in (line ??)
            NOTE this line:
            <?php if($emailERROR) print 'class="mistake"'; ?>
            this prints out a css class so that we can highlight the background etc. to
            make it stand out that a mistake happened here.
       */
    }
?>    



<form action = "<?php print $phpSelf; ?>"
              id = "frmHiker"
              method = "post">

                <fieldset class = "lstHiker">
                    <legend></legend>
                    <p>
                        <label for="lstHiker">Hiker Name</label>
                                <?php 
                                if ($hikerNameERROR) {
                                    print '<class="mistake">';
                                }
                                print '<select id="lstHikers"';
                                print '     name = "lstHikers"';
                                print '     tabindex="300">';
                                print '     <option selected="selected" value=""></option>';
                                
                                foreach ($hikers as $hiker) {
                                    print '<option ';
                                    if ($hikerName == $hiker["pmkHikersId"]){
                                        print " selected='selected' ";
                                    }
                                    print 'value="' . $hiker["pmkHikersId"] . '">' . $hiker["fldFirstName"] . ' ' . $hiker["fldLastName"];

                                    print '</option>';
                                }
                                print ' </select>';
                                ?>      
                    </p>
                </fieldset>
                    
                    <p>
                <fieldset class="txtDateHiked">
                        <label for = "txtDateHiked">Date Hiked</label>
                                <?php 
                                print '<input id="txtDateHiked" ';
                                print '     name="txtDateHiked"';
                                print '     maxlength="45"';
                                print '     onfocus = "this.select()"';
                                print '     tabindex="120"';
                                print '     type="date"';
                                print '     value="' . $dateHiked . '">';

                                ?>    
                </fieldset> <!-- ends contact -->

            <fieldset class="radTrail">
                <label for = "radTrail">Trails Hiked</label>
                <p>
                <?php
                foreach ($trails as $trail) {
                    print '<input type="radio" ';
                    print 'id="radTrail" ' . $trail["fldTrailName"] . '"';
                    print ' name="radTrail"';
                    if ($trailName == $trail["pmkTrailsId"])
                        print " checked='checked' ";
                print ' value="' . $trail["pmkTrailsId"] . '">' . ' ' . $trail["fldTrailName"];
                    print '<br/>';
                }
                ?>
                </p>                    
            </fieldset>
            <fieldset class="buttons">
                    <input class = "button" id = "btnSubmit" name = "btnSubmit" tabindex = "500" type = "submit" value = "Register" >
            </fieldset> <!-- ends buttons -->
</form>     
    </article>     
</main>  
<?php 
    include 'footer.php';
?>