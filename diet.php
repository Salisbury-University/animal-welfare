<?php
ob_start();
include 'diet.html.php';
$output = ob_get_clean();
include 'layout.html.php';
