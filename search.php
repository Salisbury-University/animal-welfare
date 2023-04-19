<?php
include 'Includes/DatabaseConnection.php';

#put sql here

##Needed for styling
ob_start();
include 'search.html.php';
$output = ob_get_clean();
include 'page_frame.php';

?>