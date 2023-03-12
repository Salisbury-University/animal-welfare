<?php
ob_start();
include 'home.html';
$output = ob_get_clean();
include 'page_frame.php';
