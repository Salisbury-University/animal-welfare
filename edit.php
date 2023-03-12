<?php
ob_start();
include 'edit.html';
$output = ob_get_clean();
include 'page_frame.php';
