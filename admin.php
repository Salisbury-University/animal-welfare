<?php
ob_start();
include 'Includes/DatabaseConnection.php';
echo "admin.php loaded <br><br>";

//Add code here later that checks if the current user has the Is_Admin flag.
//Redirect to the homepage if theyre not an admin.

// Display table of users
// TODO: Instead of displaying everything at once automatically,
// add the ability to search or display all users.
$sql = "select email, pass, administrator from users";
$result = mysqli_query($connection, $sql);
if(mysqli_num_rows($result) > 0){
    echo "<Table border = '1'>
            <thead>
                <tr>
                    <th> email </th>
                    <th> pass </th>
                    <th> is_admin </th>
                </tr>
            </thead>";
    while($row = mysqli_fetch_array($result)){
        echo "<tr>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['pass'] . "</td>";
        echo "<td>" . $row['administrator'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
else{
    echo "Error: Empty table <br>";
}

echo "";



$output = ob_get_clean();
include 'page_frame.php';
?>