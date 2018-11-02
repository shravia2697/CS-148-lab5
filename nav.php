<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
        // This sets a class for current page so you can style it differently
        
        print '<li';
        if ($path_parts['filename'] == 'index') {
            print ' class="activePage" ';
        }
        print '><a href="index.php">Home</a></li>';
       
        print '<li';
        if ($path_parts['filename'] == 'form') {
            print ' class="activePage" ';
        }
        print '><a href="form.php">Form</a></li>';
        
        print '<li';
        if ($path_parts['filename'] == 'form-trails') {
            print ' class="activePage" ';
        }
        print '><a href="form-trails.php">Add Trails</a></li>';
       
        print '<li';
        if ($path_parts['filename'] == 'tables') {
            print ' class="activePage" ';
        }
        print '><a href="tables.php">Tables</a></li>';

        ?>
    </ol>
</nav>
<!-- #################### Ends Main Navigation    ########################## -->

