<?php
ob_start();
include 'diet.html';
$output = ob_get_clean();
include 'page_frame.php';
