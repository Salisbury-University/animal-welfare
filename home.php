<?php
ob_start();
include 'home.html';
$output = ob_get_clean();
include 'header.php';
