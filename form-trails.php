<?php
include 'top.php';

print  PHP_EOL . '<!-- SECTION: 1 Initialize variables -->' . PHP_EOL;       

$update = false;

$yourURL = DOMAIN . PHP_SELF;

print  PHP_EOL . '<!-- SECTION: 1a. debugging setup -->' . PHP_EOL;

if (DEBUG){ 
    print '<p>Post Array:</p><pre>';
    print_r($_POST);
    print '</pre>';
}

print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;

$pmkTrailsId = -1;
$trailNameNew = "";
$trailDistance ="";
$hikingTime= "";
$verticalRise= "";
$trailRating = "";
$trails ="";
$tags= "";
$ratings = "";

$query = "SELECT pmkTrailsId,fldTrailName, fldTotalDistance, fldHikingTime, fldVerticalRise, fldRating, fldDefaultValue ";
$query .= "FROM tblTrails ";
$query .= "ORDER BY fldTrailName";

$query1 = 'SELECT pmkTrailsId, fldTrailName, fldTotalDistance, fldHikingTime, fldVerticalRise, fldRating ';
$query1 .= 'FROM tblTrails WHERE pmkTrailsId = ?';

if ($thisDatabaseReader->querySecurityOk($query, 1, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $trails = $thisDatabaseReader->select($query, '');
}

if ($thisDatabaseReader->querySecurityOk($query, 0, 1)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $ratings = $thisDatabaseReader->select($query, '');
}

$query = "SELECT pmkTag,fldDefaultValue  ";
$query .= "FROM tblTags ";
$query .= "ORDER BY fldDisplayOrder";

if ($thisDatabaseReader->querySecurityOk($query, 0, 1)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $tags = $thisDatabaseReader->select($query, '');
}

if (isset($_GET["id"])){
    $pmkTrailsId= (int) htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");
    $query = 'SELECT fldTrailName, fldTotalDistance, fldHikingTime, fldVerticalRise, fldRating ';
    $query .= 'FROM tblTrails WHERE pmkTrailsID =?';
    
    $data = array($pmkTrailsId);
    if ($thisDatabaseReader->querySecurityOk($query, 1)) {
        $query = $thisDatabaseReader->sanitizeQuery($query);
        $trails = $thisDatabaseReader->select($query, '');
    }
    
    $fldTrailName = $trails[0]["fldTrailName"];
    $fldTotalDistance = $trails[0]["fldTotalDistance"];
    $fldHikingTime = $trails[0]["fldHikingTime"];
    $fldVerticalRise = $trails[0]["fldVerticalRise"];
    $fldRating = $trails[0]["fldRating"];
    
}
print PHP_EOL . '<!-- SECTION: 1c form error flags -->' . PHP_EOL;

$trailNameERROR = false;
$trailDistanceERROR = false;
$hikingTimeERROR = false;
$verticalRiseERROR = false;
$trailRatingERROR = false;
$tagsERROR = false;
$ratingsERROR = false;

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
    
    $pmkId = (int) htmlentities($_POST["hidTrailsId"], ENT_QUOTES, "UTF-8");
    if ($pmkTrailsId > 0) {
        $update = true;
    }

    $trailNameNew = htmlentities($_POST["txtTrailNameNew"], ENT_QUOTES, "UTF-8");
    $trailDistance = htmlentities($_POST["numTrailDistance"], ENT_QUOTES, "UTF-8");
    $hikingTime = htmlentities($_POST["timtHikingTime"], ENT_QUOTES, "UTF-8");
    $verticalRise = htmlentities($_POST["numVerticalRise"], ENT_QUOTES, "UTF-8");
    $trailRating = htmlentities($_POST["lstTrailRating"], ENT_QUOTES, "UTF-8");
    
    print PHP_EOL . '<!-- SECTION: 2c Validation -->' . PHP_EOL;
    
    if($trailName == ""){
        $errorMsg[] ="Please enter the trail name.";
        $trailNameERROR = true;
    } elseif (!verifyAlphaNum($trailName)) {
        $errorMsg[] = "Your trail name seems to have an extra character.";
        $trailNameERROR = true;
    }    
    if($trailDistance == ""){
        $errorMsg[] ="Please enter the trail distance.";
        $trailDistanceERROR = true;
    } elseif (!verifyNumeric($trailDistance)) {
        $errorMsg[] = "Please enter trail distance";
        $trailDistanceERROR = true;
    }    
    if($hikingTime == ""){
        $errorMsg[] ="Please enter the hiking time.";
        $hikingTimeERROR = true;
    } elseif (!verifyAlphaNum($hikingTime)) {
        $errorMsg[] = "Please enter the hiking time.";
        $hikingTimeERROR = true;
    }
    if($verticalRise == ""){
        $errorMsg[] ="Please enter the vertical rise.";
        $verticalRiseERROR = true;
    } elseif (!verifyAlphaNum($verticalRise)) {
        $errorMsg[] = "Please enter the vertical rise.";
        $verticalRiseERROR = true;
    }
    if($trailRating == ""){
        $errorMsg[] ="Please enter the trail rating.";
        $trailRatingERROR = true;
    } elseif (!verifyAlphaNum($trailRating)) {
        $errorMsg[] = "Please enter the trail rating.";
        $trailRatingERROR = true;
    }   
    print PHP_EOL . '<!-- SECTION: 2d Process Form - Passed Validation -->' . PHP_EOL;
    if (!$errorMsg) {
        if (DEBUG) {
            print "<p>Form is valid</p>";
        }

        print PHP_EOL . '<!-- SECTION: 2e Save Data -->' . PHP_EOL;

        $dataEntered = false;
        $data[]= array();
        
        $data[]= $trailName;
        $data[]= $trailDistance;
        $data[]= $hikingTime;
        $data[]= $verticalRise;
        $data[]= $trailRating;
        try {
            $thisDatabaseWriter->db->beginTransaction();
            
            if ($update) {
                $query = 'UPDATE tblTrails SET ';
            } else {
                $query = 'INSERT INTO tblTrails SET ';
            }
            
            $query .= 'fldTrailName = ?, ';
            $query .= 'fldTotalDistance = ?, ';
            $query .= 'fldHikingTime = ?, ';
            $query .= 'fldVerticalRise = ?, ';
            $query .= 'fldRating = ?, ';
            
            if (DEBUG) {
                $thisDatabaseWriter->TestSecurityQuery($query, 0);
                print_r($data);
            }
            if($update){
              $query .= 'WHERE pmkTrailsId = ? ';
              $data[] = $pmkTrailsId;
              
              if ($thisDatabaseReader->querySecurityOk($query, 1)) {
                    $query = $thisDatabaseWriter->sanitizeQuery($query);
                    $results = $thisDatabaseWriter->update($query, $data);
                }
            } else {
                if ($thisDatabaseWriter->querySecurityOk($query, 0)) {
                    $query = $thisDatabaseWriter->sanitizeQuery($query);

                    $results = $thisDatabaseWriter->insert($query, $data);

                    $primaryKey = $thisDatabaseWriter->lastInsert();
                }
            }
            if (DEBUG) {
                print "<p>pmk= " . $primaryKey;
            }
            $dataEntered = $thisDatabaseWriter->db->commit();

            if (DEBUG)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabaseWriter->db->rollback();
            if (DEBUG)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accepting your data please contact us directly.";
        }

        print PHP_EOL . '<!-- SECTION: 2f Create message -->' . PHP_EOL;

        print PHP_EOL . '<!-- SECTION: 2g Mail to user -->' . PHP_EOL;
    } // end form is valid
} // ends if form was submitted.

print PHP_EOL . '<!-- SECTION 3 Display Form -->' . PHP_EOL;
?>
<main>
    <article id="main">
        <?php
        print PHP_EOL . '<!-- SECTION 3a  -->' . PHP_EOL;

        if ($dataEntered) { // closing of if marked with: end body submit
            print "<h1>Record Saved</h1> ";

            // Display the message you created in in SECTION: 2f
        } else {

            print PHP_EOL . '<!-- SECTION 3b Error Messages -->' . PHP_EOL;

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

            print PHP_EOL . '<!-- SECTION 3c html Form -->' . PHP_EOL;
            ?>
            <h2>Trails</h2>
            <form action="<?php print PHP_SELF; ?>"
                  method="post"
                  id="frmRegister">
                <input type="hidden" id="hidPoetId" name="hidPoetId"
                       value="<?php print $pmkTrailsId; ?>"
                       >
                <fieldset class = "contact">
                    <p>
                        <label class="required" for="txtTrailNameNew">Trail Name</label>  
                        <input autofocus
                        <?php if ($trailNameERROR) print 'class="mistake"'; ?>
                               id="txtTrailNameNew"
                               maxlength="45"
                               name="txtTrailName"
                               onfocus="this.select()"
                               placeholder="Enter your trail name"
                               tabindex="100"
                               type="text"
                               value="<?php print $trailNameNew; ?>"                    
                               >                    
                    </p>
                    
                    <p>
                        <label class="required" for="txtTrailDistance">Trail Distance</label>  
                        <input
                        <?php if ($trailDistanceERROR) print 'class="mistake"'; ?>
                            id="txtTrailDistance"
                            maxlength="45"
                            name="txtTrailDistance"
                            onfocus="this.select()"
                            placeholder="Enter the trail distance"
                            tabindex="110"
                            type="text"
                            value="<?php print $trailDistance; ?>"                    
                            >                    
                    </p>
                    
                    <p>
                        <label class="required" for="txtHikingTime">Hiking Time</label>  
                        <input
                        <?php if ($hikingTimeERROR) print 'class="mistake"'; ?>
                            id="txtHikingTime"
                            maxlength="45"
                            name="txtHikingTime"
                            onfocus="this.select()"
                            placeholder="Enter the hiking time"
                            tabindex="110"
                            type="text"
                            value="<?php print $hikingTime; ?>"                    
                            >                    
                    </p>
                    
                    <p>
                        <label class="required" for="txtVerticalRise">Vertical Rise</label>  
                        <input
                        <?php if ($verticalRiseERROR) print 'class="mistake"'; ?>
                            id="txtVerticalRise"
                            maxlength="45"
                            name="txtVerticalRise"
                            onfocus="this.select()"
                            placeholder="Enter the vertical rise"
                            tabindex="110"
                            type="text"
                            value="<?php print $verticalRise; ?>"                    
                            >                    
                    </p> 
                </fieldset>
                
                <label for="lstTrailRating">Ratings
                        <select id="lstTrailRating"
                                name="lstTrailRating"
                                tabindex="300" >
                            <option selected="selected" value=""></option>

                            <option value="Easy">Easy</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Strenuous">Strenuous</option>
                            <option value="Moderately Strenuous">Moderately Strenuous</option>

                        </select></label>
                </fieldset>
                
                <fieldset class ="checkbox">
                    <?php
                    print '<h2>Tags</h2>' .PHP_EOL;
                    if ($tagsERROR) {
                        print ' mistake';
                    }
                    print '' . PHP_EOL;
                    print '<legend>Check the tags that apply: </legend>' . PHP_EOL;

                    $i = 0;

                    if (is_array($tags)) {
                        foreach ($tags as $tag) {

                            print "\t" . '<label for="chk' . str_replace(" ", "", $tag["pmkTag"]) . '"><input type="checkbox" ';
                            print ' id="chk' . str_replace(" ", "", $tag["pmkTag"]) . '" ';
                            print ' name="chk' . str_replace(" ", "", $tag["pmkTag"]) . '" ';

                            if ($tag["fldDefaultValue"]) {
                                print ' checked ';
                            }

                            // the value is the index number of the hobby array
                            print 'value="' . $i++ . '">' . $tag["pmkTag"];
                            print '</label>' . PHP_EOL;
                        }
                    }
                    ?>
                </fieldset>
                <fieldset class="buttons">
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Save" tabindex="900" class="button">
                </fieldset> <!-- ends buttons -->            
            </form>
            <?php
        } // end body submit
        ?>
    </article>
</main>

<?php
include "footer.php";

if (DEBUG)
    print "<p>END OF PROCESSING</p>";
?>  