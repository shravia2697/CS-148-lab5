<?php
include 'top.php';
?>
<a href="form-trails.php"> Add new trail </a>
<?php
//##############################################################################
//
// This page lists the records based on the query given
// 
//##############################################################################
$records = '';

$query = 'SELECT fldTrailName, fldTotalDistance, fldHikingTime, fldVerticalRise, fldRating FROM tblTrails ORDER BY fldTrailName ';

// NOTE: The full method call would be:
//           $thisDatabaseReader->querySecurityOk($query, 0, 0, 0, 0, 0)
if ($thisDatabaseReader->querySecurityOk($query, 0,1)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $records = $thisDatabaseReader->select($query, '');
    
}

if (DEBUG) {
    print '<p>Contents of the array<pre>';
    print_r($records);
    print '</pre></p>';
}
if ($isAdmin){
    print '<ol><li ';
    if ($path_parts['filename']== 'form'){
        print ' class="activePage" ';
    }
    print '><a href="form-trails.php">Edit</a></li></ol>';
}
print '<h2 class="alternateRows">Information on the different Trails</h2>';
print "<table>
       <tr>
       <th>Name </th>
       <th> Distance in Miles</th>
       <th> Hiking Time in Hours </th>
       <th> Vertical Rise in Feet</th>
       <th> Rating </th>
       </tr>"; 
if (is_array($records)) {
    foreach ($records as $record) {
        print "<tr>";
        print "<td> " . "" . $record['fldTrailName'] . " </td> ". "" . " <td> " . "" . $record['fldTotalDistance'] . " </td>" ."" ;
        print "<td> " . "" . $record['fldHikingTime'] . "" . " </td>" ."" ;
        print "<td> " . "" . $record['fldVerticalRise'] . "" . " </td>" ."" ;
        print "<td> " . "" . $record['fldRating'] . "" . " </td>" . "" ;
        print "</tr>"; 
    }
}
print '</table>';
?>

<?php
include 'footer.php';
?>