<?php
// require_once "../vendor/autoload.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(",", $output);

    echo "<script>console.log('Debug Objects: " . $output . "');</script>";
}

$did = $_POST['did'];
$zims = $_POST['zims'];
debug_to_console($did);
debug_to_console($zims);

$query = "DELETE FROM diet WHERE did = ? AND zim = ?";
if ($result = $user->getDatabase()->runParameterizedQuery($query, "ii", array($did, $zims))) {
    echo "<br> Records deleted. <br>";
} else {
    echo "<br> Error deleting records. <br>";
}

session_write_close();
exit();
?>