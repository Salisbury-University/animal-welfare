<?php
ob_start();
include 'search.html';
$output = ob_get_clean();
include 'header.php';
