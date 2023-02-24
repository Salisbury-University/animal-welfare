<?php
ob_start();
include 'edit.html.php';
$output = ob_get_clean();
include 'layout.html.php';
