<?php
ob_start();
include 'search.html.php';
$output = ob_get_clean();
include 'layout.html.php';
