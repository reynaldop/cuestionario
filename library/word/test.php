<?php
       require ("wordconvert.php"); # The class file wordconvert.php should be saved on the PHP include directory 
    # Change as you wish with your own values: 
        $filename="a.doc"; 
        $ext = "htm"; # - will convert rtf file above to htm file 
        $ext = 8;       # Same as above. See class info for Extension <-> Number correspondence. 
        $vis= 1; # =1Word will be visible, =0 to hide it 
    # Run the script with the above values    
    new wordconvert($filename,$ext,$vis); #Do the trick 
    print "done!"; 